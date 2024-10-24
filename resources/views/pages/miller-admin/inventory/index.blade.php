@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('topItem')
<!-- view delivery -->
@if($is_adding_inventory == 1)
<div style="position: absolute; z-index: 1050; background-color: #2222; width: 100vw; min-height: 100vh;">
    <div class="container-fluid h-100 w-100">
        <div class="row h-100">
            <div class="col"></div>
            <div class="col-6 card h-100">
                <div class="card-header">
                    <div class="card-title position-relative">
                        <a class="position-absolute top-5 left-5 btn btn-outline-dark" href="?">
                            <i class="mdi mdi-close"></i>
                        </a>
                        <h4 class="text-center">Add Inventory</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-warning m-2 border border-warning p-2 rounded">You are working on a draft inventory. Publish it to apply changes</div>
                    <div>
                        <form action="{{route('miller-admin.inventory.save', $draftInventory->id)}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="inventory_number">Inventory Number</label>
                                <input type="text" class="form-control {{ $errors->has('inventory_number') ? ' is-invalid' : '' }}" id="inventory_number" placeholder="Inventory Number" name="inventory_number" value="{{$inventoryNumber}}" @if(!is_null($draftInventory)) readonly @endif>

                                @if ($errors->inventory_number)
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('inventory_number') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="order_id">Order</label>
                                <select name="order_id" id="order_id" class="form-control form-select {{ $errors->has('order_id') ? ' is-invalid' : '' }}" onchange="submitForm()">
                                    <option value="">-- Select Order Item --</option>
                                    @foreach($selectableOrderItems as $item)
                                    <option value="{{$item->id}}" @if(!is_null($draftInventory) && $draftInventory->order_id == $item->id) selected @endif> {{ $item->batch_number }}</option>
                                    @endforeach

                                    @if ($errors->has('order_id'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('order_id')  }}</strong>
                                    </span>
                                    @endif
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Inventory</button>
                        </form>
                        <div class="mt-3">

                            <button id="addInventoryButton" class="btn border p-3 w-100" data-toggle="collapse" data-target="#addInventoryItemForm" aria-expanded="@if ($errors->count() > 0) true @else false @endif" aria-controls="addInventoryItemForm">Add Inventory Item</button>
                            <form id="addInventoryItemForm" class="border rounded p-2 collapse @if ($errors->count() > 0) show @endif " action="{{route('miller-admin.inventory.add-item')}}" method="POST">
                                @csrf
                                {{$errors}}
                                <div class="row">
                                    <input type="hidden" name="inventory_id" value="{{$draftInventory->id}}">
                                    <div class="form-group col-lg-4 col-md-4 col-12">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" placeholder="Enter name" value="{{ old('name') }}" required>

                                        @if ($errors->has('name'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('name')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-12">
                                        <label for="quantity">Quantity</label>
                                        <input type="number" name="quantity" class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}" id="quantity" placeholder="Enter quantity" value="{{ old('quantity') }}" required>

                                        @if ($errors->has('batch_number'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('batch_number')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-12">
                                        <label for="unit">Select Unit</label>
                                        <select name="unit" id="unit" class="form-control form-select {{ $errors->has('unit') ? ' is-invalid' : '' }}" readonly>
                                            <option value="">-- Select Unit --</option>
                                            @foreach(config('enums.units') as $key => $unit)
                                            <option value="{{$key}}"> {{ $key }}</option>
                                            @endforeach

                                            @if ($errors->has('unit'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('unit')  }}</strong>
                                            </span>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-12">
                                        <label for="product_grade_id">Select Grade</label>
                                        <select name="product_grade_id" id="product_grade_id" class="form-control form-select {{ $errors->has('product_grade_id') ? ' is-invalid' : '' }}" readonly>
                                            <option value="">-- Select Grade --</option>
                                            @foreach($grades as $grade)
                                            <option value="{{$grade->id}}"> {{ $grade->name }}</option>
                                            @endforeach

                                            @if ($errors->has('product_grade_id'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('product_grade_id')  }}</strong>
                                            </span>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <button class="btn btn-primary">Save Inventory Item</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="font-weight-bold">Inventory Items</div>

                        <div class="table-responsive p-2">
                            <table class="table table-hover dt clickable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Quantity</th>
                                        <th>Grade</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($draftInventoryItems as $key => $inventory_item)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$inventory_item->name}}</td>
                                        <td>{{$inventory_item->quantity}} {{$inventory_item->unit}}</td>
                                        <td>{{$inventory_item->product_grade}}</td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 d-flex">
                            <a class="btn btn-secondary ml-2" href="?">Discard Draft</a>
                            <a class="btn btn-success ml-2" href="{{route('miller-admin.inventory.publish', $draftInventory->inventory_number)}}">Publish</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- /view delivery -->
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Inventory</div>
        <div class="d-flex justify-content-end">
            <a href="?is_adding_inventory=1" class="btn btn-primary">Add Inventory</a>
        </div>
        <div class="table-responsive p-2">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Inventory Number</th>
                        <th>Batch Number</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventories as $key => $inventory)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$inventory->inventory_number}}</td>
                        <td>{{$inventory->order ? $inventory->order->batch_number : 'N/A'}}</td>
                        <td>
                            @if (!is_null($inventory->published_at))
                            <div>Published</div>
                            @else
                            <div>Pending</div>
                            @endif
                        </td>
                        <td>
                            @if (is_null($inventory->published_at))
                            <a title="publish" href="?is_adding_inventory=1" class="btn btn-outline-primary mr-1"><i class="mdi mdi-check-all"></i></a>
                            <button class="btn btn-outline-danger">
                                <i class="mdi mdi-delete"></i>
                            </button>
                            @endif
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