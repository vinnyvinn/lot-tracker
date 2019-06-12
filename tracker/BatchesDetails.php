<?php
/**
 * Created by PhpStorm.
 * User: vinnyvinny
 * Date: 3/23/19
 * Time: 12:58 PM
 */

namespace LotTracker;


use App\ApprovedPurchaseOrder;
use App\BatchLine;
use App\SaleOrder;
use Carbon\Carbon;
use function PHPSTORM_META\map;

class BatchesDetails
{
static function init(){
    return new self();
}

    public function sampleData()
    {
      $data []= [
           'PO' => 'P001',
           'ITEM CODE' => 'I-01',
           'BATCH / SERIAL NO' => 'B-01',
           'QTY' => 50,
           'EXPIRY' => date('d/m/Y')

       ];

      return collect($data);
}
    public function sampleOp()
    {
        $data []= [
            'PO' => 'Opening Balance('.date('d/m/Y H:i:s').')',
            'ITEM CODE' => 'I-01',
            'BATCH / SERIAL NO' => 'B-01'
        ];

        return collect($data);
    }

    public function qtyAvailable($id)
    {
        $inv_lines = SaleOrder::find($id);

        $so_data =[];
        foreach ($inv_lines->lines as $so){
            $b_count = ApprovedPurchaseOrder::leftjoin('approved_sale_orders','approved_sale_orders.item','=','approved_purchase_orders.item')
                ->select('approved_purchase_orders.id','approved_purchase_orders.item','approved_purchase_orders.batch')
                ->where('approved_purchase_orders.item',$so->item)->get();
            $so_data[]= [
                'InvDate' => Carbon::parse($so->InvDate)->format('d/m/Y'),
                'OrderNum' => $so->OrderNum,
                'cAccountName' => $so->cAccountName,
                'item' => $so->item,
                'cDescription' => $so->cDescription,
                'fQuantity' => $so->fQuantity,
                'qty_received' => $so->qty_received,
                'qty_remaining' => $so->qty_remaining,
                'status' => $so->status,
                'qty_available' => $b_count->count(),
                'batches' => collect($b_count)->flatten(1),
                'id' => $so->id
            ];

        }


      return collect($so_data);

    }

}
