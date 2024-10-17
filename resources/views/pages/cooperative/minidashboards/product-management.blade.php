@extends('layouts.app')

@push('plugin-styles')
    {{--    <link rel="stylesheet" href="{{ asset('/assets/plugins/plugin.css') }}" type="text/css">--}}
@endpush

@section('content')
    {{--  cooperive admins--}}
    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('cooperative admin'))
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
                        <div class="collapse @if(request()->date or request()->products) show @endif "
                             id="reportFilterAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Filters</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.product-mini-dashboard') }}" method="get">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="product">Product</label>
                                        <select name="products[]"  multiple="multiple" id="products"
                                                class=" form-control select2bs4 {{ $errors->has('product') ? ' is-invalid' : '' }}">
                                            <option value=""></option>
                                            @foreach($products as $product)
                                                <option value="{{$product->id}}" {{ request()->products ? (in_array($product->id,  request()->products) ?  'selected' : '') : ''}}> {{ $product->name }}</option>
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

                                        @if ($errors->has('date'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('date')  }}</strong>
                                        </span>
                                        @endif
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
                                        <a href="{{ route('cooperative.product-mini-dashboard') }}" type="submit"
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
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics card-bg-color-1">
                    <div class="card-body">
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                            <div class="float-left">
                                <i class="mdi mdi-account text-danger icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right text-white">Suppliers</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{number_format($data->suppliers,0)}}</h3>
                                </div>
                            </div>
                        </div>

                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            <a href="{{ route('cooperative.products.suppliers.show') }}" class="text-white">
                                <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> Number of
                                suppliers/farmers
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics card-bg-color-2">
                    <div class="card-body">
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                            <div class="float-left">
                                <i class="mdi mdi-receipt text-warning icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right text-white">Products</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{number_format($data->products,0)}}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            <a href="{{ route('cooperative.products.show') }}" class="text-white">
                                <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i>Number of products
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics card-bg-color-3">
                    <div class="card-body">
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                            <div class="float-left">
                                <i class="mdi mdi-poll-box text-success icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right text-white">Highest Collection</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ $data->highest_supply ? $data->highest_supply->name : '' }}</h3>
                                </div>
                            </div>
                        </div>

                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            <a href="{{ route('cooperative.collections.show') }}" class="text-white">
                                <i class="mdi mdi-poll-box mr-1" aria-hidden="true"></i>Product with Highest Collection ({{ number_format($data->highest_supply ? $data->highest_supply->total : 0) }})
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics card-bg-color-4">
                    <div class="card-body">
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                            <div class="float-left">
                                <i class="mdi mdi-arrow-up-bold text-info icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right text-white">Most Supplied Product</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{$data->most_supplied ? $data->most_supplied->name : ''}}</h3>
                                </div>
                            </div>
                        </div>

                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            <a href="{{ route('cooperative.collections.reports') }}" class="text-white">
                                <i class="mdi mdi-reload mr-1"
                                   aria-hidden="true"></i>Supplied {{number_format($data->most_supplied ? $data->most_supplied->total : 0, 0)}}
                                Times
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-sm-flex justify-content-between align-items-center pb-4">
                            <h4 class="card-title mb-0">Collected vs Available</h4>
                            <div id="stacked-bar-traffic-legend"></div>
                        </div>
                        <canvas id="productBpSpAnalysis" style="height:250px"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-sm-flex justify-content-between align-items-center pb-4">
                            <h4 class="card-title mb-0"> Product Supply Trend</h4>
                            <div id="bar-traffic-legend"></div>
                        </div>
                        <canvas id="productSupplyTrend" style="height:50px"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('plugin-scripts')
    <script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script>
@endpush

@push('custom-scripts')
    @include('pages.charts.mini-dashboards.product')
    <script>
      dateRangePickerFormats("date")
    </script>
@endpush
