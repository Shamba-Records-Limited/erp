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
                                <th>Collection Time</th>
                                <th>Product</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalQuantity = 0; // Variable to hold the cumulative quantity
                            @endphp
                            @foreach($collections as $key => $collection)
                            @php
                                $totalQuantity += $collection->quantity;
                            @endphp
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($collection->date_collected)->format('Y-m-d') }}</td>
                                <td>{{ $collection->collection_number }}</td>
                                <td>{{ $collection->lot_number }}</td>
                                <td>{{ number_format($collection->quantity, 0) }}</td> <!-- Format quantity with commas -->
                                <td>
                                    @if($collection->collection_time == 1)
                                        Morning
                                    @elseif($collection->collection_time == 2)
                                        Afternoon
                                    @elseif($collection->collection_time == 3)
                                        Evening
                                    @else
                                        Unknown
                                    @endif
                                </td>
                                <td>{{ $collection->product_name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right"><strong>Total Quantity:</strong></td>
                                <td><strong>{{ number_format($totalQuantity, 0) }}</strong></td> <!-- Format total quantity with commas -->
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
