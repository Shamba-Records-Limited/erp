@extends('layout.master')

@push('plugin-styles')
    {{--    <link rel="stylesheet" href="{{ asset('/assets/plugins/plugin.css') }}" type="text/css">--}}
@endpush

@section('content')
    {{--  cooperive admins--}}
    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('cooperative admin'))

        <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Farmers Per Route</h4>
                        <div class="table-responsive">
                            <table class="table table-hover dt clickable">
                                <thead>
                                <tr>
                                    <th>Route</th>
                                    <th>Farmers</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($farmer_routes as  $f)
                                    <tr>
                                        <td>{{ $f->route }}</td>
                                        <td>{{ $f->farmers }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Collections Per Route</h4>
                        <div class="table-responsive">
                            <table class="table table-hover dt clickable">
                                <thead>
                                <tr>
                                    <th>Route</th>
                                    <th>Volume of Collection</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($collections_per_route as  $f)
                                    <tr>
                                        <td>{{ $f->route }}</td>
                                        <td>{{ $f->collections }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-sm-flex justify-content-between align-items-center pb-4">
                            <h4 class="card-title mb-0">Farmers per Route</h4>
                            <div id="bar-traffic-legend"></div>
                        </div>
                        <canvas id="farmersPerRoute" style="height:50px"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-sm-flex justify-content-between align-items-center pb-4">
                            <h4 class="card-title mb-0">Volume of Collection Per Route</h4>
                            <div id="bar-traffic-legend-2"></div>
                        </div>
                        <canvas id="volumeOfCollectionPerRoute" style="height:50px"></canvas>
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
    @include('pages.charts.mini-dashboards.farmer')
@endpush
