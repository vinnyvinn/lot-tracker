<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarehouseMaster extends Model
{
    protected $connection='sqlsrv2';
    protected $table='Whsemst';
}
