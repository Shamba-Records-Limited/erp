@extends('layout.master')

@push('plugin-styles')
@endpush

@section('topItem')
@if($isMilling == 1)
<div style="position: absolute; z-index: 1050; background-color: #2222; width: 100vw; min-height: 100vh;">
    <div class="container-fluid h-100 w-100">
        <div class="row h-100">
            <div class="col"></div>
            <div class="col-6 card h-100">
                <div class="card-header">
                    <div class="card-title position-relative">
                        <a class="position-absolute top-5 left-5 btn btn-outline-dark" href="?is_milling=0">
                            <i class="mdi mdi-close"></i>
                        </a>
                        <h4 class="text-center">Mill</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('miller-admin.milling.save')}}" method="POST">
                            @csrf
                            <input type="hidden" name="pre_milled_inventory_id" value="{{$preMilledInventoryId}}" />
                            @if($errors->has('pre_milled_inventory_id'))
                            <div class="text-danger">{{$errors->first('pre_milled_inventory_id')}}</div>
                            @endif

                            <div class="form-group">
                                <label for="milled_quantity">Milled Quantity</label>
                                <input type="number" name="milled_quantity" id="milled_quantity" class="form-control @error('milled_quantity') is-invalid @enderror" required />

                                @if($errors->has('milled_quantity'))
                                <div class="text-danger">{{$errors->first('milled_quantity')}}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="waste_quantity">Waste Quantity</label>
                                <input type="number" name="waste_quantity" id="waste_quantity" class="form-control @error('waste_quantity') is-invalid @enderror" required />

                                @if($errors->has('waste_quantity'))
                                <div class="text-danger">{{$errors->first('waste_quantity')}}</div>
                                @endif
                            </div>
                            <p>Totals should add up to: {{$millingQty}} KG</p>
                            <button type="submit" class="btn btn-primary">Mill</button>
                        </form>
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
        <div class="card-title">Pre Milled Inventory</div>

        <div class="d-flex justify-content-end">
            <a class="btn btn-primary btn-fw btn-sm" href="{{route('miller-admin.pre-milled-inventory.export', 'xlsx')}}"><span class="mdi mdi-file-excel"></span>Export Excel
            </a>
            <a class="btn btn-primary btn-fw btn-sm ml-1" href="{{route('miller-admin.pre-milled-inventory.export', 'pdf')}}"><span class="mdi mdi-file-pdf"></span>Export Pdf
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
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($preMilledInventories as $key => $inventory)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$inventory->inventory_number}}</td>
                        <td>{{$inventory->batch_number}}</td>
                        <td>{{$inventory->l_num}}</td>
                        <td>{{$inventory->quantity}} KG</td>
                        <td>
                            <span class="text-{{$inventory->milled_inventory_id ? 'success' : 'warning'}}">
                            {{$inventory->milled_inventory_id ? "Milled" : "Not Milled"}}</span></td>
                        <td>
                            @if(!$inventory->milled_inventory_id)
                            <a class="btn btn-primary" href="?pre_milled_inventory_id={{$inventory->id}}&is_milling=1">Mill</a>
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