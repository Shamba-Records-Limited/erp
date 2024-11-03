@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
@php
$collection_time_options = config('enums.collection_time');
$total_collection_quantity = 0; // Initialize total quantity
$total_collections = count($collections); // Total number of collections
$units = []; // Array to store unique units
$cooperative_totals = []; // Array to store totals per cooperative

// Initialize variables for additional metrics
$product_totals = []; // Array to track total quantities for products
$collection_time_distribution = []; // Array to track counts by collection time

foreach ($collections as $collection) {
    // Calculate total quantity and product totals
    $total_collection_quantity += $collection->quantity; // Sum the quantities
    $units[] = $collection->unit; // Collect all units

    // Track product totals
    if (!isset($product_totals[$collection->product_name])) {
        $product_totals[$collection->product_name] = 0;
    }
    $product_totals[$collection->product_name] += $collection->quantity;

    // Track collection time distribution
    if (!isset($collection_time_distribution[$collection->collection_time])) {
        $collection_time_distribution[$collection->collection_time] = 0;
    }
    $collection_time_distribution[$collection->collection_time]++;
}

// Calculate average quantity per collection
$average_quantity = $total_collections > 0 ? $total_collection_quantity / $total_collections : 0;

// Determine top product
$top_product = $product_totals ? array_keys($product_totals, max($product_totals))[0] : null;

// Get cooperative totals
foreach ($collections as $collection) {
    if (!isset($cooperative_totals[$collection->cooperative_name])) {
        $cooperative_totals[$collection->cooperative_name] = [
            'quantity' => 0,
            'unit' => $collection->unit // Assuming unit is the same for simplicity
        ];
    }
    $cooperative_totals[$collection->cooperative_name]['quantity'] += $collection->quantity;
}
@endphp

<div class="header bg-custom-green pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <!-- Total Number of Collections -->
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Total Collections</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $total_collections }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-box"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Average Quantity per Collection -->
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Average Quantity</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ number_format($average_quantity, 2) }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-calculator"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
                                <span class="text-nowrap">Since last week</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Top Product -->
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Top Product</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $top_product ?? 'N/A' }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-warning mr-2"><i class="fas fa-arrow-up"></i> 5.25%</span>
                                <span class="text-nowrap">Since yesterday</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Collection Time Distribution -->
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Collection Times</h5>
                                    <ul class="list-unstyled">
                                        @foreach($collection_time_distribution as $time => $count)
                                            <li>{{ $collection_time_options[$time] ?? $time }}: {{ $count }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Collections per Cooperative</h4>
                <div class="table-responsive mb-10">
                    <table class="table table-hover dt clickable mb-3">
                        <thead>
                            <tr>
                                <th>Cooperative</th>
                                <th>Total Quantity</th>
                                <th>Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cooperative_totals as $cooperative => $data)
                                <tr>
                                    <td>{{ $cooperative }}</td>
                                    <td>{{ number_format($data['quantity']) }}</td>
                                    <td>{{ $data['unit'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-190" style="margin-top:180px;">
                <h4 class="card-title mt-10">Detailed Collections</h4>
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
                            <tr class="border-top">
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
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush
