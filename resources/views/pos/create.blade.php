@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">New Opening Balance Batches/Serials</div>
                <div class="card-body">
                    <p></p>
                    <div class="col-md-10 mx-1">

                        <form action="{{url('new-serials')}}" method="POST">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label for="warehouse">Warehouse</label>
                                <select name="warehouse" id="warehouse" class="form-control" style="height: 100%" required>
                                    @foreach($wh as $h)
                                    <option value="{{$h->id}}">{{$h->name}}</option>
                                        @endforeach
                                </select>
                            </div>
                            <table class="table table-bordered" id="dynamicTable">
                                <tr>

                                    <th>Item Code</th>
                                    <th>Batch/Serial #</th>
                                    <th>Action</th>
                                </tr>
                                <tr>

                                    <td><input type="text" name="addmore[0][item]" placeholder="Enter Item" class="form-control" required/></td>
                                    <td><input type="text" name="addmore[0][batch]" placeholder="Enter Batch" class="form-control" required/></td>
                                    <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>

                                </tr>
                            </table>

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
       $("#add").click(function(){
            ++i;
            $("#dynamicTable").append('<tr><td><input type="text" name="addmore['+i+'][item]" placeholder="Enter Item Code" class="form-control" required/></td><td><input type="text" name="addmore['+i+'][batch]" placeholder="Enter Batch/Serial" class="form-control" required/></td></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');
        });
        $(document).on('click', '.remove-tr', function(){
            $(this).parents('tr').remove();
        });

    </script>

@endsection
