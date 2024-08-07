@extends('layout.printable')
@section('content')
<div>
    <h4>Invoice</h4>
    <div class="d-flex justify-content-between">
        <h3>Invoice Number: {{$invoice->invoice_number}}</h3>
        <div>{{$invoice->created_at}}</div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>
                    <div class="font-weight-bold item_number">{{$item->number}}</div>
                    <!-- <div>Nescafe 10 Kgs</div> -->
                </td>
                <td>KES {{$item->price}}</td>
                <td class="item_quantity">{{$item->quantity}}</td>
                <td>KES {{$item->price * $item->quantity}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-end">
        <div>Total: <span style="font-weight: bold;">KES. {{$invoice->total_price}}</span> </div>
    </div>
    <div style="background-color: white !important;" class="d-flex justify-content-center">
        {!! QrCode::size(150)->generate('Invoice') !!}
    </div>
</div>
@endsection