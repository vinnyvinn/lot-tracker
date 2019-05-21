<?php


namespace LotTracker;


use App\BatchLine;
use App\InvoiceLine;

class Reports
{
static function init(){
    return new self();
}

    public function checkType($data)
    {

if($data['s_p'] =='so'){
    return self::getSo($data);
}
return self::getPos($data);
}

    public function getPos($data)
    {
        $date_from = date('Y/m/d',strtotime($data['date_from']));
        $date_to = date('Y/m/d',strtotime($data['date_to']));
$po = BatchLine::whereBetween('updated_at',[$date_from,$date_to])->get();
return $po;
}

    public function poDetails($data)
    {
        $date_from = date('Y/m/d',strtotime($data['date_from']));
        $date_to = date('Y/m/d',strtotime($data['date_to']));
        $po = BatchLine::whereBetween('updated_at',[$date_from,$date_to])->get();
        return collect($po)->map(function ($item){

            return [
              'PO' => $item->po,
              'Item' => $item->item,
              'Batch' => $item->batch,
              'Quantity' => $item->qty,
              'Expiry Date' => $item->actual_expiry,
              'Date Created' => date('d-m-Y',strtotime($item->updated_at)),
              'Status' => $item->status
            ];
        });
}

    public function getSo($data)
    {
        $date_from = date('Y/m/d',strtotime($data['date_from']));
        $date_to = date('Y/m/d',strtotime($data['date_to']));
        $so = InvoiceLine::whereBetween('created_at',[$date_from,$date_to])->get();
        $batches =[];
        foreach ($so as $ln){
            if($ln->batch_data){
                foreach (json_decode($ln->batch_data) as $bt){
                    $batches [] = [
                       'order_no' => $ln->OrderNum,
                        'account_name' => $ln->cAccountName,
                        'item' => $ln->item,
                        'batch' => $bt->name,
                        'qty' => $bt->qty,
                        'date' => date('d/m/Y',strtotime($ln->InvDate)),
                        'status' => $ln->status,
                        'created' => date('d/m/Y',strtotime($ln->created_at))
                    ];
                }
            }
        }
return collect($batches);
}


}