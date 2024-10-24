@extends('layouts.app')

@push('plugin-styles')
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
                            <p class="mb-0 text-right">Current Wallet Balance</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">
                                    {{ auth()->user()->cooperative->currency }}{{ $wallet_balance->current_balance ?? 0 }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i>Balance</p>
                </div>
            </div>
        </div>

        @foreach($farmer_savings as $saving)
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics card-outline-warning">
                    <div class="card-body">
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                            <div class="float-left">
                                <i class="mdi mdi-cash-multiple text-warning icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">{{ $saving->saving_type->type }}</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">
                                        {{ auth()->user()->cooperative->currency }} {{ $saving->amount }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i>Maturity Date:
                            {{ \Carbon\Carbon::parse($saving->maturity_date)->format('d M Y')}}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#addSavingAccordion"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="addSavingAccordion">Saving
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="addSavingAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Save</h4>
                            </div>
                        </div>


                        <form method="post" action="{{ route('farmer.wallet.savings.add')}}">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <label for="period">Type</label>
                                    <select name="type" id="type" required
                                            class=" form-control form-select {{ $errors->has('type') ? ' is-invalid' : '' }}">
                                        <option value=""> Select Type</option>
                                        @foreach($saving_types as $type)
                                            <option value="{{ $type->id }}"> {{ $type->type }} ({{$type->period}}Ms)
                                            </option>
                                        @endforeach

                                        @if ($errors->has('type'))
                                            <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('type')  }}</strong>
                                                </span>
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <label for="amount">Amount</label>
                                    <input type="number" name="amount" min="{{ 10 }}"
                                           class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                           id="amount" placeholder="2000" value="{{ old('amount')}}" required>

                                    @if ($errors->has('amount'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('amount')  }}</strong>
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

    @if($matured_savings->count() > 0)
        <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#withDrawFromSavingAccordion"
                            aria-expanded="@if ($errors->has('saving_type')) true @else false @endif"
                            aria-controls="withDrawFromSavingAccordion">Withdraw
                    </button>
                    <div class="collapse @if ($errors->has('saving_type')) show @endif "
                         id="withDrawFromSavingAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Withdraw</h4>
                            </div>
                        </div>


                        <form method="post" action="{{ route('farmer.wallet.savings.withdraw')}}">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <label for="saving_type_withdraw">Saving Type</label>
                                    <select name="saving_type" id="saving_type_withdraw" required
                                            class=" form-control form-select {{ $errors->has('saving_type') ? ' is-invalid' : '' }}">
                                        @foreach($matured_savings as $type)
                                            <option value="{{ $type->saving_type->id }}"> {{ $type->saving_type->type }}</option>
                                        @endforeach

                                        @if ($errors->has('saving_type'))
                                            <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('saving_type')  }}</strong>
                                                </span>
                                        @endif
                                    </select>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Withdraw</button>
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
{{--                    <a class="btn btn-sm btn-info float-right text-white"--}}
{{--                       href="{{ route('download.savings.report', 'csv') }}">--}}
{{--                        <i class="mdi mdi-download"></i> CSV--}}
{{--                    </a>--}}

{{--                    <a class="btn btn-sm btn-github float-right text-white"--}}
{{--                       href="{{ route('download.savings.report', 'xlsx') }}" style="margin-right: -5px!important;">--}}
{{--                        <i class="mdi mdi-download"></i> Excel--}}
{{--                    </a>--}}
{{--                    <a class="btn btn-sm btn-success float-right text-white"--}}
{{--                       href="{{ route('download.savings.report', env('PDF_FORMAT')) }}"--}}
{{--                       style="margin-right: -8px!important;">--}}
{{--                        <i class="mdi mdi-download"></i> PDF--}}
{{--                    </a>--}}
                    <h4 class="card-title">Savings</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Saving ID</th>
                                <th>Saving Type</th>
                                <th>Amount</th>
                                <th>Interest Rate</th>
                                <th>Interest</th>
                                <th>Total Amount</th>
                                <th>Start Date</th>
                                <th>Maturity Date</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_full_amount = 0;
                                $total_interest = 0;
                                $total_loan_amount = 0;
                            @endphp
                            @foreach($savings as $key => $saving)
                                @php
                                    $interest =   ($saving->interest_rate*$saving->amount)/100;
                                    $total_amount = $interest + $saving->amount;
                                    $total_full_amount +=$total_amount;
                                   $total_interest += $interest;
                                   $total_loan_amount += $saving->amount;
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>
                                        <a href="{{route('farmer.wallet.saving.installments', $saving->id)}}">{{sprintf("%03d", $saving->id) }} </a>
                                    </td>
                                    <td>{{$saving->saving_type }}</td>
                                    <td>{{ $currency.' '.number_format($saving->amount) }}</td>
                                    <td>{{$saving->interest_rate.'%' }}</td>
                                    <td>{{ $currency.' '.number_format($interest) }}</td>
                                    <td>{{$currency.' '.number_format($total_amount) }}</td>
                                    <td>{{$saving->date_started }}</td>
                                    <td>{{$saving->maturity_date }}</td>
                                    <td>@if($saving->status == \App\SavingAccount::STATUS_ACTIVE)
                                            <div class="badge badge-success ml-2 text-white"> Active</div>
                                        @else
                                            <div class="badge badge-info ml-2 text-white"> withdrawn</div>
                                        @endif</td>
                                    <td><a href="{{route('farmer.wallet.saving.installments', $saving->id)}}">Installments</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="3">Totals</th>
                                <th colspan="2">{{ $currency.' '.number_format($total_loan_amount) }}</th>
                                <th colspan="1">{{ $currency.' '.number_format($total_interest) }}</th>
                                <th colspan="5">{{ $currency.' '.number_format($total_full_amount) }}</th>
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
