@extends('layouts.app')

@push('plugin-styles')
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
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if(has_right_permission(config('enums.system_modules')['Financial Products']['loan_repayments'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('download.loan.repayments.report', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('download.loan.repayments.report', 'xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('download.loan.repayments.report', env('PDF_FORMAT')) }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Loan Repayments</h4>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>Loan ID</th>
                                <th>Loan Type</th>
                                <th>Principle Amount</th>
                                <th>Interest Amount</th>
                                <th>Interest + Amount</th>
                                <th>Installment Amount</th>
                                <th>Loan Balance</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Source</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_installments = 0;
                            @endphp
                            @foreach($loan_repayments as $key => $lr)
                                @php
                                    $total_installments += $lr->installment;
                                    $interest_amount = ($lr->amount * $lr->principle)/100;
                                    $name = ucwords(strtolower($lr->first_name.' '.$lr->other_names))
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $name }}</td>
                                    <td>{{ $lr->phone_no }}</td>
                                    <td>{{  sprintf("%03d", $lr->id) }}</td>
                                    <td>{{ $lr->type }}</td>
                                    <td>{{ number_format($lr->principle) }}</td>
                                    <td>{{ $currency.' '.number_format($interest_amount) }}</td>
                                    <td>{{ $currency.' '.number_format($interest_amount + $lr->principle) }}</td>
                                    <td>{{ $currency.' '.number_format($lr->installment) }}</td>
                                    <td>{{ $currency.' '.number_format($lr->balance) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($lr->installment_date)->format('d-m-Y') }}</td>
                                    <td>
                                        @if($lr->status == \App\LoanInstallment::STATUS_PENDING)
                                            <badge class="badge badge-danger text-white">Pending</badge>
                                        @elseif($lr->status == \App\LoanInstallment::STATUS_PAID)
                                            <badge class="badge badge-success text-white">Paid</badge>
                                        @elseif($lr->status == \App\LoanInstallment::STATUS_PARTIALLY_PAID)
                                            <badge class="badge badge-warning text-white">Partial</badge>
                                        @endif
                                    </td>
                                    <td>
                                        {{ config('enums')['loan_payment_options'][0][$lr->source]}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="8">Total</th>
                                <th colspan="4">{{ $currency.' '.number_format($total_installments, 2, '.',',')}}</th>
                            </tr>
                            </tfoot>
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
@endpush
