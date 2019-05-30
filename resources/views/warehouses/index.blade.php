@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">Warehouses
                   <span class="pull-right">
                    <a href="{{route('wh.create')}}" class="btn btn-info pull-right"> <i class="fa fa-plus">Add New</i></a>
                  </span>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered wh" style="width:100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($wh as $h)
                                <tr>
                                <td>{{$h->id}}</td>
                                <td>{{$h->name}}</td>
                                <td>
                                    <a href="{{url('wh/'.$h->id.'/edit')}}" class="btn btn-success btn-xs pull-right" style="margin: 2px"><i class="fa fa-edit">Edit</i></a>
                                   @if($h->id !=1)
                                    <span class="pull-right">
                                        <form action="{{route('wh.destroy',['id' => $h->id])}}" method="POST">
                                            {{csrf_field()}}
                                            {{method_field('DELETE')}}
                                        <input type="submit" class="btn btn-danger btn-xs" value="Delete">
                                    </form>
                                    @endif
                                   </span>
                               </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $('.wh').DataTable()
        $(function () {

        })
    </script>
@endsection
