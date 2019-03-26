@extends('layouts.app')

@section('content')

    <div class="row">
      <div class="col-md-10 mx-auto">
          <div class="card">
              <div class="card-header">Purchase Orders</div>
              <div class="card-body">
                  <table class="table table-striped table-bordered pos" style="width:100%">
                      <thead>
                      <tr>
                          <th>PO #</th>
                          <th>Date</th>
                          <th>Supplier</th>
                          <th>Item</th>
                          <th>Qty</th>
                          <th>Status</th>

                      </tr>
                      </thead>
                      <tbody>
                      @foreach($pos as $po)
                      <tr>
                          <td>
                              @if($po->status ==\App\PurchaseOrder::ARRIVED_PO)
                              <a href="{{url('batches/'.$po->id.'/edit')}}" class="btn btn-info btn-xs"><img src="{{asset('assets/img/tap.png')}}" alt="" width="25px"> {{$po->OrderNum}}</a></td>
                          @elseif($po->status ==\App\PurchaseOrder::RECEIVED_STATUS)
                              <a href="{{url('batches/'.$po->id.'/edit')}}" class="btn btn-warning btn-xs"><img src="{{asset('assets/img/tap.png')}}" alt="" width="25px"> {{$po->OrderNum}}</a>
                              @else
                              <a href="{{url('approved-pos/'.$po->id.'/edit')}}" class="btn btn-success btn-xs"><img src="{{asset('assets/img/tap.png')}}" alt="" width="25px"> {{$po->OrderNum}}</a>
                          @endif
                          <td>{{ \Carbon\Carbon::parse($po->InvDate)->format('d/m/Y')}}</td>
                          <td>{{$po->supplier}}</td>
                          <td>{{$po->cDescription}}</td>
                          <td>{{$po->fQuantity}}</td>
                          <td>
                              @if($po->status== \App\PurchaseOrder::ARRIVED_PO)
                                  <span class="label label-info">{{$po->status}}</span>
                              @elseif($po->status ==\App\PurchaseOrder::RECEIVED_STATUS)
                                  <span class="label label-warning">{{$po->status}}</span>
                              @else
                                  <span class="label label-success">{{$po->status}}</span>
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
