@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Market/Auction</div>
        <div class="card-subtitle">Cart Checkout</div>
        <div class="font-weight-bold">Cooperative: {{$cooperative->name}}</div>

        <div class="p-3">
            @foreach($cartItems as $key => $item)
            <div class="row border rounded shadow-sm bg-white p-2 mt-2">
                <div class="col-7">{{$item->lot_number}}</div>
                <div class="col-2">
                    {{$item->quantity}} -KG-
                </div>
                <div class="col-3 justify-self-end">
                    <button class="btn btn-danger btn-sm">Remove</button>
                </div>
            </div>
            @endforeach
        </div>

        <form class="mt-3" action="{{route('miller-admin.market-auction.checkout-cart', $cooperative->id)}}" method="POST">
            {{$errors}}
            @csrf
            <div class="form-row">
                <div class="form-group col-lg-3 col-md-6 col-12">
                    <label for="batch_number">Batch Number</label>
                    <input type="text" name="batch_number" class="form-control {{ $errors->has('batch_number') ? ' is-invalid' : '' }}" id="batch_number" placeholder="Enter batch number" value="{{ old('batch_number') ? old('batch_number') : $default_batch_number}}" required>

                    @if ($errors->has('batch_number'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('batch_number')  }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group col-lg-3 col-md-6 col-12">
                    <label for="bank_id">Select milling warehouse</label>
                    <select name="miller_warehouse_id" id="miller_warehouse_id" class="form-control select2bs4 {{ $errors->has('miller_warehouse_id') ? ' is-invalid' : '' }}">
                        <option value="">-- Select Warehouse --</option>
                        @foreach($warehouses as $warehouse)
                        <option value="{{$warehouse->id}}"> {{ $warehouse->name }}</option>
                        @endforeach

                        @if ($errors->has('miller_warehouse_id'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('miller_warehouse_id')  }}</strong>
                        </span>
                        @endif
                    </select>
                </div>
            </div>
            <button class="btn btn-primary">Create Order</button>
        </form>

    </div>

</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush