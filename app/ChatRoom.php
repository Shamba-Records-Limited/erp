<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Webpatser\Uuid\Uuid;

class ChatRoom extends Model
{
    //
    
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "chat_rooms";


    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }

    public function chatMessages()
    {
        return $this->hasMany('App\ChatMessage');
    }

    public function chatRoomUsers()
    {
        return $this->hasMany('App\ChatRoomUser');
    }

    public function getRoomNameAttribute()
    {
        $user_id = Auth::id();

        if ($this->is_group){
            return $this->group_name;
        }
        
        // get other user
        $rawOtherUser = $this->chatRoomUsers()->where('user_id', '!=', $user_id)->first();
        if ($rawOtherUser){
            $otherUser = $rawOtherUser->user;
            
            return ucfirst($otherUser->first_name) . " " . ucfirst($otherUser->other_names);
        }

        return "Some name";
    }

    public function getUnreadAttribute()
    {
        $user_id = Auth::id();
        $chatRoomUser = $this->chatRoomUsers()->where('user_id', $user_id)->first();
        if ($chatRoomUser){
            $lastReadAt = $chatRoomUser->last_read_at;
            $msgsCount = 0;
            if (!$lastReadAt){
                $msgsCount = $this->chatMessages()->count();
            } else {
                $msgsCount = $this->chatMessages()->where('created_at', '>', $lastReadAt)->count();
            }

            return $msgsCount;
        }

        return 0;
    }

}
