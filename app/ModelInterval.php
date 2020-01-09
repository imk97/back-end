<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelInterval extends Model
{
    //
    protected $table = 'item';
    protected $fillable = [
        'model', 'interval','item1','item2','item3'
    ];
}
