<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Inspiring;
use Illuminate\Mail\Mailable;
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
     * Build the message.
     */
    public function build(): self
    {
        return $this->from('karlhillx@gmail.com', 'Karl Hill')
            ->subject('Thank you for subscribing to our newsletter')
            ->with([
                'text' => strip_tags(Inspiring::quote()),
            ])
            ->markdown('emails.subscribers');
    }
}
