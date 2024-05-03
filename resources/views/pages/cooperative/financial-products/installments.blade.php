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

                    <h4 class="card-title">Installments for {{$farmer}}. Loan ID {{$loan_id}}</h4>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Repaid Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Account Balance</th>
                                <th>Repay Option</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_installments = 0;
                                $total_repayments = 0;
                                $canEdit = has_right_permission(config('enums.system_modules')['Financial Products']['loan_repayments'], config('enums.system_permissions')['edit']);
                            @endphp
                            @foreach($installments as $key => $i)
                                @php
                                    $total_installments += $i->amount;
                                    $total_account_balances -= $i->amount;
                                    $total_repayments += $i->repaid_amount;
                                @endphp
                                <tr class="{{ (strtotime($i->date) < strtotime(date('Y-m-d'))) && $i->status != \App\LoanInstallment::STATUS_PAID ? 'bg-color-range' : ''}}">
                                    <td>{{++$key }}</td>
                                    <td>{{ $currency.' '.number_format($i->amount) }}</td>
                                    <td>{{ $currency.' '.number_format($i->repaid_amount) }}</td>
                                    <td>{{$i->date }}</td>
                                    <td>
                                        @if($i->status == \App\LoanInstallment::STATUS_PENDING)
                                            <badge class="badge badge-dark text-white">Pending
                                            </badge>
                                        @elseif($i->status == \App\LoanInstallment::STATUS_PAID)
                                            <badge class="badge badge-success text-white">
                                                Paid
                                            </badge>
                                            via {{ config('enums')['loan_payment_options'][0][$i->source]}}
                                        @elseif($i->status == \App\LoanInstallment::STATUS_PARTIALLY_PAID)
                                            <badge class="badge badge-warning text-white">Partial
                                            </badge>
                                            via {{ config('enums')['loan_payment_options'][0][$i->source]}}
                                        @elseif($i->status == \App\LoanInstallment::STATUS_FAILED)
                                            <badge class="badge badge-danger text-white">Failed
                                            </badge>
                                        @endif
                                    </td>
                                    <td> {{ $currency.' '.number_format($total_account_balances)  }}</td>
                                    <td>
                                        @if(($i->status != \App\LoanInstallment::STATUS_PAID) && $canEdit)
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
                                                                Repay Loan #{{$i->loan_id}}</h5>
                                                            <button type="button" class="close"
                                                                    data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{route('loan.installment.repay', $i->id)}}"
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
                                                                                @if( ($total_account_balances >  0 && $k == \App\LoanInstallment::WALLET_REPAYMENT_OPTION) || $k == \App\LoanInstallment::MPESA_REPAYMENT_OPTION)
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
                                                                               max="{{$i->amount}}"
                                                                               min="{{$i->amount}}"
                                                                               class="form-control"
                                                                               value="{{$i->amount}}"
                                                                               readonly
                                                                        >
                                                                    </div>

                                                                    <div class="form-group col-12 d-none"
                                                                         id="phone_{{$i->id}}">
                                                                        <label for="phone_input_{{$i->id}}">Phone</label>
                                                                        <input type="text"
                                                                               id="phone_input_{{$i->id}}"
                                                                               name="phone"
                                                                               class="form-control"
                                                                               value="{{$phone}}">
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
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="1">Total</th>
                                <th colspan="1">{{ $currency.' '.number_format($total_installments, 2, '.',',')}}</th>
                                <th colspan="5">{{ $currency.' '.number_format($total_repayments, 2, '.',',')}}</th>
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
