@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')

<div class="container mt-4">
    <div class="row">
        @foreach($paginatedProducts as $product)
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card product-card">
                <!-- Lazy loading implemented here -->
                <img src="{{ $product['image'] }}" class="card-img-top" alt="{{ $product['name'] }}" loading="lazy">
                <div class="card-body">
                    <h5 class="card-title">{{ $product['name'] }}</h5>
                    <p class="card-text">KES {{ number_format($product['price'], 2) }}</p>
                    <button class="btn btn-primary add-to-cart-btn">Add to Cart</button>
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
    // When any "Add to Cart" button is clicked
    $('.add-to-cart-btn').on('click', function() {
        // Change the button text to "✔ Added"
        $(this).text('✔ Added');
        // Change the button class from 'btn-primary' to 'btn-success' to make it green
        $(this).removeClass('btn-primary').addClass('btn-success');
        // Disable the button to prevent further clicks
        $(this).prop('disabled', true);
    });
});
</script>
@endpush
