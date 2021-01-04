<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = [];

    protected $with = [
        'campaign:id,campaign_name',
        'city:id,city_name'
    ];

    public function campaign()
    {
        return $this->belongsTo('App\Campaign');
    }
    public function city()
    {
        return $this->belongsTo('App\City');
    }
}
