@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">New Opening Balance Batches/Serials</div>
                <div class="card-body">
                    <p></p>
                    <div class="col-md-10 mx-1">
                        <div style="margin-bottom: 10px">
                            <label class="radio-inline">
                                <input type="radio" name="optradio" checked class="walla" value="1"><b>COMMAR SEPARATED LIST</b>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="optradio" class="walla" value="2"><b>MULTIPLE LINES</b>
                            </label>
                        </div>

                        <div class="commar_s">
                            <form action="{{url('more-serials')}}" method="POST">
                                {{csrf_field()}}
                                <div class="form-group">

                                    <label for="warehouse">Warehouse</label>
                                    <select name="warehouse" id="warehouse" class="form-control" style="height: 100%" required>
                                        @foreach($wh as $h)
                                            <option value="{{$h->id}}">{{$h->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="from-group">
                                <label for="type">Type</label>
                                <select name="type" class="form-control type" style="height:100%" required>
                                    <option></option>
                                <option value="LOT">LOT</option>
                                <option value="SERIAL">SERIAL</option>
                                </select>
                                </div>

                                <br>

                                <div class="form-group">
                                    <label for="item">Item Code</label>
                                    <input type="text" name="item" class="form-control item_code" required>
                                </div>
                                <div class="form-group quantity">
                                    <label for="qty">Quantity</label>
                                    <input type="number" name="qty" class="form-control qty">
                                </div>

                                <div class="form-group serials">
                                    <label for="all_serials">Serials</label>
                                    <textarea name="all_serials" id="all_serials" cols="30" rows="10" class="form-control"  placeholder="Enter Commar Separated List"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success btn-block">Submit</button>
                            </form>
                        </div>
<div class="manual_s">

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
        <div class="from-group">
                                <label for="type">Type</label>
                                <select name="type" class="form-control" style="height:100%">
                                    <option></option>
                                <option value="LOT">LOT</option>
                                <option value="SERIAL">SERIAL</option>
                                </select>
                                </div>
                                <br>
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
    </div>

@endsection
@section('scripts')
    <script src="{{asset('assets/js/jquery-ui.js')}}"></script>
    <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/dataTables.bootstrap4.min.js')}}"></script>
    <script>
   $('.pos').DataTable()
   $('.manual_s').hide();
   $('.serials').hide();
   $('.quantity').hide();
   $('.type').on('change',function () {
       if ($(this).val() =='LOT'){
           $('.serials').hide();
           $('.quantity').show();
       }
       if ($(this).val() =='SERIAL'){
           $('.serials').show();
           $('.quantity').hide();
       }
   })
   $('.walla').on('click',function () {
      if ($(this).val() ==1){
          $('.manual_s').hide();
          $('.commar_s').show();
      }
      else if($(this).val()==2){
          $('.manual_s').show();
          $('.commar_s').hide();
      }
   })
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
