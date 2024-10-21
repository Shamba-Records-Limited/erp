@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="card shadow-lg">
    <div class="card-body">
        <div class="row">
            <!-- Left Section: Product Name and Category with reduced width and updated background and text styling -->
            <div class="col-12 col-md-3">
                <h5 class="card-title text-primary mb-4">Product Details</h5>
                <div class="mb-3">
                    <div class="border rounded p-3" style="background-color: #F3F1F1;">
                        <span class="font-weight-bold text-dark">Name:</span>
                        <span class="font-weight-normal ml-2" style="color:#172B4D">{{$product->name}}</span>
                    </div>
                </div>
                <div class=" mb-3">
                    <div class="border rounded p-3" style="background-color: #F3F1F1;">
                        <span class="font-weight-bold text-dark">Category:</span>
                        <span class="font-weight-normal ml-2" style="color:#172B4D">{{$product->category_name}}</span>
                    </div>
                </div>
            </div>

            <!-- Right Section: Button and Form -->
            <div class="col-12 col-md-9 d-flex flex-column">
                <!-- Button container with flexbox alignment to right -->
                <div class="d-flex justify-content-end mb-3">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="collapse"
                        data-target="#addPricingAccordion"
                        aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                        aria-controls="addPricingAccordion">
                        <i class="mdi mdi-plus"></i> Add Product Pricing
                    </button>
                </div>

                <!-- Collapsible Pricing Form appears to the right -->
                <div class="collapse @if($errors->count() > 0) show @endif" id="addPricingAccordion">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <form action="{{ route('cooperative-admin.products.store_product_pricing') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="product_id" value="{{$product->id}}">

                                <h6 class="mb-3">Pricing Details</h6>
                                <div class="row">
                                    <!-- Pricing Unit -->
                                    <div class="form-group col-lg-6">
                                        <label for="unit_id">Pricing Unit</label>
                                        <select name="unit_id" id="unit_id"
                                            class="form-control select2bs4 {{ $errors->has('unit_id') ? ' is-invalid' : '' }}"
                                            required>
                                            <option value="">-- Select Unit --</option>
                                            @foreach($units as $unit)
                                            <option value="{{$unit->id}}" @if($unit->id == old('unit_id')) selected
                                                @endif>{{$unit->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('unit_id'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('unit_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <!-- Min Quantity -->
                                    <div class="form-group col-lg-6">
                                        <label for="min">Min</label>
                                        <input type="number" name="min"
                                            class="form-control {{ $errors->has('min') ? ' is-invalid' : '' }}" id="min"
                                            placeholder="Min Quantity" value="{{ old('min')}}" required>
                                        @if ($errors->has('min'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('min') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <!-- Max Quantity -->
                                    <div class="form-group col-lg-6">
                                        <label for="max">Max</label>
                                        <input type="number" name="max"
                                            class="form-control {{ $errors->has('max') ? ' is-invalid' : '' }}" id="max"
                                            placeholder="Max Quantity" value="{{ old('max')}}">
                                        @if ($errors->has('max'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('max') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <!-- Buying Price -->
                                    <div class="form-group col-lg-6">
                                        <label for="buying_price">Buying Price</label>
                                        <input type="number" name="buying_price"
                                            class="form-control {{ $errors->has('buying_price') ? ' is-invalid' : '' }}"
                                            id="buying_price" placeholder="Buying Price"
                                            value="{{ old('buying_price')}}" required>
                                        @if ($errors->has('buying_price'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('buying_price') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <!-- Selling Price -->
                                    <div class="form-group col-lg-6">
                                        <label for="selling_price">Selling Price</label>
                                        <input type="number" name="selling_price"
                                            class="form-control {{ $errors->has('selling_price') ? ' is-invalid' : '' }}"
                                            id="selling_price" placeholder="Selling Price"
                                            value="{{ old('selling_price')}}" required>
                                        @if ($errors->has('selling_price'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('selling_price') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Button at the bottom right of the form -->
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success">Add Pricing</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing Table (unchanged) -->
        <div class="table-responsive mt-4">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Qty Range</th>
                        <th>Buying Price</th>
                        <th>Buying VAT</th>
                        <th>Selling Price</th>
                        <th>Selling VAT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pricings as $key => $pricing)
                    <tr>
                        <td>{{++$key }}</td>
                        @if(empty($pricing->max))
                        <td>larger than {{$pricing->min}} {{$pricing->unit_abbr}}</td>
                        @else
                        <td>{{$pricing->min}} {{$pricing->unit_abbr}} - {{$pricing->max}} {{$pricing->unit_abbr}}
                        </td>
                        @endif
                        <td>{{$pricing->buying_price }}</td>
                        <td>{{$pricing->buying_vat }}</td>
                        <td>{{$pricing->selling_price }}</td>
                        <td>{{$pricing->selling_vat }}</td>
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
@endpush