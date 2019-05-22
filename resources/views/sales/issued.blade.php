@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">Invoice Lines</div>
                <div class="card-body">
                    <table class="table table-striped table-bordered so" style="width:100%">
                        <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Account Name</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Qty Received</th>
                            <th>Qty Remaining</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($inv_lines->lines as $so)
                            <tr>
                                <td>{{$so->OrderNum}}</td>
                                <td>{{\Carbon\Carbon::parse($so->InvDate)->format('d/m/Y')}}</td>
                                <td>{{$so->cAccountName}}</td>
                                <td>{{$so->item}}</td>
                                <td>{{$so->fQuantity}}</td>
                                <td>{{$so->qty_received}}</td>
                                <td>{{$so->qty_remaining}}</td>
                                <td>{{$so->cDescription}}</td>
                                <td><span class="label label-success">{{$so->status}}</span></td>
                                <td><a href="{{url('so/'.$so->id.'/edit')}}" class="btn btn-primary btn-xs"> <i class="fa fa-eye" style="font-size: 14px" title="Show details"></i></a></td>
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
    <script src="{{asset('assets/js/jquery-ui.js')}}"></script>
    <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $('.so').DataTable()
    </script>

@endsection
