@extends('layout.master')

@push('plugin-styles')

@endpush

@push('style')
<style>
    .items-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
    }

    .items-subgrid {
        display: grid;
        grid-column: 1/4;
        grid-template-columns: subgrid;
        /* border: 1px solid black; */
    }

    .items-subgrid>div {
        padding: 5px 3px;
    }

    .items-header {
        font-weight: bold;
        text-transform: uppercase;
    }

    .totals-grid {
        margin-top: 20px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        width: fit-content;
    }

    .totals-subgrid {
        display: grid;
        grid-column: 1/3;
        grid-template-columns: subgrid;
    }

    .totals-grid div {
        padding: 5px;
    }

    .qty-div {
        display: flex;
        align-items: center;
        gap: 1px;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Create Order</div>
        <div class="card-subtitle">Cooperative: {{$cooperative->name}}</div>

        <div class="items-grid">
            <div class="items-subgrid items-header">
                <div>product*</div>
                <div>lot_number</div>
                <div>Quatity</div>
            </div>
            <!-- <div class="items-subgrid">
                <div>
                    <select class="form-control select2bs4">
                        <option value="">-- Select Product --</option>
                    </select>
                </div>
                <div>
                    <select class="form-control select2bs4">
                        <option value="">-- Select Product Type --</option>
                    </select>
                </div>
                <div>
                    <select class="form-control select2bs4">
                        <option value="">-- Select Lot Number --</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>3 OF 4</div>
                    <button><i class="mdi mdi-eye"></i></button>
                </div>
                <div class="qty-div">
                    <div style="width: 70px;">
                        <input class="form-control" type="text" style="width: 70px;" >
                    </div>
                    <div style="width: 70px;">
                        <select class="form-control select2bs4">
                            <option value="">Unit</option>
                        </select>
                    </div>

                    <div> OF 15 KGs</div>
                </div>
            </div> -->
        </div>



        <div class="mt-3">
            <button class="btn btn-block btn-outline-primary">Add Order Item</button>
        </div>

        <div class="totals-grid">
            <div class="font-weight-bold border rounded" style="grid-column: span 2">Totals By Product</div>
            <div class="totals-subgrid">
                <div>COFFEE</div>
                <div class="font-weight-bold">10 KGs</div>
            </div>
        </div>
        <div>
            <button class="btn btn-primary">Save Draft</button>
            <button class="btn btn-success">Publish</button>
            <button class="btn btn-secondary">Discard</button>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
<script>
    // get order rows
    // add order row

    // update order row
    // remove order row

    // refresh order total
</script>
@endpush

@push('custom-scripts')
@endpush