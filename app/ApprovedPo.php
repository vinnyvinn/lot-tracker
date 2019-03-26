<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovedPo extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'approved_purchase_orders';
    protected $guarded = [];
}
