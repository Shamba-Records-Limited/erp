@extends('layout.printable')
@section('content')
<div>
    <h4>Quotation</h4>
    <div class="d-flex justify-content-between">
        <h3>Quotation Number: {{$quotation->quotation_number}}</h3>
        <div>{{$quotation->created_at}}</div>
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
            @foreach($quotation->items as $item)
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
        <div>Total: <span style="font-weight: bold;">KES. {{$quotation->total_price}}</span> </div>
    </div>
    <div style="background-color: white !important;" class="d-flex justify-content-center">
        {!! QrCode::size(150)->generate('Quotation') !!}
    </div>
</div>
@endsection