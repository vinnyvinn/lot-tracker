@extends('layouts.app')
@section('content')

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">Add Warehouse</div>
                <div class="card-body">
                    <form action="{{route('wh.store')}}" method="POST">
                       {{csrf_field()}}
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                       <center>
                           <input type="submit" class="btn btn-primary" value="Save">
                       </center>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
