@extends('layout.master')

@push('plugin-styles')

@endpush

@section('topItem')
@if($action == 'add_grade_distribution')
<!-- add grade_distribution -->
<div style="position: absolute; z-index: 1050; background-color: #2222; width: 100vw; height: 100vh;">
    <div class="container-fluid h-100 w-100">
        <div class="row h-100">
            <div class="col"></div>
            <div class="col-6 card h-100">
                <div class="card-header">
                    <div class="card-title position-relative">
                        <a class="position-absolute top-5 left-5 btn btn-outline-dark" href="?tab=grade_distributions">
                            <i class="mdi mdi-close"></i>
                        </a>
                        <h4 class="text-center">Add Grade Distribution</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form id="addDeliveryItemForm" class="border rounded p-2" action="{{route('cooperative-admin.lots.store-grade-distribution', $lot->lot_number)}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="product_grade_id">Select Order Item</label>
                                <select name="product_grade_id" id="product_grade_id" class="form-control select2bs4 {{ $errors->has('product_grade_id') ? ' is-invalid' : '' }}" required>
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
                                <button class="btn btn-primary">Save Grade Distribution</button>
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
        <div class="card-title">Lot Detail</div>
        <div class="card-subtitle">Lot Number: <span class="font-weight-bold">{{$lot->lot_number}}</span></div>
        <div class="row my-2">
            <div class="col-md-4 col-12 border rounded m-2">
                <div>Total Collection Quantity</div>
                <div class="font-weight-bold">{{$lot->total_collection_quantity}} {{$lot_unit}}</div>
            </div>
            <div class="col-md-4 col-12 border rounded m-2">
                <div>Graded</div>
                <div><span class="font-weight-bold">{{$lot->total_graded_quantity}} {{$lot_unit}}</span> OF <span class="font-weight-bold">{{$lot->total_collection_quantity}} {{$lot_unit}}</span></div>
            </div>
        </div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'collections'?'active':'' }}" href="?tab=collections">Collections</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'grade_distributions'?'active':'' }}" href="?tab=grade_distributions">Grade Distribution</a>
            </li>
        </ul>


        @if ($tab == 'collections')
        <div class="table-responsive mt-3">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>Collection</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($collections as $collection)
                    <tr>
                        <td>{{ $collection->collection_number }}</td>
                        <td>{{ $collection->quantity }} {{$collection->unit}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif ($tab == 'grade_distributions')
        <div class="d-flex justify-content-end">
            <a href="?tab=grade_distributions&action=add_grade_distribution" class="btn btn-primary">Add Grade Distribution</a>
        </div>
        <div class="table-responsive mt-3">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>Grade</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gradeDistributions as $grade)
                    <tr>
                        <td>{{ $grade->grade }}</td>
                        <td>{{ $grade->quantity }} {{$grade->unit}}</td>
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