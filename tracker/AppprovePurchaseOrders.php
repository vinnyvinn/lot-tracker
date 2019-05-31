<?php
/**
 * Created by PhpStorm.
 * User: vinnyvinny
 * Date: 3/25/19
 * Time: 9:10 AM
 */

namespace LotTracker;


use App\ApprovedPurchaseOrder;
use App\PurchaseOrder;
use Session;

class AppprovePurchaseOrders
{
static function init(){
    return new self();
}

    public function storeToSage($id)
    {

        $batches = PurchaseOrder::find($id)->batches;
            PurchaseOrder::find($id)->update(['status' =>PurchaseOrder::APPROVED_PO,'state' => PurchaseOrder::POSTED_TO_SAGE]);
        $batches->map(function ($batch){
           ApprovedPurchaseOrder::create([
               'po' => $batch->po,
               'item' => $batch->item,
               'batch' => $batch->batch,
               'expiry_date' => $batch->expiry_date,
               'qty' => $batch->qty,
               'status' => $batch->status,
               'warehouse' => $batch->warehouse
           ]);
        });


}

    public function validateQty($id)
    {
        $batches = PurchaseOrder::find($id)->batches;
        $total =0;
        foreach ($batches as $batch){
            $total +=$batch->actual_qty;
        }
        return $total;
}
}
