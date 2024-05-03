<?php

namespace App\Listeners;

use App\Events\NewCooperativeRegisteredEvent;
use App\Mail\CooperativeRegisterMail;
use Illuminate\Support\Facades\Mail;

class NewCooperativeRegisteredListener
{

    public function handle(NewCooperativeRegisteredEvent $event)
    {
        Mail::to($event->data['email'])->send(new CooperativeRegisterMail($event->data));
    }
}
