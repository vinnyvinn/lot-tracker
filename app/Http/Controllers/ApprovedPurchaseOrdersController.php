<?php

namespace App\Http\Controllers;

use App\ApprovedPurchaseOrder;
use App\PurchaseOrder;
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

        $batches = PurchaseOrder::find($id)->batches;
        if (count($batches) < 1){
        return response()->json($batches);
        }
        $qty = AppprovePurchaseOrders::init()->validateQty($id);

        if($qty !=PurchaseOrder::find($id)->fQuantity){
            return response()->json(['qty' => 'Sorry,sum quantities must be equal to PO quantity']);
        }
        AppprovePurchaseOrders::init()->storeToSage($id);
        return response()->json($batches);

       }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('pos.approved.edit')->with('batches',PurchaseOrder::find($id)->batches)->with('id',$id);
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
        //
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
