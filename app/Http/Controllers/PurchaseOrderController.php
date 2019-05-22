<?php

namespace App\Http\Controllers;

use App\BatchLine;
use App\PurchaseOrder;
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
       return view('pos.index')->with('pos',PurchaseOrder::all());
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

}
