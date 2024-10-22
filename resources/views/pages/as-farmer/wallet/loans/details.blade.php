@extends('layouts.app')

@push('plugin-styles')
    {{--    <link rel="stylesheet" href="{{ asset('/assets/plugins/plugin.css') }}" type="text/css">--}}
    <style>
        @keyframes bg-color-change {
        0% {
                background-color: #FFEBEE;
        }
        50% {
            background-color: #FFCDD2;
        }
        100% {
            background-color: #EF9A9A;
        }
        }

        .bg-color-range {
        animation: bg-color-change 1s ease-in-out infinite;
        }
    </style>
@endpush

@section('content')
    {{--  cooperive admins--}}
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-outline-success">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-wallet text-success icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Current Loan Limit</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ \Illuminate\Support\Facades\Auth::user()->cooperative->currency }} {{ number_format($loan_limit ? $loan_limit->limit : 0, 0, '.', ',') }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i>My Current Loan Limit </p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-outline-warning">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-bank text-warning icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Loan Amount</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ isset($loans) ? $loans->amount : 0 }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i>Interest: {{ isset($loans) ? $loans->interest : 0 }}%</p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-outline-primary">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-autorenew text-primary icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Loan Balance</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ isset($loans) ? $loans->balance : 0 }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i>Selected Loan Balance </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Loan Repayment</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($loans)
                                @if(count($loans->loanRepayment) > 0)
                                    @foreach($loans->loanRepayment as $key => $repayment)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ \Illuminate\Support\Facades\Auth::user()->cooperative->currency }} {{ number_format($repayment->amount, 2, '.', ',') }}</td>
                                            <td> {{ \Carbon\Carbon::parse($repayment->date)->format('d M, Y') }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endif

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
                    <h4 class="card-title">Loan Installments</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($loans)
                                @if(count($loans->loanInstallment) > 0)
                                    @foreach($loans->loanInstallment as $key => $installment)
                                        <tr class="{{ strtotime($installment->date) < strtotime(date('Y-m-d')) ? 'bg-color-range' : ''}}">
                                            <td>{{ ++$key }}</td>
                                            <td>{{ \Illuminate\Support\Facades\Auth::user()->cooperative->currency }} {{ number_format($installment->amount, 2, '.', ',') }}</td>
                                            <td> {{ \Carbon\Carbon::parse($installment->date)->format('d M, Y') }}</td>
                                            <td>
                                                @if($installment->status == 0)
                                                    <badge class="badge badge-danger text-white">Pending</badge>
                                                @elseif($installment->status == 1)
                                                    <badge class="badge badge-success text-white">Paid</badge>
                                                @elseif($installment->status == 2)
                                                    <badge class="badge badge-warning text-white">Partial</badge>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    $('#type').on('change', function () {
        let data = JSON.parse(this.value);
        console.log(data)
        if(data) {
            $('#preview').removeClass('d-none')
        } else {
            $('#preview').addClass('d-none')
        }
        $('#type_id').val(data.id);
        $('#period').text(data.period);
        $('#interest').text(data.interest);
        $('#installments').text(data.installments);
    });
</script>
@endpush
