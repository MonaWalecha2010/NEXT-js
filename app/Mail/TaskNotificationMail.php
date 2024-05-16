<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Notification;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    public $user;

   /**
     * @var Notification
     */
    public $notification;

     /**
     * @var Task
     */
    public $task;


      /**
     * @var string
     */

    public $message;

    public $loginUrl = null;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( User $user, Notification $notification, Task $task ,string $message)
    {
        $this->user = $user;
        $this->notification = $notification;
        $this->task = $task;
        $this->message = $message;
        $this->loginUrl = route('login');
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'),config('mail.from.name')),
            subject: config('app.name').' Task Notification Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.training.notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
