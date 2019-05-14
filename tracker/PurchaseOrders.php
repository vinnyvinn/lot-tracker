<?php
/**
 * Created by PhpStorm.
 * User: vinnyvinny
 * Date: 3/22/19
 * Time: 5:42 PM
 */

namespace LotTracker;

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
        $pos = Invnum::join('OrdersSt', 'OrdersSt.StatusCounter', '=', 'InvNum.OrderStatusID')
            ->join('Vendor', 'Vendor.DCLink', '=', 'InvNum.AccountID')
            ->join('_btblInvoiceLines', '_btblInvoiceLines.iInvoiceID', '=', 'InvNum.AutoIndex')
            ->join('StkItem','StkItem.StockLink','=','_btblInvoiceLines.iStockCodeID')
            ->where('Doctype', 5)
            ->where('DocState','<>',4)
            ->orWhereIn('StkItem.ulIIItemType',['LOT','SERIAL'])
            ->Where('OrdersSt.StatusDescrip', 'ARRIVED')
            ->select('Invnum.AutoIndex', 'Invnum.OrderNum', 'Invnum.InvDate', 'Vendor.Name', '_btblInvoiceLines.cDescription', '_btblInvoiceLines.fQuantity','StkItem.ulIIItemType')
            ->get();

        self::checkPos($pos);

    }

    public function storePos($pos)
    {
        collect($pos)->map(function ($po) {
            PurchaseOrder::create([
                'auto_index' => $po->AutoIndex,
                'OrderNum' => $po->OrderNum,
                'InvDate' => $po->InvDate,
                'supplier' => $po->Name,
                'cDescription' => $po->cDescription,
                'fQuantity' => $po->fQuantity,
                'status' => PurchaseOrder::ARRIVED_PO,
                'type' =>$po->ulIIItemType
            ]);
        });
        return true;
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
        $pos = Invnum::join('OrdersSt', 'OrdersSt.StatusCounter', '=', 'InvNum.OrderStatusID')
            ->join('Vendor', 'Vendor.DCLink', '=', 'InvNum.AccountID')
            ->join('_btblInvoiceLines', '_btblInvoiceLines.iInvoiceID', '=', 'InvNum.AutoIndex')
            ->join('StkItem','StkItem.StockLink','=','_btblInvoiceLines.iStockCodeID')
            ->where('Doctype', 5)
            ->where('DocState','<>',4)
            ->orWhereIn('StkItem.ulIIItemType',['LOT','SERIAL'])
            ->Where('OrdersSt.StatusDescrip', 'ARRIVED')
            ->select('Invnum.AutoIndex', 'Invnum.OrderNum', 'Invnum.InvDate', 'Vendor.Name', '_btblInvoiceLines.cDescription', '_btblInvoiceLines.fQuantity','StkItem.ulIIItemType')
            ->get();
       return $pos;
    }
}
