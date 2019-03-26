<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invnum extends Model
{
    protected $table = 'InvNum';
    protected $connection = 'sqlsrv2';
}
