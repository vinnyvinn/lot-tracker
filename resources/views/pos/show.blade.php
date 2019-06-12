@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">PO Details
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered pos" style="width:100%">
                        <thead>
                        <tr>
                            <th>PO#</th>
                            <th>Item Code</th>
                            <th>Qty</th>
                            <th>Qty Received</th>
                            <th>Status</th>


                        </tr>
                        </thead>
                        <tbody>
                        @if(count($batches->batches))
                            @foreach($batches->batches as $batch)
                                <tr>
                                    <td>{{$batch->po}}</td>
                                    <td>{{$batch->item}}</td>
                                    <td>{{$batch->qty}}</td>
                                    <td>{{$batch->qty_received}}</td>
                                    <td><span class="label label-success">{{$batch->status}}</span>
                                        <a href="{{url('show-lines/'.$batch->id)}}" class="btn btn-success btn-xs pull-right" title="Show Batches"> <i class="fa fa-eye"></i></a>

                                    </td>
                                </tr>
                            @endforeach
                        @endif

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

        $('.pos').DataTable()

    </script>

@endsection
