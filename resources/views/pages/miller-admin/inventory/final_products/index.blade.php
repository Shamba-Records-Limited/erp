@extends('layout.master')

@push('plugin-styles')
@endpush
@section('topItem')
@php
$units = config('enums.units')
@endphp
@if($isCreatingFinalProduct == "1")
<div style="position: absolute; z-index: 1050; background-color: #2222; width: 100vw; min-height: 100vh;">
    <div class="container-fluid h-100 w-100">
        <div class="row h-100">
            <div class="col"></div>
            <div class="col-6 card h-100">
                <div class="card-header">
                    <div class="card-title position-relative">
                        <a class="position-absolute top-5 left-5 btn btn-outline-dark" href="?is_creating_final_product=0">
                            <i class="mdi mdi-close"></i>
                        </a>
                        <h4 class="text-center">Create Final Product</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-warning m-2 border border-warning p-2 rounded">You are working on a draft final product. Publish it to apply changes</div>
                    <div>
                        <a href="?is_creating_final_product=1&cur_step=1" class="btn {{$curStep == '1' ? 'btn-primary' : 'btn-outline-primary'}}">
                            1. Final Product Details
                        </a>
                        <a href="?is_creating_final_product=1&cur_step=2" class="btn {{$curStep == '2' ? 'btn-primary' : 'btn-outline-primary'}}">
                            2. Raw Materials
                        </a>
                    </div>
                    <div>
                        @if($curStep == '1')
                        <form action="{{route('miller-admin.final-product.save-details')}}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{$draftProduct->id}}" />
                            @if($errors->has('product_id'))
                            <div class="text-danger">{{$errors->first('product_id')}}</div>
                            @endif

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" list="productNames" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{$draftProduct->name}}" required />
                                <datalist id="productNames">
                                    @foreach($uniqueProductNames as $productName)
                                    <option value="{{$productName->name}}">
                                        @endforeach
                                </datalist>
                                @if($errors->has('name'))
                                <div class="text-danger">{{$errors->first('name')}}</div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="quantity">Quantity</label>
                                        <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{$draftProduct->quantity}}" required />

                                        @if($errors->has('quantity'))
                                        <div class="text-danger">{{$errors->first('quantity')}}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="unit">Unit</label>
                                        <select name="unit" id="unit" class=" form-control select2bs4 {{ $errors->has('unit') ? ' is-invalid' : '' }}" value="{{$draftProduct->unit}}">
                                            @foreach($units as $unit => $unit_name)
                                            <option value="{{$unit}}" @if($unit==$draftProduct->unit) selected @endif> {{ $unit }}</option>
                                            @endforeach
                                        </select>

                                        @if($errors->has('unit'))
                                        <div class="text-danger">{{$errors->first('unit')}}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="selling_price">Selling Price</label>
                                <input type="number" name="selling_price" id="selling_price" class="form-control @error('selling_price') is-invalid @enderror" value="{{$draftProduct->selling_price}}" required />

                                @if($errors->has('selling_price'))
                                <div class="text-danger">{{$errors->first('selling_price')}}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="count">Count</label>
                                <input type="number" name="count" id="count" class="form-control @error('count') is-invalid @enderror" value="{{$draftProduct->count}}" required />

                                @if($errors->has('count'))
                                <div class="text-danger">{{$errors->first('count')}}</div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col"></div>
                                <div class="col">
                                    <div class="form-group ml-10">
                                        <input type="checkbox" class="form-check-input" id="is_wholesale" name="is_wholesale" @if($draftProduct->is_wholesale) checked @endif>
                                        <label class="form-check-label" for="is_wholesale">Is Wholesale?</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <input type="checkbox" class="form-check-input" id="is_retail" name="is_retail" @if($draftProduct->is_retail) checked @endif>
                                        <label class="form-check-label" for="is_retail">Is Retail?</label>
                                    </div>
                                </div>
                            </div>



                            <button type="submit" class="btn btn-outline-primary">Next: Raw Materials</button>
                        </form>
                        @elseif($curStep == '2')
                        <div>
                            <div class="font-weight-bold">Raw Materials</div>
                            <button type="button" class="btn btn-outline-dark" data-toggle="collapse" data-target="#addRawMaterial" aria-controls="addRawMaterial">
                                Add Raw Material
                            </button>
                            <div id="addRawMaterial" class="collapse border rounded m-2 p-2">
                                <form action="{{route('miller-admin.final-product.save-raw-material')}}" method="post">
                                    {{$errors}}
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{$draftProduct->id}}" />
                                    @if($errors->has('product_id'))
                                    <div class="text-danger">{{$errors->first('product_id')}}</div>
                                    @endif

                                    <div class="form-group">
                                        <label for="milled_inventory_id">Select Inventory</label>
                                        <select name="milled_inventory_id" id="milled_inventory_id" class="form-control select2bs4 {{ $errors->has('milled_inventory_id') ? ' is-invalid' : '' }}" value="{{old('milled_inventory_id', '')}}">
                                        <option value="">-- Select Inventory --</option>    
                                            @foreach($milledInventories as $milledInventory)
                                            <option value="{{$milledInventory->id}}">{{$milledInventory->inventory_number}}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('milled_inventory_id'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('milled_inventory_id')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="quantity">Quantity</label>
                                                <input type="text" name="quantity" id="quantity" class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}" value="{{old('quantity', '')}}">

                                                @if ($errors->has('quantity'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('quantity')  }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="unit">Unit</label>
                                                <select name="unit" id="unit" class=" form-control {{ $errors->has('unit') ? ' is-invalid' : '' }}" value="{{old('unit', '')}}">
                                                    @foreach($units as $unit => $unit_name)
                                                    <option value="{{$unit}}" @if($unit==old('unit', '' )) selected @endif> {{ $unit }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('unit'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('unit')  }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <button class="btn btn-primary">Add Raw Material</button>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive p-2">
                                <table class="table table-hover dt">
                                    <thead>
                                        <tr>
                                            <th>Inventory</th>
                                            <th>No</th>
                                            <th>Qty</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rawMaterials as $rawMaterial)
                                        <tr>
                                            <td>{{$rawMaterial->milled_inventory->inventory_number}}</td>
                                            <td>{{$rawMaterial->quantity}} {{$rawMaterial->unit}}</td>
                                            <td>
                                                <form action="{{route('miller-admin.final-product.delete-raw-material', $rawMaterial->id)}}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger text-white"><i class="mdi mdi-delete"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-2 d-flex justify-content-between">
                                <a class="btn btn-outline-primary" href="?is_creating_final_product=1&cur_step=1">Prev: Update Details</a>
                                <div class="d-flex">
                                    <a class="btn btn-outline-dark" href="?">Exit</a>
                                    <a href="{{route('miller-admin.final-products.publish')}}" class="btn btn-primary">Publish</a>
                                    <form action="{{route('miller-admin.final-product.discard-draft')}}" method="post">
                                        @csrf
                                        @method("DELETE")
                                        <button class="btn btn-secondary" type="submit">Discard Draft</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-end">
            <div class="card-title">Final Products</div>
            <a href="?is_creating_final_product=1" class="btn btn-primary">Create Final Product</a>
        </div>
    </div>
    <div class="table-responsive p-2">
        <table class="table table-hover dt clickable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Number</th>
                    <th>Product</th>
                    <th>Count</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($finalProducts as $key => $product)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$product->product_number}}</td>
                    <td>{{$product->name}} {{$product->quantity}} {{$product->unit}}</td>
                    <td>{{$product->count}}</td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush