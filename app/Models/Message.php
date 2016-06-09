<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	protected $table = 'messages';

    protected $fillable = ['fk_chat_id', 'fk_user_id', 'msg_type', 'msg_text', 'msg_attachment'];

    public function user()
    {
    	return $this->hasOne('\App\User', 'id', 'fk_user_id');
    }

    public function attachments()
    {
    	return $this->hasMany('\App\Models\Message_Attachment', 'fk_message_id', 'id');
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