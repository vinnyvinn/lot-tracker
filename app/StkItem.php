<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StkItem extends Model
{
    protected $connection='sqlsrv2';
    protected $table='StkItem';
}
