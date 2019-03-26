<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'OrdersSt';
    protected $connection = 'sqlsrv2';
}
