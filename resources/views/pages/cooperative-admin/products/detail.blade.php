@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Product Details</div>
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="border rounded m-2 p-2">
                    <span>Name:</span>
                    <span class="font-weight-bold">{{$product->name}}</span>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="border rounded m-2 p-2">
                    <span>Category:</span>
                    <span class="font-weight-bold">{{$product->category_name}}</span>
                </div>
            </div>
        </div>
        <h3>
            Pricing
        </h3>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse" data-target="#addPricingAccordion" aria-expanded="@if ($errors->count() > 0) true @else false @endif" aria-controls="addComapnyAccordion"><span class="mdi mdi-plus"></span>Add Product Pricing
                        </button>
                        <div class="collapse @if($errors->count() > 0) show @endif" id="addPricingAccordion">
                            <form action="{{ route('cooperative-admin.products.store_product_pricing') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="product_id" value="{{$product->id}}">
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <h6 class="mb-3">Pricing Details</h6>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="unit_id">Pricing Unit</label>
                                        <select name="unit_id" id="unit_id" class="form-control select2bs4 {{ $errors->has('unit_id') ? ' is-invalid' : '' }}" required>
                                            <option value="">-- Select Unit --</option>
                                            @foreach($units as $unit)
                                            <option value="{{$unit->id}}" @if($unit->id == old('unit_id')) selected @endif>{{$unit->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('unit_id'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('unit_id')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="min">Min</label>
                                        <input type="text" name="min" class="form-control {{ $errors->has('min') ? ' is-invalid' : '' }}" id="min" placeholder="Min Quantity" value="{{ old('min')}}" required>

                                        @if ($errors->has('min'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('min')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="max">Max</label>
                                        <input type="text" name="max" class="form-control {{ $errors->has('max') ? ' is-invalid' : '' }}" id="max" placeholder="Max Quantity" value="{{ old('max')}}">

                                        @if ($errors->has('max'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('max')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="buying_price">Buying Price</label>
                                        <input type="number" name="buying_price" class="form-control {{ $errors->has('buying_price') ? ' is-invalid' : '' }}" id="buying_price" placeholder="Buying Price" value="{{ old('buying_price')}}" required>

                                        @if ($errors->has('buying_price'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('buying_price')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="selling_price">Selling Price</label>
                                        <input type="number" name="selling_price" class="form-control {{ $errors->has('selling_price') ? ' is-invalid' : '' }}" id="selling_price" placeholder="Selling Price" value="{{ old('selling_price')}}" required>

                                        @if ($errors->has('selling_price'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('selling_price')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<div class="table-responsive">
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
                                <td> {{$pricing->min}} {{$pricing->unit_abbr}} - {{$pricing->max}} {{$pricing->unit_abbr}}</td>
                                @endif
                                <td>{{$pricing->buying_price }}</td>
                                <td>{{$pricing->buying_vat }}</td>
                                <td>{{$pricing->selling_price }}</td>
                                <td>{{$pricing->selling_vat }}</td>
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
@endpush