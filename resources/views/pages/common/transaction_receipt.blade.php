@extends('layout.printable')
@section('content')
<div>
    <h4>Transaction Receipt</h4>
    <h3>Receipt Number: {{$transaction->receipt_number}}</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Lot Number</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalQty = 0;
            @endphp
            @foreach($lots as $lot)
            @php
            $totalQty += $lot->quantity
            @endphp
            <tr>
                <td>{{$lot->lot_number}}</td>
                <td>{{$lot->quantity}} KG</td>
            </tr>
            @endforeach
            <tr>
                <td></td>
                <td>Total Quantity: <span class="font-weight-bold">{{$totalQty}} KG<span></td>
            </tr>
        </tbody>
    </table>
    <div class="d-flex justify-content-end">
        <div>Amount: {{$transaction->amount}}</div>
    </div>
    <div class="d-flex justify-content-center">
    {!! QrCode::size(150)->generate('Transaction Receipt') !!}
    </div>
</div>
@endsection