<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    //

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
}
