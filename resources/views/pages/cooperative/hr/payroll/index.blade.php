@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    @php
        $user = Auth::user();
        $currency = $user->cooperative->currency;
        $canDownload = has_right_permission(config('enums.system_modules')['HR Management']['employees'], config('enums.system_permissions')['download']);
        $canView = has_right_permission(config('enums.system_modules')['HR Management']['payroll'], config('enums.system_permissions')['view']);
        $canCreate = has_right_permission(config('enums.system_modules')['HR Management']['payroll'], config('enums.system_permissions')['create']);
    @endphp
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    @if($canCreate)
                        <button type="button" class="btn btn-info btn-fw btn-sm float-right mr-3"
                                data-toggle="collapse"
                                data-target="#addEmployeePayrollAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addEmployeePayrollAccordion">
                            <span class="mdi mdi-plus">Add Payroll</span>
                        </button>
                    @endif

                    <div class="collapse @if ($errors->count() > 0) show @endif "
                         id="addEmployeePayrollAccordion">
                        <form action="{{ route('hr.employees.generatepayroll') }}" method="post"
                              id="addPayroll">
                            @csrf
                            <div class="form-row">

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="employmntType">Employment Type</label>
                                    <select name="employment_type" id="employmentType" class=" form-control select2bs4
                                        {{ $errors->has('employment_type') ? ' is-invalid' : '' }}">
                                        <option value="all">All</option>
                                        @foreach($employment_types as $type)
                                            <option value="{{$type->id}}" {{$type->id == old('employment_type') ? 'selected' : '' }}>
                                                {{ ucwords(strtolower($type->type)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('employment_type'))
                                        <span class="help-block text-danger">
                                                <strong>{{ $errors->first('employment_type')  }}</strong>
                                            </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="employees">Employees</label>
                                    <select name="employees[]" multiple="multiple" id="employees"
                                            class=" form-control select2bs4 {{ $errors->has('employees') ? ' is-invalid' : '' }}">
                                        <option value="">All Employees</option>
                                        @foreach($employees as $emp)
                                            <option value="{{$emp->id}}"> {{ucwords($emp->name)}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('employee'))
                                        <span class="help-block text-danger">
                                        <strong>{{ $errors->first('employee')  }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="year">Year</label>
                                    <select name="year" id="year" class=" form-control select2bs4
                                        {{ $errors->has('year') ? ' is-invalid' : '' }}">
                                        @foreach($years as $yr)
                                            <option value="{{$yr}}"
                                                    {{ $yr == (int)\Carbon\Carbon::now()->format('Y') ||
                                                        $yr == old('year') ? 'selected' : '' }}>
                                                {{ $yr }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('year'))
                                        <span class="help-block text-danger">
                                                <strong>{{ $errors->first('year')  }}</strong>
                                            </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="month">Month</label>
                                    <select name="month" id="month" class=" form-control select2bs4
                                        {{ $errors->has('month') ? ' is-invalid' : '' }}">
                                        @foreach(config('enums.Months') as $key => $m)
                                            <option value="{{$key}}"
                                                    {{ $key == (int)\Carbon\Carbon::now()->format('m') ||
                                                        $key == old('month') ? 'selected' : '' }}>
                                                {{ $m }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('month'))
                                        <span class="help-block text-danger">
                                                <strong>{{ $errors->first('month')  }}</strong>
                                            </span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="button" class="btn btn-info btn-fw btn-block"
                                            id="submitPayrollBtn">
                                        Generate
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="collapse" id="addEmployeeAccordion">
                        <form id="allow" name="allow" action="" method="get">
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label>Employee</label>
                                    <select name="employee" id="employeeFilter"
                                            class=" form-control select2bs4 {{ $errors->has('employee') ? ' is-invalid' : '' }}">
                                        <option value="">All Employees</option>
                                        @foreach($employees as $emp)
                                            <option value="{{$emp->id}}"> {{ucwords($emp->name)}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('employee'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('employee')  }}</strong>
                                        </span>
                                    @endif
                                </div>


                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label>Period To</label>
                                    <input type="date" name="to"
                                           class="form-control {{ $errors->has('period_end') ? ' is-invalid' : '' }}"
                                           id="period_end_Filter" placeholder="Period End">
                                    @if ($errors->has('period_end'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('period_end')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">
                                        Filter
                                    </button>
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

                    @if($canCreate)
                        <button type="button" class="btn btn-info btn-fw btn-sm float-right mr-3"
                                data-toggle="collapse"
                                data-target="#filterPayrollAccordion"
                                aria-expanded="@if(
                                                    request()->employee
                                                    or request()->year
                                                    or request()->month
                                                    or request()->employment_type
                                                    or request()->department
                                                ) true @else false @endif"
                                aria-controls="filterPayrollAccordion">
                            <span class="mdi mdi-database-search">Filter</span>
                        </button>
                    @endif

                    <div class="collapse @if(
                            request()->employees
                            or request()->year
                            or request()->month
                            or request()->employment_type
                            or request()->department
                        ) show @endif "
                         id="filterPayrollAccordion">
                        <form action="{{ route('hr.employees.payroll') }}" method="get">
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="employmentTypeFilter">Employment Type</label>
                                    <select name="employment_type" id="employmentTypeFilter"
                                            class=" form-control select2bs4">
                                        <option value="all">All</option>
                                        @foreach($employment_types as $type)
                                            <option value="{{$type->id}}" {{$type->id == request()->employment_type ? 'selected' : '' }}>
                                                {{ ucwords(strtolower($type->type)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="employees">Employees</label>
                                    <select name="employees[]" multiple="multiple"
                                            id="filter_employees"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach($employees as $emp)
                                            <option value="{{$emp->id}}"
                                                    {{  request()->employees  != null ?
                                                        (in_array($emp->id,request()->employees) ?
                                                            'selected' : '') : '' }}>
                                                {{ucwords($emp->name)}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="year">Year</label>
                                    <select name="year" id="filter_year"
                                            class=" form-control select2bs4">
                                        <option></option>
                                        @foreach($years as $yr)
                                            <option value="{{$yr}}"
                                                    {{$yr == request()->year ? 'selected' : '' }}>
                                                {{ $yr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="month">Month</label>
                                    <select name="month" id="filter_month"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach(config('enums.Months') as $key => $m)
                                            <option value="{{$key}}"
                                                    {{$key == request()->month ? 'selected' : '' }}>
                                                {{ $m }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="department">Department</label>
                                    <select name="department" id="department"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach($departments as $d)
                                            <option value="{{$d->id}}"
                                                    {{$d->id == request()->month ? 'selected' : '' }}>
                                                {{ $d->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit"
                                            class="btn btn-primary btn-fw btn-block">Search
                                    </button>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <a href="{{route('hr.employees.payroll') }}"
                                       type="submit"
                                       class="btn btn-info btn-fw btn-block">
                                        Reset
                                    </a>
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
                    @if($canDownload)
                        <form action="{{ route('hr.payroll-summary.download','csv') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('hr.payroll-summary.download','xlsx') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>

                        <form action="{{ route('hr.payroll-summary.download','pdf') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-success float-right text-white">
                                <i class="mdi mdi-download"></i> PDF
                            </button>
                        </form>
                    @endif
                    <h4 class="card-title">Payrolls</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Month</th>
                                <th>Basic Pay</th>
                                <th>Taxable Income</th>
                                <th>P.A.Y.E</th>
                                <th>Net Pay</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $total_net = 0;
                                $total_basic = 0;
                                $total_paye = 0;
                                $total_taxable_income = 0;
                            @endphp
                            @forelse($payrolls as $key => $payroll)
                                @php
                                    $total_net += $payroll->net_pay;
                                    $total_basic += $payroll->basic_pay;
                                    $total_paye += $payroll->paye;
                                    $total_taxable_income += $payroll->taxable_income;
                                @endphp
                                <tr @if($canView)data-href="{{ route('hr.employees.payroll.details', $payroll->id) }} @endif">
                                    <td>{{++$key }}</td>
                                    <td>
                                        <a href="{{ route('hr.employees.details', $payroll->employee_id) }}">
                                            {{ ucwords(strtolower($payroll->name)) }}
                                        </a></td>
                                    <td>{{ $payroll->department }}</td>
                                    <td>{{ config('enums.Months')[$payroll->period_month].', '.$payroll->period_year }}</td>
                                    <td> {{$currency}} {{ number_format($payroll->basic_pay,2) }} </td>
                                    <td> {{$currency}} {{ number_format($payroll->taxable_income,2) }} </td>
                                    <td> {{$currency}} {{ number_format($payroll->paye,2) }} </td>
                                    <td> {{$currency}} {{ number_format($payroll->net_pay,2) }} </td>
                                    <td>

                                        @if($canDownload)
                                            <a href="{{ route('hr.employee.payslip.pdf', $payroll->id)}}"
                                               target="_blank"
                                               class="btn btn-sm btn-info btn-rounded">
                                                <span class="mdi mdi-printer"></span>
                                                Print Payslip
                                            </a>
                                            @endif

                                            </form>
                                    </td>
                                </tr>
                            @empty

                            @endforelse
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="4">Total</th>
                                <th colspan="1">{{$currency}} {{number_format($total_basic)}}</th>
                                <th colspan="1">{{$currency}} {{number_format($total_taxable_income)}}</th>
                                <th colspan="1">{{$currency}} {{number_format($total_paye)}}</th>
                                <th colspan="2">{{$currency}} {{number_format($total_net)}}</th>
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
      document.addEventListener("DOMContentLoaded", () => {
        const rows = document.querySelectorAll("tr[data-href]");
        rows.forEach(row => {
          row.addEventListener("click", () => {
            window.location.href = row.dataset.href
          })
        })
      })

      $(document).ready(function () {
        $('#submitPayrollBtn').on('click', function () {

          const employees = $('#employees').val();
          const year = $('#year').val();
          const month = $('#month').val();
          let empNumbers = null;
          if (year == null || year == '') {
            alert('Please select a year')
          }

          if (month == null || month == '') {
            alert('Please select a month')
          }

          if (employees == null || employees == '') {
            empNumbers = 'ALL employees'
          } else {
            empNumbers = employees.length + ' employees'
          }

          const monthNames = @json(config('enums.Months'));

          const monthName = monthNames[month]
          const message = 'Are you sure you want to generate payroll for ' + monthName + ', ' + year
              + ' for ' + empNumbers+'?'

          centerConfirmDialog(message, 'addPayroll', true)

        });
      });

    </script>
@endpush
