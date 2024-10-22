@extends('layouts.app')

@push('plugin-styles')
    {{--    <link rel="stylesheet" href="{{ asset('/assets/plugins/plugin.css') }}" type="text/css">--}}
    <link rel="stylesheet" href="{{ asset('css/override.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/full-calendar/main.min.css') }}" type="text/css">
@endpush

@section('content')
    {{--  cooperive admins--}}
    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('cooperative admin'))
        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics card-bg-color-1">
                    <div class="card-body">
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                            <div class="float-left">
                                <i class="mdi mdi-apps text-danger icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right text-white">Breeds</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ $breeds }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">

                            <a class="text-white" href="{{ route('cooperative.farm.breeds') }}">
                                <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i>Breeds
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
                                <i class="mdi mdi-cow text-warning icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right text-white">Livestock/Poultry</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ $cows }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            <a class="text-white" href="{{ route('cooperative.farm.animals') }}">
                                <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i>Number of Livestock/Poultry
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
                                <i class="mdi mdi-water text-success icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right text-white">Crops</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ $crops }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            <a class="text-white" href="{{ route('cooperative.farm.crops') }}">
                                <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i>Number of Crop variety
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
                                <i class="mdi mdi-calendar text-info icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right text-white">Total Production Cost</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ number_format($total_production_cost) }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            <a class="text-white" href="{{ route('cooperative.farm.farmer-crops') }}">
                                <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i>Crops/Livestock/Poultry Tracked
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
                        <h4 class="card-title mb-0">Breed Distribution</h4>
                        <div id="chart-legend" class="mr-4"></div>
                    </div>
                    <div class="card-body d-flex">
                        <canvas class="my-auto" id="breedDistributionChart" height="130"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="p-4 pr-5 border-bottom bg-light d-sm-flex justify-content-between">
                        <h4 class="card-title mb-0">Crop Variety Distribution</h4>
                        <div id="chart-legend-2" class="mr-4"></div>
                    </div>
                    <div class="card-body d-flex">
                        <canvas class="my-auto" id="cropVarietyChart" height="130"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Calendar View</h4>
                        <div class="table-responsive">
                            <div id='calendar'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('plugin-scripts')
    <script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/full-calendar/main.min.js') }}"></script>
@endpush

@push('custom-scripts')
    @include('pages.charts.mini-dashboards.herd')
@endpush
