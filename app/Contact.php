<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = [];

    protected $with = [
        'group:id,group_name',
        'city:id,city_name',
        'user:id,name,email'
    ];

    public function group()
    {
        return $this->belongsTo('App\Group');
    }
    public function city()
    {
        return $this->belongsTo('App\City');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
