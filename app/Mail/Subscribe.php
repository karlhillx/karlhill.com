<?php

namespace App\Mail;

use Faker\Provider\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Inspiring;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Subscribe extends Mailable
{
    use Queueable, SerializesModels;

    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('karlhillx@gmail.com', 'Karl Hill'),
            replyTo: [
                new Address('karlhillx@gmail.com', 'Karl Hill'),
            ], subject: 'Thank you for subscribing to our newsletter',);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.subscribers',
            with: [
                'text' => strip_tags(Inspiring::quote()),
            ],);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

}
