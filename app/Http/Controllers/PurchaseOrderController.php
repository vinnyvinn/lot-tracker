<?php

namespace App\Http\Controllers;

use App\BatchLine;
use App\PurchaseOrder;

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
}
