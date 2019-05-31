<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $guarded =[];

    function serials(){
        return $this->hasMany(BatchLine::class,'warehouse');
    }
}
