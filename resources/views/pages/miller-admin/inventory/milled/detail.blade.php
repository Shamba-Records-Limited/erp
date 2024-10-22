@extends('layout.master')

@push('plugin-styles')
@endpush

@section('topItem')
@if($isAddingGrade == '1')
<!-- add grade_distribution -->
<div style="position: absolute; z-index: 1050; background-color: #2222; width: 100vw; height: 100vh;">
    <div class="container-fluid h-100 w-100">
        <div class="row h-100">
            <div class="col"></div>
            <div class="col-6 card h-100">
                <div class="card-header">
                    <div class="card-title position-relative">
                        <a class="position-absolute top-5 left-5 btn btn-outline-dark" href="?">
                            <i class="mdi mdi-close"></i>
                        </a>
                        <h4 class="text-center">Add Grade</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form id="addDeliveryItemForm" class="border rounded p-2" action="{{route('miller-admin.milled-inventory.store-grade')}}" method="POST">
                        @csrf
                        <input type="hidden" name="milled_inventory_id" value="{{$id}}">
                        <input type="hidden" name="unit" value="{{$lot_unit}}">
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="product_grade_id">Select Grade</label>
                                <select name="product_grade_id" id="product_grade_id" class="form-control select2bs4 {{ $errors->has('product_grade_id') ? ' is-invalid' : '' }}" required>
                                    <option value="">-- Select Grade --</option>
                                    @foreach($grades as $grade)
                                    <option value="{{$grade->id}}" @if(old('product_grade_id')==$grade->id) selected @endif> {{ $grade->name }}</option>
                                    @endforeach

                                    @if ($errors->has('product_grade_id'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('product_grade_id')  }}</strong>
                                    </span>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-12">
                                <label for="quantity">Quantity</label>
                                <div class="input-group">
                                    <input type="number" name="quantity" class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}" id="quantity" placeholder="Enter quantity" value="{{ old('quantity') }}" required>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{$lot_unit}}</span>
                                    </div>
                                </div>

                                @if ($errors->has('quantity'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('quantity')  }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-primary">Save Grade</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /add grade_distribution -->
@endif
@endsection


@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Milled Inventory Details</div>
        <div class="card-subtitle">Inventory Number: <span class="font-weight-bold">{{$milling->inventory_number}}</span></div>
        <div class="row">
            <div class="col-3 border rounded d-flex m-2">
                <div>Milled Quantity:&nbsp;</div>
                <div class="text-primary">{{$milling->milled_quantity}} KG</div>
            </div>
            <div class="col-3 border rounded d-flex m-2">
                <div>Waste Quantity:&nbsp;</div>
                <div class="text-primary">{{$milling->waste_quantity}} KG</div>
            </div>
            <div class="col-3 border rounded d-flex m-2">
                <div>Milled Grading:&nbsp;</div>
                <div class="text-primary">{{$milling->milled_grading_total}} of {{$milling->milled_quantity}} KG</div>
            </div>
        </div>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'milled_grading'?'active':'' }}" href="?tab=milled_grading">Milled Grading</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'pre_milled_grading'?'active':'' }}" href="?tab=pre_milled_grading">Pre-Milled Grading</a>
            </li>
        </ul>

        @if($tab == 'milled_grading' || empty($tab))
        <div class="d-flex justify-content-end">
            <a href="?is_adding_grade=1" class="btn btn-primary">Add Grading</a>
        </div>
        <div class="p-2 table-responsive">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>Grade</th>
                        <th>Quantity</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($milledGradings as $grading)
                    <tr>
                        <td>{{$grading->product_grade->name}}</td>
                        <td>{{$grading->quantity}} {{$grading->unit}}</td>
                        <td>
                            <form action="{{route('miller-admin.milled-inventory.delete-grade', $grading->id)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this grading?')"><i class="mdi mdi-delete-outline"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif($tab == 'pre_milled_grading')
        <div class="p-2 table-responsive">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>Grade</th>
                        <th>Quantity</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($preMilledGradings as $grading)
                    <tr>
                        <td>{{$grading->product_grade->name}}</td>
                        <td>{{$grading->quantity}} {{$grading->unit}}</td>
                        <td>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

@endsection


@push('plugin-scripts')
@endpush


@push('custom-scripts')
@endpush