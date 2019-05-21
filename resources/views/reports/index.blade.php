@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">Purchase Orders Report
                    <a href="{{url('/po/excel/'.date('d-m-Y',strtotime($date['date_from'])).'/'.date('d-m-Y',strtotime($date['date_to'])))}}" class="btn btn-primary btn-sm pull-right">
                        <i class="fa fa-download" aria-hidden="true"></i>Export Excel</a>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered pos" style="width:100%">
                        <thead>
                        <tr>
                            <th>PO</th>
                            <th>Item</th>
                            <th>Batch</th>
                            <th>Qty</th>
                            <th>Expiry</th>
                            <th>Date Created</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($batches as $batch)
                            <tr>
                                <td>{{$batch->po}}</td>
                                <td>{{$batch->item}}</td>
                                <td>{{$batch->batch}}</td>
                                <td>{{$batch->qty}}</td>
                                <td>{{$batch->actual_expiry}}</td>
                                <td>{{\Carbon\Carbon::parse($batch->updated_at)->format('d/m/Y')}}</td>
                                <td>@if($batch->status ==\App\PurchaseOrder::APPROVED_STATUS)
                                        <span class="label label-success">{{$batch->status}}</span>
                                    @else
                                        <span class="label label-warning">{{$batch->status}}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $('.pos').DataTable()
        $(function () {

        })
    </script>
@endsection
