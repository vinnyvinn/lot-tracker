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
                                <td>
                                    <a href="#" title="Modify" class="label label-primary modify" data-toggle="modal" data-target="#modify" modify_id="{{$so->id}}"><i class="fa fa-edit">{{$so->OrderNum}}</i></a>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($so->InvDate)->format('d/m/Y')}}</td>
                                <td>{{$so->cAccountName}}</td>
                                <td>{{$so->item}}</td>
                                <td>{{$so->fQuantity}}</td>
                                <td>{{$so->qty_received}}</td>
                                <td>{{$so->qty_remaining}}</td>
                                <td>
                                {{$so->cDescription}}
                                </td>
                                <td>
                                    @if($so->status== \App\InvoiceLine::STATUS_PENDING)
                                        <span class="label label-info">{{$so->status}}</span>
                                        @elseif($so->status== \App\InvoiceLine::STATUS_REJECTED)
                                        <span class="label label-warning">{{$so->status}}</span>
                                    @else
                                        <span class="label label-success">{{$so->status}}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($so->status==\App\InvoiceLine::STATUS_PENDING)
                                        <i class="fa fa-check-circle approval" title="Approve" data-toggle="modal" data-target="#approve" approve_id="{{$so->id}}" style="font-size: 20px"></i>
                                        <i class="fa fa-times rejected mx-3" title="Reject" data-toggle="modal" data-target="#rejects" reject_id="{{$so->id}}" style="font-size: 20px"></i>
                                   @else
                                        <a href="{{url('so/'.$so->id.'/edit')}}" class="btn btn-primary btn-xs"> <i class="fa fa-eye" style="font-size: 14px" title="Show details"></i></a>
                                    @endif
                               </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if($inv_lines->status == \App\SaleOrder::STATUS_NOT_ISSUED)
                    <div class="row">
                        <div class="col text-center">
                            <button class="btn btn-primary border border-warning border-4 process_now my-2" post_to="{{$inv_lines->id}}"><img src="{{asset('assets/img/approved.png')}}" class="approve_all"><span class="walla_img">Process</span></button>
                        </div>
                    </div>
                @endif

                <!-- The Approve Modal -->
                    <div class="modal" id="modify">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Confirm Invoice Line</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form class="modify_form" action="{{url('update-inv-lines')}}" method="POST">
                                        {{csrf_field()}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="order_no">Order #</label>
                                                <input type="text" name="OrderNum" class="form-control" id="order_no_e" disabled>
                                            </div>
                                        </div>
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for="item">Item</label>
                                               <input type="text" name="item" class="form-control" id="item_e" disabled>
                                           </div>
                                       </div>
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="account_name">Account Name</label>
                                                <input type="text" name="cAccountName" class="form-control" id="account_name_e" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <input type="text" name="cDescription" class="form-control" id="description_e" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="lot_number">Lot Number</label>
                                                <select name="lot_number" id="lot_number" class="form-control" required style="height: 100%">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="qty">Qty</label>
                                                <input type="number" name="fQuantity" step="0.01" class="form-control qty_e" disabled>
                                                <input type="hidden" name="fQuantity" step="0.01" class="form-control qty_e">
                                            </div>
                                        </div>
                                       <input type="hidden" name="id" id="inv_id">
                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- The Approve Modal -->
                    <div class="modal" id="approve">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Confirm Invoice Line</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form class="approve_form">
                                        {{csrf_field()}}
                                        <div class="form-group">
                                            <label for="order_no">Order #</label>
                                            <input type="text" name="OrderNum" class="form-control" id="order_no" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="item">Item</label>
                                            <input type="text" name="item" class="form-control" id="item" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="account_name">Account Name</label>
                                            <input type="text" name="cAccountName" class="form-control" id="account_name" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="qty">Qty</label>
                                            <input type="number" name="fQuantity" step="0.01" class="form-control" id="qty" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <input type="text" name="cDescription" class="form-control" id="description" disabled>
                                        </div>
                                        <input type="hidden" name="status" value="{{\App\InvoiceLine::STATUS_APPROVED}}">
                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Approve</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- The Reject Modal -->
                    <div class="modal" id="rejects">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Reason For Rejection</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form class="reject_form">
                                        {{csrf_field()}}

                                        <div class="form-group">
                                            <label for="order_no">Order #</label>
                                            <input type="text" name="OrderNum" class="form-control" id="order_no_id" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="item">Item</label>
                                            <input type="text" name="item" class="form-control" id="item_id" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="account_name">Account Name</label>
                                            <input type="text" name="cAccountName" class="form-control" id="account_name_id" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="qty">Qty</label>
                                            <input type="text" name="fQuantity" class="form-control" id="qty_id" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <input type="text" name="cDescription" class="form-control" id="description_id" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Reason</label>
                                            <textarea name="reason" id="reason" cols="10" rows="5" class="form-control"></textarea>
                                        </div>
                                        <input type="hidden" name="status" value="{{\App\InvoiceLine::STATUS_REJECTED}}">
                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-warning">Reject</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
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
        $(function () {
            var approve_id;
            var reject_id;
            var modify_id;
            $('.modify').on('click',function () {
                //alert('cool');
                modify_id = $(this).attr('modify_id');
                $('#inv_id').val(modify_id);
                $.ajax({
                    url:'{{url('fetch-so')}}'+ '/'+modify_id,
                    type:'GET',
                    success:function (response) {
                        $('#order_no_e').val(response.lines.OrderNum);
                        $('#item_e').val(response.lines.item);
                       /// $('#qty_e').val(response.lines.fQuantity);
                        $('#description_e').val(response.lines.cDescription);
                        $('#account_name_e').val(response.lines.cAccountName);
                        var len = response.batches.length;
                        if(len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {
                                var id = response['batches'][i].id;
                                var batch = response['batches'][i].batch;
                                var option = "<option value='" + id + "'>" + batch + "</option>";
                                $("#lot_number").append(option);
                            }
                            $('#lot_number').on('change',function () {
                               $.ajax({
                                   url:'{{url('lot-qty')}}'+'/'+$(this).val(),
                                   type:'GET',
                                   success: function (response) {
                                       console.log(response);
                                       $('.qty_e').val(response);
                                   }
                               })
                            })

                        }
                    }
                });
            })
            $('.approval').on('click',function () {
                //alert('cool');
                approve_id = $(this).attr('approve_id');
                $.ajax({
                    url:'{{url('fetch-so')}}'+ '/'+approve_id,
                    type:'GET',
                    success:function (response) {
                        $('#order_no').val(response.lines.OrderNum);
                        $('#item').val(response.lines.item);
                        $('#qty').val(response.lines.qty_received);
                        $('#description').val(response.lines.cDescription);
                        $('#account_name').val(response.lines.cAccountName);
                    }
                });
            })
            $('.rejected').on('click',function () {
                reject_id = $(this).attr('reject_id');
                $.ajax({
                    url:'{{url('fetch-so')}}'+ '/'+reject_id,
                    type:'GET',
                    success:function (response) {
                        console.log(response);
                        $('#order_no_id').val(response.lines.OrderNum);
                        $('#item_id').val(response.lines.item);
                        $('#qty_id').val(response.lines.qty_received);
                        $('#description_id').val(response.lines.cDescription);
                        $('#account_name_id').val(response.lines.cAccountName);
                    }
                });
            })
            $('.approve_form').on('submit',function (e) {
                e.preventDefault();
               $.ajax({
                    url:'{{url('update-so')}}'+ '/'+approve_id,
                    type:'POST',
                    data:$(this).serialize(),
                    success:function (response) {
                        console.log(response);
                        window.location.reload();
                    }
                })
            });
            $('.reject_form').on('submit',function (e) {
                e.preventDefault();
                $.ajax({
                    url:'{{url('update-so')}}'+ '/'+reject_id,
                    type:'POST',
                    data:$(this).serialize(),
                    success:function (response) {
                        console.log(response);
                        window.location.reload();
                    }
                })
            });
            $('.post_now').on('click',function () {
                var posted = $(this).attr('post_to_sage');

                $.ajax({
                    url:'{{url('approved-so')}}' + '/'+ posted,
                    type:'GET',
                    success: function (response) {
                        console.log(response);
                        if(response=='noitems'){
                            return toastr.warning('fail','Sorry,You do not have invoice lines in this invoice')
                        }

                            window.location.reload();

                    }
                })
            });
            $('.process_now').on('click',function () {
                var so = $(this).attr('post_to');

               $.ajax({
                    url:'{{url('process-so')}}'+ '/'+so,
                    type:'GET',
                    success: function (response) {
                       console.log(response)
                        if (response == 'proceed'){
                        console.log(so);
                            $.ajax({
                                url:'{{url('approved-so')}}' + '/'+ so,
                                type:'GET',
                                success: function (response) {
                                    console.log(response);
                                    if(response=='fail'){
                                       return toastr.warning('fail','Sorry,Kindly Inspect first all the invoice lines before proceeding.')
                                    }
                                    if(response=='noitems'){
                                        return toastr.warning('fail','Sorry,You do not have invoice lines in this invoice')
                                    }
                                  window.location.href='{{url('/so')}}';
                                   }
                            })
                        }
                        if(response=='fail'){
                            return toastr.warning('fail','Sorry,You have not either approved/rejected all the invoice lines.')
                        }
                        window.location.reload();
                    }
                })
            })
        })
    </script>

@endsection
