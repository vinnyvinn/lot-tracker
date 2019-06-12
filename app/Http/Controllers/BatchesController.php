<?php

namespace App\Http\Controllers;

use App\BatchLine;
use App\BtblInvoiceLines;
use App\InvoiceLine;
use App\PurchaseOrder;
use App\RejectReason;
use App\Warehouse;
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
                    'auto_index' => PurchaseOrder::find($request->id)->auto_index,
                    'warehouse' => request()->get('warehouse')
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
              if (count($pos)){
                  Session::flash('success','Items Imported successfully.');
                  BatchLine::insert($pos);
                  PurchaseOrder::find($request->id)->update(['status' => PurchaseOrder::RECEIVED_STATUS]);
              }else{

               Session::flash('fail','Sorry,No much found for the PO uploaded');
              }

            }
        }

        return redirect('/batches/'.$request->id.'/edit');
    }

    public function storeBalance()
    {
        $path = request()->file('import_file')->getRealPath();
        $data = Excel::load($path)->get();
        $qty =0;
        if($data->count()){
            foreach ($data as $key => $value) {
                $qty += 1;
                $arr[] = [
                    'po' => 'Opening Balance('.date('d/m/Y H:i:s').')',
                    'item' => $value->item_code,
                    'batch' => $value->batch_serial_no,
                    'qty' => 1,
                    'expiry_date' => date('d/m/Y'),
                    'status' => PurchaseOrder::PENDING_STATUS,
                    'actual_batch' => $value->batch_serial_no,
                    'actual_qty' => 1,
                    'actual_expiry' => date('d/m/Y'),
                    'purchase_order_id' => PurchaseOrder::count()+1,
                    'auto_index' => PurchaseOrder::orderby('id','desc')->first()->auto_index+1,
                    'warehouse' => request()->get('warehouse'),
                    'type' => request()->get('type')
                ];
            }

            if(!empty($arr)){
             //dd($arr);
                Session::flash('success','Items Imported successfully.');
                BatchLine::insert($arr);
                PurchaseOrder::create([
                    'OrderNum' => 'Opening Balance('.date('d/m/Y H:i:s').')',
                    'InvDate' => Carbon::now(),
                    'cAccountName' => 'SUPP01',
                    'fQuantity'=> $qty,
                    'type' => request()->get('type'),
                    'auto_index' => PurchaseOrder::orderby('id','desc')->first()->auto_index+1,
                    'status' => PurchaseOrder::RECEIVED_STATUS,
                    'open_balance' => 1
                    ]);
            }
        }
        Session::flash('success','Items Imported successfully.');
        return redirect('/pos');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return view('batches.show')->with('id',$id)->with('wh',Warehouse::all());
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
            if($batches->open_balance==1){
                return  view('batches.edit_open')->with('batches',$batches)->with('id',$id);
            }
            else{
                return view('batches.edit')->with('batches',$batches)->with('id',$id);
            }
           
        }
        elseif ($batches->status == PurchaseOrder::PROCESSED_STATUS){
               return view('pos.approved.edit')->with('batches',PurchaseOrder::find($id))->with('id',$id)->with('reasons',RejectReason::all());
        }
        elseif ($batches->status == PurchaseOrder::APPROVED_STATUS){

           return view('pos.show')->with('batches',PurchaseOrder::find($id))->with('id',$id);
        }
        return view('batches.edit')->with('batches',PurchaseOrder::find($id))->with('id',$id);


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
    public function SampleOp()
    {
        $sample = BatchesDetails::init()->sampleOp();
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

    public function receiceBatches($id)
    {
        $batch = BatchLine::find($id);
        $available_lots = [];
        if ($batch->batch){
            $available_lots[] = $batch->batch;
        }
        $qty = 0;
        $batch_data = [];
           foreach (request()->get('addmore') as $q){
              $qty+=$q['qty'];
               $batch_data [] = [
                 'keyVal' => $id,
                 'qty' => $qty,
                 'lot_serial' => $q['lot']
               ];
        }
           array_push($available_lots,json_encode($batch_data));
           $final_batch = [];
           foreach ($available_lots as $lot){
              foreach (json_decode(collect($lot)) as $l){
                  foreach (json_decode($l) as $item){
                      $final_batch [] = [
                          'keyVal' => $item->keyVal,
                          'qty' => $item->qty,
                          'lot_serial' => $item->lot_serial
                      ];
                  }

              }
           }
        if($batch->qty -($batch->qty_received + $qty) < 0){
            return response('negativevalue');
        }
        if ($batch->qty -($batch->qty_received + $qty) >= 0){
              foreach (request()->get('addmore') as $q){

                $batch->update([
                   'batch' => json_encode($final_batch),
                   'qty_received'=> $batch->qty_received + $q['qty'],
                   'qty_accepted' => $batch->qty_received + $q['qty'],
                ]);

            }
        }

}

    public function receiveSerials($id)
    {
        $batch = BatchLine::find($id);
        $available_lots = [];
        if ($batch->batch){
            $available_lots[] = $batch->batch;
        }
        $qty = 0;
        $batch_data = [];
        foreach (request()->get('addmore') as $q){
            $qty++;
            $batch_data [] = [
                'keyVal' => $id,
                'qty' => 1,
                'lot_serial' => $q['lot']
            ];
        }
        array_push($available_lots,json_encode($batch_data));
        $final_batch = [];
        foreach ($available_lots as $lot){
            foreach (json_decode(collect($lot)) as $l){
                foreach (json_decode($l) as $item){
                    $final_batch [] = [
                        'keyVal' => $item->keyVal,
                        'qty' => 1,
                        'lot_serial' => $item->lot_serial
                    ];
                }

            }
        }

        if($batch->qty -($batch->qty_received + $qty) < 0){
            return response('negativevalue');
        }

        if ($batch->qty -($batch->qty_received + $qty) >= 0){
            foreach (request()->get('addmore') as $q){

                $batch->update([
                    'batch' => json_encode($final_batch),
                    'qty_received'=> $batch->qty_received + 1,
                    'qty_accepted' => $batch->qty_received + 1,
                ]);

            }
        }

    }
    public function showBatches($id)
    {
return view('batches.show_batch')->with('batches',BatchLine::find($id));
}

public function showBatchesBal($id)
    {
return view('batches.show_batches_bal')->with('batches',PurchaseOrder::find($id)->batches);
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
