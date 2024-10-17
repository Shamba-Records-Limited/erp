<?php

namespace App\Providers;

use App\Events\AuditTrailEvent;
use App\Events\NewCooperativeRegisteredEvent;
use App\Events\NewUserRegisteredEvent;
use App\Listeners\AuditTrailListener;
use App\Listeners\NewCooperativeRegisteredListener;
use App\Listeners\NewUserRegisteredListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewCooperativeRegisteredEvent::class =>[
            NewCooperativeRegisteredListener::class
        ],
        NewUserRegisteredEvent::class =>[
            NewUserRegisteredListener::class
        ],
        AuditTrailEvent::class =>[
            AuditTrailListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
