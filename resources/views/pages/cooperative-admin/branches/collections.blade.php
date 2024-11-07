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
                                <th>Collection Number</th>
                                <th>Lot Number</th>
                                <th>Quantity</th>
                                <th>Unit Price</th> <!-- Updated header to match `unit_price` -->
                                <th>Branch Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($collections as $key => $collection)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($collection->created_at)->format('Y-m-d') }}</td>
                                <td>{{ $collection->collection_number }}</td>
                                <td>{{ $collection->lot_number }}</td>
                                <td>{{ $collection->quantity }}</td>
                                <td>{{ $collection->unit_price }}</td> <!-- Display `unit_price` -->
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
