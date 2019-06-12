<?php

namespace App\Http\Controllers;

use App\ApprovedPurchaseOrder;
use App\BatchLine;
use App\PurchaseOrder;
use App\RejectReason;
use App\Setting;
use http\Env\Response;
use Illuminate\Http\Request;
use LotTracker\AppprovePurchaseOrders;
use LotTracker\PurchaseOrders;
use Session;

class ApprovedPurchaseOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $batches = PurchaseOrder::find($id);

        if (count($batches->batches) < 1){

        return response()->json('nobatches');
        }
//        $qty = AppprovePurchaseOrders::init()->validateQty($id);
//
//        if($qty !=PurchaseOrder::find($id)->fQuantity){
//
//           return response()->json(['qty' => 'Sorry,sum quantities must be equal to PO quantity']);
//        }

//       if (Setting::first()->enable_inspection == Setting::ENABLE_INSPECTION){
//           foreach ($batches as $batch){
//                if ($batch->qc_done == 0){
//                   return response('fail');
//               }
//           }
//       }



      AppprovePurchaseOrders::init()->storeToSage($id);
      //  return response()->json($batches);
       }
       public function approvePO($id)
    {

        $batches = PurchaseOrder::find($id);

        if (count($batches->lines) < 1){

        return response()->json('nobatches');
        }



      AppprovePurchaseOrders::init()->storeToSage_2($id);
      
       }

    public function processPos($id)
    {
        PurchaseOrder::find($id)->update(['status' => PurchaseOrder::PROCESSED_STATUS]);
        return response()->json($id);
       }

    public function poStatus($id)
    {
        $batches = PurchaseOrder::find($id);
          if (count($batches->batches) < 1){
            return response('nodata');
        }
        foreach ($batches->batches as $batch){
               if ($batch->status == PurchaseOrder::PENDING_STATUS){
                return response('fail');
            }
        }
        if (Setting::first()->enable_inspection == Setting::DISABLE_INSPECTION){
            return response('proceed');
        }

        return response('success');

       }
       public function poStatus_2($id){
        $batches = PurchaseOrder::find($id);
            if (count($batches->lines) < 1){
          return response('nodata');
      }
      foreach ($batches->lines as $batch){
             if ($batch->status == PurchaseOrder::PENDING_STATUS){
             return response('fail');
          }
      }
      if (Setting::first()->enable_inspection == Setting::DISABLE_INSPECTION){
          return response('proceed');
      }

      return response('success');
       }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('pos.approved.edit')->with('batches',PurchaseOrder::find($id)->batches)->with('id',$id)->with('reasons',RejectReason::all());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        BatchLine::find($request->get('id'))->update([
            'qty_rejected' => $request->get('qty_rejected'),
            'qty_accepted' => $request->get('qty_accepted'),
            'reject_reason' => $request->get('rejection_reason'),
            'qc_done' => 1
        ]);
        Session::flash('success','Inspection successfully done.');
        return response()->json($request->all());
    }

    public function newReason()
    {

        RejectReason::create(['reason' => request()->get('reason')]);
        Session::flash('success','Reason was successfully added');
        return redirect()->back();
    }

    public function approveAll($id)
    {
     $batches = PurchaseOrder::find($id);

     foreach ($batches->batches as $batch){
         BatchLine::find($batch->id)->update(['status' => PurchaseOrder::APPROVED_STATUS]);
     }

     return response('success');
    }
    public function approveAllBal($id)
    {
       

     $batches = PurchaseOrder::find($id);
       foreach ($batches->lines as $batch){
         BatchLine::find($batch->id)->update([
             'status' => PurchaseOrder::APPROVED_STATUS,
             'qty_accepted' => $batch->qty,
             'qty_received' => $batch->qty
             ]);
     }

     return response('success');
    }

    public function inspectAll($id)
    {
        $batches = PurchaseOrder::find($id);

        foreach ($batches->batches as $batch){
            BatchLine::find($batch->id)->update([
                'qc_done' => 1,
                'qty_received' => $batch->qty,
                'qty_accepted' => $batch->qty
            ]);
        }

        return response('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
