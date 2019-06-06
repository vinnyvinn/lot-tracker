<?php

namespace App\Http\Controllers;

use App\BatchLine;
use App\BtblInvoiceLines;
use App\Invnum;
use App\InvoiceLine;
use App\RejectReason;
use App\SaleOrder;
use App\Setting;
use Illuminate\Http\Request;
use LotTracker\Reports;
use LotTracker\SaleOrders;
use Session;

class SaleOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       SaleOrders::sales()->getAll();

        return view('sales.index')->with('sales', SaleOrder::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
     return view('sales.show_batches')->with('batches',SaleOrders::sales()->getBatchLines($id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

     Reports::init()->checkType($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


        if(SaleOrder::find($id)->status== SaleOrder::STATUS_NOT_ISSUED){
         return view('sales.show')->with('inv_lines',SaleOrder::find($id));
        }
        elseif(SaleOrder::find($id)->status== SaleOrder::STATUS_PROCESSED){

           return view('sales.create')->with('inv_lines',SaleOrder::find($id))->with('reasons',RejectReason::all());
        }
     elseif (SaleOrder::find($id)->status== SaleOrder::STATUS_ISSUED){
         return view('sales.issued')->with('inv_lines',SaleOrder::find($id))->with('reasons',RejectReason::all());
     }

    }

    public function addReason()
    {
        RejectReason::create(['reason' => request()->get('reason')]);
        Session::flash('success','Reason was successfully added');
        return redirect('/so/'.request()->get('id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       return view('sales.edit')->with('batches',InvoiceLine::find($id));
    }

    public function fetchSo($id)
    {
        $inv = InvoiceLine::find($id);
        $batches = [];
        $b_ids = [];
        if ($inv->batch_data){
          foreach (json_decode($inv->batch_data) as $bt){
             $b_ids[] = $bt->lot_number;
          }
            foreach ($inv->batch_lines as $b){
                if(!in_array($b->id,collect($b_ids)->flatten()->toArray())){
                    $batches[] = $b;
                }
            }
        }
        return response()->json(['lines' => $inv,'batches' => $batches ? $batches :$inv->batch_lines]);
    }
    public function updateSo(Request $request, $id)
    {

        InvoiceLine::find($id)->update($request->except('_token'));
        return response()->json($request->all());
    }

    public function lotQty($id)
    {
  $qty =0;
  foreach (request()->get('ids') as $idd){
      $qty += BatchLine::find($idd)->actual_qty;
  }

   $inv = InvoiceLine::find($id);
  if (($inv->qty_remaining -$qty) < 0 ){
      return response('fail');
  }

       return response()->json($qty);
}

    public function updateInvLines()
    {

        $inv =InvoiceLine::find(request()->get('id'));
        $my_data= [];
        $clean_data =[];
         if($inv->batch_data){
          $my_data[] = $inv->batch_data;
        }
       $batch_data[] = [
            'lot_number' => request()->get('lot_number'),
            'qty' => request()->get('fQuantity'),

        ];
       array_push($my_data,json_encode($batch_data));

foreach (json_decode(collect($my_data)) as $d){
    foreach (collect(json_decode($d)) as $value){

             $clean_data[] =[
            'lot_number' => $value->lot_number,
            'qty' => $value->qty,

        ];
    }
    }
if (($inv->qty_remaining-request()->get('fQuantity')) < 0 ){
    session::flash('success','Sorry,Quantity Issued cannot be more than than the available Qty.');
    return redirect()->back();
}

        $inv->update([
            'batch_data' => json_encode($clean_data),
            'qty_remaining' => $inv->qty_remaining-request()->get('fQuantity'),
            'qty_received' => $inv->qty_received+request()->get('fQuantity'),
        ]);
        $inv->update([
            'state' => $inv->qty_remaining < 1 ? InvoiceLine::STATE_FULLY_RECEIVED : InvoiceLine::STATE_PARTIALLY_RECEIVED
        ]);

  Session::flash('success','Operation successfully performed.');
  return redirect()->back();

}
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        InvoiceLine::find($request->get('id'))->update([
            'qty_rejected' => $request->get('qty_rejected'),
            'qty_accepted' => $request->get('qty_accepted'),
            'reject_reason' => $request->get('rejection_reason'),
            'qc_done' => 1,
            'state' => $request->get('actual_qty_2') == $request->get('qty_accepted') ? InvoiceLine::STATE_FULLY_RECEIVED : InvoiceLine::STATE_PARTIALLY_RECEIVED
        ]);
        Session::flash('success','Inspection successfully done.');
        return redirect()->back();
    }

    public function approvedSo($id)
    {
       $so =SaleOrders::sales()->updateLines($id);

       return response()->json($so);



    }

    public function processSo($id)
    {

    $so = SaleOrder::find($id);
    foreach ($so->lines as $ln){
    if($ln->status == InvoiceLine::STATUS_PENDING){
        return response('fail');
    }
        if (Setting::first()->enable_inspection == Setting::DISABLE_INSPECTION){
            return response('proceed');
        }
}
    $so->update(['status' => SaleOrder::STATUS_PROCESSED]);
   return redirect('/so');
    }

    public function approveAll($id)
    {

        $saleso = SaleOrder::find($id);

        foreach ($saleso->lines as $so){

            InvoiceLine::find($so->id)->update(['status' => InvoiceLine::STATUS_APPROVED]);
        }
        return response('success');
    }

    public function inspectAll($id)
    {


        $saleso = SaleOrder::find($id);
        foreach ($saleso->lines as $so){
            InvoiceLine::find($so->id)->update([
                'qty_received' => $so->fQuantity,
                'qty_accepted' => $so->fQuantity,
                'qc_done' => 1
            ]);
        }

        return response('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
