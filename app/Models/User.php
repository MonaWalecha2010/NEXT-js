<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Helpers\Helper;
use App\Interfaces\CanDelete;
use App\Traits\CanAttachUploads;
use App\Traits\HasUpload;
use App\Traits\HasValidationRules;
use App\Traits\Paginatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements CanDelete {

    use HasApiTokens, HasFactory, Notifiable, Paginatable, HasValidationRules, CanAttachUploads, HasUpload;

    public const SUPERADMIN_ROLE = 1;
    public const ADMIN_ROLE = 2;
    public const MECHANIC_ROLE = 3;
    public const ENGINEER_ROLE = 4;
    public const SENIORENGINEER_ROLE = 5;


    public const TRAINING_ADMIN = 1;
    public const TRAINING_OFFICER = 2;
    public const TRAINING_MANAGER = 3;
    public const TRAINING_ENGINEER = 4;
    public const TRAINING_MECHANIC = 5;


    protected  $attributes = [
        'active' => 1,
        'color' => 'blue',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fname',
        'lname',
        'email',
        'code',
        'stamp',
        'phone',
        'active',
        'role_id',
        'training_role_id',
        'license_number',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @var array
     */
    protected static array $validationRules = [
        'fname'              => 'required',
        'lname'              => 'required',
        'email'              => 'required|email|unique:users,email',
        'role_id'            => 'required|integer',
        'training_role_id'   => 'required|integer',
        'code'               => 'nullable|digits:6|unique:users,code',
        'license_number'     => 'required|digits:6|unique:users,license_number',
        'signature'          => 'nullable|file|required|mimetypes:image/jpeg,image/gif,image/png',
        'stamp'              => 'required',
    ];


    protected static function boot()
    {
        parent::boot();

        //Automatically insert the fullname when adding or saving a User
        static::saving( function ( $user ) {
            $user->name = $user->fname . ' '. $user->lname;
        });

    }


    /**
     * Get any validation rules defined for this model
     * @return array
     */
    public function getValidationRules()
    {
        $rules = self::$validationRules;
        // https://laravel.com/docs/9.x/validation#rule-unique
        // Because we have a unique constraint on the email we have to adjust any unique validation rules so that it does trigger on every update...
        if ( ! empty( $this->id ) )
        {
            $rules[ 'email' ] .= ',' . $this->id;
            $rules[ 'code' ]  .= ',' . $this->id;
            $rules[ 'license_number' ]  .= ',' . $this->id;

        }

        return $rules;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo( Role::class );
    }

    public function training_role()
    {
        return $this->belongsTo( TrainingRole::class );
    }

    /**
     * @return BelongsToMany
     */
    public function airframes()
    {
        return $this->belongsToMany( Airframe::class )->withPivot('qualification')->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function workpack_panel_tasks()
    {
        return $this->hasMany( WorkpackPanelTask::class );
    }

    /**
     * @return HasMany
     */
    public function workpack_panels()
    {
        return $this->hasMany( WorkpackPanel::class );
    }

    /**
     * @return HasMany
     */
    public function schematics()
    {
        return $this->hasMany( Schematic::class );
    }

    /**
     * @return HasMany
     */
    public function user_tasks()
    {
        return $this->hasMany( TaskRequest::class ,'user_id');
    }


     /**
     * @return HasOne
     */
    public function readStatus()
    {
        return $this->hasOne( MarkReadNotice::class);
    }

    /**
     * Interact with the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function fname(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst($value),
            set: fn ($value) => ucfirst($value),
        );
    }

    /**
     * Interact with the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function lname(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst($value),
            set: fn ($value) => ucfirst($value),
        );
    }


    /**
     * @return bool
     */
    public function isAuthenticatedUser()
    {
        if ( in_array( $this->role_id, [
            self::SUPERADMIN_ROLE,
            self::ADMIN_ROLE,
            self::MECHANIC_ROLE,
            self::ENGINEER_ROLE,
            self::SENIORENGINEER_ROLE
        ] ) )
        {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isSuperAdministrator()
    {
        if ( $this->role_id === 1 )
        {
            return true;
        }

        return false;
    }


    /**
     * @return bool
     */
    public function isAdministrator()
    {
        if ( in_array( $this->role_id, [
            self::SUPERADMIN_ROLE,
            self::ADMIN_ROLE
        ] ) )
        {
            return true;
        }

        return false;
    }


    /**
     * @return bool
     */
    public function isPlaneWorker()
    {
        if ( in_array( $this->role_id, [
            self::MECHANIC_ROLE,
            self::ENGINEER_ROLE,
            self::SENIORENGINEER_ROLE
        ] ) )
        {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isMechanic()
    {
        if ( in_array( $this->role_id, [ self::MECHANIC_ROLE ] ) )
        {
            return true;
        }

        return false;
    }


    /**
     * @return bool
     */
    public function isEngineer()
    {
        if ( in_array( $this->role_id, [ self::ENGINEER_ROLE ] ) )
        {
            return true;
        }

        return false;
    }


    /**
     * @return bool
     */
    public function isSeniorEngineer()
    {
        if ( in_array( $this->role_id, [ self::SENIORENGINEER_ROLE ] ) )
        {
            return true;
        }

        return false;
    }


    // Training Roles  -----------

    /**
     * @return bool
     */
    public function isTrainingAdmin()
    {
        if ( $this->training_role_id === 1 )
        {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isTrainingOfficer()
    {
        if ( $this->training_role_id === 2 )
        {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isTrainingManager(){

        if ( $this->training_role_id === 3 )
        {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isTrainingEngineer()
    {
        if ( $this->training_role_id === 4 )
        {
            return true;
        }
        return false;
    }

     /**
     * @return bool
     */
    public function isTrainingMechanic()
    {
        if ( $this->training_role_id === 5 )
        {
            return true;
        }
        return false;
    }

    public function getFullName()
    {
        return $this->fname . " " . $this->lname;
    }

    /**
     * @return string
     */
    public function getInitials()
    {
        return Helper::getInitials( $this->name );
    }

    /**
     * @return bool
     */
    public function canRemovePanel()
    {
        return ( $this->isMechanic() || $this->isEngineer() || $this->isSeniorEngineer() );
    }


    /**
     * @return bool
     */
    public function canInspectClearPanel()
    {
        return ( $this->isEngineer() || $this->isSeniorEngineer() );
    }

    /**
     * @return bool
     */
    public function canInspectInstallPanel()
    {
        return ( $this->isEngineer() || $this->isSeniorEngineer() );

    }


    /**
     * @param $code
     *
     * @return bool
     */
    public function checkCode( $code ){
        return $this->code == $code;
    }

    /**
     * @return bool
     */
    public function canInstallPanel()
    {
        return ( $this->isMechanic() || $this->isEngineer() || $this->isSeniorEngineer() );

    }

    /**
     * @return bool
     */
    public function canSealPanel()
    {
        return ( $this->isMechanic() || $this->isEngineer() || $this->isSeniorEngineer() );
    }


    /**
     * @return bool
     */
    public function canCompleteWorkpack()
    {
        return $this->isSeniorEngineer();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getSeniorEngineeers()
    {
        return self::with('airframes')->where('role_id', self::SENIORENGINEER_ROLE )->get();
    }


    /**
     * @return false
     */
    public function canDelete() : bool
    {
        $fdgdf = true;
        // We don't want to get rid of the Super Admin ever...
        if ( $this->isSuperAdministrator() ){
            return false;
        }

        // We don't want to get rid of the only Admin account...
        if ( $this->isAdministrator() ){
            if( User::where( 'role_id', self::ADMIN_ROLE)->get()->count() <= 1 ) {
                return false;
            }
        }

        // If a user has ever performed work on a Workpack or WorkpackPanel we don't want their history to be deleted...
        if ( ( $this->workpack_panels->count() === 0 ) && ( $this->workpack_panel_tasks->count() === 0 ) ){
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $this->airframes()->detach();
        if( parent::delete() ){
            Storage::delete($this->signature);
            return true;
        }
        return false;
    }



}
