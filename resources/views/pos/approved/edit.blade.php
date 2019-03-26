@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">PO Batches

                    <a href="{{url('batches/'.$id)}}" class="btn btn-info pull-right"><img src="{{asset('assets/img/export.png')}}" alt="" width="25">Import</a>
                    <a href="{{url(url('sample'))}}" class="btn btn-info pull-right mx-2"><img src="{{asset('assets/img/download.png')}}" alt="" width="25">Download Sample</a>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered pos" style="width:100%">
                        <thead>
                        <tr>
                            <th>PO</th>
                            <th>Item</th>
                            <th>Batch</th>
                            <th>Act. Batch</th>
                            <th>Qty</th>
                            <th>Act. Qty</th>
                            <th>Expiry</th>
                            <th>Act. Expiry</th>
                            <td>Status</td>
                            <th>Reason</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($batches))
                            @foreach($batches as $batch)
                                <tr>
                                    <td>{{$batch->po}}</td>
                                    <td>{{$batch->item}}</td>
                                    <td>{{$batch->batch}}</td>
                                    <td>{{$batch->actual_batch}}</td>
                                    <td>{{$batch->qty}}</td>
                                    <td>{{$batch->actual_qty}}</td>
                                    <td>{{$batch->expiry_date}}</td>
                                    <td>{{$batch->actual_expiry}}</td>
                                    <td>
                                        @if($batch->status== \APP\PurchaseOrder::PENDING_STATUS)
                                            <span class="label label-info">{{$batch->status}}</span>
                                        @elseif($batch->status==\App\PurchaseOrder::APPROVED_STATUS)
                                            <span class="label label-success">{{$batch->status}}</span>
                                        @else
                                            <span class="label label-warning">{{$batch->status}}</span>
                                        @endif
                                    </td>
                                    <td>
                                       {{$batch->description}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col text-center">
                            <button class="btn btn-primary border border-warning border-4 my-2"><img src="{{asset('assets/img/approved.png')}}" class="approve_all"><span class="walla_img">Save</span></button>

                        </div>
                    </div>







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

            </script>

@endsection
