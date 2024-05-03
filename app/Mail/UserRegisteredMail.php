<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;


    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function build()
    {
        return $this->subject('Welcome on Board')->markdown('emails.register.user-register-mail');
    }
}
