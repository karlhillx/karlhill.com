<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Inspiring;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserSubscribed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('karlhillx@gmail.com', 'Karl Hill')
            ->replyTo('karlhillx@gmail.com', 'Karl Hill')
            ->subject('Thank you for subscribing to our newsletter')
            ->view('mail.user.subscribed')
            ->with([
                'text' => strip_tags(Inspiring::quote()),
            ]);
    }
}
