@extends('layout.printable')
@section('content')
<div>
    <h4>Collections Receipt</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Lot Number</th>
                <th>Quantity</th>
                <th>collection Number</th>
                <th>Product</th>
                <th>Unit</th>
                <th>Farmer</th>
                <th>Collection Time</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$collections->lot_number}}</td>
                <td>{{$collections->quantity}} KG</td>
                <td>{{$collections->collection_number}}</td>
                <td>{{$collections->product->name}}</td>
                <td>{{$collections->unit}}</td>
                <td>{{$collections->farmer->user->first_name}} {{$collections->farmer->user->other_names}}</td>
                <td>{{ $collectionTimeLabels[$collections->collection_time] }}</td>
            </tr>
        </tbody>
    </table>
    <div class="d-flex justify-content-end">
    </div>
    <div class="d-flex justify-content-center">
    {!! QrCode::size(150)->generate('Transaction Receipt') !!}
    </div>
</div>
@endsection