@extends('layout.master')

@push('plugin-styles')
    {{--    <link rel="stylesheet" href="{{ asset('/assets/plugins/plugin.css') }}" type="text/css">--}}
@endpush

@section('content')
    {{--  cooperive admins--}}
    @php $currency = \Illuminate\Support\Facades\Auth::user()->cooperative->currency @endphp
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#filterFormAccordion"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="filterFormAccordion">Filter
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="filterFormAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Filter Data</h4>
                            </div>
                        </div>


                        <form method="get" action="{{ route('financial_products.dashboard')}}">
                            <div class="form-row">
                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <label for="purpose">Date Range</label>
                                    <input type="text" name="dates"
                                           class="form-control date-range{{ $errors->has('start_date') ? ' is-invalid' : '' }}"
                                           id="dates">
                                    @if ($errors->has('purpose'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('purpose')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-6">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block" id="filterBtn"> <span> <i class="mdi mdi-calendar"></i></span>Filter</button>
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
                            <i class="mdi mdi-wallet text-success icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right text-white">Loaned Amount</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $currency}} {{ number_format($data->amount_in_loans) }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <a href="{{ route('cooperative.loaned-farmers') }}" class="text-white">
                            <i class="mdi mdi-alert-octagon mr-1 text-white" aria-hidden="true"></i>
                            Amount loaned to farmers
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
                            <i class="mdi mdi-bank text-warning icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right text-white">Defaulted Loans</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ number_format($data->defaulted_loans) }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <a href="{{ route('cooperative.loaned-farmers') }}" class="text-white">
                            <i class="mdi mdi-bookmark-outline mr-1 text-white" aria-hidden="true"></i>
                            Number of defaulted loans
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
                            <i class="mdi mdi-cash-multiple text-primary icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right text-white">Total Repayments</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $currency}} {{ number_format($data->amount_in_repayments) }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <a href="{{ route('cooperative.loaned-farmers') }}" class="text-white">
                            <i class="mdi mdi-calendar mr-1 text-white" aria-hidden="true"></i>
                            Amount loaned out to farmers
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-bg-color-5">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-autorenew text-info icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right text-white">Total Savings</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $currency}} {{ number_format($data->amount_in_savings) }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <a href="{{ route('cooperative.farmer-savings') }}" class="text-white">
                            <i class="mdi mdi-reload mr-1 text-white" aria-hidden="true"></i>
                            Amount in savings
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="p-4 pr-5 border-bottom bg-light d-sm-flex justify-content-between">
                    <h4 class="card-title mb-0">Loans By Status</h4>
                </div>
                <div class="card-body d-flex">
                    <canvas class="my-auto" id="loansByStatus" height="130"></canvas>
                </div>
                <div class="p-4 pr-5 border-bottom bg-light d-sm-flex justify-content-center">
                    <div id="loansByStatusLegend" class="mr-4"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex justify-content-between align-items-center pb-4">
                        <h4 class="card-title mb-0">Saving</h4>
                        <div id="savings-legend"></div>
                    </div>
                    <canvas id="savingChart" style="height:250px"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex justify-content-between align-items-center pb-4">
                        <h4 class="card-title mb-0">Loans vs Repayments</h4>
                        <div id="stacked-bar-traffic-legend"></div>
                    </div>
                    <canvas id="stackedbarChart" style="height:250px"></canvas>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('plugin-scripts')
    <script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script>
@endpush

@push('custom-scripts')
    @include('pages.charts.mini-dashboards.financial-products')
    <script>
        dateRangePickerFormats("dates")
    </script>
@endpush
