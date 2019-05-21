<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
    protected $guarded = [];

    const STATUS_APPROVED = 'Approved';
    const STATUS_REJECTED = 'Rejected';
    const STATUS_PENDING = 'Pending';
    const STATE_NOT_RECEIVED= 'Not Received';
    const STATE_PARTIALLY_RECEIVED = 'Partially Received';
    const STATE_FULLY_RECEIVED = 'Fully Received';

function batch_lines(){
    return $this->hasMany(BatchLine::class,'auto_index','auto_index');
}
}
