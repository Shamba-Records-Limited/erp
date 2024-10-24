@extends('layouts.app')

@push('plugin-styles')
    {{--    <link rel="stylesheet" href="{{ asset('/assets/plugins/plugin.css') }}" type="text/css">--}}
    <style>
        .disp {
            display: none;
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
                            <p class="mb-0 text-right">Current Loan</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $loan_balances }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i>My Current Loan Balance </p>
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
                            <p class="mb-0 text-right">Total Loans</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $loan_totals }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i>My Total Loans Borrowed </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#requestLoanAccordion" aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="requestLoanAccordion">Request Loan
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="requestLoanAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Request Loan</h4>
                            </div>
                        </div>


                        <form method="post" action="{{ route('farmer.wallet.loans.request')}}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <label for="amount">Amount</label>
                                    <input type="number" name="amount" max="{{ $loan_limit->limit ?? 0 }}"
                                           class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                           id="amount" placeholder="2000" value="{{ old('amount')}}" required>

                                    @if ($errors->has('amount'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('amount')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <label for="period">Type</label>
                                    <select name="type" id="type" required
                                            class=" form-control form-select {{ $errors->has('type') ? ' is-invalid' : '' }}">
                                        <option value=""> Select Type</option>
                                        @foreach($loan_configs as $config)
                                            <option value="{{$config}}"> {{ $config->type }}</option>
                                        @endforeach

                                        @if ($errors->has('type'))
                                            <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('type')  }}</strong>
                                                </span>
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <label for="mode_of_repayment">Repayment Mode</label>
                                    <select name="mode_of_repayment" id="mode_of_repayment"
                                            class=" form-control form-select {{ $errors->has('mode_of_repayment') ? ' is-invalid' : '' }}">
                                        <option value="1">One Off Auto Deduction</option>
                                        <option value="2">Monthly Deductions</option>
                                        @if ($errors->has('mode_of_repayment'))
                                            <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('mode_of_repayment')  }}</strong>
                                                </span>
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <label for="purpose">Purpose</label>
                                    <input type="text" name="purpose"
                                           class="form-control  {{ $errors->has('purpose') ? ' is-invalid' : '' }}"
                                           value="{{ old('purpose')}}" id="purpose" placeholder="Personal"
                                           required>
                                    @if ($errors->has('purpose'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('purpose')  }}</strong>
                                </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-6">
                                    <button type="button" id="preview-btn" class="btn btn-info btn-fw btn-block">Preview</button>
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-6">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Request</button>
                                </div>
                            </div>
                            <div id="preview" class="table-responsive d-none">
                                <h3>Total Amount: <span id="payable"></span>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Interest</th>
                                            <td><span id="interest"></span>%</td>
                                            <th>Period</th>
                                            <td><span id="period"></span> months</td>
                                        </tr>
                                        <tr>
                                            <th>Due Date</td>
                                            <td><span id="due"></span></td>
                                            <th>Installments</th>
                                            <td><span id="installments"></span> monthly</td>
                                        </tr>
                                    </table>
                                    <!-- generated table -->
                                    <div id="generated-table">
                                    </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    {{--<button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#repayLoanAccordion" aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="repayLoanAccordion">Repay Loan
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="repayLoanAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Repay Loan</h4>
                            </div>
                        </div>


                        <form action="#" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-4 col-md-6 col-12">
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

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-4 col-md-6 col-12">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Repay</button>
                                </div>
                            </div>
                        </form>
                    </div>--}}
                    <h4 class="card-title">My Loans</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Due Date</th>
                                <th>Interest</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($loans as $key => $loan)
                                <tr data-href="{{ route('farmer.wallet.loans.details', $loan->id) }}">
                                    <td>{{ ++$key }}</td>
                                    <td>{{ \Illuminate\Support\Facades\Auth::user()->cooperative->currency }} {{ number_format($loan->amount, 2, '.', ',') }}</td>
                                    <td>{{ \Illuminate\Support\Facades\Auth::user()->cooperative->currency }} {{ number_format($loan->balance, 2, '.', ',') }}</td>
                                    <td> {{ \Carbon\Carbon::parse($loan->date)->format('d M, Y') }}</td>
                                    <td> {{ $loan->interest }}</td>
                                    <td>
                                        @if($loan->status == 2)
                                            {{ 'Repaid' }}
                                        @elseif($loan->status == 1)
                                            {{ 'Approved'}}
                                        @elseif($loan->status == 0)
                                            {{ 'Rejected'}}
                                        @endif
                                    </td>
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
        //create table preview
        $('#preview-btn').on('click', function () {
            //show table
            let data = JSON.parse($('#type').val());
            console.log(data)
            if(data) {
                console.log('in')
                $('#preview').removeClass('d-none')
            } else {
                $('#preview').addClass('d-none')
            }
            let today = new Date();
            let date = today.setMonth(today.getMonth()+6)

            $('#type_id').val(data.id);
            $('#period').text(data.period);
            $('#due').text(new Date(date).toDateString());
            $('#interest').text(data.interest);
            $('#installments').text(data.installments);
            let amount = $('#amount').val();
            let payable = amount*((100+data.interest)/100)
            $('#payable').text(payable);
            calculate_installments(data)
        });

        $('#type').on('change', function () {
            let data = JSON.parse(this.value);
            console.log(data)
            if(data) {
                console.log('in')
                $('#preview').removeClass('d-none')
            } else {
                $('#preview').addClass('d-none')
            }
            let today = new Date();
            let date = today.setMonth(today.getMonth()+6)

            $('#type_id').val(data.id);
            $('#period').text(data.period);
            $('#due').text(new Date(date).toDateString());
            $('#interest').text(data.interest);
            $('#installments').text(data.installments);
            let amount = $('#amount').val();
            let payable = amount*((100+data.interest)/100)
            $('#payable').text(payable);
        });
        //function to calculate installments
        function calculate_installments(data) {
            //calculate installments
            let amount = $('#amount').val();
            let payable = amount*((100+data.interest)/100)
            //calculate installment amt
            let installment_no = data.installments
            let initial_installment_no = data.installments
            let installment_period = data.period

            let divided =  Math.floor(payable/initial_installment_no);
            let remainder = payable%initial_installment_no;

            //loop
            // get the div element with the specified id
            var div = document.getElementById("generated-table");

            // create a table element and add class table
            var table = document.createElement("table");

            //add class table
            table.classList.add('table', 'table-hover', 'table-bordered');
            //add heading row
            const row = document.createElement("tr");

            // Create cells for each piece of data
            const idCell = document.createElement("th");
            idCell.textContent = '#';

            const amtCell = document.createElement("th");
            amtCell.textContent = 'Installment Amount';

            const dueDateCell = document.createElement("th");
            dueDateCell.textContent = 'Due Date';
            // Add the cells to the row
            row.appendChild(idCell);
            row.appendChild(amtCell);
            row.appendChild(dueDateCell);
            // Add the row to the table
            table.appendChild(row);
            //decleare and iniitialize id
            let id = 0;

            while(installment_no > 0) {
                let installment_amount = 0;
                if(installment_no == initial_installment_no){
                    installment_amount = divided+remainder;
                }
                else {
                    installment_amount = divided;
                }
                let today = new Date();
                inst_period = installment_no ?? installment_period;
                let dueDate = today.setMonth(today.getMonth()+inst_period)
                installment_no--;
                dueDate = new Date(dueDate).toDateString()

                // Create a new row
                const row = document.createElement("tr");

                // Create cells for each piece of data
                const idCell = document.createElement("td");
                idCell.textContent = id;

                const amountCell = document.createElement("td");
                amountCell.textContent = installment_amount;

                const dueDateCell = document.createElement("td");
                dueDateCell.textContent = dueDate;

                // Add the cells to the row
                row.appendChild(idCell);
                row.appendChild(amountCell);
                row.appendChild(dueDateCell);

                // Add the row to the table
                table.appendChild(row);
                //increment id
                id++;
                div.appendChild(table);
            }
        }
        document.addEventListener("DOMContentLoaded", () => {
            const rows = document.querySelectorAll("tr[data-href]");
            rows.forEach(row => {
                row.addEventListener("click", () => {
                    window.location.href = row.dataset.href
                })
            })
        })
    </script>
@endpush
