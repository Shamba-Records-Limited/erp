@extends('layout.master')

@push('plugin-styles')
@endpush


@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Final Product Details</div>
        <div class="row">
            <div class="col-3 shadow p-2 border m-2 rounded">Name: <span class="font-weight-bold">{{$finalProduct->name}}</span></div>
            <div class="col-3 shadow p-2 border m-2 rounded">Unit Weight: <span class="font-weight-bold">{{$finalProduct->quantity}} KG</span></div>
            <div class="col-3 shadow p-2 border m-2 rounded">Selling Price: <span class="font-weight-bold">{{$finalProduct->selling_price}}</span></div>
            <div class="col-3 shadow p-2 border m-2 rounded">Count: <span class="font-weight-bold">{{$finalProduct->count}}</span></div>
        </div>

        <div class="bg-secondary p-1"></div>

        <div class="card-title">Raw Materials</div>

        <div class="table-responsive p-2">
            <table class="table table-hover dt">
                <thead>
                    <tr>
                        <th>Inventory No</th>
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
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection