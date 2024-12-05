@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')

<div class="container mt-4">
    <!-- Register Product Button -->
    <div class="row">
            <div class="d-flex justify-content-start mb-3">
                <h3>Marketplace Products</h3>
            </div>
            <div class="col-2 dropdown">
            <a href="#" class="btn btn-outline btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown" id="cartDropdown">
                    <i class="mdi mdi-cart"></i>
                    <span class="badge badge-light">{{$items_in_cart_count > 9 ? '9+' : $items_in_cart_count }}</span>
                </a>
                @if ($items_in_cart_count == 0)
                    <a class="dropdown-item" disabled>Please add items to cart first</a>
                        @else
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="cartDropdown">
                        <form action="{{route('farmer.marketplace.clear-cart')}}" method="POST">
                            @csrf
                            {{ method_field('DELETE') }}
                            <button onclick="return confirm('Sure to clear cart?')" class="dropdown-item"> Clear Cart</button>
                        </form>  
                        <a class="dropdown-item" href="#"> Checkout</a> 
                </div>
                @endif
            </div>
    </div>
    <!-- Register Product Form -->
    <div id="register-product-form" class="card p-4 mb-4 position-relative" style="display: none;">
        <!-- X Button to Close the Form -->
        <button type="button" class="close-btn btn btn-danger btn-sm position-absolute" style="top: 10px; right: 10px;" id="close-register-form">
            &times;
        </button>
    </div>

    <!-- Product Cards -->
    <div class="row">
        @foreach($paginatedProducts as $product)
        <div class="col-md-4 col-lg-3 mb-4 d-flex">
            <div class="card product-card flex-fill" style="height: 100%;">
                <!-- Lazy loading implemented here -->
                <img src="{{ asset('storage/' . ($product['image'] ?? 'default-image.jpg')) }}" class="card-img-top" alt="{{ $product->name ?? 'Product' }}" loading="lazy" style="object-fit: cover; height: 200px;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title">{{ $product['name'] }}</h5>
                    <p class="card-text">KES {{ number_format($product['sale_price'], 2) }}</p>
                    <!-- <button class="btn btn-success add-to-cart-btn mt-auto" style="background-color: #28a745; border-color: #28a745;">Add to Cart</button>-->
                     <button class="btn btn-success" style="background-color: #28a745; border-color: #28a745;" 
                            onclick="addToCart('{{ $product['miller_id'] }}', '{{ $product['id'] }}','{{  $product['quantity'] }}',this)">Add to Cart </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $paginatedProducts->links() }}
    </div>
</div>

@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // Toggle the visibility of the register product form
    $('#register-product-btn').on('click', function() {
        $('#register-product-form').toggle();
    });

    // Close the form when the "X" button is clicked
    $('#close-register-form').on('click', function() {
        $('#register-product-form').hide();
    });

    // When any "Add to Cart" button is clicked
    $('.add-to-cart-btn').on('click', function() {
        // Change the button text to "✔ Added"
        $(this).text('✔ Added');
        // Change the button color to a different shade of green
        $(this).css('background-color', '#218838');
        $(this).css('border-color', '#218838');
        // Disable the button to prevent further clicks
        $(this).prop('disabled', true);
    });
});
function addToCart(millerId, productId, available_qnty, button) {
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

              // Update the button styles and disable it
                button.textContent = "✔ Added";
                button.style.backgroundColor = "#218838";
                button.style.borderColor = "#218838";
                button.disabled = true;

            // Redirect to the add-to-cart route with the quantity as a query parameter
            const url = `/farmer/marketplace/${millerId}/add_to_cart/${productId}?quantity=${encodeURIComponent(quantity)}`;
            window.location.href = url;
        } 
</script>
@endpush
