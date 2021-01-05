<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Smsdata extends Model
{
    protected $guarded = [];

    protected $with = [
        'user:id,name,email'
    ];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
