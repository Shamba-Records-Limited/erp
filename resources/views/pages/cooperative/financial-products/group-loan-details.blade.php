@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    {{--<a class="btn btn-sm btn-info float-right text-white"
                       href="{{ route('download.loan.installment.report', [$loan_id, 'csv']) }}">
                        <i class="mdi mdi-download"></i> CSV
                    </a>

                    <a class="btn btn-sm btn-github float-right text-white"
                       href="{{ route('download.loan.installment.report', [$loan_id, 'xlsx']) }}"
                       style="margin-right: -5px!important;">
                        <i class="mdi mdi-download"></i> Excel
                    </a>
                    <a class="btn btn-sm btn-success float-right text-white"
                       href="{{ route('download.loan.installment.report', [$loan_id, env('PDF_FORMAT')]) }}"
                       style="margin-right: -8px!important;">
                        <i class="mdi mdi-download"></i> PDF
                    </a>--}}

                    <h4 class="card-title">Group Loan Details
                        for: {{$group_loan_summery->group_loan_type->name}}</h4>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>Loan Id</th>
                                <th>Farmer</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Account Balance</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_amount = 0;
                                $total_balance = 0;
                                $canEdit = has_right_permission(config('enums.system_modules')['Financial Products']['loan_repayments'], config('enums.system_permissions')['download']);
                            @endphp
                            @foreach($group_loan_details as $i)
                                @php
                                    $wallet = \App\Wallet::where('farmer_id', $i->farmer_id)->first();
                                    $total_amount += $i->amount;
                                    $total_balance += $i->balance;
                                    $total_account_balances = $wallet->current_balance + $wallet->available_balance;
                                @endphp
                                <tr>
                                    <td>{{$i->id }}</td>
                                    <td>{{ ucwords(strtolower($i->farmer->user->first_name.' '.$i->farmer->user->other_names)) }}</td>
                                    <td>{{ $currency.' '.number_format($i->amount) }}</td>
                                    <td>{{ $currency.' '.number_format($i->balance) }}</td>
                                    <td>
                                        @if($i->status == \App\GroupLoan::STATUS_DISBURSED)
                                            <badge class="badge badge-danger text-white">Disbursed
                                            </badge>
                                        @elseif($i->status == \App\GroupLoan::STATUS_PAID)
                                            <badge class="badge badge-success text-white">Paid
                                            </badge>
                                        @elseif($i->status == \App\GroupLoan::STATUS_PARTIALLY_PAID)
                                            <badge class="badge badge-warning text-white">Partially
                                                Paid
                                            </badge>
                                        @endif
                                    </td>
                                    <td> {{ $currency.' '.number_format($total_account_balances)  }}</td>
                                    <td> {{ \Carbon\Carbon::parse($i->created_at)->format('d F, Y') }}</td>
                                    <td>
                                        @if(($i->balance > 0) && $canEdit)
                                            <button data-toggle="modal"
                                                    data-target="#payModal_{{$i->id}}"
                                                    type="button"
                                                    class="btn btn-info btn-rounded btn-sm">
                                                <span class="mdi mdi-cash"> Repay</span>
                                            </button>
                                            {{--  modals edit start--}}
                                            <div class="modal fade" id="payModal_{{$i->id}}"
                                                 tabindex="-1"
                                                 role="dialog"
                                                 aria-labelledby="modalLabel_{{$i->id}}"
                                                 aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered"
                                                     role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="modalLabel_{{$i->id}}">
                                                                Repay Loan #{{$i->id}}</h5>
                                                            <button type="button" class="close"
                                                                    data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{route('admin.group.loan.repay', $i->id)}}"
                                                              method="post">
                                                            <div class="modal-body">
                                                                @csrf
                                                                <div class="form-row">
                                                                    <div class="form-group col-12">
                                                                        <label for="source_{{$i->id}}">Source</label>

                                                                        <select name="source"
                                                                                id="source_{{$i->id}}"
                                                                                class=" form-control select2bs4 {{ $errors->has('source') ? ' is-invalid' : '' }}"
                                                                                onchange="showPhoneField('phone_{{$i->id}}', 'source_{{$i->id}}', '{{\App\LoanInstallment::MPESA_REPAYMENT_OPTION}}')"
                                                                        >
                                                                            <option value="">
                                                                                --Select--
                                                                            </option>
                                                                            @foreach(config('enums')['loan_payment_options'][0] as $k=>$option)
                                                                                @if( ($total_account_balances > 0 && $k == \App\LoanInstallment::WALLET_REPAYMENT_OPTION) || $k == \App\LoanInstallment::MPESA_REPAYMENT_OPTION)
                                                                                    <option value="{{$k}}">{{$option}}</option>
                                                                                @endif
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div class="form-group col-12"
                                                                         id="amount_{{$i->id}}">
                                                                        <label for="amount_input_{{$i->id}}">Amount</label>
                                                                        <input type="number"
                                                                               name="amount"
                                                                               id="amount_input_{{$i->id}}"
                                                                               max="{{$i->balance}}"
                                                                               class="form-control"
                                                                               value="{{$i->balance}}">
                                                                    </div>

                                                                    <div class="form-group col-12 d-none"
                                                                         id="phone_{{$i->id}}">
                                                                        <label for="phone_input_{{$i->id}}">Phone</label>
                                                                        <input type="text"
                                                                               id="phone_input_{{$i->id}}"
                                                                               name="phone"
                                                                               class="form-control"
                                                                               value="{{ '254'.substr($i->farmer->phone_no, -9)}}">
                                                                    </div>

                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button"
                                                                        class="btn btn-secondary"
                                                                        data-dismiss="modal">
                                                                    Close
                                                                </button>
                                                                <button type="submit"
                                                                        class="btn btn-primary">
                                                                    Pay
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--  modal end   --}}
                                        @endif
                                        @if($i->status != \App\GroupLoan::STATUS_DISBURSED)
                                            <a class="btn btn-primary btn-sm btn-rounded"
                                               href="{{route('admin.group.loan.repayments', $i->id)}}">Repayment
                                                History</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="2">Total</th>
                                <th colspan="1">{{ $currency.' '.number_format($total_amount, 2, '.',',')}}</th>
                                <th colspan="5">{{ $currency.' '.number_format($total_balance, 2, '.',',')}}</th>
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
    <script>
      const showPhoneField = (fieldId, id, valueToAllowDisplay) => {
        const paymentOptn = $("#" + id).val()
        if (paymentOptn === valueToAllowDisplay) {
          $("#" + fieldId).removeClass('d-none')
        } else {
          $("#" + fieldId).addClass('d-none')
        }
      }
    </script>
@endpush
