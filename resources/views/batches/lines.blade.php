@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12 mx-auto">
            <div class="card">
                <div class="card-header">PO #: {{$batches->OrderNum}}
                    <br>
                    @if($batches->status == \App\PurchaseOrder::ARRIVED_PO || \App\PurchaseOrder::PENDING_STATUS && !\App\PurchaseOrder::where('id',$id)->where('OrderNum','like','open'.'%')->first())
                        <a href="{{url('batches/'.$id)}}" class="btn btn-info pull-right"><img src="{{asset('assets/img/export.png')}}" alt="" width="25">Import</a>

                        <a href="{{url(url('sample'))}}" class="btn btn-info pull-right mx-2"><img src="{{asset('assets/img/download.png')}}" alt="" width="25">Download Sample</a>
                        <a href="{{url('create-batch/'.$id)}}" class="btn btn-info pull-right mx-2"><i class="fa fa-plus">Add New</i></a>
                    @endif
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered pos" style="width:100%">
                        <thead>
                        <tr>
                            <th>PO</th>
                            <th>Item Code</th>
                            <th>Qty</th>
                            <th>Qty Received</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                            @if(count($batches->lines))
                                <span class="pull-right" style="margin-top: 15px;margin-right: 5px;">
                            @if($batches->open_balance==1)
                                        <a href="#" class="btn btn-primary btn-xs confirmApproveB" app_all_b="{{$id}}"><i class="fa fa-check-circle"></i>Approve All</a>
                                    @elseif($batches->open_balance==0)
                                        <a href="#" class="btn btn-primary btn-xs confirmApprove" app_all="{{$id}}"><i class="fa fa-check-circle"></i>Approve All</a>
                                    @endif
                            </span>

                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($batches->lines))
                            @foreach($batches->lines as $batch)
                                <tr>
                                    <td>
                                        @if($batches->status == \App\PurchaseOrder::PENDING_STATUS || \App\PurchaseOrder::RECEIVED_STATUS)
                                            @if($batch->type =='LOT' && $batches->open_balance !=1)
                                                <a href="#" title="Modify" class="label label-primary modify" data-toggle="modal" data-target="#modify" modify_id="{{$batch->id}}"><i class="fa fa-edit">{{$batch->po}}</i></a>
                                            @elseif($batch->type =='SERIAL' && $batches->open_balance !=1)
                                                <a href="#" title="Modify Serial" class="label label-primary serial" data-toggle="modal" data-target="#serial" serial_id="{{$batch->id}}"><i class="fa fa-edit">{{$batch->po}}</i></a>
                                            @elseif($batch->type =='LOT' || $batch->type =='SERIAL' && $batches->open_balance==1)
                                                <a href="#" title="Modify Opening Balance" class="label label-primary">{{$batch->po}}</a>

                                            @endif
                                        @else
                                            {{$batch->po}}
                                        @endif
                                    </td>
                                    <td>{{$batch->item}}</td>
                                    <td>{{$batch->qty}}</td>
                                    <td>{{$batch->qty_received}}</td>
                                    <td>{{$batch->type}}</td>
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
                                        @if($batch->status==\App\PurchaseOrder::PENDING_STATUS)
                                            <i class="fa fa-check-circle approval" title="Approve" data-toggle="modal" data-target="#approve" approve_id="{{$batch->id}}" style="font-size: 20px"></i>
                                            <i class="fa fa-times rejected mx-3" title="Reject" data-toggle="modal" data-target="#rejects" reject_id="{{$batch->id}}" style="font-size: 20px"></i>
                                        @endif
                                        @if($batch->batch)
                                            @if($batches->open_balance == 1)
                                                <a href="{{url('show-lines-b/'.$batches->id)}}" class="btn btn-success btn-xs" title="Show Batches"> <i class="fa fa-eye"></i></a>
                                            @else
                                                <a href="{{url('show-lines/'.$batch->id)}}" class="btn btn-success btn-xs" title="Show Batches"> <i class="fa fa-eye"></i></a>
                                            @endif
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @if(count($batches->lines))
                        <div class="row">
                            <div class="col text-center">
                                <button class="btn btn-primary border border-warning border-4 process_now my-2" post_to="{{$id}}"><img src="{{asset('assets/img/approved.png')}}" class="approve_all"><span class="walla_img">Process</span></button>
                            </div>
                        </div>
                @endif
                <!-- The Serial Modal -->
                    <div class="modal" id="serial">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <!-- Modal Header -->

                                <div class="modal-header panel panel-success" style="background-color: #1da1f2;">

                                    <div class="modal-title panel-heading">
                                        <span style="font-size: 20px">Po#: <strong class="text-left po_s"> </strong></span>
                                        <span style="margin-left: 15px;font-size: 20px"> Item Code: <strong class="text-center item_s"> </strong></span>
                                        <span style="margin-left: 15px;font-size: 20px">Quantity: <strong class="text-right actual_qty_s"> </strong></span>

                                    </div>

                                    <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
                                </div>
                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form class="serial_form">
                                        {{csrf_field()}}
                                        <table class="table table-bordered" id="dynamicTableS">
                                            <tr>
                                                <th>Serial #</th>
                                                <th>Action</th>
                                            </tr>
                                            <tr>

                                                <td><input type="text" name="addmore[0][lot]" placeholder="Enter Serial Number" class="form-control" required/></td>
                                                <td><button type="button" name="add" id="addS" class="btn btn-success">Add More</button></td>

                                            </tr>
                                        </table>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success btn-block"><i class="fa fa-shopping-cart" aria-hidden="true">Save</i>
                                            </button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- The Edit Modal -->
                    <div class="modal" id="modify">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <!-- Modal Header -->

                                <div class="modal-header panel panel-success" style="background-color: #1da1f2;">

                                    <div class="modal-title panel-heading">
                                        <span style="font-size: 20px">Po#: <strong class="text-left po_e"> </strong></span>
                                        <span style="margin-left: 15px;font-size: 20px"> Item Code: <strong class="text-center item_e"> </strong></span>
                                        <span style="margin-left: 15px;font-size: 20px">Quantity: <strong class="text-right actual_qty_e"> </strong></span>

                                    </div>

                                    <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
                                </div>
                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form class="modify_form">
                                        {{csrf_field()}}
                                        <table class="table table-bordered" id="dynamicTable">
                                            <tr>
                                                <th>LOT #</th>
                                                <th>Quantity</th>
                                                <th>Action</th>

                                            </tr>
                                            <tr>

                                                <td><input type="text" name="addmore[0][lot]" placeholder="Enter Lot Number" class="form-control" required/></td>
                                                <td><input type="number" name="addmore[0][qty]" placeholder="Enter Quantity" class="form-control" required/></td>
                                                <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>

                                            </tr>
                                        </table>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success btn-block"><i class="fa fa-shopping-cart" aria-hidden="true">Save</i>
                                            </button>
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
                                    <h4 class="modal-title">Approve PO Batch</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form class="approve_form">
                                        {{csrf_field()}}
                                        <div class="form-group">
                                            <label for="po">PO</label>
                                            <input type="text" name="po" class="form-control" id="po" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="item">Item</label>
                                            <input type="text" name="item" class="form-control" id="item" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="actual_batch">Actual Batch</label>
                                            <input type="text" name="actual_batch" class="form-control" id="actual_batch" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="actual_qty">Actual Qty</label>
                                            <input type="text" name="actual_qty" class="form-control" id="actual_qty" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="actual_expiry">Actual Expiry</label>
                                            <input type="text" name="actual_expiry" class="form-control" id="actual_expiry" disabled>
                                        </div>
                                        <input type="hidden" name="status" value="{{\App\PurchaseOrder::APPROVED_STATUS}}">


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
                                            <label for="po">PO</label>
                                            <input type="text" name="po" class="form-control" id="po_id" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="item">Item</label>
                                            <input type="text" name="item" class="form-control" id="item_id" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="actual_batch">Actual Batch</label>
                                            <input type="text" name="actual_batch" class="form-control" id="actual_batch_id" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="actual_qty">Actual Qty</label>
                                            <input type="text" name="actual_qty" class="form-control" id="actual_qty_id" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="actual_expiry">Actual Expiry</label>
                                            <input type="text" name="actual_expiry" class="form-control datepicker" id="actual_expiry_id" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="10" rows="5" class="form-control"></textarea>
                                        </div>
                                        <input type="hidden" name="status" value="{{\App\PurchaseOrder::REJECTED_STATUS}}">





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
        $('.pos').DataTable()
        $(function () {
            var i = 0;
            $("#add").click(function(){
                ++i;
                $("#dynamicTable").append('<tr><td><input type="text" name="addmore['+i+'][lot]" placeholder="Enter Lot Number" class="form-control" required/></td><td><input type="number" name="addmore['+i+'][qty]" placeholder="Enter Quantity" class="form-control" required/></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');
            });
            $(document).on('click', '.remove-tr', function(){
                $(this).parents('tr').remove();
            });
            var ii = 0;
            $("#addS").click(function(){
                ++ii;
                $("#dynamicTableS").append('<tr><td><input type="text" name="addmore['+ii+'][lot]" placeholder="Enter Serial Number" class="form-control" required/></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');
            });
            $(document).on('click', '.remove-tr', function(){
                $(this).parents('tr').remove();
            });

            var approve_id;
            var reject_id;
            var modify_id;
            var serial_id
            var balance_id;
            $('#actual_expiry').datepicker();
            $('.pos').on('click','.modify', function () {
                //alert('cool');
                modify_id = $(this).attr('modify_id');
                $.ajax({
                    url: '{{url('fetch-po')}}' + '/' + modify_id,
                    type: 'GET',
                    success: function (response) {
                        $('.po_e').text(response.po);
                        $('.item_e').text(response.item);
                        $('.actual_qty_e').text(response.actual_qty);
                        $('#actual_expiry_e').val(response.actual_expiry);
                        $('#actual_batch_e').val(response.actual_batch);
                    }
                });

            })


            $('.pos').on('click','.serial', function () {

                serial_id = $(this).attr('serial_id');
                $.ajax({
                    url: '{{url('fetch-po')}}' + '/' + serial_id,
                    type: 'GET',
                    success: function (response) {
                        $('.po_s').text(response.po);
                        $('.item_s').text(response.item);
                        $('.actual_qty_s').text(response.actual_qty);

                    }
                });

            })

            $('.confirmApprove').on('click',function () {
                if (confirm("Are you sure you want to Approve All")) {
                    $.ajax({
                        url:'{{url('approve-all-pos')}}'+'/'+$(this).attr('app_all'),
                        type:'GET',
                        success: function (response) {
                            console.log(response);
                            window.location.reload();
                        }
                    })
                }

            })

            $('.confirmApproveB').on('click',function () {
                if (confirm("Are you sure you want to Approve All")) {
                    $.ajax({
                        url:'{{url('approve-all-pos-b')}}'+'/'+$(this).attr('app_all_b'),
                        type:'GET',
                        success: function (response) {
                            console.log(response);
                            window.location.reload();
                        }
                    })
                }

            })



            $('.pos').on('click','.approval', function () {
                //alert('cool');
                approve_id = $(this).attr('approve_id');
                console.log('walla')
                console.log(approve_id)
                console.log('-----------')
                $.ajax({
                    url: '{{url('fetch-po')}}' + '/' + approve_id,
                    type: 'GET',
                    success: function (response) {
                        $('#po').val(response.po);
                        $('#item').val(response.item);
                        $('#actual_qty').val(response.actual_qty);
                        $('#actual_expiry').val(response.actual_expiry);
                        $('#actual_batch').val(response.actual_batch);
                    }
                });

            })

            $('.pos').on('click','.rejected', function () {
                reject_id = $(this).attr('reject_id');

                $.ajax({
                    url: '{{url('fetch-po')}}' + '/' + reject_id,
                    type: 'GET',
                    success: function (response) {
                        console.log(response);
                        $('#po_id').val(response.po);
                        $('#item_id').val(response.item);
                        $('#actual_qty_id').val(response.actual_qty);
                        $('#actual_expiry_id').val(response.actual_expiry);
                        $('#actual_batch_id').val(response.actual_batch);
                    }
                });
            })

            $('.modify_form').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: '{{url('receice-batches')}}' + '/' + modify_id,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        console.log(response);
                        if (response=='negativevalue'){
                            return toastr.warning('fail','Sorry,Qty Received cannot be greater than the available Qty.');
                        }
                        window.location.reload();
                    }
                })

            });

            $('.serial_form').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: '{{url('receive-serials')}}' + '/' + serial_id,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        console.log(response);
                        if (response=='negativevalue'){
                            return toastr.warning('fail','Sorry,Qty Received cannot be greater than the available Qty.');
                        }
                        window.location.reload();
                    }
                })

            });

            $('.approve_form').on('submit', function (e) {

                e.preventDefault();

                $.ajax({
                    url: '{{url('update-po')}}' + '/' + approve_id,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        console.log(response);
                        window.location.reload();
                    }
                })

            });
            $('.reject_form').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{{url('update-po')}}' + '/' + reject_id,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        console.log(response);
                        window.location.reload();
                    }
                })

            });

            $('.post_now').on('click', function () {
                var posted = $(this).attr('post_to_sage');

                $.ajax({
                    url: '{{url('approved-pos')}}' + '/' + posted,
                    type: 'GET',
                    success: function (response) {
                        if (response=='nobatches') {
                            toastr.warning('fail', 'Sorry,You must import po batches first.');
                            //window.location.reload();
                        } else if (response.length > 0) {

                            toastr.success('success', 'Pos successfully imported.');
                            window.location.href = '{{url('/pos')}}';
                        } else if (response.qty) {
                            toastr.warning('fail', response.qty);
                        }

                    }
                })
            });
            $('.process_now').on('click', function () {
                var posted = $(this).attr('post_to');

                $.ajax({
                    url: '{{url('check-po-status')}}' + '/' + posted,
                    type: 'GET',
                    success: function (response) {
                        console.log(response);
                        if (response == 'nodata'){
                            return toastr.warning('fail','Sorry.Import the batches first.');
                        }
                        if (response == 'fail') {
                            return toastr.warning('fail', 'You must either Approve or Reject all the batch lines before processing request.')
                        }
                        if (response =='proceed'){
                            $.ajax({
                                url: '{{url('approved-pos')}}' + '/' + posted,
                                type: 'GET',
                                success: function (response) {
                                    if (response == 'nobatches') {
                                        return toastr.warning('fail', 'Sorry,You must import po batches first.');

                                    }
                                    window.location.href = '{{url('/pos')}}';
                                }
                            })
                        }
                        if (response == 'success') {
                            $.ajax({
                                url: '{{url('process-pos')}}' + '/' + posted,
                                type: 'GET',
                                success: function (response) {
                                    console.log(response);
                                    window.location.href = '{{url('/pos')}}';
                                }
                            })
                        }
                    }
                })
            })
        })
    </script>

@endsection
