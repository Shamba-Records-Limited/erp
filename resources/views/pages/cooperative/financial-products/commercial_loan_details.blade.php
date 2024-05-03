@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    @php
                        $first_name = ucwords(strtolower($loanDetails->loan->farmer->user->first_name));
                        $last_name = ucwords(strtolower($loanDetails->loan->farmer->user->other_names));
                        $farmer = $first_name.' '.$last_name;
                        $loan = $loanDetails->loan;
                    @endphp
                    <h4 class="card-title">Loan Details for {{$farmer}}. Loan
                        ID {{$loanDetails->loan->id}}</h4>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>Loan Id</th>
                                <th>Applied Amount</th>
                                <th>Due Date</th>
                                <th>Original Rate</th>
                                <th>Applied Rate</th>
                                <th>Wallet Balance</th>
                                <th>Average Cash flow</th>
                                <th>Pending Payments</th>
                                <th>Qualified Limit</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = $loanDetails->loan->farmer->user->cooperative->currency;
                                $canEdit = has_right_permission(config('enums.system_modules')['Financial Products']['loan_application'], config('enums.system_permissions')['edit']);
                            @endphp
                            <tr class="{{ (strtotime($loan->date) < strtotime(date('Y-m-d'))) && $loan->status != \App\LoanInstallment::STATUS_PAID ? 'bg-color-range' : ''}}">
                                <td>
                                    <a href="{{route('cooperative.farmer-loan_installments', $loan->id)}}">{{ sprintf("%03d", $loan->id) }}</a>
                                </td>
                                <td>{{ $currency.' '.number_format($loan->amount) }}</td>
                                <td>{{$loan->due_date }}</td>
                                <td>{{$loanDetails->original_rate.'%' }}</td>
                                <td>{{$loanDetails->rate_applied.'%' }}</td>
                                <td>{{$currency.' '.number_format($loanDetails->wallet_balance) }}</td>
                                <td>{{$currency.' '.number_format($loanDetails->average_cash_flow) }}</td>
                                <td>{{$currency.' '.number_format($loanDetails->pending_payments) }}</td>
                                <td>{{$currency.' '.number_format($loanDetails->limit) }}</td>
                                <td>
                                    @if($loanDetails->supporting_document)
                                        <a href="{{route('download.files', $loanDetails->supporting_document)}}">
                                            Download File/Image
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    @if($loan->status == \App\Loan::STATUS_REJECTED)
                                        <badge class="badge badge-danger text-white">Rejected
                                        </badge>
                                    @elseif($loan->status == \App\Loan::STATUS_APPROVED)
                                        <badge class="badge badge-info text-white">Approved
                                        </badge>
                                    @elseif($loan->status == \App\Loan::STATUS_REPAID)
                                        <badge class="badge badge-success text-white">Repaid
                                        </badge>
                                    @elseif($loan->status == \App\Loan::STATUS_PENDING)
                                        <badge class="badge badge-dark text-white">Pending
                                        </badge>
                                    @elseif($loan->status == \App\Loan::STATUS_BOUGHT_OFF)
                                        <badge class="badge badge-success text-white">Bought
                                            off
                                        </badge>
                                        @php
                                            $bought = \App\Loan::select('id')->where('bought_off_loan_id', $loan->id)->first();
                                        @endphp
                                        <span>Bought By Loan ID: @if($bought)
                                                <a href="{{route('cooperative.farmer-loan_installments', $bought->id)}}">{{ sprintf("%03d", $bought->id) }}</a>
                                            @else
                                                -
                                            @endif</span>
                                    @else
                                        <badge class="badge badge-warning text-white">Partially
                                            Paid
                                        </badge>
                                    @endif
                                </td>
                                <td>
                                    @if($loan->status == \App\Loan::STATUS_PENDING && $canEdit)
                                        <a href="{{ route('admin.loan.farmer.commercial_loan_details.action', [$loan->id, \App\Loan::STATUS_APPROVED]) }}"
                                           class="btn btn-sm btn-rounded ml-3 btn-success text-white">
                                            Approve
                                        </a>

                                        <a href="{{ route('admin.loan.farmer.commercial_loan_details.action', [$loan->id, \App\Loan::STATUS_REJECTED]) }}"
                                           class="btn btn-sm btn-rounded ml-3 btn-danger text-white">
                                            Reject
                                        </a>
                                    @endif
                                </td>
                            </tr>
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
