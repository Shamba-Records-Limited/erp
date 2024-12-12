@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('topItem')
@if($isMilling == 1)
<div class="overlay">
    <div class="modal-container">
        <div class="modal-card">
            <div class="modal-header">
                <h4 class="text-center">Mill</h4>
                <a class="close-btn" href="?is_milling=0">
                    <i class="mdi mdi-close"></i>
                </a>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.milling.save') }}" method="POST">
                    @csrf
                    <input type="hidden" name="pre_milled_inventory_id" value="{{ $preMilledInventoryId }}" />
                    @if($errors->has('pre_milled_inventory_id'))
                        <div class="text-danger">{{ $errors->first('pre_milled_inventory_id') }}</div>
                    @endif

                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="milled_quantity">Milled Quantity</label>
                            <input type="number" name="milled_quantity" id="milled_quantity" class="form-control @error('milled_quantity') is-invalid @enderror" required>
                            @if($errors->has('milled_quantity'))
                                <span class="text-danger">{{ $errors->first('milled_quantity') }}</span>
                            @endif
                        </div>

                        <div class="form-group col-12">
                            <label for="waste_quantity">Waste Quantity</label>
                            <input type="number" name="waste_quantity" id="waste_quantity" class="form-control @error('waste_quantity') is-invalid @enderror" required>
                            @if($errors->has('waste_quantity'))
                                <span class="text-danger">{{ $errors->first('waste_quantity') }}</span>
                            @endif
                        </div>
                    </div>

                    <p>Totals should add up to: {{ $millingQty }} KG</p>
                    <button type="submit" class="btn btn-primary">Mill</button>
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
        <div class="card-title">Pre Milled Inventory</div>

        <div class="d-flex justify-content-end mb-3">
            <a class="btn btn-primary btn-fw btn-sm" href="{{ route('admin.pre-milled-inventory.export', 'xlsx') }}">
                <span class="mdi mdi-file-excel"></span> Export Excel
            </a>
            <a class="btn btn-primary btn-fw btn-sm ml-1" href="{{ route('admin.pre-milled-inventory.export', 'pdf') }}">
                <span class="mdi mdi-file-pdf"></span> Export PDF
            </a>
        </div>

        <div class="table-responsive p-2">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Inventory Number</th>
                        <th>Batch Number</th>
                        <th>Lot Number</th>
                        <th>Quantity</th>
                        <th>Miller</th>
                        <!--<th>Action</th>-->
                    </tr>
                </thead>
                <tbody>
                    @php $totalQuantity = 0; @endphp
                    @foreach($preMilledInventories as $key => $inventory)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $inventory->inventory_number }}</td>
                        <td>{{ $inventory->batch_number }}</td>
                        <td>{{ $inventory->l_num }}</td>
                        <td>{{ $inventory->quantity }} KG</td>
                        <td>{{ $inventory->miller_name }} KG</td>
                       <!-- <td>
                            <a class="btn btn-primary" href="?pre_milled_inventory_id={{ $inventory->id }}&is_milling=1">Mill</a>
                        </td>-->
                    </tr>
                    @php $totalQuantity += $inventory->quantity; @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right font-weight-bold">Total Quantity:</td>
                        <td class="font-weight-bold">{{ $totalQuantity }} KG</td>
                        <td></td>
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