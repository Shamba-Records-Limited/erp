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
            Cooperative:
            <span class="font-weight-bold">{{$cooperative->name}}</span>
        </div>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'collections'?'active':'' }}" href="?tab=collections">Collections</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'lots'?'active':'' }}" href="?tab=lots">Lots</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'grades'?'active':'' }}" href="?tab=grades">Grades</a>
            </li>
        </ul>


        @if($tab == 'collections')
        <div>
            <form class="d-flex justify-content-end">
                <div class="d-flex align-items-center">
                    Product Filter: 
                    <select name="product_id" class="form-control select2bs4" onchange="this.form.submit()">
                        <option value="">All</option>
                        @foreach($selectableProducts as $product)
                        <option value="{{$product->id}}" @if($product->id == $productId) selected @endif> {{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

            </form>
        </div>
        <div class="table-responsive p-2">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>#</th>
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
                        <td>{{$collection->collection_number}}</td>
                        <td>{{$collection->lot_number}}</td>
                        <td>
                            <a href="{{route('cooperative-admin.farmers.detail', $collection->farmer_id)}}">{{$collection->first_name}} {{$collection->other_names}} - {{$collection->member_no}}</a>
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
            <div class="col border rounded p-2">
                <div>Total Collections:</div>
                <div>{{$totalCollectionQty}} KGs</div>
            </div>
            <div class="col">

            </div>
        </div>
        @elseif($tab == 'lots')
        <div class="table-responsive p-2">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lot No</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lots as $key => $lot)
                    <tr>
                        <td>{{++$key }}</td>
                        <td><a href="{{route('cooperative-admin.lots.detail', $lot->lot_number)}}">{{$lot->lot_number}}</a></td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="table-responsive p-2">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Grade</th>
                        <th>Quantity</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grades as $key => $grade)
                    <tr>
                        <td>{{++$key }}</td>
                        <td>{{$grade->name}}</td>
                        <td>{{$grade->quantity}} KG</td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush