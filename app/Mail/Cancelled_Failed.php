<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Cancelled_Failed extends Mailable
{
    use Queueable, SerializesModels;
    
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.cancelled_failed')
            ->subject("RCD Convention 2022 Failed/Cancelled Registration")
            ->with(array('user' => $this->user));
    }
    
}
