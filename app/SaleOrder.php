<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    protected $guarded =[];

    const STATUS_ISSUED='Issued';
    const STATUS_NOT_ISSUED='Not Issued';
    const STATUS_PROCESSED = 'Processed';

    function lines(){
        return $this->hasMany(InvoiceLine::class,'auto_index','auto_index');
    }
}
