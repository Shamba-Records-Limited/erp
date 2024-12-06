@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Market Place</div>
        <div class="card-subtitle">Cart Checkout</div>

        <div class="border border-success rounded  shadow-sm p-2">
            <div class="d-flex justify-content-between">
                <div class="text-success">Items Count: <span class="font-weight-bold">{{$totalInCart}}</span> </div>
                <div class="text-warning">KSH.<span class="font-weight-bold">{{$totalAmntInCart}}</span></div>
            </div>

        </div>

        <div class="p-3">
            @foreach($cartItems as $key => $item)
            <div class="row border rounded shadow-sm bg-white p-2 mt-2">
                <div class="col-7">{{$item->name}}</div>
                <div class="col-3">
                    Shs.{{$item->quantity*$item->sale_price}} 
                </div>
                <div class="col-2 justify-self-end">
                    <form action="{{route('farmer.marketplace.remove-item-from-cart', [$item->cart_id, $item->id])}}" method="post">
                        @csrf
                        {{ method_field('DELETE') }}
                        <button onclick="return confirm('sure to remove?')" class="btn btn-outline-danger">Remove</button>
                    </form>
                </div>
              
            </div>
            @endforeach
        </div>

        <form class="mt-3" action="{{route('farmer.marketplace.checkout-cart', $item->id)}}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group col-lg-3 col-md-6 col-6">
                    <label for="batch_number">Sales Number</label>
                    <input type="text" name="batch_number" class="form-control {{ $errors->has('batch_number') ? ' is-invalid' : '' }}" id="batch_number" placeholder="Enter batch number" value="{{ old('batch_number') ? old('batch_number') : $default_batch_number}}" required readonly>
                    
                    @if ($errors->has('batch_number'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('batch_number')  }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group col-lg-3 col-md-6 col-6">
                    <label for="cart_amount">Sale Total Amount</label>
                    <input type="text" name="cart_amount" class="form-control {{ $errors->has('cart_amount') ? ' is-invalid' : '' }}" id="cart_amount" placeholder="Enter Amount" value="{{ old('cart_amount') ? old('cart_amount') : $totalAmntInCart}}" required readonly>

                    @if ($errors->has('totalAmntInCart'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('totalAmntInCart')  }}</strong>
                    </span>
                    @endif
                </div>

                
            </div>
            <button class="btn btn-primary">Make Payments</button>
        </form>

    </div>

</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush