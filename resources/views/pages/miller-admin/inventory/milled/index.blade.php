@extends('layout.master')

@push('plugin-styles')
@endpush

@section('topItem')
@php
$units = config('enums.units')
@endphp
@if($isGrading == "1")
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
                        <h4 class="text-center">Grade Milled Coffee</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-warning m-2 border border-warning p-2 rounded">You are working on a draft grading. Publish it to apply changes</div>
                    <div class="table-responsive p-2">
                        <table>
                            <thead>
                                <tr>
                                    <th>Grade</th>
                                    <th>Quantity</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gradings as $grading)
                                <tr>
                                    <td>{{$grading->grade}}</td>
                                    <td>{{$grading->quantity}}</td>
                                    <td></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
        <div class="card-title">Milled Inventory</div>

        <div class="table-responsive p-2">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Batch Number</th>
                        <th>Lot Number</th>
                        <th>Quantity</th>
                        <th>Milled Quantity</th>
                        <th>Waste Quantity</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($milledInventories as $key => $inventory)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$inventory->batch_number}}</td>
                        <td>{{$inventory->l_num}}</td>
                        <td>{{$inventory->milled_quantity + $inventory->waste_quantity}} KG</td>
                        <td>{{$inventory->milled_quantity}} KG</td>
                        <td>{{$inventory->waste_quantity}} KG</td>
                        <td>
                            <div class="btn-group dropdown">
                                <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="text-primary dropdown-item" href="{{route('miller-admin.milled-inventory.detail', $inventory->id )}}">
                                        <i class="fa fa-edit"></i>View Details
                                    </a>
                                    <a class="text-warning dropdown-item" href="?is_grading=1&milled_inventory_id={{$inventory->id}}">
                                        <i class="fa fa-edit"></i>Grade The Coffee
                                    </a>
                                </div>
                            </div>
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