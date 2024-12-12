@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('topItem')
@if($isGrading == "1")
<div class="overlay">
    <div class="modal-container">
        <div class="modal-card">
            <div class="modal-header">
                <h4 class="text-center">Grade Milled Coffee</h4>
                <a class="close-btn" href="?is_creating_final_product=0">
                    <i class="mdi mdi-close"></i>
                </a>
            </div>
            <div class="modal-body">
                <div class="text-warning m-2 border border-warning p-2 rounded">
                    You are working on a draft grading. Publish it to apply changes
                </div>
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
                                <td>{{ $grading->grade }}</td>
                                <td>{{ $grading->quantity }}</td>
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
@endif
@endsection


@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Milled Inventory</div>

        <div class="d-flex justify-content-end">
            <a class="btn btn-primary btn-fw btn-sm" href="{{route('admin.milled-inventory.export', 'xlsx')}}"><span class="mdi mdi-file-excel"></span> Export Excel
            </a>
            <a class="btn btn-primary btn-fw btn-sm ml-1" href="{{route('admin.milled-inventory.export', 'pdf')}}"><span class="mdi mdi-file-pdf"></span> Export PDF
            </a>
        </div>

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
                        <th>Miller</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalQuantity = 0;
                        $totalMilledQuantity = 0;
                        $totalWasteQuantity = 0;
                    @endphp
                    @foreach($milledInventories as $key => $inventory)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$inventory->batch_number}}</td>
                        <td>{{$inventory->l_num}}</td>
                        <td>{{$inventory->milled_quantity + $inventory->waste_quantity}} KG</td>
                        <td>{{$inventory->milled_quantity}} KG</td>
                        <td>{{$inventory->waste_quantity}} KG</td>
                        <td>{{ $inventory->miller_name }}</td>
                        <td>
                            <div class="btn-group dropdown">
                                <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="text-primary dropdown-item" href="{{route('admin.milled-inventory.detail', $inventory->id )}}">
                                        <i class="fa fa-edit"></i> View Details
                                    </a>
                                    <!--<a class="text-warning dropdown-item" href="?is_grading=1&milled_inventory_id={{$inventory->id}}">
                                        <i class="fa fa-edit"></i> Grade The Coffee
                                    </a>-->
                                </div>
                            </div>
                        </td>
                    </tr>
                    @php
                        $totalQuantity += $inventory->milled_quantity + $inventory->waste_quantity;
                        $totalMilledQuantity += $inventory->milled_quantity;
                        $totalWasteQuantity += $inventory->waste_quantity;
                    @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">Total</th>
                        <th>{{ $totalQuantity }} KG</th>
                        <th>{{ $totalMilledQuantity }} KG</th>
                        <th>{{ $totalWasteQuantity }} KG</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
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