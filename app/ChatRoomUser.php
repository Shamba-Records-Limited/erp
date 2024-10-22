<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class ChatRoomUser extends Model
{
    //
    
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "chat_room_user";


    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function chatRoom()
    {
        return $this->belongsTo('App\ChatRoom');
    }

}
