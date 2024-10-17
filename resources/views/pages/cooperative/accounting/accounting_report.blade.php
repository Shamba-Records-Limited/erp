@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
    {{--  cooperive admins--}}
    @php $user = Auth::user();@endphp
    <div class="row">
        <div class="col-12">
            <h3>{{ ucwords(strtolower($fy->type)) }} Report:  <b>{{ \Illuminate\Support\Carbon::parse($fy->start_period)->format('d M Y') }} - {{ \Illuminate\Support\Carbon::parse($fy->end_period)->format('d M Y') }} </b></h3>
            <hr>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-bg-color-1">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-cash text-danger icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right text-white">Balance BF</p>
                            <div class="fluid-container">
                                <h4 class="font-weight-medium text-right mb-0">{{ $user->cooperative->currency }} {{ number_format($fy->balance_bf, 2, '.',',') }}</h4>
                            </div>
                        </div>
                    </div>
                    <p class="text-white mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i>
                        {{ ucwords(strtolower($fy->type)).' Balance BF'}}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics card-bg-color-2">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-cash text-danger icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right text-white">Balance CF</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $user->cooperative->currency }} {{ number_format($fy->balance_cf, 2, '.',',') }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-white mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i>
                        {{ ucwords(strtolower($fy->type)).' Balance CF'}}
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if(has_right_permission(config('enums.system_modules')['Accounting']['reports'], config('enums.system_permissions')['download']))
     <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Generate Reports</h4>
                    <form action="#" method="GET">

                        <input type="hidden" id="financial_period_id" value="{{$fy->id}}">
                        <div class="form-row">
                            <div class="form-group col-lg-3 col-md-6 col-sm-12">
                                <label for="report_type">Report Type</label>
                                <select id="report_type"
                                        class=" form-control select2bs4 {{ $errors->has('report_type') ? ' is-invalid' : '' }}">
                                    <option value="">--Select Type--</option>
                                    <option value="1">Trial Balance</option>
                                    <option value="2">Balance Sheet</option>
                                    <option value="3">Ledgers</option>
                                    <!-- <option value="4">Profit And Loss</option> -->
                                    <option value="5">Income Statement</option>
                                    <optgroup label="Budget">
                                        <option value="6">Budget VS Actual</option>
                                    </optgroup>
                                    <optgroup label="Account Payables/Receivables">
                                        <option value="7">Account Payables Summary</option>
                                        <option value="8">Account Receivables Summary</option>
                                    </optgroup>
                                    <optgroup label="Consolidated Reports">
                                        <option value="9">Individual Farmer</option>
                                        <option value="10">Whole Cooperative</option>
                                    </optgroup>

                                    @if ($errors->has('report_type'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('report_type')  }}</strong>
                                </span>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-sm-12 ledger_accounts d-none">
                                <label for="account_ledger">Account Ledgers</label>
                                <select id="ledger_account"
                                        class=" form-control select2bs4 {{ $errors->has('ledger_account') ? ' is-invalid' : '' }}">

                                    <option value="">---Select Ledger Account---</option>
                                    @foreach($ledgers as $acc)
                                        <option value="{{$acc->id}}">{{$acc->name}}</option>
                                    @endforeach

                                    @if ($errors->has('ledger_account'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('ledger_account')  }}</strong>
                                </span>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="date_from">Date From</label>
                                <input id="from" type="date" name="from"
                                        class="form-control {{ $errors->has('from') ? ' is-invalid' : '' }}">

                                @if ($errors->has('from'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('from')  }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="date_to">Date To</label>
                                <input id="to" type="date" name="to"
                                        class="form-control {{ $errors->has('to') ? ' is-invalid' : '' }}">

                                @if ($errors->has('to'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('to')  }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12 compare_to">
                                <label for="compare">Compare To (year)</label>
                                <input id="compare" type="year" name="compare"
                                    class="form-control {{ $errors->has('compare') ? ' is-invalid' : '' }}"
                                    placeholder="{{ date('Y', strtotime('- 1 year')) }}" />

                                @if ($errors->has('compare'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('compare') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12 select_farmer d-none">
                                <label for="farmer">Farmer</label>
                                <select id="farmer"
                                    class="form-control select2bs4 {{ $errors->has('farmer') ? ' is-invalid' : '' }}"
                                    name="farmer">
                                    <option value="">---Select Farmer---</option>

                                    @foreach($farmers as $farmer)
                                        <option value="{{$farmer->id}}">{{$farmer->name}}</option>
                                    @endforeach                        
                                </select>

                                @if ($errors->has('farmer'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('farmer')  }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-row">
                            <div id="generate_report" class="form-group col-lg-3 col-md-6 col-12 d-none">
                                <button type="submit" class="btn btn-primary btn-fw btn-block">
                                    Generate Report
                                </button>
                            </div>
                            <div id="download_pdf" class="form-group col-lg-3 col-md-6 col-12">
                                <button type="submit" name="download" value="pdf"
                                    class="btn btn-primary btn-fw btn-block">
                                    <i class="mdi mdi-cloud-download"></i>
                                    Download Pdf
                                </button>
                            </div>
                            <div id="download_excel" class="form-group col-lg-3 col-md-6 col-12">
                                <button type="submit" name="download" value="excel"
                                    class="btn btn-primary btn-fw btn-block">
                                    <i class="mdi mdi-cloud-download"></i>
                                    Download Excel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(isset($records))
        <div class="row" id="showLedgerAccountReport">
            <div class="col-md-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{  $ledger_account->name }} Legder Account</h4>
                        <form action="{{ route('cooperative.reports.print_ledger_reports', [$fy->id, $ledger_account->id]) }}"
                              method="post">
                            @csrf
                            <input type="hidden" name="from" value="{{ request('from') }}">
                            <input type="hidden" name="to" value="{{ request('to') }}">
                            <button class="btn btn-info btn-rounded btn-sm float-right"><i
                                        class="mdi mdi-download mr-2"></i>Download
                            </button>
                        </form>
                        @include('pages.cooperative.accounting.summery.ledger')
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(isset($income_expenses))
        <div class="row" id="showProfitAndLossReport">
            <div class="col-md-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Profit And Loss</h4>
                        <form action="{{ route('cooperative.reports.print_profit_loss_reports',$fy->id)}}"
                              method="post">
                            @csrf
                            <input type="hidden" name="from" value="{{ request('from') }}">
                            <input type="hidden" name="to" value="{{ request('to') }}">
                            <button class="btn btn-info btn-rounded btn-sm float-right" type="submit"><i
                                        class="mdi mdi-download mr-2"></i>Download
                            </button>
                        </form>
                        @include('pages.cooperative.accounting.summery.profit_loss')
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('plugin-scripts')

@endpush

@push('custom-scripts')
    <script>
        dateRangePickerFormats("dates")

        $("#report_type").change(() => {
            const report_type = $('#report_type').val();
            const financial_period = $('#financial_period_id').val();
            $('.ledger_accounts').addClass('d-none');
            $('.compare_to').removeClass('d-none');
            $('.select_farmer').addClass('d-none');
            $('#download_pdf').removeClass('d-none');
            $('#download_excel').removeClass('d-none');
            $('#generate_report').addClass('d-none');
            if (report_type === "1") {
                $('form').attr('action', "/cooperative/accounting/reports/trial_balance/" + financial_period);
                // window.location.href = "/cooperative/reports/trial_balance"
                $('#showLedgerAccountReport').addClass('d-none')
                $('#showProfitAndLossReport').addClass('d-none')
            } else if (report_type === "2") {
                $('form').attr('action', "/cooperative/accounting/reports/balance_sheet/" + financial_period);
                $('#showLedgerAccountReport').addClass('d-none')
                $('#showProfitAndLossReport').addClass('d-none')
            } else if (report_type === "3") {
                $('#download_pdf').addClass('d-none');
                $('#download_excel').addClass('d-none');
                $('#generate_report').removeClass('d-none');
                $('.ledger_accounts').removeClass('d-none');
                $('.compare_to').addClass('d-none');
                $('#showLedgerAccountReport').removeClass('d-none');
                $('#showProfitAndLossReport').addClass('d-none')
                $('#ledger_account').change(() => {
                    const ledger_account = $('#ledger_account').val();
                    $('form').attr('action', "/cooperative/accounting/reports/show-ledger-report/" + financial_period + "/" + ledger_account);
                });

            } else if (report_type === "4") {
                $('form').attr('action', "/cooperative/accounting/reports/show-profit-loss-report/" + financial_period);
                $('#showLedgerAccountReport').addClass('d-none')
                $('#showProfitAndLossReport').removeClass('d-none')
            } else if (report_type === "5") {
                $('form').attr('action', "/cooperative/accounting/reports/income_statement/" + financial_period);
                $('#showLedgerAccountReport').addClass('d-none')
                $('#showProfitAndLossReport').addClass('d-none')
            } else if (report_type === "6") {
                $('form').attr('action', "/cooperative/accounting/reports/budget_vs_actual/" + financial_period);
                $('#showLedgerAccountReport').addClass('d-none')
                $('#showProfitAndLossReport').addClass('d-none')
                $('.compare_to').addClass('d-none');
            } else if (report_type === "7") {
                $('form').attr('action', "/cooperative/accounting/reports/account_payables_summary/" + financial_period);
                $('#showLedgerAccountReport').addClass('d-none')
                $('#showProfitAndLossReport').addClass('d-none')
                $('.compare_to').addClass('d-none');
            } else if (report_type === "8") {
                $('form').attr('action', "/cooperative/accounting/reports/account_receivables_summary/" + financial_period);
                $('#showLedgerAccountReport').addClass('d-none')
                $('#showProfitAndLossReport').addClass('d-none')
                $('.compare_to').addClass('d-none');
            } else if (report_type === "9") {
                $('form').attr('action', "/cooperative/accounting/reports/farmer_consolidated/" + financial_period);
                $('#showLedgerAccountReport').addClass('d-none')
                $('#showProfitAndLossReport').addClass('d-none')
                $('.compare_to').addClass('d-none');
                $('.select_farmer').removeClass('d-none');
            } else if (report_type === "10") {
                $('form').attr('action', "/cooperative/accounting/reports/cooperative_consolidated/" + financial_period);
                $('#showLedgerAccountReport').addClass('d-none')
                $('#showProfitAndLossReport').addClass('d-none')
                $('.compare_to').addClass('d-none');
            }
        });
    </script>
@endpush
