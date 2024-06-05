@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <div class="card-title">Market/Auction</div>
                <div class="card-subtitle">Cooperative: {{$cooperative->name}}</div>
            </div>
            <div class="col-2 dropdown">
                <a href="#" class="btn btn-outline btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown" id="cartDropdown">
                    <i class="mdi mdi-cart"></i>
                    <span class="badge badge-light">{{$items_in_cart_count > 9 ? '9+' : $items_in_cart_count }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="cartDropdown">
                    @if ($items_in_cart_count == 0)
                    <a class="dropdown-item" disabled>Please add items to cart first</p>
                        @else
                        <form action="{{ route('miller-admin.market-auction.clear-cart', $cooperative->id) }}" method="POST">
                            @csrf
                            {{ method_field('DELETE') }}
                            <button onclick="return confirm('Sure to clear cart?')" class="dropdown-item"> Clear Cart</button>
                        </form>
                        <a class="dropdown-item" href="{{ route('miller-admin.market-auction.view-checkout-cart', $cooperative->id) }}"> Checkout</a>
                        @endif
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lot No</th>
                        <th>Available Quantity</th>
                        <th>In Cart</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lots as $key => $lot)
                    <tr>
                        <td>{{++$key }}</td>
                        <td>{{$lot->lot_number}}</td>
                        <td>{{$lot->available_quantity}} unit_here</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="{{ route('miller-admin.market-auction.decrease-quantity-in-cart', [$cooperative->id, $lot->lot_number]) }}" class="btn btn-outline-danger"><i class="mdi mdi-minus"></i></a>
                                <form class="in_cart_quantity_form" action="{{ route('miller-admin.market-auction.set-quantity-in-cart', [$cooperative->id, $lot->lot_number]) }}" method="POST">
                                    @csrf
                                    {{ method_field('PUT') }}
                                    <input type="number" class="mx-2 form-control form-control-sm in_cart_quantity" name="in_cart_quantity" style="width: 100px;" value="{{$lot->qty}}" onblur="this.form.submit()">
                                </form>
                                <a href="{{ route('miller-admin.market-auction.increase-quantity-in-cart', [$cooperative->id, $lot->lot_number]) }}" class="btn btn-outline-primary"><i class="mdi mdi-plus"></i></a>
                            </div>
                        </td>
                        <td class="text-right">
                            @if ($lot->qty > 0)
                            <form action="{{route('miller-admin.market-auction.remove-from-cart', [$cooperative->id, $lot->lot_number])}}" method="post">
                                @csrf
                                {{ method_field('DELETE') }}
                                <button onclick="return confirm('sure to remove?')" class="btn btn-danger">Remove from Cart</button>
                            </form>
                            @endif
                        </td>
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
<script>
</script>
@endpush