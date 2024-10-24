@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Accounting']['journal_entries'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addCowAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addCowAccordion"><span class="mdi mdi-plus"></span>Create Transaction
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addCowAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Create Transaction</h4>
                                </div>
                            </div>

                            <form action="{{ route('cooperative.accounting.create_transaction') }}" method="post">
                                @csrf
                                <div class="form-row">

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="approach">Approach</label>
                                        <select name="approach" id="approach" class=" form-control form-select"
                                                onchange="chooseApproach()">
                                            <option value="" selected>-- Select Approach ---</option>
                                            <option value="1" {{ old('approach') == "1" ? 'selected ' : ''}}>Accounting
                                                Rules
                                            </option>
                                            <option value="2" {{ old('approach') == "2" ? 'selected ' : ''}}>New
                                                Expenses
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 acc_rule {{!$errors->has('acc_rule') ? 'd-none' : '' }}">
                                        <label for="acc_rule">Accounting Rule</label>
                                        <select name="acc_rule" id="acc_rule"
                                                class=" form-control form-select {{ $errors->has('acc_rule') ? ' is-invalid' : '' }}">
                                            <option value="" selected>-- Select Ledger ---</option>
                                            @foreach($rules as $r)
                                                <option value="{{$r->id}}" {{ old('acc_rule') ==  $r->id ? 'selected ' : ''}}> {{ $r->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('acc_rule'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('acc_rule') }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 {{!$errors->has('credit_ledger_account') ? 'd-none' : '' }} credit_ledger_account">
                                        <label for="credit_ledger_account">Credit Ledger Account</label>
                                        <select name="credit_ledger_account" id="credit_ledger_account"
                                                class=" form-control form-select {{ $errors->has('credit_ledger_account') ? ' is-invalid' : '' }}">
                                            <option value="" selected>-- Select Ledger ---</option>
                                            @foreach($ledgers as $l)
                                                <option value="{{$l->id}}" {{ old('credit_ledger_account') ==  $l->id ? 'selected ' : ''}}> {{ $l->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('credit_ledger_account'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('credit_ledger_account')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 debit_ledger_account {{!$errors->has('debit_ledger_account') ? 'd-none' : '' }}">
                                        <label for="debit_ledger_account">Debit Ledger Account</label>
                                        <select name="debit_ledger_account" id="debit_ledger_account"
                                                class=" form-control form-select {{ $errors->has('debit_ledger_account') ? ' is-invalid' : '' }}">
                                            <option value="" selected>-- Select Ledger ---</option>
                                            @foreach($ledgers as $l)
                                                <option value="{{$l->id}}" {{ old('debit_ledger_account') ==  $l->id ? 'selected ' : ''}}> {{ $l->name }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('debit_ledger_account'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('debit_ledger_account')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="amount">Amount</label>
                                        <input type="text" name="amount"
                                               class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                               id="amount" placeholder="2000" value="{{ old('amount')}}" required>

                                        @if ($errors->has('amount'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('amount')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="ref_no">Reference Number</label>
                                        <input type="text" name="ref_no"
                                               class="form-control {{ $errors->has('ref_no') ? ' is-invalid' : '' }}"
                                               id="ref_no" placeholder="QWTG56RFST" value="{{ old('ref_no')}}">

                                        @if ($errors->has('ref_no'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('ref_no')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="description">Comments</label>
                                        <input type="text" name="description"
                                               class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                               id="description" placeholder="ABC" value="{{ old('description')}}"
                                               required>

                                        @if ($errors->has('description'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('description')  }}</strong>
                                </span>
                                        @endif
                                    </div>


                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <button type="submit" class="btn btn-primary btn-fw btn-block">Add</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if(has_right_permission(config('enums.system_modules')['Accounting']['journal_entries'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('cooperative.transactionsentry.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('cooperative.transactionsentry.download','xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('cooperative.transactionsentry.download', 'pdf') }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Accounting Transactions</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Reference Number</th>
                                <th>Ledger</th>
                                <th>Type</th>
                                <th>Credit</th>
                                <th>Debit</th>
                                <th>Date</th>
                                <th>Particulars</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $currency =Auth::user()->cooperative->currency @endphp
                            @foreach($transactions as $key => $trx)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$trx->ref_no }}</td>
                                    <td>{{$trx->ledger }}</td>
                                    <td>{{ ucwords($trx->ledger_type) }} {{ $trx->account_type}}</td>
                                    <td>{{ $trx->credit ? $currency .' '.number_format($trx->credit, 2, '.', ',') : '-'  }}</td>
                                    <td> {{ $trx->debit ? $currency .' '.number_format($trx->debit, 2, '.', ',') : '-'  }}</td>
                                    <td>{{ \Carbon\Carbon::parse($trx->date)->format('d M, Y') }}</td>
                                    <td>{{ $trx->particulars }}</td>
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
@endpush

@push('custom-scripts')
    <script>
        function chooseApproach() {
            const approach = $("#approach").val()
            if (approach === "1") {
                $('.acc_rule').removeClass('d-none')
                $('.credit_ledger_account').addClass('d-none')
                $('.debit_ledger_account').addClass('d-none')
            } else if (approach === "2") {
                $('.debit_ledger_account').removeClass('d-none')
                $('.credit_ledger_account').removeClass('d-none')
                $('.acc_rule').addClass('d-none')

            } else {
                $('.debit_ledger_account').addClass('d-none')
                $('.credit_ledger_account').addClass('d-none')
                $('.acc_rule').addClass('d-none')
            }
        }
    </script>
@endpush
