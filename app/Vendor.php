<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'Vendor';
    protected $connection = 'sqlsrv2';
}
