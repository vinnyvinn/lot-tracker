<?php

namespace App\Http\Controllers;

use App\BtblInvoiceLines;
use App\InvoiceLine;
use App\SaleOrder;
use Illuminate\Http\Request;
use LotTracker\SaleOrders;

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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return view('sales.show')->with('inv_lines', SaleOrder::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function fetchSo($id)
    {
        $inv = InvoiceLine::find($id);
        return response()->json($inv);
    }

    public function updateSo(Request $request, $id)
    {
        InvoiceLine::find($id)->update($request->except('_token'));
        return response()->json($request->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function approvedSo($id)
    {
         $so =SaleOrders::sales()->updateLines($id);
       return response()->json($so);
         return redirect('/so');


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
