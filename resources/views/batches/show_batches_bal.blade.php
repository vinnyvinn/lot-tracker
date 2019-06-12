@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">Batches / Serials</div>
                <div class="card-body">
                    <table class="table table-striped table-bordered pos" style="width:100%">
                        <thead>
                        <tr>
                            <th>PO</th>
                            <th>Item Code</th>
                            <th>Batch/Serial</th>
                            <th>Type</th>
                            <th>Quantity</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($batches as $batch)
                               <tr>
                                <td>{{$batch->po}}</td>
                                <td>{{$batch->item}}</td>
                                <td>{{$batch->batch}}</td>
                                <td>{{$batch->type}}</td>
                                <td>{{$batch->qty}}</td>

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
