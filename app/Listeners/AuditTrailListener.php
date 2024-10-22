<?php

namespace App\Listeners;

use App\AuditTrail;
use App\Events\AuditTrailEvent;

class AuditTrailListener
{


    public function handle(AuditTrailEvent $event)
    {
        AuditTrail::create([
            "user_id" => $event->data["user_id"],
            "cooperative_id" => $event->data["cooperative_id"],
            "activity" => $event->data["activity"]
        ]);
    }
}
