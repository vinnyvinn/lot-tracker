@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">New PO Batches</div>
                <div class="card-body">
                    <p></p>
                       <div class="col-md-10 mx-1">

                           <input type="hidden" id="p_o" value="{{$po->OrderNum}}">
                        <form action="{{url('store-batch')}}" method="POST">
                            {{csrf_field()}}
                            <table class="table table-bordered" id="dynamicTable">
                                <tr>
                                    <th>PO #</th>
                                    <th>Item</th>
                                    <th>Batch/Serial #</th>
                                    <th>Qty</th>
                                    <th>Expiry</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <td><input type="text" name="addmore[0][po]" value="{{$po->OrderNum}}" class="form-control" disabled/></td>
                                    <td><input type="text" name="addmore[0][item]" placeholder="Enter Item" class="form-control" required/></td>
                                    <td><input type="text" name="addmore[0][batch]" placeholder="Enter Batch" class="form-control" required/></td>
                                    <td><input type="number" name="addmore[0][qty]" placeholder="Enter Qty" class="form-control" required/></td>
                                    <td><input type="text" name="addmore[0][expiry]" placeholder="Enter Expiry" class="form-control datep" required/></td>
                                    <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>

                                </tr>
                            </table>
                            <input type="hidden" name="id" value="{{$po->id}}">
                            <button type="submit" class="btn btn-success btn-block"><i class="fa fa-shopping-cart" aria-hidden="true">Save</i>
                                </button>

                        </form>
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

        var i = 0;
       var po = $('#p_o').val();
        $("#add").click(function(){
             ++i;
            $("#dynamicTable").append('<tr><td><input type="text" name="addmore['+i+'][po]" value="'+po+'" class="form-control" disabled/></td><td><input type="text" name="addmore['+i+'][item]" placeholder="Enter Item" class="form-control" required/></td><td><input type="text" name="addmore['+i+'][batch]" placeholder="Enter Batch" class="form-control" required/></td><td><input type="number" name="addmore['+i+'][qty]" placeholder="Enter Qty" class="form-control" required/></td><td><input type="text" name="addmore['+i+'][expiry]" placeholder="Enter Expiry" class="form-control date_p" required/></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');
        });
        $(document).on('click', '.remove-tr', function(){
            $(this).parents('tr').remove();
        });
        $(function () {
            $('.datep').datepicker();
        })
        $(document).on('focus', '.date_p', function() {
            $(this).datepicker();
        });
    </script>

@endsection
