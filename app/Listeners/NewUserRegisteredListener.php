<?php

namespace App\Listeners;

use App\Events\NewUserRegisteredEvent;
use App\Mail\UserRegisteredMail;
use Illuminate\Support\Facades\Mail;

class NewUserRegisteredListener
{


    /**
     * Handle the event.
     *
     * @param  NewUserRegisteredEvent  $event
     * @return void
     */
    public function handle(NewUserRegisteredEvent $event)
    {
        Mail::to($event->data['email'])->send(new UserRegisteredMail($event->data));
    }
}
