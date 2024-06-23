<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class SystemTicketComment extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "system_ticket_comment";

    protected $primaryKey = 'id';

    public function getRouteKeyName()
    {
        return 'id';
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }
}
