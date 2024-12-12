@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('topItem')
<!-- add sale -->
@if($is_adding_sale == 1)
<div style="position: absolute; z-index: 1050; background-color: #2222; width: 100vw; min-height: 100vh;">
    <div class="container-fluid h-100 w-100">
        <div class="row h-100">
            <div class="col"></div>
            <div class="col-6 card h-100">
                <div class="card-header">
                    <div class="card-title position-relative">
                        <a class="position-absolute top-5 left-5 btn btn-outline-dark" href="?">
                            <i class="mdi mdi-close"></i>
                        </a>
                        <h4 class="text-center">Add Sale</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('cooperative-admin.inventory-auction.add-sale')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="inventory_number">Inventory Number</label>
                                <input type="text" class="form-control {{ $errors->has('inventory_number') ? ' is-invalid' : '' }}" id="inventory_number" placeholder="Inventory Number" name="inventory_number" value="">

                                @if ($errors->inventory_number)
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('inventory_number') }}</strong>
                                </span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Save Inventory</button>
                        </form>

                        <div class="mt-4 d-flex">
                            <a class="btn btn-secondary ml-2" href="?">Add Sale</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- /view delivery -->
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Inventory Auction Sales</div>
        <div class="d-flex justify-content-end">
            <a href="?is_adding_sale=1" class="btn btn-primary">Record Sale</a>
        </div>
        <div class="table-responsive p-2">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sale Number</th>
                        <th>Description</th>
                        <th>Total</th>
                        <th>Created At</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
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