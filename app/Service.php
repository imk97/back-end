<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
    protected $table = 'service';
    protected $fillable = [
        'u_id', 'type', 'start_time', 'end_time'
    ];
}
