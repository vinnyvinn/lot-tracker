<?php


namespace LotTracker;


use App\BtblInvoiceLines;
use App\Invnum;
use App\InvoiceLine;
use App\SaleOrder;
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
            ->select('Invnum.AutoIndex', 'Invnum.OrderNum', 'Invnum.InvDate', 'Client.Name', '_btblInvoiceLines.cDescription', '_btblInvoiceLines.fQuantity','StkItem.ulIIItemType','StkItem.Code')
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
                'type' => $inv->ulIIItemType,
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
        'iStockCodeID' => $line->iStockCodeID

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
        if (count($lines->lines) < 1){
            return 'noitems';
        }
        collect($lines->lines)->map(function ($line) {
                 BtblInvoiceLines::where('idInvoiceLines', $line->idInvoiceLines)
                ->where('iStockCodeID', $line->iStockCodeID)
                ->update(['fQtyToProcess' => $line->fQuantity,'_btblInvoiceLines_dModifiedDate' => Carbon::now()]);
        });
        $lines->update(['status' => SaleOrder::STATUS_ISSUED]);
        return true;
    }

}
