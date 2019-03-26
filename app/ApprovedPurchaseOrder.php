<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovedPurchaseOrder extends Model
{
    protected $connection ='sqlsrv2';
    protected $guarded = [];
}
