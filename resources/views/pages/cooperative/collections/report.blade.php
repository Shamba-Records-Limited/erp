@extends('layout.master')

@push('plugin-styles')
    {{--    <link rel="stylesheet" href="{{ asset('/assets/plugins/plugin.css') }}" type="text/css">--}}
@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-info btn-fw btn-sm float-right"
                            data-toggle="collapse"
                            data-target="#filterAccordion"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="filterAccordion"><span
                                class="mdi mdi-database-search"></span>Date Filter
                    </button>
                    <div class="collapse
                         @if(request()->date)
                             show @endif"
                         id="filterAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Filter Product</h4>
                            </div>
                        </div>
                        <form action="{{ route('cooperative.collections.reports') }}" method="get">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="date">Date</label>
                                    <input type="text" name="date"
                                           class="form-control"
                                           id="date"
                                           value="{{ request()->date }}">
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit"
                                            class="btn btn-primary btn-fw btn-block">Search
                                    </button>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <a href="{{route('cooperative.collections.reports') }}"
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
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-bg-color-1">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-format-list-bulleted text-danger icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right text-white">Volume Collected</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ number_format($data['volume_collected'],2) }}</h3>
                            </div>
                            <div>
                                @php
                                $total = $data['volume_collected_old'] + $data['volume_collected'];
                                $difference = $data['volume_collected'] - $data['volume_collected_old'];
                                $percentage_difference = $total > 0 ? abs(($difference*100)/$total) : 0
                                @endphp
                                @if($difference > 0)
                                <span class=" icon-lg text-success mdi mdi-arrow-up"></span>
                                <span>{{number_format($percentage_difference,2)}}%</span>
                                @elseif($difference < 0)
                                    <span class=" icon-lg text-danger mdi mdi-arrow-down"></span>
                                    <span>{{ number_format($percentage_difference,2)}}%</span>
                                @else
                                    <span>{{ number_format($percentage_difference,2)}}%</span>
                                @endif
                            </div>

                        </div>
                    </div>

                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <a class="text-white" href="{{ route('cooperative.collections.show') }}">
                            <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> Collections
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-bg-color-2">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-account-multiple text-warning icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right text-white">Active Farmers</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $data['farmers'] }}</h3>
                            </div>
                            <div>
                                @php
                                    $total = $data['old_farmers'] + $data['farmers'];
                                    $difference = $data['farmers'] - $data['old_farmers'];
                                    $percentage_difference = $total > 0 ? abs(($difference*100)/$total) : 0
                                @endphp
                                @if($difference > 0)
                                    <span class=" icon-lg text-success mdi mdi-arrow-up"></span>
                                    <span>{{number_format($percentage_difference,2)}}%</span>
                                @elseif($difference < 0)
                                    <span class=" icon-lg text-danger mdi mdi-arrow-down"></span>
                                    <span>{{ number_format($percentage_difference,2)}}%</span>
                                @else
                                    <span>{{ number_format($percentage_difference,2)}}%</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">

                        <a class="text-white" href="{{ route('cooperative.farmers.show') }}">
                            <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i> Total no. of farmers
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-bg-color-3">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-receipt text-success icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right text-white">Products Collected</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $data['products'] }}</h3>
                            </div>
                            <div>
                                @php
                                    $total = $data['old_products'] + $data['products'];
                                    $difference = $data['products'] - $data['old_products'];
                                    $percentage_difference = $total > 0 ? abs(($difference*100)/$total) : 0
                                @endphp
                                @if($difference > 0)
                                    <span class=" icon-lg text-success mdi mdi-arrow-up"></span>
                                    <span>{{number_format($percentage_difference,2)}}%</span>
                                @elseif($difference < 0)
                                    <span class=" icon-lg text-danger mdi mdi-arrow-down"></span>
                                    <span>{{ number_format($percentage_difference,2)}}%</span>
                                @else
                                    <span>{{ number_format($percentage_difference,2)}}%</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">

                        <a class="text-white" href="{{ route('cooperative.products.show') }}">
                            <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i>All products in collection
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-sm-flex justify-content-between align-items-center pb-4">
                                <h4 class="card-title mb-0"> Product Supplied in the last 7 days</h4>
                                <div id="bar-traffic-legend"></div>
                            </div>
                            <canvas id="productSupplyTrend" style="height:250px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <p class="mb-0 text-right">Latest Collections</p>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Farmer</th>
                                <th>Member No</th>
                                <th>Produce</th>
                                <th>Quantity</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['latest'] as $key => $item)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$item->farmer->user->first_name }}</td>
                                    <td>{{$item->farmer->member_no }}</td>
                                    <td>{{$item->product->name }}</td>
                                    <td>{{ number_format($item->quantity,2,'.',',') }} {{$item->product->unit->name }}   </td>
                                    <td>{{ \Carbon\Carbon::create($item->date_collected)->format('Y-m-d') }}   </td>
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
    <script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script>
@endpush

@push('custom-scripts')
    @include('pages.charts.mini-dashboards.collections')
    <script>
      dateRangePickerFormats("date");
    </script>
@endpush
