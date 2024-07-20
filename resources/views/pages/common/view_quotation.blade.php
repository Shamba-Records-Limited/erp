@extends('layout.master-mini')
@section('content')
<div class="w-100">
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                Quotation
            </div>
            <div style="text-align: center;">
                {!! QrCode::generate(route('common.view-quotation', $quotation->id)) !!}
            </div>
            <div class="d-flex justify-content-between">
                <div>{{$quotation->quotation_number}}</div>
                <div>{{$quotation->created_at}}</div>
            </div>
            <div>
                Customer: {{$quotation->customer->name}}
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

            <div class="mt-3">
                <div>Total: <span style="font-weight: bold;">KES. {{$quotation->total_price}}</span> </div>
            </div>
        </div>
    </div>
</div>

@endsection