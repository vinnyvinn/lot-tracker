@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-12 mx-auto">
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
                            <th>Description</th>
                            <th>Qty</th>
                           <th>Qty Accepted</th>
                            <th>Qty Rejected</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th>QC done</th>
                            <span class="pull-right" style="margin-top: 15px;margin-right: 5px;">
                                <a href="#" class="btn btn-primary btn-xs confirmInspect" app_all="{{$inv_lines->id}}"><i class="fa fa-check-circle"></i>Inspect All</a>
                            </span>


                        </tr>
                        </thead>
                        <tbody>
                        @foreach($inv_lines->lines as $so)
                            <tr>
                                <td>
                                    @if($so->qc_done ==0)
                                    <a href="#" class="btn btn-info btn-xs inspect" title="Inspect" data-toggle="modal" data-target="#inspect" inspect_id="{{$so->id}}"><img src="{{asset('assets/img/tap.png')}}" alt="" width="20px"> {{$so->OrderNum}}</a>
                                    @else
                                       <span class="label label-default" style="font-size: 14px">{{$so->OrderNum}}</span>
                                        @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($so->InvDate)->format('d/m/Y')}}</td>
                                <td>{{$so->cAccountName}}</td>
                                <td>{{$so->item}}</td>
                                <td>
                                    {{$so->cDescription}}
                                </td>
                                <td>{{$so->fQuantity}}</td>

                                <td>{{$so->qty_accepted}}</td>
                                <td>{{$so->qty_rejected}}</td>
                                <td>
                                    @if($so->status== \App\InvoiceLine::STATUS_PENDING)
                                        <span class="label label-info">{{$so->status}}</span>
                                    @elseif($so->status== \App\InvoiceLine::STATUS_REJECTED)
                                        <span class="label label-warning">{{$so->status}}</span>
                                    @else
                                        <span class="label label-success">{{$so->status}}</span>
                                    @endif
                                </td>
                                <td>{{$so->reject_reason}}</td>
                                <td>
                                     <span class="checkbo">
                                            <input type="checkbox" value="{{$so->qc_done}}" @if($so->qc_done ==1) checked="'checked'" @endif>
                                             <span class="checkmark"></span>
                                        </span>
                                @if($so->qc_done ==1)
                                        <a href="{{url('show-batches/'.$so->id)}}" class="btn btn-primary btn-xs pull-right" style="margin-top: -10px"><i class="fa fa-eye" style="font-size: 14px" title="Show Details"></i></a>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if($inv_lines->status == \App\SaleOrder::STATUS_PROCESSED)
                        <div class="row">
                            <div class="col text-center">
                                <button class="btn btn-primary border border-warning border-4 post_now my-2" post_to_sage="{{$inv_lines->id}}"><img src="{{asset('assets/img/approved.png')}}" class="approve_all"><span class="walla_img">Finalize</span></button>
                            </div>
                        </div>
                @endif

                <!-- The Inspect Modal -->
                    <div class="modal" id="inspect">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Inspect Invoice Line</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                               <!-- Modal body -->
                                <div class="modal-body">
                                    <form  action="{{url('update-lines')}}" method="POST" class="inspect_form">
                                        {{csrf_field()}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="order_no">Order #</label>
                                                <input type="text" name="order_no" class="form-control" id="order_no" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="account_name">Account Name</label>
                                                <input type="text" name="account_name" class="form-control" id="account_name" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="description">description</label>
                                                <input type="text" name="description" class="form-control" id="description" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="item">Item</label>
                                                <input type="text" name="item" class="form-control" id="item" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="actual_qty">Actual Qty</label>
                                                <input type="number" name="actual_qty" class="form-control" id="actual_qty" disabled>
                                                <input type="hidden" name="actual_qty_2" id="actual_qty_2">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="qty_received">Qty Approved</label>
                                                <input type="number" name="qty_received" class="form-control" id="qty_received" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="qty_accepted">Qty Accepted</label>
                                                <input type="number" name="qty_accepted" class="form-control" id="qty_accepted" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="qty_rejected">Qty Rejected</label>
                                                <input type="number" name="qty_rejected" class="form-control" id="qty_rejected" required>
                                            </div>
                                        </div>

                                        <div class="col-md-12 rs">
                                            <div class="form-group">
                                                <label for="rejection_reason">Rejection Reason</label>

                                                <select name="rejection_reason" id="rejection_reason" class="form-control" style="width: 80%;height: 100%">
                                                    <option></option>
                                                    @foreach($reasons as $reason)
                                                        <option value="{{$reason->reason}}">{{$reason->reason}}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="id" class="insp_id">
                                                <a href="#" class="btn btn-primary btn-sm pull-right" title="Add New" data-toggle="modal" data-target="#addNew" style="margin-top: -32px"><i class="fa fa-plus">Add New</i></a>
                                            </div>
                                        </div>
                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- The Add New Modal -->
                                <div class="modal" id="addNew">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Add New Reason</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <form action="{{url('/add-reason')}}" method="POST">
                                                    {{csrf_field()}}
                                                    <div class="form-group">
                                                        <label for="reason">Reason</label>
                                                        <input type="text" name="reason" class="form-control" id="reason"  placeholder="Enter reason" required>
                                                    </div>
                                                    <input type="hidden" name="id" value="{{$inv_lines->id}}">
                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save</button>
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
            var inspect_id;

            $('.confirmInspect').on('click',function () {
                if (confirm("Are you sure you want to Inspect All")) {
                    $.ajax({
                        url:'{{url('inspect-all-so')}}'+'/'+$(this).attr('app_all'),
                        type:'GET',
                        success: function (response) {
                            console.log(response);
                            window.location.reload();
                        }
                    })
                }

            })
            $('.inspect').on('click',function () {
                //alert('cool');
                inspect_id = $(this).attr('inspect_id');
                 $('.insp_id').val(inspect_id);
                $.ajax({
                    url:'{{url('fetch-so')}}'+ '/'+inspect_id,
                    type:'GET',
                    success:function (response) {
                        $('#order_no').val(response.lines.OrderNum);
                        $('#item').val(response.lines.item);
                        $('#actual_qty').val(response.lines.fQuantity);
                        $('#qty_received').val(response.lines.qty_received);
                        $('#description').val(response.lines.cDescription);
                        $('#account_name').val(response.lines.cAccountName);
                        $('#actual_qty_2').val(response.lines.fQuantity)

                        var qty_rejected;
                        var qty_accepted;
                        $('#qty_accepted').on('keyup',function () {
                            qty_rejected = $('#qty_received').val() - $(this).val();
                            console.log(qty_rejected)
                            $('#qty_rejected').val(qty_rejected);
                            if (qty_rejected < 1){
                                $('.rs').hide();
                            }
                            else{
                                $('.rs').show();
                            }
                        })
                        $('#qty_rejected').on('keyup',function () {
                            console.log('walla')
                            qty_accepted = $('#qty_received').val() - $(this).val();
                            $('#qty_accepted').val(qty_accepted);

                            if(qty_accepted < 1){
                                $('.rs').hide();
                            }
                           else{
                                $('.rs').show();
                            }
                        })
                        $('.inspect_form').on('submit',function (e) {
                            e.preventDefault();
                            if (qty_accepted > $('#qty_received').val()) {
                                return toastr.warning('fail','Sorry,Qty Accepted cannot be greater than Approved Qty.')
                            }
                            if(qty_rejected > $('#qty_received').val()){
                                return toastr.warning('fail','Sorry,Qty Rejected cannot be greater than Approved Qty.')
                            }
                            if(qty_accepted < 0 || qty_rejected < 0){
                                return toastr.warning('fail','Sorry,negative quantities are not allowed.')
                            }
                            $.ajax({
                                url:$(this).attr('action'),
                                type:'POST',
                                data:$(this).serialize(),
                                success: function (response) {
                                    console.log(response)
                                    window.location.reload();
                                }
                            })
                        })
                    }
                });
            })
            $('.post_now').on('click',function () {
                var posted = $(this).attr('post_to_sage');
                $.ajax({
                    url:'{{url('approved-so')}}' + '/'+ posted,
                    type:'GET',
                    success: function (response) {
                        console.log(response);
                        if(response=='fail'){
                         return toastr.warning('fail','Sorry,Kindly Inspect first all the invoice lines before proceeding.')
                        }
                        if(response=='noitems'){
                            return toastr.warning('fail','Sorry,You do not have invoice lines in this invoice')
                        }
                        else{
                            window.location.reload();
                        }
                    }
                })
            });
           })
    </script>

@endsection
