@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
@php
$collection_time_options = config('enums.collection_time');
@endphp
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Collections</h4>
                <div class="d-flex">
                    
                </div>
                <div class="table-responsive">
                    <table class="table table-hover dt clickable">
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
                                <td>{{++$key }}</td>
                                <td>{{$collection->cooperative_name}}</td>
                                <td>{{$collection->collection_number}}</td>
                                <td>{{$collection->lot_number}}</td>
                                <td>
                                    <a href="{{route('cooperative-admin.farmers.detail', $collection->farmer_id)}}">{{$collection->username}}</a>
                                </td>
                                <td>{{$collection->product_name}}</td>
                                <td>{{$collection->quantity}}</td>
                                <td>{{$collection->unit}}</td>
                                <td>{{ $collection_time_options[$collection->collection_time]}}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <!-- view aggregations -->
                    <div class="col-md-4 col-12 border rounded p-3">
                        Total Collection Quantity: <span class="font-weight-bold">10</span>
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