@extends('layouts.app')

@push('plugin-styles')
    {{--    <link rel="stylesheet" href="{{ asset('/assets/plugins/plugin.css') }}" type="text/css">--}}
@endpush

@section('content')
    {{--  cooperive admins--}}
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-bg-color-1">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-timer-sand text-danger icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right text-white">Pending Payments</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ \Illuminate\Support\Facades\Auth::user()->cooperative->currency }}
                                    @php $value = $data->wallet->sum('current_balance') @endphp
                                    {{ abs($value) > 999999 ? number_format(($value / 1000000),2,'.',',').'M' :
                                        number_format(($value/1000), 2,'.',',').'K'
                                    }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <a class="text-white" href="{{ route('cooperative.wallet.pending_payments') }}">
                            <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i>Pending Payment in {{ date('Y') }}
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
                            <i class="mdi mdi-bank text-success icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right text-white">Completed Payments</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">
                                    {{ \Illuminate\Support\Facades\Auth::user()->cooperative->currency }}
                                    @php $value = $data->wallet->sum('available_balance') @endphp
                                    {{ abs($value) > 999999 ? number_format(($value / 1000000),2,'.',',').'M' :
                                        number_format(($value/1000), 2,'.',',').'K'
                                    }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left text-white">
                        <i class="mdi mdi-bookmark-outline mr-1 text-white" aria-hidden="true"></i>
                        <span class="text-white"> Completed Payment as at {{ date('M Y') }}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-bg-color-3">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-account-box-multiple text-warning icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right text-white">Loans issued</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">
                                    {{ \Illuminate\Support\Facades\Auth::user()->cooperative->currency }}
                                    @php $value = $data->loans @endphp
                                    {{ abs($value) > 999999 ? number_format(($value / 1000000),2,'.',',').'M' :
                                        number_format(($value/1000), 2,'.',',').'K'
                                    }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <a class="text-white" href="{{ route('cooperative.wallet.get_loaned_farmers') }}">
                            <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i>Loans issued
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
                            <i class="mdi mdi-chart-line text-info icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right text-white">Profits</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">
                                    {{ \Illuminate\Support\Facades\Auth::user()->cooperative->currency }}
                                    @php $value = $data->profit_margin @endphp
                                    {{ abs($value) > 999999 ? number_format(($value / 1000000), 2,'.',',').'M'
                                        : number_format(($value / 1000000), 2,'.',',').'K'}}
                                </h3>
                            </div>
                        </div>
                    </div>

                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <a class="text-white" href="{{ route('cooperative.accounting.reports') }}">
                            <i class="mdi mdi-chart-line mr-1" aria-hidden="true"></i>Profits/Losses as
                            on {{ date('M, Y') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script>
@endpush

@push('custom-scripts')
    @include('pages.charts.admin-wallet-chart')
@endpush
