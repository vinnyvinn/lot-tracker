<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LotTracker\Reports;
use Excel;
class ReportsController extends Controller
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
        return view('reports.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->get('s_p')=='po'){
            return view('reports.index')->with('batches',Reports::init()->getPos($request->all()))->with('date',$request->all());
        }
        else{
        return view('reports.so_index')->with('inv_lines',Reports::init()->getSo($request->all()))->with('date',$request->all());
        }

    }

    public function soExcelExport($from,$to)
    {

       $data = Reports::init()->getSo(['date_from' =>$from, 'date_to' => $to]);

        return Excel::create('sale_orders', function ($excel) use ($data) {

            $excel->sheet('mySheet', function ($sheet) use ($data) {

                $sheet->fromArray($data);

            });

        })->download('xls');


    }
    public function poExcelExport($from,$to)
    {

        $data = Reports::init()->poDetails(['date_from' =>$from, 'date_to' => $to]);
        return Excel::create('purchase_orders', function ($excel) use ($data) {

            $excel->sheet('mySheet', function ($sheet) use ($data) {

                $sheet->fromArray($data);

            });

        })->download('xls');


    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
