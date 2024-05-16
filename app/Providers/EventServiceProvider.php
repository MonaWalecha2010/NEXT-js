<?php

namespace App\Providers;

use App\Events\UserActivated;
use App\Events\WorkpackChanged;
use App\Events\WorkpackCompleted;
use App\Events\WorkpackPanelChanged;
use App\Events\WorkpackPanelTaskCreated;
use App\Listeners\GenerateCompletedWorkpackPdf;
use App\Listeners\SendUserActiveNotification;
use App\Listeners\UpdateWorkpackPanelStats;
use App\Listeners\UpdateWorkpackSchematicStats;
use App\Listeners\UpdateWorkpackStats;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        WorkpackChanged::class => [
            UpdateWorkpackStats::class,
        ],
        WorkpackPanelChanged::class => [
            UpdateWorkpackStats::class,
            UpdateWorkpackSchematicStats::class,
        ],
        WorkpackPanelTaskCreated::class => [
            UpdateWorkpackPanelStats::class,
        ],
        WorkpackCompleted::class => [
            GenerateCompletedWorkpackPdf::class,
        ],
        UserActivated::class => [
            SendUserActiveNotification::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
