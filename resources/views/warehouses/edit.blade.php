@extends('layouts.app')
@section('content')

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">Edit Warehouse</div>
                <div class="card-body">
                    <form action="{{route('wh.update',['id' =>$wh->id])}}" method="POST">
                        {{csrf_field()}}
                        {{method_field('PUT')}}
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" value="{{$wh->name}}">
                        </div>
                        <center>
                            <input type="submit" class="btn btn-primary" value="Update">
                        </center>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>
@endsection
