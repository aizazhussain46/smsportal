<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    protected $fillable = [
        's_id', 'r_id', 'job_id', 'msg'
    ];

    protected $with = ['sender:id,name,master','reciever:id,name'];

    public function sender()
    {
        return $this->belongsTo('App\User','s_id');
    }

    public function reciever()
    {
        return $this->belongsTo('App\User','r_id');
    }

    protected $casts = [
        'created_at' => 'datetime:d-M-y',
    ];
}
