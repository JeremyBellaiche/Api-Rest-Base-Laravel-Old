<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * This mutator automatically hashes the password.
     *
     * @var string
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = \Hash::make($value);
    }

    public function books()
    {
        return $this->hasMany('App\Models\Book');
    }


    public function chats()
    {
        return $this->hasMany('App\Models\Chat_User', 'fk_user_id', 'id')
                    ->with('chat');                    
        // $chats = \App\Models\Chat_User::where('fk_user_id', $this->id)->with(['chat'])->get();

        // return $chats;
    }

    public function invitations()
    {
        return $this->hasMany('App\Models\Contact', 'fk_user_id_intended', 'id')
                    ->where('status', 'waiting');
    }


    // Contacts 
    public function contacts_user_intended(){
        return $this->hasMany('App\Models\Contact', 'fk_user_id_intended', 'id')
                    ->where('status', 'accepted');
    }
    public function contacts_user_applicant(){
        return $this->hasMany('App\Models\Contact', 'fk_user_id_applicant', 'id')
                    ->where('status', 'accepted');
    }

    public function contacts()
    {
        return $this->contacts_user_intended
                    ->merge($this->contacts_user_applicant);
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
