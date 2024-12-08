@extends('layouts.app')

@push('plugin-styles')
<!-- Add custom styles here if needed -->
@endpush

@section('content')

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow-sm p-4 mb-5 bg-white rounded">

            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#addWarehouseForm" aria-expanded="@if ($errors->count() > 0) true @else false @endif" aria-controls="addWarehouseForm">
                        <span class="mdi mdi-plus"></span> Add Sales
                    </button>
                    <div>
                        <a class="btn btn-primary btn-sm" href="{{route('miller-admin.inventory-auction.export', 'xlsx')}}"><span class="mdi mdi-file-excel"></span> Download Excel Sheet</a>
                        <a class="btn btn-primary btn-sm" href="{{route('miller-admin.inventory-auction.export', 'pdf')}}"><span class="mdi mdi-file-pdf"></span> Download PDF</a>
                    </div>
                </div>

                <div class="collapse @if($errors->count() > 0) show @endif" id="addWarehouseForm">
                    <div class="card p-4 mt-4 shadow-sm">
                        <h4 class="text-primary mb-4">Add Sales</h4>
                        <form action="{{ route('miller-admin.inventory-auction.add-sale') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label for="product_id">Product</label>
                                    <select name="product_id" id="product_id" class="form-control form-select {{ $errors->has('product_id') ? ' is-invalid' : '' }}" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach($products as $product)
                                        <option value="{{$product->id}}" @if($product->id == old('product_id')) selected @endif>{{$product->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('product_id'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('product_id')  }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="quantity" class="font-weight-bold">Quantity</label>
                                    <input type="decimal" name="quantity" class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}" id="quantity" placeholder="1.0" value="{{ old('quantity') }}" required>
                                    @if ($errors->has('quantity'))
                                    <div class="invalid-feedback">{{ $errors->first('quantity') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="amount" class="font-weight-bold">Amount</label>
                                    <input type="decimal" name="amount" class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}" id="amount" placeholder="1.0" value="{{ old('amount') }}" required>
                                    @if ($errors->has('amount'))
                                    <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
                                    @endif
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="sale_batch_numbe" class="font-weight-bold">Batch Number</label>
                                    <input type="text" name="sale_batch_number" class="form-control {{ $errors->has('sale_batch_number') ? ' is-invalid' : '' }}" id="sale_batch_number" value="{{$saleNumber}}" readonly required>
                                    @if ($errors->has('sale_batch_number'))
                                    <div class="invalid-feedback">{{ $errors->first('sale_batch_number') }}</div>
                                    @endif
                                </div>

                               <!-- <div class="form-group col-md-6">
                                    <label for="discount" class="font-weight-bold">Discount</label>
                                    <input type="decimal" name="discount" class="form-control {{ $errors->has('discount') ? ' is-invalid' : '' }}" id="discount" placeholder="0.0" value="{{ old('discount') }}" required>
                                    @if ($errors->has('discount'))
                                    <div class="invalid-feedback">{{ $errors->first('discount') }}</div>
                                    @endif
                                </div>-->
                            </div>

                            <div class="form-row">
                             
                             </div>

                            <div class="form-row">
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary btn-block mt-3">Add Sales</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Sales Table -->
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow-sm p-4 mb-5 bg-white rounded">
            <div class="card-body">
                <h4 class="card-title">Sales</h4>
                <div class="table-responsive">
                    <table id="warehouseTable" class="table table-hover dt clickable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Batch No</th>
                                <th>Amount</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $key => $sale)
                            <tr>
                                <td>{{++$key }}</td>
                                <td>{{$sale->sale_batch_number}}</td>
                                <td>{{$sale->paid_amount}}</td>
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
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#warehouseTable').DataTable();
        
        // Set warehouse count in the card based on the number of rows in the DataTable
        $('#warehouse-count').text(table.rows().count());
        
        // Get unique locations and set location count in the card
        var uniqueLocations = [];
        table.column(2).data().each(function(value) {
            if (uniqueLocations.indexOf(value) === -1) {
                uniqueLocations.push(value);
            }
        });
        $('#location-count').text(uniqueLocations.length);
    });
</script>
@endpush
