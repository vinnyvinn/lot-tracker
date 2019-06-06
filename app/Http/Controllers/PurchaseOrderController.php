<?php

namespace App\Http\Controllers;

use App\BatchLine;
use App\PurchaseOrder;
use Carbon\Carbon;
use App\Warehouse;
use Illuminate\Http\Request;
use LotTracker\PurchaseOrders;
use Session;

class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
       
        PurchaseOrders::init()->getPOs();
        return view('pos.index')->with('pos',PurchaseOrder::all())->with('wh',Warehouse::all());
    }

    public function syncPO()
    {
    PurchaseOrders::init()->getPOs();
  Session::flash('success','Pos imported successfully');
  return redirect('/pos');
    }

    public function show($id)
    {
  return view('pos.show')->with('batches',PurchaseOrder::find($id));
    }

    public function transferSerial()
    {
      return view('batches.transfer')->with('pos',PurchaseOrder::all())->with('wh',Warehouse::all());
    }

    public function fetchBatches($id)
    {

        $batches =  Warehouse::find($id)->serials;
        if (count($batches)){
            return response()->json($batches);

        }
        else{
            return response('notfound');
        }
}

    public function storeSerial()
    {


        $batches = BatchLine::whereIn('id',request()->get('batch'))->get();
        if (count($batches)) {
            foreach ($batches as $batch) {
                BatchLine::find($batch->id)->update(['warehouse' => request()->get('to')]);
            }
        }
        Session::flash('success','Operation successfully done.');
        return redirect('pos');
}

    public function fetchWh($id)
    {
        $wh = Warehouse::where('id','!=',$id)->get();
       return response()->json($wh);
}

    public function addSerials()
    {
      return view('pos.create')->with('wh',Warehouse::all());
}

    public function newSerials()
    {

        $data = request()->get('addmore');
        $po = 'Opening Balance('.date('d/m/Y H:i:s').')';
        $i=0;
          foreach ($data as $b){
            $i++;
            BatchLine::create([
                'po' => $po,
                'item' => $b['item'],
                'qty'=>1,
                'batch' => $b['batch'],
                'expiry_date'=> date('d/m/Y'),
                'status' => PurchaseOrder::PENDING_STATUS,
                'actual_batch'=> $b['batch'],
                'actual_qty' => 1,
                'actual_expiry' =>date('d/m/Y'),
                'purchase_order_id' => PurchaseOrder::count()+1,
                'auto_index' => PurchaseOrder::orderby('id','desc')->first()->auto_index+1,
                'warehouse' => request()->get('warehouse')
            ]);
        }

        PurchaseOrder::create([
            'OrderNum' => $po,
            'InvDate' => Carbon::now(),
            'supplier' => 'SUPP01',
            'cDescription' => 'Lot Item 01',
            'fQuantity'=> $i,
            'type' => 'LOT',
            'auto_index' => PurchaseOrder::orderby('id','desc')->first()->auto_index+1,
            'status' => PurchaseOrder::RECEIVED_STATUS
        ]);

        Session::flash('success','Item Serials Imported successfully.');
        return redirect('/pos');
}
    public function moreSerials()
    {

     $serials = $this->comma_separated_to_array(request()->get('all_serials'));
        $po = 'Opening Balance('.date('d/m/Y H:i:s').')';
        $i=0;
     foreach ($serials as $s){
         $i++;
         BatchLine::create([
             'po' => $po,
             'item' => request()->get('item'),
             'qty'=>1,
             'batch' => $s,
             'expiry_date'=> date('d/m/Y'),
             'status' => PurchaseOrder::PENDING_STATUS,
             'actual_batch'=> $s,
             'actual_qty' => 1,
             'actual_expiry' =>date('d/m/Y'),
             'purchase_order_id' => PurchaseOrder::count()+1,
             'auto_index' => PurchaseOrder::orderby('id','desc')->first()->auto_index+1,
             'warehouse' => request()->get('warehouse')
         ]);
     }

        PurchaseOrder::create([
            'OrderNum' => $po,
            'InvDate' => Carbon::now(),
            'supplier' => 'SUPP01',
            'cDescription' => 'Lot Item 01',
            'fQuantity'=> $i,
            'type' => 'LOT',
            'auto_index' => PurchaseOrder::orderby('id','desc')->first()->auto_index+1,
            'status' => PurchaseOrder::RECEIVED_STATUS
        ]);

        Session::flash('success','Item Serials Imported successfully.');
        return redirect('/pos');
    }
    function comma_separated_to_array($string, $separator = ',')
    {
        //Explode on comma
        $vals = explode($separator, $string);

        //Trim whitespace
        foreach($vals as $key => $val) {
            $vals[$key] = trim($val);
        }
        //Return empty array if no items found

         return array_diff($vals, array(""));
    }
}
