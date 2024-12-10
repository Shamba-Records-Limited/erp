@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
<div class="container mt-4">
    <!-- Register Product Button -->
    <div class="card">
        <div class="card-body">
               <div class="card-title">Sales</div>
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-primary" id="register-product-btn">Add Sale</button>
                </div>
        </div>
    </div>
    <!-- Register Product Form -->
    <div id="register-product-form" class="card p-4 mb-4 position-relative" style="display: none;">
        <!-- X Button to Close the Form -->
        <button type="button" class="close-btn btn btn-danger btn-sm position-absolute" style="top: 10px; right: 10px;" id="close-register-form">
            &times;
        </button>

        <form action="{{ route('cooperative-admin.marketplace-products.add_product') }}" method="POST" enctype="multipart/form-data">
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

    <!-- SALES LIST -->
    <div class="row">
       <div>List Sales</div>
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
});
        
</script>
@endpush
