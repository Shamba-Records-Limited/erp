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
    @if(has_right_permission(config('enums.system_modules')['Financial Products']['loan_application'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                                data-toggle="collapse"
                                data-target="#requestLoanAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="requestLoanAccordion">Request Loan
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif "
                             id="requestLoanAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Request Loan for Farmer</h4>
                                </div>
                            </div>


                            <form method="post" action="{{ route('admin.loan.request')}}"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="farmer">Farmer</label>
                                        <input name="farmer_id" id="farmer_id" hidden/>
                                        <input name="wallet_id" id="wallet_id" hidden/>
                                        <select name="farmer" id="farmer" required
                                                class=" form-control form-select {{ $errors->has('type') ? ' is-invalid' : '' }}">
                                            <option value=""> Select Farmer</option>
                                            @foreach($farmers as $farmer)
                                                <option value="{{$farmer}}"> {{ $farmer->user->first_name.' '.$farmer->user->other_names }}</option>
                                            @endforeach

                                            @if ($errors->has('type'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('type')  }}</strong>
                                                </span>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="farm_tools">Farm Tools</label>
                                        <select name="farm_tools" id="farm_tools"
                                                class=" form-control form-select {{ $errors->has('farm_tools') ? ' is-invalid' : '' }}">
                                            <option value="">--Select--</option>
                                            @foreach(config('enums')['farm_tools'][0] as $k=>$tool)
                                                <option value="{{$k}}">{{$tool}}</option>
                                            @endforeach
                                            @if ($errors->has('farm_tools'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('farm_tools')  }}</strong>
                                                </span>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="amount">Amount</label>
                                        <input type="number" name="amount"
                                               class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                               id="amount" placeholder="2000"
                                               value="{{ old('amount')}}" required>


                                        <span class="help-block text-danger" id="show-limit">
                                        <strong>{{ $errors->first('amount')  }}</strong>
                                    </span>
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="period">Type</label>
                                        <input id="type_id" name="type_id" hidden/>
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

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="mode_of_repayment">Repayment Mode</label>
                                        <select name="mode_of_repayment" id="mode_of_repayment"
                                                class=" form-control form-select {{ $errors->has('mode_of_repayment') ? ' is-invalid' : '' }}">
                                            <option value="">--Select Mode of Payment--</option>
                                            <option value="1">One Off Auto Deduction</option>
                                            <option value="2">Monthly Deductions</option>
                                            @if ($errors->has('mode_of_repayment'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('mode_of_repayment')  }}</strong>
                                                </span>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="purpose">Purpose</label>
                                        <input type="text" name="purpose"
                                               class="form-control  {{ $errors->has('purpose') ? ' is-invalid' : '' }}"
                                               value="{{ old('purpose')}}" id="purpose"
                                               placeholder="Personal"
                                               required>
                                        @if ($errors->has('purpose'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('purpose')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">

                                        <label for="mainImage">Supporting Documents <small>(optional)</small></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file"
                                                       class="custom-file-input @error('image') is-invalid @enderror"
                                                       id="supporting_document"
                                                       name="supporting_document"
                                                       value="{{ old('supporting_document') }}">
                                                <label class="custom-file-label"
                                                       for="supporting_document">Document</label>

                                                @if ($errors->has('supporting_document'))
                                                    <span class="help-block text-danger">
                                                        <strong>{{ $errors->first('supporting_document')}}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-6">
                                        <button type="button" id="preview-btn"
                                                class="btn btn-info btn-fw btn-block">
                                            Preview
                                        </button>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-6">
                                        <button type="submit"
                                                class="btn btn-primary btn-fw btn-block">Request
                                        </button>
                                    </div>
                                </div>
                                <div id="preview" class="table-responsive d-none mt-3">
                                    <h3>Total Amount: <span id="payable"></span></h3>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Interest</th>
                                            <td><span id="interest"></span>%</td>
                                            <th>Period</th>
                                            <td><span id="period"></span> months</td>
                                        </tr>
                                        <tr>
                                            <th>Due Date</th>
                                            <td><span id="due"></span></td>
                                            <th>Installments</th>
                                            <td><span id="installments"></span></td>
                                        </tr>
                                    </table>
                                    <!-- generated table -->
                                    <div id="generated-table">
                                    </div>
                                </div>
                                @include('pages.cooperative.financial-products.farmer-limit-break-down')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(has_right_permission(config('enums.system_modules')['Financial Products']['loan_application'], config('enums.system_permissions')['view']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                                data-toggle="collapse"
                                data-target="#filterLoanAccordion"
                                aria-expanded="@if (request()->get('farmer') != null || request()->get('status') != null) true @else false @endif"
                                aria-controls="filterLoanAccordion">Filter Loans
                        </button>
                        <div class="collapse @if (request()->get('farmer') != null || request()->get('status') != null) show @endif"
                             id="filterLoanAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Filter Loans</h4>
                                </div>
                            </div>


                            <form method="get" action="{{ route('cooperative.loaned-farmers')}}">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="filterFarmer">Farmer</label>
                                        <select name="farmer" id="filterFarmer"
                                                class=" form-control form-select">
                                            <option value=""> --Select--</option>
                                            @foreach($farmers as $farmer)
                                                <option value="{{$farmer->id}}" {{ request()->get('farmer') == $farmer->id ? 'selected' : '' }}> {{ $farmer->user->first_name.' '.$farmer->user->other_names }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="status">Status</label>
                                        <select name="status" id="status"
                                                class=" form-control form-select">
                                            <option value="">--Select--</option>
                                            @foreach(config('enums')['loan_status'][0] as $k=>$status)
                                                <option value="{{$k}}" {{ request()->get('status') == $k ? 'selected' : '' }}>{{$status}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-6">
                                        <button type="submit"
                                                class="btn btn-primary btn-fw btn-block">Filter
                                        </button>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-6">
                                        <a href="{{route('cooperative.loaned-farmers')}}"
                                           class="btn btn-info btn-fw btn-block">
                                            Reset Filters
                                        </a>
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
                    @if(has_right_permission(config('enums.system_modules')['Financial Products']['loan_application'], config('enums.system_permissions')['create']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('download.loan.report', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('download.loan.report', 'xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('download.loan.report', 'PDF') }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Loans</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Loan ID</th>
                                <th>Farmer</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_amount = 0;
                                $total_balance = 0;
                            @endphp
                            @foreach($loans as $key => $loan)
                                @php
                                    $total_amount += $loan->amount;
                                    $total_balance += $loan->balance;
                                    $loanId = $loan->id;
                                @endphp
                                <tr class="{{ (strtotime($loan->due_date) < strtotime(date('Y-m-d')) && ($loan->status == \App\Loan::STATUS_PARTIAL_REPAYMENT || $loan->status == \App\Loan::STATUS_APPROVED)) ? 'bg-color-range' : ''}}">
                                    <td>{{++$key }}</td>
                                    <td>
                                        <a href="{{route('cooperative.farmer-loan_installments', $loan->id)}}">{{ sprintf("%03d", $loan->id) }}</a>
                                    </td>
                                    <td>{{ucwords(strtolower($loan->first_name .' '.$loan->other_names)) }}</td>
                                    <td>{{ $currency.' '.number_format($loan->amount) }}</td>
                                    <td>{{ $currency.' '.number_format($loan->balance) }}</td>
                                    <td>{{ $loan->type }}</td>
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
                                    <td>{{$loan->due_date }}</td>
                                    <td>
                                        @if($loan->status != \App\Loan::STATUS_PENDING)
                                            <a class="mr-3"
                                               href="{{route('cooperative.farmer-loan_installments', $loan->id)}}">Installments</a>
                                        @endif

                                    </td>
                                    <td>
                                        <a href="{{route('admin.loan.farmer.commercial_loan_details', $loan->id)}}">Details</a>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th colspan="1">{{$currency.' '.$total_amount}}</th>
                                <th colspan="6">{{$currency.' '.$total_balance}}</th>
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
      //create table preview
      $('#preview-btn').on('click', function () {
        const modeOfPayment = $('#mode_of_repayment').val();

        if (modeOfPayment === '') {
          alert('Please select mode of payment')
          return;
        }
        $('#payable').text('')
        //show table
        let data = JSON.parse($('#type').val());
        if (data) {
          $('#preview').removeClass('d-none')
        } else {
          $('#preview').addClass('d-none')
        }
        let today = new Date();
        let date = today.setMonth(today.getMonth() + 6)
        if (modeOfPayment === "1") {
          data.installments = 1;
        }

        $('#type_id').val(data.id);
        $('#period').text(data.period);
        $('#due').text(new Date(date).toDateString());
        $('#interest').text(data.interest);
        $('#installments').text(data.installments);
        let amount = $('#amount').val();
        let payable = amount * ((100 + data.interest) / 100)
        $('#payable').append(numberWithCommas(payable.toFixed(2)));
        calculate_installments(data)
      });

      $('#type').on('change', function () {
        let data = JSON.parse(this.value);
        if (data) {
          $('#preview').removeClass('d-none')
        } else {
          $('#preview').addClass('d-none')
        }
        let today = new Date();
        let date = today.setMonth(today.getMonth() + data.period)

        $('#type_id').val(data.id);
        $('#period').text(data.period);
        $('#due').text(new Date(date).toDateString());
        $('#interest').text(data.interest);
        $('#installments').text(data.installments);
        let amount = $('#amount').val();
        let payable = amount * ((100 + data.interest) / 100)
        $('#payable').text(numberWithCommas(payable.toFixed(2)));
      });
      //get farmer loan limit
      $('#farm_tools').on('change', function () {
        $("#show-limit").text('')
        $("#limit-calculation-summery").removeClass('d-none')
        const farmTools = this.value
        const farmerRawString = $("#farmer").val()

        if (farmTools === "" || farmerRawString === "") {
          $("#limit-calculation-summery").addClass('d-none')
          alert("Please select a farmer and Farm Tools")
        } else {
          let farmer = JSON.parse(farmerRawString)
          let farmerId = farmer.id;
          let url = '{{route('admin.loan.farmer.limit', [":farmer_id", ":has_farm_tools"]) }}'
          url = url.replace(':farmer_id', farmerId);
          url = url.replace(':has_farm_tools', farmTools)

          axios.post(url).then(res => {
            const limit = res.data.limit
            $('#farmer_id').val(farmer.id);
            $('#wallet_id').val(farmer.wallet.id);
            $("#amount").attr("max", limit);
            const htmlCode = `<stron> Loan Limit ${numberWithCommas(limit)} </strong>`
            $("#show-limit").append(htmlCode);
            populate_limit_break_down(res.data)
            $("#limit-calculation-summery").removeClass('d-none')
          }).catch((err) => {
            console.log(err)
            $("#limit-calculation-summery").addClass('d-none')
            const htmlCode = `<stron> Loan Limit 0 </strong>`
            $("#show-limit").append(htmlCode);
          })
        }

      });

      const populate_limit_break_down = (data) => {
        const currency = '{{$currency}}'

        $('#wallet_balance').text(currency + ' ' + numberWithCommas(data.wallet_balance))
        $('#cash_flow').text(currency + ' ' + numberWithCommas(data.average_cash_flow))
        $('#pending_payments').text(currency + ' ' + numberWithCommas(data.pending_payments))
        $('#limit_rate').text(data.original_rate)
        $('#final_rate').text(data.rate)
        $('#loan_limit').text(currency + ' ' + numberWithCommas(data.limit))

        if (data.loan_history === "Good") {
          $('#loan_history').text("Increased Rate by 1%")
        } else if (data.loan_history === "Bad") {
          $('#loan_history').text("Decreased Rate by 2%")
        } else {
          $('#loan_history').text("No Loan History")
        }

        if (data.farm_size && data.farm_size > 0) {
          $('#has_farm').text("Increased Rate by 1%")
        } else {
          $('#has_farm').text("No Farm/Land")
        }

        if (data.has_farm_tools) {
          $('#f_tools').text("Increased Rate by 1%")
        } else {
          $('#f_tools').text("No Farm Tools")
        }

        if (data.has_livestock) {
          $('#livestock').text("Increased Rate by 1%")
        } else {
          $('#livestock').text("No Livestock")
        }

      }

      //function to calculate installments
      function calculate_installments(data) {
        //calculate installments
        let amount = $('#amount').val();
        let payable = amount * ((100 + data.interest) / 100)
        //calculate installment amt
        let installment_no = data.installments
        let initial_installment_no = data.installments
        let installment_period = data.period

        let divided = Math.floor(payable / initial_installment_no);
        let remainder = payable % initial_installment_no;

        //loop
        // get the div element with the specified id
        var div = document.getElementById("generated-table");

        if (div.hasChildNodes() > 0) {
          div.innerHTML = "";
        }

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
        let id = 1;

        while (installment_no > 0) {
          let installment_amount = 0;
          if (installment_no == initial_installment_no) {
            installment_amount = divided + remainder;
          } else {
            installment_amount = divided;
          }
          let today = new Date();
          const inst_period = installment_no ?? installment_period;
          let dueDate = today.setMonth(Number(today.getMonth()) + Number(inst_period))
          installment_no--;
          dueDate = new Date(dueDate).toDateString()
          // Create a new row
          const row = document.createElement("tr");

          // Create cells for each piece of data
          const idCell = document.createElement("td");
          idCell.textContent = id;

          const amountCell = document.createElement("td");
          amountCell.textContent = numberWithCommas(installment_amount.toFixed(0));

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

      const numberWithCommas = (x) => {
        return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
      }
    </script>
@endpush
