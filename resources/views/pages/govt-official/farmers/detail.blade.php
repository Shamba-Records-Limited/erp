@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
@php
$collection_time_options = config('enums.collection_time');
@endphp
<div class="card">
    <div class="card-body">
        <div class="card-title">
            Farmer: 
            <span class="font-weight-bold">{{$farmer->user->first_name}} {{$farmer->user->other_names}}</span>
            <span>{{$farmer->member_no}}</span>
        </div>

        <div class="mb-3">Total Farmer Collections: <span class="font-weight-bold">{{$collectionsTotal}} KG</span></div>

        <div class="table-responsive">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Collection No</th>
                        <th>Lot No</th>
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
                        <td>{{$collection->collection_number}}</td>
                        <td>{{$collection->lot_number}}</td>
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
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush