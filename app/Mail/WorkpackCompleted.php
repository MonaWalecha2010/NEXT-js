<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Workpack;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkpackCompleted extends Mailable {

    use Queueable, SerializesModels;

    /**
     * @var User
     */
    public $user;

    /**
     * @var Workpack
     */
    public $workpack;

    /**
     * @var mixed
     */
    public $aeroplane;

    /**
     * @var string
     */
    public $loginUrl;

    /**
     * @var
     */
    public $workpackUrl;

    /**
     * @var
     */
    public $workpackPdf;

    protected $workpackPdfAttachment = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( Workpack $workpack )
    {
        $this->workpack    = $workpack;
        $this->aeroplane   = $this->workpack->aeroplane;
        $this->loginUrl    = route( 'login' );
        $this->workpackUrl = route( 'workpacks.show', [ 'workpack' => $this->workpack->id ] );
        $this->workpackPdf = route( 'workpacks.report.pdf', [ 'workpack' => $this->workpack->id ] );
    }

    public function setUser( User $user )
    {
        $this->user        = $user;
    }


    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: "Workpack {$this->workpack->name} for {$this->aeroplane->name} completed",
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.workpack.completed'
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        if( $this->workpackPdfAttachment == null ){
            $this->workpackPdfAttachment = $this->workpack->getReportPdfAsString();
        }
        $filename = $this->workpack->getReportPdfFileName();
        return [
            Attachment::fromData( fn(
            ) => $this->workpackPdfAttachment, $filename )
                      ->withMime( 'application/pdf' ),
        ];
    }
}
