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
use Illuminate\Support\Facades\Auth;
use Config;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;
    public $userEmail;
    public $message;
//    public $userEmailFrom;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $message)
    {
        $this->userEmail = $user->email;
        $this->message = strval($message);
//        $this->userEmailFrom = strval(Auth::user()->email);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
//        $sendFrom = strval(Config::get('sklad.emailmodestatus')) == "dev" ? strval(Config::get('sklad.emailaddress')) : $this->userEmailFrom;
        $sendFrom = strval('rafradio@gmail.com');
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
                'userMess' => $this->message,
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

    
}
