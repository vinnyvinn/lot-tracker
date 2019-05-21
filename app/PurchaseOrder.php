<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{

protected $connection ='sqlsrv';

const ARRIVED_PO= 'ARRIVED';
const APPROVED_PO = 'APPROVED';
const RECEIVED_STATUS= 'RECEIVED';
const APPROVED_STATUS= 'APPROVED';
const REJECTED_STATUS= 'REJECTED';
const  INSPECTED_STATUS = 'INSPECTED';
const PROCESSED_STATUS = 'PROCESSED';
const PENDING_STATUS= 'PENDING';
const POSTED_STATUS= 'POSTED';
const POSTED_TO_SAGE = 1;
const NOT_POSTED_TO_SAGE = 0;


protected $guarded = [];

    public function batches()
    {
        return $this->hasMany(BatchLine::class);
}
}
