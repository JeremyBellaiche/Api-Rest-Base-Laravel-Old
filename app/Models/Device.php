<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'devices';

    protected $fillable = ['fk_user_id', 'country_code', 'phone_number', 'msisdn', 'user_agent'];

    public function getUser(){
    	return $this->belongsTo('\App\User', 'fk_user_id', 'id');
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
