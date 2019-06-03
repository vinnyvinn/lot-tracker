@extends('layouts.app')

@section('content')

    <div class="row">
            <div class="card">
                <div class="card-header">PO Batchespp

{{--                @if($batches->status != \App\PurchaseOrder::APPROVED_STATUS || $batches->status != \App\PurchaseOrder::PROCESSED_STATUS)--}}
                    <a href="{{url('batches/'.$id)}}" class="btn btn-info pull-right"><img src="{{asset('assets/img/export.png')}}" alt="" width="25">Import</a>
                    <a href="{{url(url('sample'))}}" class="btn btn-info pull-right mx-2"><img src="{{asset('assets/img/download.png')}}" alt="" width="25">Download Sample</a>
{{--               @endif--}}
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered pos">
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
                            <th>Qty Accepted</th>
                            <th>Qty Rejected</th>
                            <th>Reason</th>
                            <th>QC Done</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($batches->batches))
                            @foreach($batches->batches as $batch)

                                <tr>
                                    <td>
                                        @if($batch->qc_done ==0)
                                        <a href="#" title="Inspect" data-toggle="modal" data-target="#inspect" inspect_id="{{$batch->id}}" class="btn btn-info btn-xs inspect"><img src="{{asset('assets/img/tap.png')}}" alt="" width="20px"> {{$batch->po}}</a>
                                    @else
                                    <span class="label label-default" style="font-size: 14px">{{$batch->po}}</span>
                                     @endif
                                    </td>
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
                                    <td>{{$batch->qty_accepted}}</td>
                                    <td>{{$batch->qty_rejected}}</td>
                                      <td>
                                       {{$batch->reject_reason}}
                                    </td>
                                    <td>
                                        <span class="checkbo">
                                            <input type="checkbox" value="{{$batch->qc_done}}" @if($batch->qc_done ==1) checked="'checked'" @endif>
                                             <span class="checkmark"></span>
                                        </span>
                                        </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col text-center">
                            <button class="btn btn-primary border border-warning border-4 post_now my-2" post_to_sage="{{$id}}"><img src="{{asset('assets/img/approved.png')}}" class="approve_all"><span class="walla_img">Save</span></button>

                        </div>
                    </div>

                    <!-- The Inspect Modal -->
                    <div class="modal" id="inspect">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Inspect PO Batch</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form  action="{{url('update-batch')}}" method="POST" class="inspect_form">
                                        {{csrf_field()}}
{{--                                        {{method_field('PUT')}}--}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="po">PO #</label>
                                                <input type="text" name="po" class="form-control" id="po" disabled>
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
                                                <label for="batch">Batch</label>
                                                <input type="text" name="batch" class="form-control" id="batch" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="actual_batch">Actual Batch</label>
                                                <input type="text" name="actual_batch" class="form-control" id="actual_batch" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="qty">Qty</label>
                                                <input type="number" name="qty" class="form-control" id="qty" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="actual_qty">Actual Qty</label>
                                                <input type="number" name="actual_qty" class="form-control" id="actual_qty" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="expiry">expiry</label>
                                                <input type="text" name="expiry" class="form-control" id="expiry" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="actual_expiry">Actual expiry</label>
                                                <input type="text" name="actual_expiry" class="form-control" id="actual_expiry" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="qty_accepted">Qty Approved</label>
                                                <input type="number" name="qty_accepted" class="form-control" id="qty_approved" required>
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
                                                <form action="{{url('/new-reason')}}" method="POST">
                                                    {{csrf_field()}}
                                                         <div class="form-group">
                                                        <label for="reason">Reason</label>
                                                        <input type="text" name="reason" class="form-control" id="reason"  placeholder="Enter reason" required>
                                                    </div>
                                                    <input type="hidden" name="id" value="{{$id}}">
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
            <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
            <script src="{{asset('assets/js/dataTables.bootstrap4.min.js')}}"></script>
            <script>
                $('.pos').DataTable()
                var inspect_id;
                $(function () {
                    var qty_rejected;
                    var qty_approved;
                    $('#qty_approved').on('keyup',function () {
                        console.log($(this).val())
                        qty_rejected = $('#actual_qty').val() - $(this).val();
                        $('#qty_rejected').val(qty_rejected);
                        if (qty_rejected < 1){
                            $('.rs').hide();
                        }
                        else{
                            $('.rs').show();
                        }
                    })

                    $('#qty_rejected').on('keyup',function () {
                        qty_approved = $('#actual_qty').val() - $(this).val();
                        $('#qty_approved').val(qty_approved);

                        if(qty_approved < 1){
                            $('.rs').hide();
                        }
                        else {
                            $('.rs').show();
                        }
                    })
                    $('.inspect_form').on('submit',function (e) {
                        e.preventDefault();
                        if (qty_approved > $('#actual_qty').val()) {
                            return toastr.warning('fail','Sorry,Qty Approved cannot be greater than Actual Qty.')
                        }
                        if(qty_rejected > $('#actual_qty').val()){
                            return toastr.warning('fail','Sorry,Qty Rejected cannot be greater than Actual Qty.')
                        }
                        if(qty_approved < 0 || qty_rejected < 0){
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

                    $('.inspect').on('click',function () {

                        inspect_id = $(this).attr('inspect_id');
                        $('.insp_id').val(inspect_id);
                        $.ajax({
                            url:'{{url('fetch-po')}}'+ '/'+inspect_id,
                            type:'GET',
                            success:function (response) {
                                $('#po').val(response.po);
                                $('#item').val(response.item);
                                $('#qty').val(response.qty);
                                $('#actual_qty').val(response.actual_qty);
                                $('#batch').val(response.batch);
                                $('#expiry').val(response.expiry_date);
                                $('#actual_expiry').val(response.actual_expiry);
                                $('#actual_batch').val(response.actual_batch);
                            }
                        });

                    })

                    $('.post_now').on('click',function () {
                        var posted = $(this).attr('post_to_sage');

                        $.ajax({
                            url:'{{url('approved-pos')}}' + '/'+ posted,
                            type:'GET',
                            success: function (response) {
                                if(response.length < 1){
                                    return toastr.warning('fail','Sorry,You must import po batches first.');

                                }
                                if(response == 'fail'){
                                    return toastr.warning('fail','Sorry,You must perform the QC for all the batches before proceeding.');
                                }
                                else if(response.length > 0)
                                {

                                    toastr.success('success','Pos successfully approved.');
                                    window.location.href='{{url('/pos')}}';
                                }
                                else if(response.qty){
                                    toastr.warning('fail',response.qty);
                                }

                            }
                        })
                    });
                })

            </script>

@endsection
