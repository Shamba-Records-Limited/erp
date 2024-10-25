@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Market/Auction</div>
        <div class="card-subtitle">Cart Checkout</div>
        <div class="font-weight-bold">Cooperative: {{$cooperative->name}}</div>

        <div class="border border-success rounded  shadow-sm p-2">
            <div class="d-flex justify-content-between">
                <div class="text-success">Total: <span class="font-weight-bold">{{$totalInCart}}KG</span> </div>
                <div>
                    <button class="btn btn-outline-primary" data-toggle="collapse" data-target="#aggregateDistribution" aria-controls="aggregateDistribution">
                        <div class="mdi mdi-chevron-down"></div>
                    </button>
                </div>
            </div>
            <div class="collapse p-2" id="aggregateDistribution">
                <h4>Grading Distribution</h4>
                @foreach ($aggregateGradeDistribution as $distribution)
                <div>
                    {{$distribution->total}}KG of {{ $distribution->grade }}
                </div>
                @endforeach

            </div>
        </div>

        <div class="p-3">
            @foreach($cartItems as $key => $item)
            <div class="row border rounded shadow-sm bg-white p-2 mt-2">
                <div class="col-7">{{$item->lot_number}}</div>
                <div class="col-2">
                    {{$item->quantity}} KG
                </div>
                <div class="col-2 justify-self-end">
                    <form action="{{route('miller-admin.market-auction.remove-from-cart', [$cooperative->id, $item->lot_number])}}" method="post">
                        @csrf
                        {{ method_field('DELETE') }}
                        <button onclick="return confirm('sure to remove?')" class="btn btn-outline-danger">Remove</button>
                    </form>
                </div>
                <div class="col">
                    <button class="btn btn-outline-primary" data-toggle="collapse" data-target="#distribution{{$item->id}}" aria-controls="distribution{{$item->id}}">
                        <div class="mdi mdi-chevron-down"></div>
                    </button>
                </div>

                <div id="distribution{{$item->id}}" class="col-12 collapse">
                    <h4 class="">Grade Distribution:</h4>
                    @foreach($item->distributions as $distribution)
                    <div>
                        {{$distribution->total}}KG of {{$distribution->grade}}
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <form class="mt-3" action="{{route('miller-admin.market-auction.checkout-cart', $cooperative->id)}}" method="POST">
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