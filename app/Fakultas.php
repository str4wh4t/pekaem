<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    //
    protected $primaryKey = 'kodeF'; // or null
    public $incrementing = false;
    protected $keyType = 'string';

    // protected $dateFormat = 'Y-m-d H:i:s';
    public $timestamps = false;
}
