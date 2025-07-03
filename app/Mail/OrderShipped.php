<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Headers;
use App\Models\User;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;
    public $userEmail;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->userEmail = $user->first_name;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $sendFrom = strval(env('MAIL_FROM_ADDRESS'));
        return new Envelope(
            from: new Address($sendFrom),
//            replyTo: [
//                new Address('abdyushevr@avangard.ru'),
//            ],
            subject: 'Test Email Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.test',
            with: [
                'userEmail' => $this->userEmail,
            ],
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
    
    /**

    * Get the message headers.

    */

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'X-PM-Message-Stream' => 'outbound',
            ],
        );
    }
}
