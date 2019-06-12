<?php
/**
 * Created by PhpStorm.
 * User: vinnyvinny
 * Date: 3/22/19
 * Time: 5:42 PM
 */

namespace LotTracker;

use App\BatchLine;
use App\Invnum;
use App\PurchaseOrder;


class PurchaseOrders
{
    static function init()
    {
        return new self();
    }
    public function getPOs()
    {
        $pos =Invnum::select('OrderNum', 'AutoIndex','InvDate', 'cAccountName', 'OrdTotExcl', 'OrdTotTax', 'OrdTotIncl')
            ->where('DocType' , 5)
            ->whereIn('DocState', [1,3])
            ->where('Docflag',1)
            ->get();

        self::checkPos($pos);
    }

    public function storePos($pos)
    {
        collect($pos)->map(function ($po) {
            self::invLines($po->AutoIndex);
            PurchaseOrder::create([
                'auto_index' => $po->AutoIndex,
                'OrderNum' => $po->OrderNum,
                'InvDate' => $po->InvDate,
                'cAccountName' => $po->cAccountName,
                'OrdTotExcl' => number_format((float)$po->OrdTotExcl, 2, '.', ''),
                'OrdTotIncl' =>number_format((float)$po->OrdTotIncl, 2, '.', ''),
                'OrdTotTax' =>number_format((float)$po->OrdTotTax, 2, '.', ''),
                'status' => PurchaseOrder::ARRIVED_PO,
                'type' =>$po->ulIIItemType
            ]);
        });
     return true;
    }

    public function invLines($AutoIndex)
    {
        $pos =  Invnum::join('Client','Client.DCLink','=','InvNum.AccountID')
            ->join('_btblInvoiceLines', '_btblInvoiceLines.iInvoiceID', '=', 'InvNum.AutoIndex')
            ->join('StkItem','StkItem.StockLink','=','_btblInvoiceLines.iStockCodeID')
            ->WhereIn('StkItem.ulIIItemType',['LOT','SERIAL'])
            ->where('_btblInvoiceLines.iInvoiceID',$AutoIndex)
            ->select('Invnum.AutoIndex', 'Invnum.OrderNum', 'StkItem.ulIIItemType','_btblInvoiceLines.iInvoiceID','Invnum.InvDate','Client.Name', 'Invnum.cAccountName','_btblInvoiceLines.cDescription','_btblInvoiceLines.iWarehouseID', '_btblInvoiceLines.fQuantity','_btblInvoiceLines.idInvoiceLines','_btblInvoiceLines.fUnitCost','_btblInvoiceLines.fQtyToProcess','_btblInvoiceLines.fQtyProcessed','_btblInvoiceLines.fUnitPriceExcl','StkItem.ulIIItemType', '_btblInvoiceLines.iStockCodeID','_btblInvoiceLines.iStockCodeID','StkItem.ItemGroup','StkItem.Code')
            ->get();

        self::storeInvLines($pos);
    }

    public function storeInvLines($inlinesDetails)
    {
        foreach ($inlinesDetails as $line){
            BatchLine::create([
                'po' => $line->OrderNum,
                'item' => $line->Code,
                'expiry_date' => date('d/m/Y'),
                'actual_expiry' => date('d/m/Y'),
                'status' => PurchaseOrder::PENDING_STATUS,
                'idInvoiceLines' => $line->idInvoiceLines,
                'auto_index' => $line->AutoIndex,
                'iInvoiceID' =>$line->iInvoiceID,
                'qty' => $line->fQuantity - $line->fQtyProcessed,
                'actual_qty' => $line->fQuantity - $line->fQtyProcessed,
                'warehouse' => \App\Warehouse::first()->id,
                'purchase_order_id' => PurchaseOrder::count()+1,
                'type' => $line->ulIIItemType
            ]);
        }
    }

    public function checkPos($pos)
    {
        $all_pos = PurchaseOrder::get();
        $results = [];
        $results_found = [];
        if ($all_pos->count() > 0) {
            foreach ($all_pos as $result) {
                $results[] = $result->auto_index;
            }
            foreach ($pos as $p) {
                if (!in_array($p->AutoIndex, $results)) {
                    $results_found[] = $p;
                }
            }
            if (count($results_found) > 0) {
                return self::storePos($results_found);
            } else {
                return true;
            }
        }
       self::storePos(self::allPos());
    }

    public function allPos()
    {
        $pos = Invnum::select('OrderNum', 'AutoIndex','InvDate', 'cAccountName', 'OrdTotExcl', 'OrdTotTax', 'OrdTotIncl')
            ->where('DocType' , 5)
            ->whereIn('DocState', [1,3])
            ->where('Docflag',1)
            ->get();
       return $pos;
    }
}
