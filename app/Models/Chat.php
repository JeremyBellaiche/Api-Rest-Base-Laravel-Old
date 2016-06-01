<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
	protected $table = 'chats';

    protected $fillable = ['title', 'fk_owner_id', 'fk_last_entry'];

    public function users(){
    	return $this->hasMany('\App\Models\Chat_User', 'fk_chat_id', 'id')
    	->leftJoin('users', 'chat_users.fk_user_id', '=', 'users.id')
    	->select(['users.id', 'users.fname', 'users.lname', 'chat_users.fk_user_id', 'chat_users.fk_chat_id']);
    }

    public function messages(){
    	return $this->hasMany('\App\Models\Message', 'fk_chat_id', 'id')->with('attachments');
    }

}
