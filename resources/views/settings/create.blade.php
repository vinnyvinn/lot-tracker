@extends('layouts.app')
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css" />--}}
@section('content')

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">Settings</div>
                <div class="card-body">

                 <table class="table table-condensed table-bordered">
                     <thead>
                     <tr>
                         <th>Name</th>
                         <th>Action</th>

                     </tr>

                     </thead>
                     <tbody>
                     <tr>
                         <td>Enable Inspection</td>
                         <td>
                             <input type="checkbox" name="my-checkbox" @if($setting->enable_inspection == \App\Setting::ENABLE_INSPECTION) checked='checked' @endif id="walla">
                            </td>
                     </tbody>
                 </table>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>--}}
    <script src="{{asset('assets/js/jquery-ui.js')}}"></script>
    <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $('.so').DataTable()


        $(function () {
            $('#walla').on('click',function () {
                var value;
                if($(this).is(':checked')){
                    value=1;

                }
                else{
                    value=0;
                }
                $.ajax({
                    url:'{{url('settings/1')}}',
                    type:'GET',
                    data:{value:value},
                    success:function (response) {
                        console.log(response)
                        toastr.success('success','Settings successfully updated.')
                    }
                })
            })

        })
    </script>

@endsection
