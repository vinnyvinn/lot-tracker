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
        $sales =Invnum::select('OrderNum', 'AutoIndex','InvDate', 'cAccountName', 'OrdTotExcl', 'OrdTotTax', 'OrdTotIncl')
            ->where('DocType' , 4)
            ->where('DocState', 1)
             ->get();

      self::checkInvoices($sales);
    }

    public function checkInvoices($invoices)
    {
        $vailable_invoices = SaleOrder::get();
        $inv_ids = [];
        $invoices_found = [];

        if(count($vailable_invoices) < 1){
            return self::storeInvoices($invoices);
        }
        foreach ($vailable_invoices as $inv){

            $inv_ids[] = $inv->OrderNum;
        }
        foreach ($invoices as $invoice){

            if(!in_array($invoice->OrderNum,$inv_ids)){
                $invoices_found[] = $invoice;
            }
        }

        if ($invoices_found){

         return self::storeInvoices($invoices_found);
        }
        else{
          return self::checkQty();
        }

    }

    public function checkQty()
    {

       $items = [];
        $invlines = [];
        $sales =Invnum::select('OrderNum', 'AutoIndex','InvDate', 'cAccountName', 'OrdTotExcl', 'OrdTotTax', 'OrdTotIncl')
            ->where('DocType' , 4)
            ->where('DocState', 1)
            ->get();
        foreach ($sales as $s){
            $invlines[] = Invnum::join('Client','Client.DCLink','=','InvNum.AccountID')
                ->join('_btblInvoiceLines', '_btblInvoiceLines.iInvoiceID', '=', 'InvNum.AutoIndex')
                ->join('StkItem','StkItem.StockLink','=','_btblInvoiceLines.iStockCodeID')
                ->where('Doctype', 4)
                ->where('DocState',1)
                ->WhereIn('StkItem.ulIIItemType',['LOT','SERIAL'])
                ->where('Invnum.OrderNum',$s->OrderNum)
                ->select('Invnum.AutoIndex', 'Invnum.OrderNum', 'Invnum.InvDate','Client.Name', 'Invnum.cAccountName','_btblInvoiceLines.cDescription','_btblInvoiceLines.iWarehouseID', '_btblInvoiceLines.fQuantity','_btblInvoiceLines.idInvoiceLines','_btblInvoiceLines.fUnitCost','_btblInvoiceLines.fQtyToProcess','_btblInvoiceLines.fUnitPriceExcl','StkItem.ulIIItemType', '_btblInvoiceLines.iStockCodeID','_btblInvoiceLines.iStockCodeID','StkItem.ItemGroup','StkItem.Code')
                ->get();
        }
        foreach (collect($invlines)->flatten() as $iv){

            foreach (InvoiceLine::where('idInvoiceLines',$iv->idInvoiceLines)->get() as $item){
                if ($item->fQuantity !=$iv->fQuantity){
                    $items[] = $iv;
                  }
            }
        }


        foreach (collect($items)->flatten() as $ii){

            InvoiceLine::where('idInvoiceLines','=',$ii->idInvoiceLines)->update([
               'fQuantity' => $ii->fQuantity,
                'fQtyToProcess' => $ii->fQtyToProcess ,
                'fUnitPriceExcl' => number_format((float)$ii->fUnitPriceExcl, 2, '.', ''),
                'qty_remaining' => $ii->fQuantity,
                'status' => InvoiceLine::STATUS_PENDING
            ]);

        }
     return true;


}
    public function storeInvoices($invoices)
    {

        foreach ($invoices as $inv){
        self::getInlinesDetails($inv->OrderNum);
        SaleOrder::create([
            'auto_index' => $inv->AutoIndex,
            'OrderNum' => $inv->OrderNum,
            'InvDate' => $inv->InvDate,
            'cAccountName' => $inv->cAccountName,
            'OrdTotExcl' => number_format((float)$inv->OrdTotExcl, 2, '.', ''),
            'OrdTotIncl' => number_format((float)$inv->OrdTotIncl, 2, '.', ''),
            'OrdTotTax' => number_format((float)$inv->OrdTotTax, 2, '.', ''),
            'status' => SaleOrder::STATUS_NOT_ISSUED,
        ]);
    }
  }
    public function storeInvoiceLines($inlinesDetails)
    {

foreach ($inlinesDetails as $line){
            InvoiceLine::create([
            'auto_index' => $line->AutoIndex,
            'OrderNum' => $line->OrderNum,
            'InvDate' => $line->InvDate,
            'cDescription' => $line->cDescription,
            'fQuantity' => $line->fQuantity,
            'status' => InvoiceLine::STATUS_PENDING,
            'item' => $line->Code,
            'idInvoiceLines' => $line->idInvoiceLines,
            'GrvNumber' => $line->GrvNumber,
            'cAccountName' => $line->cAccountName,
            'client' => $line->Name,
            'fUnitCost' => $line->fUnitCost,
            'fQtyToProcess'=>$line->fQtyToProcess,
            'fUnitPriceExcl' => $line->fUnitPriceExcl,
            'iStockCodeID' => $line->iStockCodeID,
            'qty_remaining' => $line->fQuantity,
            'type' => $line->ulIIItemType
        ]);
    }
}
    public function getInlinesDetails($OrderNum)
    {
      $sales =  Invnum::join('Client','Client.DCLink','=','InvNum.AccountID')
            ->join('_btblInvoiceLines', '_btblInvoiceLines.iInvoiceID', '=', 'InvNum.AutoIndex')
            ->join('StkItem','StkItem.StockLink','=','_btblInvoiceLines.iStockCodeID')
            ->where('Doctype', 4)
            ->where('DocState',1)
            ->WhereIn('StkItem.ulIIItemType',['LOT','SERIAL'])
           ->where('Invnum.OrderNum',$OrderNum)
            ->select('Invnum.AutoIndex', 'Invnum.OrderNum', 'Invnum.InvDate','Client.Name', 'Invnum.cAccountName','_btblInvoiceLines.cDescription','_btblInvoiceLines.iWarehouseID', '_btblInvoiceLines.fQuantity','_btblInvoiceLines.idInvoiceLines','_btblInvoiceLines.fUnitCost','_btblInvoiceLines.fQtyToProcess','_btblInvoiceLines.fUnitPriceExcl','StkItem.ulIIItemType', '_btblInvoiceLines.iStockCodeID','_btblInvoiceLines.iStockCodeID','StkItem.ItemGroup','StkItem.Code')
            ->get();
        self::storeInvoiceLines(collect($sales));
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
                        'po' => $ln->OrderNum,
                        'item' => $value->item,
                        'batch'=> $value->actual_batch,
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
                        'batch'=> $value->actual_batch,
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
