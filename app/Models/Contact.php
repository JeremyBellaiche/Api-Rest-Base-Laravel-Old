<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['fk_user_id_applicant', 'fk_user_id_intended', 'status'];

    public function getApplicant(){
    	return $this->hasOne('\App\User', 'fk_user_id_applicant', 'id');
    }

    public function getIntended(){
    	return $this->hasOne('\App\User', 'fk_user_id_intended', 'id');
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
