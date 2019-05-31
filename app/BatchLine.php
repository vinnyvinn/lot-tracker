<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BatchLine extends Model
{

    protected $guarded = [];

    function purchase_order(){
        return $this->belongsTo(PurchaseOrder::class);
    }
}
