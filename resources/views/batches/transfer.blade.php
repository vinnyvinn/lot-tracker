@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">Transfer Batch/Serial
                </div>
                <div class="card-body">
                    <form action="{{url('store-serial')}}" method="POST">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="from">From</label>
                            <select name="from" class="form-control from" style="height: 100%" required>
                                <option>--From Warehouse--</option>
                                @foreach($wh as $h)
                                    <option value="{{$h->id}}">{{$h->name}}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="from">To</label>
                            <select name="to" id="to" class="form-control to" style="height: 100%" required>

                            </select>
                        </div>

                            <div class="form-group bt">
                                <label for="batch">Batch / Serial</label>
                                <select name="batch[]" id="batch" class="form-control select2" required  multiple="multiple" style="width: 100%;height: 100%">
                                </select>
                            </div>
                            <center>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-exchange"></i>Transfer</button>
                           </center>
                    </form>
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
        $('.bt').hide();
        $('.select2').select2();
        $('.pos').DataTable()
      $(function () {
           var id;
          $('.from').on('change',function () {

              id = $(this).val();

              $('#batch').find('option').remove();
              $('#to').find('option').remove();
              $.ajax({
                  url:'{{url('fetch-bacthes')}}'+'/'+id,
                  type:'GET',
                  success: function (response) {
                     if (response == 'notfound') {
                         return toastr.warning('fail','Sorry,You do not have batch/serials for the selected Warehouse')
                     }
                     $('.bt').show();
                     for (var i =0;i<response.length;i++){
                         var option = "<option value='"+response[i].id+"'>"+response[i].batch+"</option>";
                         $('#batch').append(option);
                     }

                      $.ajax({
                          url:'{{url('fetch-wh')}}'+'/'+id,
                          type:'GET',
                          success:function (response) {
                              console.log(response)
                            for (var i=0;i<response.length;i++){
                                var option = "<option value='"+response[i].id+"'>"+response[i].name+"</option>";
                                $('#to').append(option);
                            }
                          }
                      })
                  }
              })

          })
      })

    </script>

@endsection
