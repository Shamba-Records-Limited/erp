@extends('layouts.app')

@push('plugin-styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <h3 class="card-title text-center mb-4">Set Default Product</h3>
        <p class="text-muted text-center">Select the main product that will be used by default across the platform.</p>

        <form action="{{ route('cooperative-admin.settings.set_main_product') }}" method="post" class="form">
            @csrf

            <div class="form-row justify-content-center">
                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                    <label for="main_product_id" class="font-weight-bold">Main Product</label>
                    <select name="main_product_id" id="main_product_id"
                        class="form-control  form-select{{ $errors->has('main_product_id') ? ' is-invalid' : '' }}"
                        required>
                        <option value="">-- Select Main Product --</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}" @if($product->id == $coop->main_product_id) selected
                            @endif>{{ $product->name }}</option>
                        @endforeach
                    </select>

                    <!-- Error message for validation -->
                    @if ($errors->has('main_product_id'))
                    <span class="invalid-feedback d-block">
                        <strong>{{ $errors->first('main_product_id') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-row justify-content-center">
                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                    <button type="submit" class="btn btn-primary btn-fw btn-block">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('plugin-scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
@endpush

@push('custom-scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 for better UI
    $('#main_product_id').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
});
</script>
@endpush