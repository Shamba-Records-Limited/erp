@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')

<div class="container mt-4">
    <!-- Register Product Button -->
   <!-- <div class="d-flex justify-content-start mb-3">
        <button class="btn btn-primary" id="register-product-btn">Register New Product</button>
    </div>-->

    <!-- Register Product Form -->
    <div id="register-product-form" class="card p-4 mb-4 position-relative" style="display: none;">
        <!-- X Button to Close the Form -->
        <button type="button" class="close-btn btn btn-danger btn-sm position-absolute" style="top: 10px; right: 10px;" id="close-register-form">
            &times;
        </button>

        <form action="{{ route('miller-admin.marketplace-products.add_product') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="product-name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product-name" name="name" placeholder="Nescafe Coffee" required>
            </div>

            <div class="mb-3">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control form-select {{ $errors->has('category_id') ? ' is-invalid' : '' }}" required>
                    <option value="">-- Select Product Category --</option>
                    @foreach($categories as $category)
                    <option value="{{$category->id}}" @if($category->id == old('category_id')) selected @endif>{{$category->name}}</option>
                    @endforeach
                </select>
                @if ($errors->has('category_id'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('category_id')  }}</strong>
                </span>
                @endif
            </div>

            <div class="mb-3">
                <label for="product-image" class="form-label">Product Image (JPEG,JPG,PNG only)</label>
                <input type="file" class="form-control" id="product-image" name="image" accept="image/*" required>
            </div>
            <div class="mb-3">
                <label for="product-quantity" class="form-label">Quantity Available</label>
                <input type="number" class="form-control" id="product-quantity" name="quantity" placeholder="32packets" required>
            </div>
            <div class="mb-3">
                <label for="product-price" class="form-label">Selling Price (KES)</label>
                <input type="number" class="form-control" id="product-price" name="price" placeholder="Kshs. 560" step="1" required>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>

    <!-- Product Cards -->
    <div class="row">
        @foreach($paginatedProducts as $product)
        <div class="col-md-4 col-lg-3 mb-4 d-flex">
            <div class="card product-card flex-fill" style="height: 100%;">
                <!-- Lazy loading implemented here -->
                <!--<img src="{{ $product['image'] }}" class="card-img-top" alt="{{ $product['name'] }}" loading="lazy" style="object-fit: cover; height: 200px;">-->
                <img src="{{ asset('storage/' . ($product['image'] ?? 'default-image.jpg')) }}" class="card-img-top" alt="{{ $product->name ?? 'Product' }}" loading="lazy" style="object-fit: cover; height: 200px;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title">{{ $product['name'] }}</h5>
                    <p class="card-text">Miller: {{$product['miller_name'] }}</p>
                    <p class="card-text">KES {{ number_format($product['sale_price'], 2) }}</p>
                     <!--<button class="btn btn-success add-to-cart-btn mt-auto" style="background-color: #28a745; border-color: #28a745;">Add to Cart</button>-->
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
        
</script>
@endpush
