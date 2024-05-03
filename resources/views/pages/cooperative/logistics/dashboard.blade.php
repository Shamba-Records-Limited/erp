@php $user = \Illuminate\Support\Facades\Auth::user(); @endphp
@extends('layout.master')

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                            data-toggle="collapse"
                            data-target="#reportFilterAccordion"
                            aria-expanded="@if(request()->date) true @else false @endif"
                            aria-controls="reportFilterAccordion"><span
                                class="mdi mdi-database-search"></span>Filter
                    </button>
                    <div class="collapse @if(request()->date) show @endif "
                         id="reportFilterAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Filters</h4>
                            </div>
                        </div>

                        <form action="{{ route('cooperative.logistics.dashboard') }}" method="get">
                            @csrf
                            <div class="form-row">
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
                                    <a href="{{ route('home') }}" type="submit"
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

    @if($user->hasRole('cooperative admin|employee'))
        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics card-bg-color-1">
                    <div class="card-body">
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                            <div class="float-left">
                                <i class="mdi mdi-van-utility text-danger icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right text-white">Trips Taken</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ number_format($trips_taken) }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            <a class="text-white" href="{{ route('cooperative.logistics.trips') }}">
                                <i class="mdi mdi-car-multiple mr-1" aria-hidden="true"></i> No. of trips booked
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
                                <i class="mdi mdi-car-multiple text-success icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right text-white">Company Vehicles</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ number_format($company_vehicles) }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            <a class="text-white" href="{{ route('cooperative.logistics.vehicles') }}">
                                <i class="mdi mdi-car-multiple mr-1" aria-hidden="true"></i> No. of company vehicles 
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
                                <i class="mdi mdi-car-3-plus text-info icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right text-white">3<sup>rd</sup> Party Vehicle </p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ number_format($transporter_vehicles) }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            <a class="text-white" href="{{ route('cooperative.accounting.charts_of_account') }}">
                                <i class="mdi mdi-car-multiple mr-1" aria-hidden="true"></i> Accounting Details </a>
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
                            <h4 class="card-title mb-0">
                                Trips by Transport Providers
                            </h4>
                        </div>
                        <canvas id="tripsChart" style="height:250px"></canvas>
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
    <script src="{{ asset('/assets/js/dashboard.js') }}"></script>
    <script>
        dateRangePickerFormats("date");
    </script>
    <script>
        $(function() {
            const data = JSON.parse('{!! json_encode($transporter_trips) !!}');
            const max_value = getMaxValue(data, "count");
            const step_size = calculateStepSize(data, "count");

            let tripsChartCanvas = $("#tripsChart").get(0).getContext("2d");
            let stackedBarChart = new Chart(tripsChartCanvas, {
                type: "bar",
                data: {
                    labels: getLabels(data, "transporter"),
                    datasets: [
                        {
                            label: "Trips",
                            backgroundColor: ChartColor[0],
                            borderColor: ChartColor[0],
                            borderWidth: 1,
                            data: getDataPoints(data, "count")
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    legend: false,
                    categoryPercentage: 0.5,
                    stacked: true,
                    layout: {
                        padding: {
                            left: 0,
                            right: 0,
                            top: 0,
                            bottom: 0
                        }
                    },
                    scales: {
                        xAxes: [
                            {
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: "Transporters",
                                    fontColor: chartFontcolor,
                                    fontSize: 12,
                                    lineHeight: 2
                                },
                                ticks: {
                                    fontColor: chartFontcolor,
                                    stepSize: 50,
                                    min: 0,
                                    max: 150,
                                    autoSkip: false,
                                    autoSkipPadding: 15,
                                    maxRotation: 0,
                                    maxTicksLimit: 10
                                },
                                gridLines: {
                                    display: false,
                                    drawBorder: false,
                                    color: chartGridLineColor,
                                    zeroLineColor: chartGridLineColor
                                }
                            }
                        ],
                        yAxes: [
                            {
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: "Trips Count",
                                    fontColor: chartFontcolor,
                                    fontSize: 12,
                                    lineHeight: 2
                                },
                                ticks: {
                                    fontColor: chartFontcolor,
                                    stepSize: step_size,
                                    min: 0,
                                    max: max_value,
                                    autoSkip: true,
                                    autoSkipPadding: 15,
                                    maxRotation: 0,
                                    maxTicksLimit: 10
                                },
                                gridLines: {
                                    drawBorder: false,
                                    color: chartGridLineColor,
                                    zeroLineColor: chartGridLineColor
                                }
                            }
                        ]
                    },
                    legend: {
                        display: false
                    },
                }
            });
        });        
    </script>
@endpush