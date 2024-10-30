@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="card shadow-lg">
    <div class="card-body">
        <div class="row">
            <!-- Left Section: Product Name and Category -->
            <div class="col-12 col-md-4">
                <div class="card border-0 rounded-4 overflow-hidden" style="background: #F3F4F6;">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <h5 class="card-title">Product Overview</h5>
                        </div>

                        <div class=" product-detail mb-4 d-flex align-items-center justify-content-between bg-white
                                border rounded-3 p-4 shadow-sm transition-shadow">
                            <div class="d-flex align-items-center">
                                <div class="icon-container me-3"
                                    style="width: 40px; height: 40px; border-radius: 50%; background:#4CAF50; display: flex; align-items: center; justify-content: center; margin-right:2px;">
                                    <i class="fas fa-box-open text-white fa-lg" style="font-size: 1em;"></i>
                                </div>
                                <div>
                                    <span class="font-weight-bold text-dark ml-2" style="font-size: 1.2em;">Product
                                        Name:</span>
                                    <span class="font-weight-normal ms-2 ml-1"
                                        style="color: #1B5E20; font-size: 1.2rem;">{{$product->name}}</span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="product-detail mb-4 d-flex align-items-center justify-content-between bg-white border rounded-3 p-4 shadow-sm transition-shadow">
                            <div class="d-flex align-items-center">
                                <div class="icon-container me-3"
                                    style="width: 40px; height: 40px; border-radius: 50%; background: #FF9800; display: flex; align-items: center; justify-content: center; margin-right:2px;">
                                    <i class="fas fa-tags text-white fa-lg" style="font-size: 1em;"></i>
                                </div>
                                <div>
                                    <span class="font-weight-bold text-dark ml-2"
                                        style="font-size: 1.2em;">Category:</span>
                                    <span class="font-weight-normal ms-2 ml-1"
                                        style="color: #1B5E20; font-size: 1.2em;">{{$product->category_name}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <span class="badge rounded-pill text-bg-info" style="font-size: 1.2em;">Ready to Set
                                Prices</span>
                        </div>

                        <div class="text-center mt-5">
                            <p class="text-muted" style="font-size: 1.1em;">Please ensure the details above are correct
                                before adding pricing information.</p>
                        </div>
                    </div>
                </div>
            </div>





            <!-- Right Section: Button and Form -->
            <div class="col-12 col-md-8 d-flex flex-column">
                <div class="d-flex justify-content-end mb-3">
                    <button type="button" class="btn btn-success btn-lg" data-toggle="collapse"
                        data-target="#addPricingAccordion"
                        aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                        aria-controls="addPricingAccordion">
                        <i class="fas fa-plus-circle"></i> Add Product Pricing
                    </button>
                </div>

                <!-- Collapsible Pricing Form -->
                <div class="collapse @if($errors->count() > 0) show @endif" id="addPricingAccordion">
                    <div class="card shadow-lg mb-4">
                        <div class="card-body">
                            <form action="{{ route('cooperative-admin.products.store_product_pricing') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="product_id" value="{{$product->id}}">

                                <h4 class="mb-3 text-success">Pricing Details</h4>
                                <div class="row">
                                    <!-- Pricing Unit -->
                                    <div class="form-group col-lg-6">
                                        <label for="unit_id">Pricing Unit</label>
                                        <select name="unit_id" id="unit_id"
                                            class="form-control form-select {{ $errors->has('unit_id') ? ' is-invalid' : '' }}"
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
                                        <label for="min">Min Quantity</label>
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
                                        <label for="max">Max Quantity</label>
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

                                <div class="d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary">Submit Pricing</button>
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