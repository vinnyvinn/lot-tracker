<?php

namespace App\Http\Controllers;

use App\BatchLine;
use App\BtblInvoiceLines;
use App\PurchaseOrder;
use App\RejectReason;
use Carbon\Carbon;
use Illuminate\Http\Request;
use LotTracker\BatchesDetails;
use Excel;
use Session;
class BatchesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('batches.index')->with('batches',BatchLine::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


    }

    public function createBatch($id)
    {
    return view('batches.create')->with('po',PurchaseOrder::find($id));
}

    public function storeBatch()
    {

       $po = PurchaseOrder::find(request()->get('id'));
         $data = request()->get('addmore');
            foreach ($data as $b){
             BatchLine::create([
                 'po' => $po->OrderNum,
                 'item' => $b['item'],
                 'qty'=>$b['qty'],
                 'batch' => $b['batch'],
                 'expiry_date'=> $b['expiry'],
                 'status' => PurchaseOrder::PENDING_STATUS,
                 'actual_batch'=> $b['batch'],
                 'actual_qty' => $b['qty'],
                 'actual_expiry' => $b['expiry'],
                 'purchase_order_id' => request()->get('id'),
                 'auto_index' => PurchaseOrder::find(request()->get('id'))->auto_index
             ]);
         }

        Session::flash('success','Items Imported successfully.');
        $po->update(['status' => PurchaseOrder::RECEIVED_STATUS]);
        return redirect('/batches/'.request()->get('id').'/edit');
}
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $path = $request->file('import_file')->getRealPath();
        $data = Excel::load($path)->get();
        if($data->count()){
            foreach ($data as $key => $value) {
                $arr[] = [
                    'po' => $value->po,
                    'item' => $value->item,
                    'batch' => $value->batch,
                    'qty' => $value->qty,
                    'expiry_date' => $value->expiry,
                    'status' => PurchaseOrder::PENDING_STATUS,
                    'actual_batch' => $value->batch,
                    'actual_qty' => $value->qty,
                    'actual_expiry' => $value->expiry,
                    'purchase_order_id' => $request->id,
                    'auto_index' => PurchaseOrder::find($request->id)->auto_index
                ];
            }
            if(!empty($arr)){

                $po_no = PurchaseOrder::find($request->id)->OrderNum;
              $pos = [];
              foreach ($arr as $po){
                if ($po['po'] ==$po_no ){
                    $pos [] = $po;
                }
              }
                Session::flash('success','Items Imported successfully.');
                BatchLine::insert($pos);
                PurchaseOrder::find($request->id)->update(['status' => PurchaseOrder::RECEIVED_STATUS]);

            }
        }

        return redirect('/batches/'.$request->id.'/edit');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return view('batches.show')->with('id',$id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $batches = PurchaseOrder::find($id);
        if($batches->status == PurchaseOrder::PENDING_STATUS || $batches->status == PurchaseOrder::RECEIVED_STATUS){
            return view('batches.edit')->with('batches',PurchaseOrder::find($id))->with('id',$id);
        }
        elseif ($batches->status == PurchaseOrder::PROCESSED_STATUS){

            return view('pos.approved.edit')->with('batches',PurchaseOrder::find($id))->with('id',$id)->with('reasons',RejectReason::all());
        }
        elseif ($batches->status == PurchaseOrder::APPROVED_STATUS){
           return view('pos.show')->with('batches',PurchaseOrder::find($id))->with('id',$id);
        }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    public function Sample()
    {
        $sample = BatchesDetails::init()->sampleData();
        return Excel::create('sample_po', function ($excel) use ($sample) {

            $excel->sheet('mySheet', function ($sheet) use ($sample) {

                $sheet->fromArray($sample);

            });

        })->download('xls');
    }

    public function fetchDetails($id)
    {
        $batches = BatchLine::find($id);
        return response()->json($batches);
}

    public function updatePoDetails(Request $request,$id)
    {
        BatchLine::find($id)->update($request->except('_token'));
        return response()->json($request->all());
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
