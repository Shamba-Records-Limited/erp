@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('topItem')
@if($isAddingGrade == '1')
<div class="overlay">
    <div class="modal-container">
        <div class="modal-card">
            <div class="modal-header">
                <h4 class="text-center">Add Grade</h4>
                <a class="close-btn" href="?">
                    <i class="mdi mdi-close"></i>
                </a>
            </div>
            <div class="modal-body">
                <form id="addDeliveryItemForm" class="border rounded p-3" action="{{ route('miller-admin.milled-inventory.store-grade') }}" method="POST">
                    @csrf
                    <input type="hidden" name="milled_inventory_id" value="{{ $id }}">
                    <input type="hidden" name="unit" value="{{ $lot_unit }}">

                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="product_grade_id">Select Grade</label>
                            <select name="product_grade_id" id="product_grade_id" class="form-control form-select {{ $errors->has('product_grade_id') ? ' is-invalid' : '' }}" required>
                                <option value="">-- Select Grade --</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" @if(old('product_grade_id') == $grade->id) selected @endif>{{ $grade->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('product_grade_id'))
                                <span class="text-danger">{{ $errors->first('product_grade_id') }}</span>
                            @endif
                        </div>

                        <div class="form-group col-12">
                            <label for="quantity">Quantity</label>
                            <div class="input-group">
                                <input type="number" name="quantity" id="quantity" class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}" 
                                placeholder="Enter quantity" value="{{ old('quantity') }}" required
                                onchange="validateQuantity(this)"
                                data-max="{{ $milling->milled_quantity }}"
                                >
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ $lot_unit }}</span>
                                </div>
                            </div>
                            @if($errors->has('quantity'))
                                <span class="text-danger">{{ $errors->first('quantity') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Save Grade</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection


@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Milled Inventory Details</div>
        <div class="card-subtitle">Inventory Number: <span class="font-weight-bold">{{$milling->inventory_number}}</span></div>
        <div class="row">
            <div class="col-4">
                <div></div>
            </div>
        </div>


        <h4>Grading</h4>
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
                    @foreach($gradings as $grading)
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
    </div>
</div>

@endsection


@push('plugin-scripts')
@endpush


@push('custom-scripts')
<script>
    function validateQuantity(input) {
        const maxQuantity = parseFloat(input.getAttribute('data-max')); // Get the max allowed quantity from the attribute
        const enteredQuantity = parseFloat(input.value); // Get the entered quantity
        if (enteredQuantity > maxQuantity) {
            alert('You cannot grade more than the milled quantity:'+maxQuantity);
            input.value = ''; // Clear the invalid input
        }
    }
</script>
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