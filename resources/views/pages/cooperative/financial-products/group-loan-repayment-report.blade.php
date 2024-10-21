@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    {{--                    @if(has_right_permission(config('enums.system_modules')['Financial Products']['loan_repayments'], config('enums.system_permissions')['download']))--}}
                    {{--                        <a class="btn btn-sm btn-info float-right text-white"--}}
                    {{--                           href="{{ route('download.loan.repayments.report', 'csv') }}">--}}
                    {{--                            <i class="mdi mdi-download"></i> CSV--}}
                    {{--                        </a>--}}

                    {{--                        <a class="btn btn-sm btn-github float-right text-white"--}}
                    {{--                           href="{{ route('download.loan.repayments.report', 'xlsx') }}"--}}
                    {{--                           style="margin-right: -5px!important;">--}}
                    {{--                            <i class="mdi mdi-download"></i> Excel--}}
                    {{--                        </a>--}}
                    {{--                        <a class="btn btn-sm btn-success float-right text-white"--}}
                    {{--                           href="{{ route('download.loan.repayments.report', env('PDF_FORMAT')) }}"--}}
                    {{--                           style="margin-right: -8px!important;">--}}
                    {{--                            <i class="mdi mdi-download"></i> PDF--}}
                    {{--                        </a>--}}
                    {{--                    @endif--}}
                    <h4 class="card-title">Group Loan Repayment History</h4>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Farmer</th>
                                <th>Loan Type</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Loan Amount</th>
                                <th>Source</th>
                                <th>Status</th>
                                <th>Initiated By</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_amount= 0;
                                $total_balance= 0;
                                $total_loan_amount= 0;
                            @endphp
                            @foreach($repayments as $key => $repayment)
                                @php
                                    $total_amount += $repayment->amount;
                                    $total_balance += $repayment->group_loan->balance;
                                    $total_loan_amount += $repayment->group_loan->amount;
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ ucwords(strtolower($repayment->group_loan->farmer->user->first_name.' '.$repayment->group_loan->farmer->user->first_name)) }}</td>
                                    <td>{{ $repayment->group_loan->group_loan_summery->group_loan_type->name }}</td>
                                    <td>{{ $currency.' '.number_format($repayment->amount) }}</td>
                                    <td>{{ $currency.' '.number_format($repayment->group_loan->balance) }}</td>
                                    <td>{{ $currency.' '.number_format($repayment->group_loan->amount) }}</td>
                                    <td>{{config('enums')['loan_payment_options'][0][$repayment->source] }}</td>
                                    <td>
                                        @if($repayment->status == \App\GroupLoanRepayment::STATUS_INITIATED)
                                            <badge class="badge badge-warning text-white">
                                                Initiated
                                            </badge>
                                        @elseif($repayment->status == \App\GroupLoanRepayment::STATUS_COMPLETED)
                                            <badge class="badge badge-success text-white">
                                                Completed
                                            </badge>
                                        @else
                                            <badge class="badge badge-danger text-white">
                                                Failed
                                            </badge>
                                        @endif
                                    </td>
                                    <td>{{ ucwords(strtolower($repayment->initiated_by->first_name.' '.$repayment->initiated_by->other_names)) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th colspan="1">{{ $currency.' '.number_format($total_amount, 2, '.',',')}}</th>
                                <th colspan="1">{{ $currency.' '.number_format($total_balance, 2, '.',',')}}</th>
                                <th colspan="4">{{ $currency.' '.number_format($total_loan_amount, 2, '.',',')}}</th>
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
