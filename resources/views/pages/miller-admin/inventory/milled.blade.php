@extends('layout.master')

@push('plugin-styles')
@endpush

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
                    @foreach($milledInventory as $key => $inventory)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$inventory->batch_number}}</td>
                        <td>{{$inventory->l_num}}</td>
                        <td>{{$inventory->quantity}} KG</td>
                        <td>{{$inventory->milled_quantity}} KG</td>
                        <td>{{$inventory->waste_quantity}} KG</td>
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


@push('plugin-scripts')
@endpush


@push('custom-scripts')
@endpush