<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message_Attachment extends Model
{
	protected $table = 'messages_attachments';

    protected $fillable = ['path', 'fk_user_id', 'fk_message_id'];

}