@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">Batch Lines</div>
                <div class="card-body">
                    <table class="table table-striped table-bordered so" style="width:100%">
                        <thead>
                        <tr>
                            <th>PO #</th>
                            <th>Item</th>
                            <th>Batch </th>
                             <th>Qty</th>
                            <th>Expiry</th>
                            <th>Status</th>
                            <th>Reason</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($batches->batch_lines as $so)
                            <tr>
                                <td>{{$so->po}}</td>
                                <td>{{$so->item}}</td>
                                <td>{{$so->actual_batch}}</td>
                                 <td>{{$so->actual_qty}}</td>
                                <td>{{$so->actual_expiry}}</td>
                                <td>
                                    @if($so->status == 'APPROVED')
                                        <span class="label label-success">{{$so->status}}</span>
                                        @else
                                        <span class="label label-warning">{{$so->status}}</span>
                                        @endif
                                </td>
                                <td>{{$so->description}}</td>
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
