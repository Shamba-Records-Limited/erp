<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class ChatMessage extends Model
{
    //
    
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "chat_messages";


    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }

    public function sender()
    {
        return $this->belongsTo('App\User', 'sender_id');
    }
}
