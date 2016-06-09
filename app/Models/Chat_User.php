<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat_User extends Model
{
	protected $table = 'chat_users';

    protected $fillable = ['fk_chat_id', 'fk_user_id', 'fk_last_message_seen'];

    public function chat()
    {
    	return $this->hasOne('\App\Models\Chat', 'id', 'fk_chat_id')
			    	->with('users');
    }

    public function getCreatedAtAttribute($date)
    {
        return strtotime($date);
    }

    public function getUpdatedAtAttribute($date)
    {
        return strtotime($date);
    }



}
