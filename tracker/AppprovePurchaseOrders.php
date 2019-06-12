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
use App\Warehouse;
use Session;
use App\BtblInvoiceLines;
use Carbon\Carbon;

class AppprovePurchaseOrders
{
static function init(){
    return new self();
}

    public function storeToSage($id)
    {
        $batches = PurchaseOrder::find($id);
        foreach ($batches->batches as $b){
            if ($b->qty_received < $b->qty){
                $batches->update(['state' => PurchaseOrder::PARTIALLY_RECEIVED_STATE]);
            }
            elseif ($b->qty_received == $b->qty){
                $batches->update(['state' => PurchaseOrder::FULLY_RECEIVED_STATE]);
            }
        }
       PurchaseOrder::find($id)->update(['status' =>PurchaseOrder::APPROVED_PO]);
       if(PurchaseOrder::find($id)->open_balance ==1){
          return self::postLines($id);
       }
        foreach ($batches->batches as $batch){
            foreach (json_decode($batch->batch) as $b){
                ApprovedPurchaseOrder::create([
                    'po' => $batch->po,
                    'item' => $batch->item,
                    'batch' => $b->lot_serial,
                    'expiry_date' => $batches->expiry_date,
                    'qty' => $b->qty,
                    'status' => $batch->status,
                    'type' => $batch->type,
                    'warehouse' => Warehouse::find($batch->warehouse)->code
                ]);

                BtblInvoiceLines::where('idInvoiceLines', $batch->idInvoiceLines)
                           ->update(['fQtyToProcess' => $b->qty,'_btblInvoiceLines_dModifiedDate' => Carbon::now()]);
            }

        }

return true;

}
public function storeToSage_2($id)
    {
        $batches = PurchaseOrder::find($id);
        foreach ($batches->lines as $b){
            if ($b->qty_received < $b->qty){
                $batches->update(['state' => PurchaseOrder::PARTIALLY_RECEIVED_STATE]);
            }
            elseif ($b->qty_received == $b->qty){
                $batches->update(['state' => PurchaseOrder::FULLY_RECEIVED_STATE]);
            }
        }
       PurchaseOrder::find($id)->update(['status' =>PurchaseOrder::APPROVED_PO]);
           
       foreach (PurchaseOrder::find($id)->lines as $batch){
            ApprovedPurchaseOrder::create([
            'po' => $batch->po,
            'item' => $batch->item,
            'batch' => $batch->batch,
            'expiry_date' => $batch->expiry_date,
            'qty' => $batch->qty,
            'status' => $batch->status,
            'type' => $batch->type,
            'warehouse' => Warehouse::find($batch->warehouse)->code
        ]);
            }
return true; 

}
public function postLines($id){
    foreach (PurchaseOrder::find($id)->batches as $batch){
       
            ApprovedPurchaseOrder::create([
                'po' => $batch->po,
                'item' => $batch->item,
                'batch' => $batch->batch,
                'expiry_date' => $batch->expiry_date,
                'qty' => $batch->qty,
                'status' => $batch->status,
                'type' => $batch->type,
                'warehouse' => Warehouse::find($batch->warehouse)->code
            ]);
        

    }
return true;    
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
