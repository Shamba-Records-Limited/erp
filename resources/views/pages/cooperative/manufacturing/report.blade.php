@extends('layouts.app')

@push('plugin-styles')
    {{-- <link rel="stylesheet" href="{{ asset('/assets/plugins/plugin.css') }}" type="text/css">--}}
@endpush

@php
    $currency = Auth::user()->cooperative->currency;
@endphp
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                            data-toggle="collapse"
                            data-target="#reportFilterAccordion"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="reportFilterAccordion"><span
                                class="mdi mdi-database-search"></span>Filter
                    </button>
                    <div class="collapse @if(request()->date or request()->product or request()->production_lot) show @endif "
                         id="reportFilterAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Report Filters</h4>
                            </div>
                        </div>


                        <form action="{{ route('cooperative.manufacturing.reports') }}"
                              method="get">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="product">Product</label>
                                    <select name="product" id="product"
                                            class=" form-control form-select {{ $errors->has('product') ? ' is-invalid' : '' }}">
                                        <option value=""></option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}" {{$product->id == request()->product ?  'selected' : ''}}> {{ $product->name }}</option>
                                        @endforeach

                                        @if ($errors->has('product'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('product')  }}</strong>
                                            </span>
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="date">Period</label>
                                    <input type="text" name="date"
                                           class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           id="date"
                                           value="{{ request()->date }}">
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <label for=""></label>
                                    <button type="submit"
                                            class="btn btn-primary btn-fw btn-block">
                                        Filter
                                    </button>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <label for=""></label>
                                    <a href="{{ route('cooperative.manufacturing.reports') }}"
                                       type="submit"
                                       class="btn btn-info btn-fw btn-block">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-bg-color-1">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-shopping text-danger icon-lg"></i>
                        </div>
                        @php
                            $opening_stock_value = 0;
                                if($stock){
                                    if($stock->opening_stock_value > 999999999){
                                        $opening_stock_value = number_format($stock->opening_stock_value/1000000000,2).'B';
                                    }else
                                    if($stock->opening_stock_value > 999999){
                                        $opening_stock_value = number_format($stock->opening_stock_value/1000000,2).'M';
                                    }else{
                                        $opening_stock_value = number_format($stock->opening_stock_value,2);
                                    }
                                }else{
                                   $opening_stock_value = number_format($opening_stock_value,2);
                                }
                        @endphp

                        <h5 class="font-weight-medium text-right mb-0"> {{ $currency.' '.$opening_stock_value }}</h5>
                    </div>
                    <p class="text-white mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> Opening Stock
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-bg-color-2">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-shopping-outline text-warning icon-lg"></i>
                        </div>
                        @php
                            $closing_stock_value = 0;
                                if($stock){
                                    if($stock->closing_stock_value > 999999999){
                                        $closing_stock_value = number_format($stock->closing_stock_value/1000000000,2).'B';
                                    }else
                                    if($stock->closing_stock_value > 999999){
                                        $closing_stock_value = number_format($stock->closing_stock_value/1000000,2).'M';
                                    }else{
                                        $closing_stock_value = number_format($stock->closing_stock_value,2);
                                    }
                                }else{
                                   $closing_stock_value = number_format($closing_stock_value,2);
                                }
                        @endphp
                        <h5 class="font-weight-medium text-right mb-0"> {{ $currency.' '.$closing_stock_value }}</h5>
                    </div>
                    <p class="text-white mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i> Closing
                        Stock
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-bg-color-3">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-bank text-success icon-lg"></i>
                        </div>
                        @php
                            $available_stock_value = 0;
                            if($available_stock > 999999999){
                                $available_stock_value = number_format($available_stock/1000000000,2).'B';
                            }else
                            if($available_stock > 999999){
                                $available_stock_value = number_format($available_stock/1000000,2).'M';
                            }else{
                                $available_stock_value = number_format($available_stock,2);
                            }
                        @endphp
                        <h5 class="font-weight-medium text-right mb-0"> {{ $currency.' '.$available_stock_value }}</h5>
                    </div>
                    <p class="text-white mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i>Available Stock
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-bg-color-4">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-cash text-success icon-lg"></i>
                        </div>
                        @php
                            $sold_stock = 0;
                            if($manufacturing_total_sales > 999999999){
                                $sold_stock = number_format($manufacturing_total_sales/1000000000,2).'B';
                            }else
                            if($manufacturing_total_sales > 999999){
                                $sold_stock = number_format($manufacturing_total_sales/1000000,2).'M';
                            }else{
                                $sold_stock = number_format($manufacturing_total_sales,2);
                            }
                        @endphp
                        <h5 class="font-weight-medium text-right mb-0"> {{ $currency.' '.$sold_stock }}</h5>
                    </div>
                    <p class="text-white mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i>Sold Stock
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-bg-color-5">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-more text-success icon-lg"></i>
                        </div>
                        @php
                            $available_raw_materials_value = 0;
                            if($available_raw_materials > 999999999){
                                $available_raw_materials_value = number_format($available_raw_materials/1000000000,2).'B';
                            }else
                            if($available_raw_materials > 999999){
                                $available_raw_materials_value = number_format($available_raw_materials/1000000,2).'M';
                            }else{
                                $available_raw_materials_value = number_format($available_raw_materials,2);
                            }
                        @endphp
                        <h5 class="font-weight-medium text-right mb-0"> {{ $currency.' '.$available_raw_materials_value }}</h5>
                    </div>
                    <p class="text-white mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i>Available Raw
                        Materials
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-bg-color-6">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-more text-success icon-lg"></i>
                        </div>
                        @php
                            $purchase_orders_value = 0;
                            if($purchase_orders > 999999){
                                $purchase_orders_value = number_format($purchase_orders/1000000000,2).'B';
                            }else
                            if($purchase_orders > 999999){
                                $purchase_orders_value = number_format($purchase_orders/1000000,2).'M';
                            }else{
                                $purchase_orders_value = number_format($purchase_orders,2);
                            }
                        @endphp
                        <h5 class="font-weight-medium text-right mb-0"> {{ $currency.' '.$purchase_orders_value }}</h5>
                    </div>
                    <p class="text-white mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i>Purchase Orders
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-bg-color-7">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi  mdi-block-helper text-danger icon-lg"></i>
                        </div>
                        @php
                            $expired_stock_value = 0;
                            if($expired_stock > 999999999){
                                $expired_stock_value = number_format($expired_stock/1000000000,2).'B';
                            }else
                            if($expired_stock > 999999){
                                $expired_stock_value = number_format($expired_stock/1000000,2).'M';
                            }else{
                                $expired_stock_value = number_format($expired_stock,2);
                            }
                        @endphp
                        <h5 class="font-weight-medium text-right mb-0"> {{ $currency.' '.$expired_stock_value }}</h5>
                    </div>
                    <p class="text-white mt-3 mb-0 text-left text-md-center text-xl-left">
                        <a class="text-white" href="{{ route('manufacturing.production.expired-stock') }}">
                            <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i>Expired Stock
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    {{--                    @if(has_right_permission(config('enums.system_modules')['Manufacturing']['reports'], config('enums.system_permissions')['download']))--}}
                    {{--                        <a class="btn btn-sm btn-info float-right text-white"--}}
                    {{--                           href="{{ route('cooperative.manufacturing.reports.download', 'csv') }}">--}}
                    {{--                            <i class="mdi mdi-download"></i> CSV--}}
                    {{--                        </a>--}}

                    {{--                        <a class="btn btn-sm btn-github float-right text-white"--}}
                    {{--                           href="{{ route('cooperative.manufacturing.reports.download','xlsx') }}"--}}
                    {{--                           style="margin-right: -5px!important;">--}}
                    {{--                            <i class="mdi mdi-download"></i> Excel--}}
                    {{--                        </a>--}}
                    {{--                        <a class="btn btn-sm btn-success float-right text-white"--}}
                    {{--                           href="{{ route('cooperative.manufacturing.reports.download', 'pdf') }}"--}}
                    {{--                           style="margin-right: -8px!important;">--}}
                    {{--                            <i class="mdi mdi-download"></i> PDF--}}
                    {{--                        </a>--}}
                    {{--                    @endif--}}
                    <h4 class="card-title">Latest Stock History</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Final Product</th>
                                <th>Selling Price</th>
                                <th>Date</th>
                                <th>Opening Stock</th>
                                <th>Opening Stock Value</th>
                                <th>Closing Stock</th>
                                <th>Closing Stock Value</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($latest_stock as $key => $stock)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $stock->final_product->name}}</td>
                                    <td>{{  $stock->final_product->selling_price }}</td>
                                    <td>{{ \Carbon\Carbon::parse($stock->date)->format('D, d M Y') }}</td>
                                    <td>{{number_format($stock->opening_quantity).' '. $stock->final_product->unit->name }}</td>
                                    <td>{{ $currency.' '.number_format($stock->opening_stock_value) }}</td>
                                    <td>{{number_format($stock->closing_stock).' '. $stock->final_product->unit->name }}</td>
                                    <td>{{ $currency.' '.number_format($stock->closing_stock_value) }}</td>
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

@push('custom-scripts')
    <script>
      dateRangePickerFormats("date")
    </script>
@endpush
