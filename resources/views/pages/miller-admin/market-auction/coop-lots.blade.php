@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="card pt-6">
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
                    <a class="dropdown-item" disabled>Please add items to cart first</a>
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
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary">Add All Lots</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lot No</th>
                        <th>Quantity</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalQuantity = 0; @endphp
                    @foreach($lots as $key => $lot)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $lot->lot_number }}</td>
                        <td>
                            {{ $lot->available_quantity }} KG
                            @php $totalQuantity += $lot->available_quantity; @endphp
                        </td>
                        
                        <td class="text-right">
                            @if ($lot->qty <= 0)
                            <button class="btn btn-outline-primary" onclick="addToCart('{{ $cooperative->id }}', '{{ $lot->lot_number }}','{{ $lot->available_quantity }}')">
                               Add to Cart
                            </button>
                            @else
                            <form action="{{ route('miller-admin.market-auction.remove-from-cart', [$cooperative->id, $lot->lot_number]) }}" method="post">
                                @csrf
                                {{ method_field('DELETE') }}
                                <button onclick="return confirm('sure to remove?')" class="btn btn-outline-danger">Remove from Cart</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-right font-weight-bold">Total Quantity:</td>
                        <td class="font-weight-bold">{{ $totalQuantity }} KG</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
        function addToCart(cooperativeId, lotNumber, available_qnty) {
            // Prompt the user for the quantity
            const quantity = prompt("Enter the quantity to add to cart:");
            // Check if the user entered a valid quantity
            if (quantity === null || quantity.trim() === "") {
                alert("Quantity is required.");
                return;
            }
            if (parseInt(quantity) > parseInt(available_qnty)) {
                    alert("Quantity exceeds available stock.");
                    return;
            }
            if (isNaN(quantity) || parseInt(quantity) <= 0) {
                alert("Please enter a valid positive number.");
                return;
            }
            // Redirect to the add-to-cart route with the quantity as a query parameter
            const url = `/miller-admin/market-auction/${cooperativeId}/add_to_cart/${lotNumber}?quantity=${encodeURIComponent(quantity)}`;
            window.location.href = url;
        }
</script>
@endpush
