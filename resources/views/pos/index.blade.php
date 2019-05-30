@extends('layouts.app')

@section('content')

    <div class="row">
      <div class="col-md-10 mx-auto">
          <div class="card">
              <div class="card-header">Purchase Orders
                  <span class="pull-right">
                      <a href="#" class="btn btn-info pull-right" title="Modify" class="label label-primary modify" data-toggle="modal" data-target="#modify"><img src="{{asset('assets/img/export.png')}}" alt="" width="25">Import Open.Bal</a>

                  <a href="{{url(url('sample-op'))}}" class="btn btn-info pull-right mx-2"><img src="{{asset('assets/img/download.png')}}" alt="" width="25">Download Sample Open.Bal</a>

                  </span>

              </div>
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
                              <a href="{{url('batches/'.$po->id.'/edit')}}" class="btn btn-info btn-xs"><img src="{{asset('assets/img/tap.png')}}" alt="" width="20px"> {{$po->OrderNum}}</a></td>
                          @elseif($po->status ==\App\PurchaseOrder::RECEIVED_STATUS)
                              <a href="{{url('batches/'.$po->id.'/edit')}}" class="btn btn-warning btn-xs"><img src="{{asset('assets/img/tap.png')}}" alt="" width="20px"> {{$po->OrderNum}}</a>

                           @elseif($po->status ==\App\PurchaseOrder::RECEIVED_STATUS)
                              <a href="{{url('batches/'.$po->id.'/edit')}}" class="btn btn-warning btn-xs"><img src="{{asset('assets/img/tap.png')}}" alt="" width="20px"> {{$po->OrderNum}}</a>
                              @elseif(\App\PurchaseOrder::APPROVED_STATUS)
                              <a href="{{url('batches/'.$po->id.'/edit')}}" class="btn btn-success btn-sm" style="width: 53%"><span class="label label-default"></span> {{$po->OrderNum}}</a>
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

    <!-- The Edit Modal -->
    <div class="modal" id="modify">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Import Opening Balance</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="{{url('store-bal')}}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="warehouse">Warehouse</label>
                            <select name="warehouse" class="form-control warehouse" style="height: 100%" required>
                                @foreach($wh as $h)
                                <option value="{{$h->id}}">{{$h->name}}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="file">Excel File</label>
                            <input type="file" name="import_file" class="form-control" required>
                        </div>


                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Upload</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>

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
