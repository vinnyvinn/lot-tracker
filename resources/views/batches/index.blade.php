@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">Purchase Orders Batches</div>
                <div class="card-body">
                    <table class="table table-striped table-bordered pos" style="width:100%">
                        <thead>
                        <tr>
                            <th>PO</th>
                            <th>Item</th>
                            <th>Batch/Serial</th>
                            <th>Qty</th>
                            <th>Expiry</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($batches as $batch)
                            <tr>
                                <th>{{$batch->po}}</th>
                                <th>{{$batch->item}}</th>
                                <th>{{$batch->batch}}</th>
                                <th>{{$batch->qty}}</th>
                                <th>{{\Carbon\Carbon::parse($po->expiry_date)->format('d/m/Y')}}</th>
                                <th></th>
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
