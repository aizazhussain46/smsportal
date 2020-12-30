<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'role_id',
        'master', 
        'email', 
        'password', 
        'name', 
        'mobile_no', 
        'cnic', 
        'status_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
     
    protected $with = [
        'role:id,role',
        'status:id,status,keyword'
    ];

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime:d-M-y',
        'isActive' => 'boolean'
    ];

}
