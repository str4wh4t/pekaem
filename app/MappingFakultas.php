<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MappingFakultas extends Model
{
    //
    protected $table = 'mapping_fakultas';

    // protected $dateFormat = 'Y-m-d H:i:s';
    public function fakultas()
    {
        return $this->belongsTo('App\Fakultas', 'kodeF', 'kodeF');
    }
}
