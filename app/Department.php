<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [ 'department' ];

    protected $casts = [
        'created_at' => 'datetime:d-M-y',
    ];
}
