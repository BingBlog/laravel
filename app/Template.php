<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    // primaryKey
    protected $primaryKey = 'format_id';

    public $timestamps = false;
}
