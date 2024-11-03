@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
@php
$collection_time_options = config('enums.collection_time');
$total_collection_quantity = 0; // Initialize total quantity
$units = []; // Array to store unique units
@endphp
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Collections</h4>
                <div class="d-flex">
                    <!-- Add any additional content here if needed -->
                </div>
                <div class="table-responsive">
                    <table class="table table-hover dt clickable mb-3">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cooperative</th>
                                <th>Collection No</th>
                                <th>Lot No</th>
                                <th>Farmer</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Collection Time</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($collections as $key => $collection)
                            @php
                            $total_collection_quantity += $collection->quantity; // Sum the quantities
                            $units[] = $collection->unit; // Collect all units
                            @endphp
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $collection->cooperative_name }}</td>
                                <td>{{ $collection->collection_number }}</td>
                                <td>{{ $collection->lot_number }}</td>
                                <td>
                                    <a href="{{ route('cooperative-admin.farmers.detail', $collection->farmer_id) }}">{{ $collection->username }}</a>
                                </td>
                                <td>{{ $collection->product_name }}</td>
                                <td>{{ number_format($collection->quantity) }}</td> <!-- Format with commas -->
                                <td>{{ $collection->unit }}</td>
                                <td>{{ $collection_time_options[$collection->collection_time] }}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                       <tfoot>
                        <tr class=" border-top">
                            <td colspan="6" class="text-right font-weight-bold text-dark">Total Collection Quantity:</td>
                            <td class="font-weight-bold text-primary">{{ number_format($total_collection_quantity) }}</td>
                            <td class="font-weight-bold text-dark">
                                @if (count(array_unique($units)) === 1) 
                                    {{ $units[0] }} 
                                @else 
                                    'Mixed Units' 
                                @endif
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush
