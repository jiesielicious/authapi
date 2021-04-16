<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Verification extends Mailable
{
    use Queueable, SerializesModels;

    protected $pin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($verificationPin)
    {
        $this->pin = $verificationPin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->text('emails.verification')->with(['pin'=>$this->pin]);
    }
}
