@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Product Management']['products'], config('enums.system_permissions')['edit']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addProductAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addProductAccordion"><span class="mdi mdi-plus"></span>Add Products
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addProductAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Products Supplied by  {{ $names }}</h4>
                                </div>
                            </div>
                            <form action="{{ route('cooperative.farmer.add.products.suppliers', $userId) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="products">Products</label>
                                        <select name="products[]" multiple="multiple" id="products"
                                                class=" form-control select2bs4 {{ $errors->has('products') ? ' is-invalid' : '' }}">
                                            <option value=""></option>
                                            @foreach($new_products as $product)
                                                <option value="{{$product->id}}" > {{ucwords($product->name)}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('products'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('products')  }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <button type="submit" class="btn btn-primary btn-fw btn-block">Add</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                @if(has_right_permission(config('enums.system_modules')['Product Management']['suppliers'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('cooperative.farmer.add.products.suppliers.download', [$userId,'csv']) }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('cooperative.farmer.add.products.suppliers.download',[$userId,'xlsx']) }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('cooperative.farmer.add.products.suppliers.download', [$userId, 'pdf']) }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif

                    <h4 class="card-title">Products Supplied by {{$names}}</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Unit Measure</th>
                                <th>Quantity Supplied</th>
                                <th>Buying Price</th>
                                <th>Total Value</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                 $currency = Auth::user()->cooperative->currency;
                                 $total_value = 0;
                                 $total_quantity = 0;
                                 $total_bp = 0;
                            @endphp
                            @foreach($products as $key => $product)
                                @php
                                    $total_value += $product->total_cost;
                                    $total_bp += $product->unit_cost;
                                    $total_quantity += $product->total_quantity;
                                @endphp
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>{{$product->product_name}}</td>
                                        <td>{{$product->category}}</td>
                                        <td>{{$product->unit}}</td>
                                        <td>{{number_format($product->total_quantity,2)}}</td>
                                        <td>{{$currency.' '.number_format($product->unit_cost,2)}}</td>
                                        <td>{{$currency.' '.number_format($product->total_cost,2)}}</td>
                                    </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="4">Total</th>
                                <th colspan="1">{{ number_format($total_quantity) }}</th>
                                <th colspan="1">{{ $currency.' '.number_format($total_bp)}}</th>
                                <th colspan="1">{{ $currency.' '.number_format($total_value) }}</th>
                            </tr>
                            </tfoot>
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
@endpush
