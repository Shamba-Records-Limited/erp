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
                    <h4 class="card-title">{{$group_loan->farmer->user->first_name.' '.$group_loan->farmer->user->other_names}}
                        Group Loan # {{$group_loan->id}} Repayment History</h4>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Source</th>
                                <th>Status</th>
                                <th>Initiated By</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_amount= 0;
                            @endphp
                            @foreach($repayment_histories as $key => $history)
                                @php
                                    $total_amount += $history->amount;
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $currency.' '.number_format($history->amount) }}</td>
                                    <td>{{config('enums')['loan_payment_options'][0][$history->source] }}</td>
                                    <td>
                                        @if($history->status == \App\GroupLoanRepayment::STATUS_INITIATED)
                                            <badge class="badge badge-warning text-white">
                                                Initiated
                                            </badge>
                                        @elseif($history->status == \App\GroupLoanRepayment::STATUS_COMPLETED)
                                            <badge class="badge badge-success text-white">
                                                Completed
                                            </badge>
                                        @else
                                            <badge class="badge badge-danger text-white">
                                                Failed
                                            </badge>
                                        @endif
                                    </td>
                                    <td>{{ ucwords(strtolower($history->initiated_by->first_name.' '.$history->initiated_by->other_names)) }}</td>
                                    <td> {{ \Carbon\Carbon::parse($group_loan->created_at)->format('d F, Y') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="1">Total</th>
                                <th colspan="5">{{ $currency.' '.number_format($total_amount, 2, '.',',')}}</th>
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
