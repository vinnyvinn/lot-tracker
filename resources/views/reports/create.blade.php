@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Generate Report</div>
                <div class="card-body">
<div class="col-lg-6 col-lg-offset-3">
    <form action="{{route('reports.store')}}" method="POST">
        {{csrf_field()}}
    <div class="form-group">
        <label for="p_s">Choose (Purchase Order/Sale Order)</label>
        <select name="s_p" id="s_p" class="form-control" style="height: 100%" required>
            <option value="po">Purchase Order</option>
            <option value="so">Sale Order</option>
        </select>
    </div>
    <div class="form-group">
        <label for="date_from">Date From</label>
        <input type="text" name="date_from" id="date_from" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="date_to">Date To</label>
        <input type="text" name="date_to" id="date_to" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Generate</button>
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
        $('.so').DataTable()
$('#date_from,#date_to').datepicker();

    </script>

@endsection
