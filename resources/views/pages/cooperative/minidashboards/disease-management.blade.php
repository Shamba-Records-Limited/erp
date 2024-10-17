@extends('layout.master')

@push('plugin-styles')
    {{--    <link rel="stylesheet" href="{{ asset('/assets/plugins/plugin.css') }}" type="text/css">--}}
@endpush

@section('content')
    {{--  cooperive admins--}}
    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('cooperative admin'))
        <div class="row">
            @foreach($disease_cases as $key => $case)
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics card-bg-color-{{++$key}}">
                    <div class="card-body">
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                            <div class="float-left">
                                <i class="mdi mdi-alert text-danger icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right text-white">{{ $case->disease }}</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{number_format($case->count,0)}}</h3>
                                </div>
                            </div>
                        </div>

                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            <a class="text-white" href="{{ route('cooperative.disease.show') }}">
                                <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> Diseases
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-sm-flex justify-content-between align-items-center pb-4">
                            <h4 class="card-title mb-0"></h4>
                            <div id="bar-traffic-legend"></div>
                        </div>
                        <canvas id="casesByStatus" style="height:50px"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-sm-flex justify-content-between align-items-center pb-4">
                            <h4 class="card-title mb-0">Disease infected Regions</h4>
                        </div>
                        <div  id="mapView" style="height:500px; width: 100%"></div>

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
    @include('pages.charts.mini-dashboards.disease')
    <script src="https://maps.google.com/maps/api/js?key={{env('MAPS_API_KEY')}}&callback=initMap&v=weekly" defer></script>
@endpush
