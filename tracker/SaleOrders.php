<?php


namespace LotTracker;


use App\ApprovedSaleOrder;
use App\BatchLine;
use App\BtblInvoiceLines;
use App\Invnum;
use App\InvoiceLine;
use App\SaleOrder;
use App\Setting;
use DB;
use Carbon\Carbon;


class SaleOrders
{

    static function sales(){
        return new self();
    }

    function getAll(){
        $sales =  Invnum::join('Client','Client.DCLink','=','InvNum.AccountID')
            ->join('_btblInvoiceLines', '_btblInvoiceLines.iInvoiceID', '=', 'InvNum.AutoIndex')
            ->join('StkItem','StkItem.StockLink','=','_btblInvoiceLines.iStockCodeID')
            ->where('Doctype', 4)
            ->where('DocState','<>',4)
            ->orWhereIn('StkItem.ulIIItemType',['LOT','SERIAL'])
            ->select('Invnum.AutoIndex', 'Invnum.OrderNum', 'Invnum.InvDate','Client.Name', '_btblInvoiceLines.cDescription','_btblInvoiceLines.iWarehouseID', '_btblInvoiceLines.fQuantity','StkItem.ulIIItemType','StkItem.Code')
            ->get();

      self::checkInvoices($sales);
    }

    public function checkInvoices($invoices)
    {

        $vailable_invoices = SaleOrder::get();
        $inv_ids = [];
        $invoices_found = [];

        if(count($vailable_invoices) < 1){
             self::storeInvoices($invoices);
        }
        foreach ($vailable_invoices as $inv){

            $inv_ids[] = $inv->auto_index;
        }
        foreach ($invoices as $invoice){
            if(!in_array($invoice->AutoIndex,$inv_ids)){
                $invoices_found[] = $invoice;
            }
        }

        if ($invoices_found){
          self::storeInvoices($invoices_found);
        }

        return true;
    }

    public function storeInvoices($invoices)
    {

        collect($invoices)->map(function ($inv) {
           self::getInlinesDetails($inv->AutoIndex);
            SaleOrder::create([
                'auto_index' => $inv->AutoIndex,
                'OrderNum' => $inv->OrderNum,
                'InvDate' => $inv->InvDate,
                'client' => $inv->Name,
                'cDescription' => $inv->cDescription,
                'fQuantity' => $inv->fQuantity,
                'status' => SaleOrder::STATUS_NOT_ISSUED,
                'type' => $inv->ulIIItemType ? $inv->ulIIItemType :'Not Set',
                'item' => $inv->Code

            ]);
        });

        return true;
    }

    public function storeInvoiceLines($inlinesDetails)
    {

   collect($inlinesDetails)->map(function ($line){
    InvoiceLine::create([
        'auto_index' => $line->AutoIndex,
        'OrderNum' => $line->OrderNum,
        'InvDate' => $line->InvDate,
        'cDescription' => $line->cDescription,
        'fQuantity' => $line->fQuantity,
        'status' => InvoiceLine::STATUS_PENDING,
        'item' => $line->item,
        'idInvoiceLines' => $line->idInvoiceLines,
        'GrvNumber' => $line->GrvNumber,
        'cAccountName' => $line->cAccountName,
        'fUnitCost' => $line->fUnitCost,
        'fQtyToProcess'=>$line->fQtyToProcess,
        'fUnitPriceExcl' => $line->fUnitPriceExcl,
        'iStockCodeID' => $line->iStockCodeID,
        'qty_remaining' => $line->fQuantity

    ]);
});
return true;
}

    public function getInlinesDetails($autoindex)
    {
        $inlinesDetails = Invnum::join('_btblInvoiceLines','_btblInvoiceLines.iInvoiceID','=','InvNum.AutoIndex')
            ->join('StkItem','StkItem.StockLink','=','_btblInvoiceLines.iInvoiceID')
            ->SELECT('InvDate','AutoIndex','_btblInvoiceLines.idInvoiceLines','GrvNumber','OrderNum','cAccountName','_btblInvoiceLines.cDescription',
                '_btblInvoiceLines.fUnitCost','_btblInvoiceLines.fQuantity','_btblInvoiceLines.fQtyToProcess','_btblInvoiceLines.fUnitPriceExcl',
                '_btblInvoiceLines.iStockCodeID','_btblInvoiceLines.iStockCodeID AS CODE','StkItem.ItemGroup','StkItem.Code as item')
            ->where('AutoIndex',$autoindex)
            ->get();
        self::storeInvoiceLines($inlinesDetails);
    }

    public function updateLines($id)
    {

        $lines = SaleOrder::find($id);
     foreach ($lines->lines as $ln){
           if($ln->qc_done ==0 && Setting::first()->enable_inspection == Setting::ENABLE_INSPECTION){
               return 'fail';
           }
       }
        if (count($lines->lines) < 1){
            return 'noitems';
        }
        foreach ($lines->lines as $ln){
            foreach (json_decode($ln->batch_data) as $b){
                foreach (BatchLine::where('id',$b->lot_number)->get() as $value){
                    ApprovedSaleOrder::create([
                        'po' => $value->po,
                        'item' => $value->item,
                        'batch'=> $b->name,
                        'expiry_date' => $value->expiry_date,
                        'qty' => $b->qty,
                        'status' => $value->status,
                        'posted' => 1
                    ]);
                }
            }
        }

        collect($lines->lines)->map(function ($line) {
                 BtblInvoiceLines::where('idInvoiceLines', $line->idInvoiceLines)
                ->where('iStockCodeID', $line->iStockCodeID)
                ->update(['fQtyToProcess' => $line->fQuantity,'_btblInvoiceLines_dModifiedDate' => Carbon::now()]);
        });
        $lines->update(['status' => SaleOrder::STATUS_ISSUED]);
        return $lines;
    }
    function getBatchLines($id){
        $batches = InvoiceLine::find($id);
        $batch_data = [];
                foreach (json_decode($batches->batch_data) as $b){
                foreach (BatchLine::where('id',$b->lot_number)->get() as $value){
                    $batch_data[] = [
                        'po' => $value->po,
                        'item' => $value->item,
                        'batch'=> $b->name,
                        'expiry_date' => $value->expiry_date,
                        'qty' => $b->qty,
                        'status' => $batches->status,
                        'reason' => $batches->reject_reason
                    ];
               }
        }
  return collect($batch_data);
    }
}
