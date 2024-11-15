@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('topItem')
@php
    $units = config('enums.units');
@endphp
@if($isCreatingFinalProduct == "1")
<div class="overlay">
    <div class="modal-container">
        <div class="modal-card">
            <div class="modal-header">
                <h4 class="text-center">Create Final Product</h4>
                <a class="close-btn" href="?is_creating_final_product=0">
                    <i class="mdi mdi-close"></i>
                </a>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">You are working on a draft final product. Publish it to apply changes.</div>

                <div class="step-navigation mb-3">
                    <a href="?is_creating_final_product=1&cur_step=1" class="btn {{ $curStep == '1' ? 'btn-primary' : 'btn-outline-primary' }}">1. Final Product Details</a>
                    <a href="?is_creating_final_product=1&cur_step=2" class="btn {{ $curStep == '2' ? 'btn-primary' : 'btn-outline-primary' }}">2. Raw Materials</a>
                </div>

                @if($curStep == '1')
                <form action="{{ route('miller-admin.final-product.save-details') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $draftProduct->id }}" />

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" list="productNames" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ $draftProduct->name }}" required />
                        <datalist id="productNames">
                            @foreach($uniqueProductNames as $productName)
                                <option value="{{ $productName->name }}"></option>
                            @endforeach
                        </datalist>
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ $draftProduct->quantity }}" required />
                                @if($errors->has('quantity'))
                                    <span class="text-danger">{{ $errors->first('quantity') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="unit">Unit</label>
                                <select name="unit" id="unit" class="form-control form-select @error('unit') is-invalid @enderror" value="{{ $draftProduct->unit }}">
                                    @foreach($units as $unit => $unit_name)
                                        <option value="{{ $unit }}" @if($unit == $draftProduct->unit) selected @endif>{{ $unit }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('unit'))
                                    <span class="text-danger">{{ $errors->first('unit') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="selling_price">Selling Price</label>
                        <input type="number" name="selling_price" id="selling_price" class="form-control @error('selling_price') is-invalid @enderror" value="{{ $draftProduct->selling_price }}" required />
                        @if($errors->has('selling_price'))
                            <span class="text-danger">{{ $errors->first('selling_price') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="count">Count</label>
                        <input type="number" name="count" id="count" class="form-control @error('count') is-invalid @enderror" value="{{ $draftProduct->count }}" required />
                        @if($errors->has('count'))
                            <span class="text-danger">{{ $errors->first('count') }}</span>
                        @endif
                    </div>

                    <div class="form-row">
                        <div class="col">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_wholesale" name="is_wholesale" @if($draftProduct->is_wholesale) checked @endif>
                                <label class="form-check-label" for="is_wholesale">Is Wholesale?</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_retail" name="is_retail" @if($draftProduct->is_retail) checked @endif>
                                <label class="form-check-label" for="is_retail">Is Retail?</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-outline-primary mt-3">Next: Raw Materials</button>
                </form>

                @elseif($curStep == '2')
                <div class="font-weight-bold">Raw Materials</div>
                <button type="button" class="btn btn-outline-dark mb-3" data-toggle="collapse" data-target="#addRawMaterial" aria-controls="addRawMaterial">
                    Add Raw Material
                </button>

                <div id="addRawMaterial" class="collapse border rounded p-3">
                    <form action="{{ route('miller-admin.final-product.save-raw-material') }}" method="post">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $draftProduct->id }}" />

                        <div class="form-group">
                            <label for="milled_inventory_id">Select Inventory</label>
                            <select name="milled_inventory_id" id="milled_inventory_id" class="form-control form-select @error('milled_inventory_id') is-invalid @enderror">
                                <option value="">-- Select Inventory --</option>
                                @foreach($milledInventories as $milledInventory)
                                    <option value="{{ $milledInventory->id }}">{{ $milledInventory->inventory_number }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('milled_inventory_id'))
                                <span class="text-danger">{{ $errors->first('milled_inventory_id') }}</span>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" required />
                                    @if($errors->has('quantity'))
                                        <span class="text-danger">{{ $errors->first('quantity') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="unit">Unit</label>
                                    <select name="unit" id="unit" class="form-control @error('unit') is-invalid @enderror">
                                        @foreach($units as $unit => $unit_name)
                                            <option value="{{ $unit }}" @if($unit == old('unit')) selected @endif>{{ $unit }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('unit'))
                                        <span class="text-danger">{{ $errors->first('unit') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary">Add Raw Material</button>
                    </form>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-hover dt">
                        <thead>
                            <tr>
                                <th>Inventory</th>
                                <th>No</th>
                                <th>Qty</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rawMaterials as $rawMaterial)
                                <tr>
                                    <td>{{ $rawMaterial->milled_inventory->inventory_number }}</td>
                                    <td>{{ $rawMaterial->quantity }} {{ $rawMaterial->unit }}</td>
                                    <td>
                                        <form action="{{ route('miller-admin.final-product.delete-raw-material', $rawMaterial->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-2 d-flex justify-content-between mt-4">
                    <a class="btn btn-outline-primary" href="?is_creating_final_product=1&cur_step=1">Prev: Update Details</a>
                    <div class="d-flex">
                        <a class="btn btn-outline-dark" href="?">Exit</a>
                        <a href="{{ route('miller-admin.final-products.publish') }}" class="btn btn-primary ml-2">Publish</a>
                        <form action="{{ route('miller-admin.final-product.discard-draft') }}" method="post" class="ml-2">
                            @csrf
                            @method("DELETE")
                            <button class="btn btn-secondary" type="submit">Discard Draft</button>
                        </form>
                    </div>
                </div>
                @endif
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
            <div>
                <a href="?is_creating_final_product=1" class="btn btn-primary">Create Final Product</a>
                <a class="btn btn-primary" href="{{route('miller-admin.final-products.export', 'xlsx')}}"><span class="mdi mdi-file-excel"></span>Export Excel
                </a>
                <a class="btn btn-primary" href="{{route('miller-admin.final-products.export', 'pdf')}}"><span class="mdi mdi-file-pdf"></span>Export Pdf
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive p-2">
        <table class="table table-hover dt clickable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Number</th>
                    <th>Product</th>
                    <th>Pricing</th>
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
                    <td>KES {{$product->selling_price}} / KG</td>
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

<style>
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1050;
    }

    .modal-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .modal-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        width: 90%;
        max-width: 600px;
        padding: 20px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
    }

    .modal-body {
        margin-top: 10px;
    }

    .alert {
        margin-bottom: 15px;
    }

    .info-box, .aggregate-info {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
    }

    .btn-toggle {
        background: transparent;
        border: none;
        cursor: pointer;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table-striped tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .container {
        margin-top: 30px;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #007bff;
        color: white;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .order-details {
        background-color: #f8f9fa;
    }

    .delivery-status {
        background-color: #ffffff;
    }

    .info-box {
        font-size: 1.2rem;
        margin-bottom: 20px;
    }

    .batch-number {
        font-weight: bold;
        color: #007bff;
    }

    .aggregate-info {
        margin-top: 20px;
    }

    .list-group-item {
        background-color: #f8f9fa;
        border: none;
    }

    .list-group-item:hover {
        background-color: #e9ecef;
    }

    .chart-container {
        position: relative;
        width: 100%;
        height: 300px;
    }
</style>