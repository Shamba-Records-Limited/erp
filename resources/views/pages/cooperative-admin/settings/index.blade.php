@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div>
    <div>
        <form action="{{ route('cooperative-admin.settings.set_main_product') }}" method="post">
            @csrf
            <h3>Set Default Product</h3>

            <div class="form-row">
                <div class="form-group col-lg-3 col-md-6 col-12">
                    <label for="main_product_id">Main Product</label>
                    <select name="main_product_id" id="main_product_id" class="form-control select2bs4 {{ $errors->has('main_product_id') ? ' is-invalid' : '' }}" required>
                        <option value="">-- Select Main Product --</option>
                        @foreach($products as $product)
                        <option value="{{$product->id}}" @if($product->id == $coop->main_product_id) selected @endif>{{$product->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('main_product_id'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('main_product_id')  }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="form-row">
<div class="form-group col-lg-3 col-md-6 col-12">
                                <button type="submit" class="btn btn-primary btn-fw btn-block">Save</button>
                            </div>
            </div>

        </form>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush