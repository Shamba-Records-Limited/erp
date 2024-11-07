@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Collections for Branch</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Collection Date</th>
                                <th>Quantity</th>
                                <th>Branch Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($collections as $key => $collection)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ \Carbon\Carbon::parse($collection->created_at)->format('Y-m-d') }}</td>
                                <td>{{ $collection->quantity }}</td>
                                <td>{{ $collection->branch_name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection