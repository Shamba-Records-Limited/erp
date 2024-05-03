@extends('layout.master')

@push('plugin-styles')
    {{--    <link rel="stylesheet" href="{{ asset('/assets/plugins/plugin.css') }}" type="text/css">--}}
@endpush

@section('content')
    {{--  cooperive admins--}}
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-outline-success">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-wallet text-success icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Available Balance</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ \Illuminate\Support\Facades\Auth::user()->cooperative->currency }} {{ number_format($data->wallet ? $data->wallet->available_balance : 0, 0, '.',',' ) }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i>Available withdrawal Balance </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-outline-warning">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-bank text-warning icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Current Balance</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ \Illuminate\Support\Facades\Auth::user()->cooperative->currency }} {{ number_format($data->wallet ? $data->wallet->current_balance : 0, 0, '.', ',') }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i>Total Current Balance </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-outline-primary">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-account-box-multiple text-primary icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Outstanding Loans</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ 0 }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i>Current outstanding loan </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-outline-danger">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-autorenew text-danger icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Transactions</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ number_format($data->transactions, 0, '.', ',') }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-reload mr-1" aria-hidden="true"></i>Transactions as of {{ date('M, Y') }} </p>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="p-4 border-bottom bg-light">
                    <h4 class="card-title mb-0">Annual Transactions and Income</h4>
                </div>
                <div class="card-body">
                    <canvas id="mixed-chart" height="100"></canvas>
                    <div class="mr-5" id="mixed-chart-legend"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="p-4 pr-5 border-bottom bg-light d-sm-flex justify-content-between">
                    <h4 class="card-title mb-0">Monthly Income</h4>
                    <div id="doughnut-chart-legend" class="mr-4"></div>
                </div>
                <div class="card-body d-flex flex-column">
                    <canvas class="my-auto" id="doughnutChart" height="200"></canvas>

                    {{--        <div class="d-flex pt-3 border-top">--}}
                    {{--          <p class="mb-0 font-weight-semibold"><span class="dot-indicator bg-success"></span>Total Income</p>--}}
                    {{--          <p class="mb-0 ml-auto text-primary">$1,325</p>--}}
                    {{--        </div>--}}
                </div>
            </div>
        </div>

        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="p-4 pr-5 border-bottom bg-light d-sm-flex justify-content-between">
                    <h4 class="card-title mb-0">Loans Vs Income</h4>
                    {{--        <div id="pie-chart-legend" class="mr-4"></div>--}}
                </div>
                <div class="card-body d-flex">
                    <canvas class="my-auto" id="pieChart" height="130"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script>
@endpush

@push('custom-scripts')
    @include('pages.charts.as-farmer.farmer-wallet-charts')
@endpush
