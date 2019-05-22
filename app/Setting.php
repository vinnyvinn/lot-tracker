<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded =[];
    const ENABLE_INSPECTION =1;
    const DISABLE_INSPECTION=0;
}
