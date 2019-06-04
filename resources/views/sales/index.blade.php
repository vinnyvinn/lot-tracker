@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">Sale Orders</div>
                <div class="card-body">
                    <table class="table table-striped table-bordered so" style="width:100%">
                        <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>cAccountName</th>
                            <th>OrdTotExcl</th>
                            <th>OrdTotIncl</th>
                            <th>OrdTotTax</th>
                            <th>Status</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sales as $so)
                            <tr>
                                <td>
                                    @if($so->status== \App\SaleOrder::STATUS_NOT_ISSUED)
                                   <a href="{{url('/so/'.$so->id)}}" class="btn btn-info btn-xs"><img src="{{asset('assets/img/tap.png')}}" alt="" width="20px"> {{$so->OrderNum}}</a>
                                @elseif($so->status == \App\SaleOrder::STATUS_PROCESSED)
                                <a href="{{url('/so/'.$so->id)}}" class="btn btn-warning btn-xs"><img src="{{asset('assets/img/tap.png')}}" alt="" width="20px"> {{$so->OrderNum}}</a>
                                    @elseif($so->status == \App\SaleOrder::STATUS_ISSUED)
                                        <a href="{{url('/so/'.$so->id)}}"> <span class="label label-default" style="font-size: 14px">{{$so->OrderNum}}</span></a>

                               @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($so->InvDate)->format('d/m/Y')}}</td>
                                <td>{{$so->cAccountName}}</td>
                                <td>{{$so->OrdTotExcl}}</td>
                                <td>{{$so->OrdTotIncl}}</td>
                                <td>
                                 {{$so->OrdTotTax}}
                                </td>
                                <td>
                                    @if($so->status== \App\SaleOrder::STATUS_NOT_ISSUED)
                                        <span class="label label-info">{{$so->status}}</span>
                                    @else
                                        <span class="label label-success">{{$so->status}}</span>
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
        $('.so').DataTable()
        $(function () {

        })
    </script>

@endsection
