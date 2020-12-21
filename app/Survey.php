<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function district()
    {
        return $this->belongsTo('App\District');
    }
    

    public function job()
    {
        return $this->belongsTo('App\Job');
    }

    protected $casts = [
        'created_at' => 'datetime:d-M-y',
        'attachment' => 'array'
    ];
}
