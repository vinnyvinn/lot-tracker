@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">Import Excel File

                    <div class="card-body">


                        <form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{route('batches.store')}}" class="form-horizontal" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="form-group" style="margin-left: 1px;margin-right: 1px">
                                <label for="warehouse">Warehouse</label>
                                <select name="warehouse" class="form-control warehouse" style="height: 100%;">
                                    @foreach($wh as $h)
                                        <option value="{{$h->id}}">{{$h->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (Session::has('success'))
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                    <p>{{ Session::get('success') }}</p>
                                </div>
                            @endif

                            <input type="file" name="import_file" required/>
                            <input type="hidden" name="id" value="{{$id}}">
                            <button class="btn btn-primary btn-outline my-2">Submit</button>
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
