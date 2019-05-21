@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">Sale Orders Report
                   <a href="{{url('/so/excel/'.date('d-m-Y',strtotime($date['date_from'])).'/'.date('d-m-Y',strtotime($date['date_to'])))}}" class="btn btn-primary btn-sm pull-right">
                       <i class="fa fa-download" aria-hidden="true"></i>Export Excel</a>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered pos" style="width:100%">
                        <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Account Name</th>
                            <th>Item</th>
                            <th>Batch</th>
                            <th>Qty</th>
                            <th>Date</th>
                            <th>Date Created</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($inv_lines as $inv)

                            <tr>
                                <td>{{$inv['order_no']}}</td>
                                <td>{{$inv['account_name']}}</td>
                                <td>{{$inv['item']}}</td>
                                <td>{{$inv['batch']}}</td>
                                <td>{{$inv['qty']}}</td>
                                <td>{{$inv['date']}}</td>
                                <td>{{$inv['created']}}</td>
                                <td>
                                    @if($inv['status'] ==\App\InvoiceLine::STATUS_APPROVED)
                                        <span class="label label-success">{{$inv['status']}}</span>
                                        @else
                                    <span class="label label-warning">{{$inv['status']}}</span>
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
