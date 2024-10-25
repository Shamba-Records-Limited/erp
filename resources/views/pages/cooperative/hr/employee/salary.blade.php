@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @php $user = Auth::user();@endphp;

    @if(has_right_permission(config('enums.system_modules')['HR Management']['employees'], config('enums.system_permissions')['edit']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('hr.employees.updateHasBenefits') }}" method="post">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-12">
                                    @if($salary)
                                        <h6>Employee: <b>{{ ucwords(strtolower($employee->user->first_name.' '.$employee->user->other_names)) }}</b></h6>
                                        <h6>Job Group: <b>{{ $salary->job_group }}</b></h6>
                                        <h6>Job Type: <b>{{ $employee->employmentType->employeeType->type }}</b></h6>
                                        <h6>Salary: <b> {{ $user->cooperative->currency }} {{ number_format($salary->amount, 2) }}</b></h6>

                                    @else
                                        <h6>Employee: <b>{{ ucwords(strtolower($employee->user->first_name.' '.$employee->user->other_names)) }}</b></h6>
                                        <h6>Job Group: - </h6>
                                        <h6>Job Type: <b>{{ $employee->employmentType->employeeType->type }}</b></h6>
                                        <h6>Salary: <b>Not Set</b></h6>
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="amount">Amount</label>
                                    <input type="text" name="amount" readonly
                                           class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                           id="amount" placeholder="Salary Amount"
                                           value="{{ $salary ? $salary->amount : '' }}" required>
                                    @if ($errors->has('amount'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('amount')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="job_group">Job Group</label>
                                    <input type="text" name="job_group" readonly
                                           value="{{$salary ? $salary->job_group : ''}}"
                                           class="form-control {{ $errors->has('job_group') ? ' is-invalid' : '' }}"
                                           id="job_group" placeholder="Job Group" required>
                                    @if ($errors->has('job_group'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('job_group')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="has_benefits">Has Benefits/Deductions</label>
                                    <select name="has_benefits" id="has_benefits"
                                            class=" form-control select2bs4 {{ $errors->has('has_benefits') ? ' is-invalid' : '' }}">
                                        <option value=""></option>
                                        <option value="no" {{ $salary ? (strtolower($salary->has_benefits) == 'no' ? 'selected' : '') : ''}}>
                                            No
                                        </option>
                                        <option value="yes" {{  $salary ? (strtolower($salary->has_benefits) == 'yes' ? 'selected' : '') : '' }}>
                                            Yes
                                        </option>
                                    </select>
                                    @if ($errors->has('has_benefits'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('has_benefits')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <input type="hidden" name="employeeId" value="{{ $employeeId }}"/>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit"disciplinary_types
                                            class="btn btn-primary btn-fw btn-block">Update
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if($salary && strtolower($salary->has_benefits) == 'yes')
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">

                            <button type="button"
                                    class="btn btn-info btn-fw btn-sm float-right"
                                    data-toggle="collapse"
                                    data-target="#addDeductionsAccordion"
                                    aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                    aria-controls="addEmployeeAccordion">
                                <span class="mdi mdi-plus"></span>Add Benefits/Deductions
                            </button>
                            <div class="collapse @if ($errors->count() > 0) show @endif "
                                 id="addDeductionsAccordion">
                                <form id="allow" name="allow"
                                      action="{{ route('hr.employees.setallowance') }}"
                                      method="post">
                                    @csrf
                                    <div class="form-row">
                                        <div class="form-group col-12">
                                            <h6 class="mb-3">Set Benefits/Allowance</h6>
                                        </div>
                                        <div class="form-group col-lg-3 col-md-6 col-12">
                                            <label for="amount">Amount/Percentage</label>
                                            <input type="text" name="amount"
                                                   class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                                   id="amount"
                                                   placeholder="e.g 1000 or 10% Add % for percentages"
                                                   value="{{ old('amount')}}" required>
                                            @if ($errors->has('amount'))
                                                <span class="help-block text-danger">
                                            <strong>{{ $errors->first('amount')  }}</strong>
                                        </span>
                                            @endif
                                        </div>

                                        <div class="form-group col-lg-3 col-md-6 col-12">
                                            <label for="title">Title</label>
                                            <input title="text" name="title"
                                                   value="{{ old('title')}}"
                                                   class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                   id="title" placeholder="e.g NSSF" required>
                                            @if ($errors->has('title'))
                                                <span class="help-block text-danger">
                                            <strong>{{ $errors->first('title')  }}</strong>
                                        </span>
                                            @endif
                                        </div>

                                        <div class="form-group col-lg-3 col-md-6 col-12">
                                            <label for="type">Type</label>
                                            <select name="type" id="type"
                                                    class=" form-control select2bs4 {{ $errors->has('type') ? ' is-invalid' : '' }}"
                                                    required>
                                                <option value=""> - Select -</option>
                                                @foreach(config('enums.hr_deduction_types') as $key => $benefit)
                                                    <option value="{{$key}}">{{$benefit}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('type'))
                                                <span class="help-block text-danger">
                                            <strong>{{ $errors->first('type')  }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-lg-3 col-md-6 col-12">
                                            <label for="description">Description</label>
                                            <textarea name="description"
                                                      value="{{ old('description')}}"
                                                      class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                                      id="description"
                                                      placeholder="Description"></textarea>
                                            @if ($errors->has('description'))
                                                <span class="help-block text-danger">
                                            <strong>{{ $errors->first('description')  }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                        <input type="hidden" name="employee_id"
                                               value="{{ $employeeId }}"/>

                                    </div>
                                    <hr class="mt-1 mb-1">
                                    <div class="form-row">
                                        <div class="form-group col-lg-3 col-md-6 col-12">
                                            <button type="submit"
                                                    class="btn btn-primary btn-fw btn-block">Set
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('hr.employees.show') }}"
                       class="btn btn-outline-primary btn-sm float-right"><
                        Back</a>
                    <h4 class="card-title">Employee Benefits/Deductions</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Details</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <b>Gross
                                Salary:</b> {{ $user->cooperative->currency }} {{number_format($gross,2)}}
                            <br/>
                            <b>Net
                                Salary:</b> {{ $user->cooperative->currency }} {{number_format($net,2)}}
                            <hr/>
                            @php
                                $canEdit = has_right_permission(config('enums.system_modules')['HR Management']['employees'], config('enums.system_permissions')['edit']);
                                $canDelete = has_right_permission(config('enums.system_modules')['HR Management']['employees'], config('enums.system_permissions')['delete']);
                                $currency = Auth::user()->cooperative->currency;
                            @endphp
                            @forelse($allAllowances as $key => $allowance)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ config('enums.hr_deduction_types')[$allowance->type] }}</a></td>
                                    <td>
                                        @if($allowance->percentage > 0)
                                            {{number_format($allowance->percentage,2,'.',',') }}%
                                        @else
                                            {{ $currency }} {{number_format($allowance->amount,2,'.',',') }}
                                        @endif
                                    </td>
                                    <td>{{$allowance->title}}</td>
                                    <td>
                                        @if($canEdit)
                                            <a href="{{ route('hr.employees.editbenefit', $allowance->id) }}"
                                               class="btn btn-info btn-sm">Edit</a>
                                        @endif
                                        @if($canDelete)
                                            <a href="{{ route('hr.employees.deleteallowance', $allowance->id) }}"
                                               onClick="return confirm('Sure to Delete?')"
                                               class="btn btn-danger btn-sm">Delete</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty

                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Payrolls</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Month</th>
                                <th>Basic Pay</th>
                                <th>Gross Pay</th>
                                <th>Taxable Income</th>
                                <th>P.A.Y.E</th>
                                <th>NetPay</th>
                                <th>Generated By</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $canDownload = has_right_permission(config('enums.system_modules')['HR Management']['employees'], config('enums.system_permissions')['download']);
                                $total_net = 0;
                                $total_basic = 0;
                                $total_paye = 0;
                                $total_gross = 0;
                                $total_taxable = 0;
                            @endphp
                            @forelse($payrolls as $key => $payroll)
                                @php
                                    $total_net += $payroll->net_pay;
                                    $total_basic += $payroll->basic_pay;
                                    $total_paye += $payroll->paye;
                                    $total_gross += $payroll->gross_pay;
                                    $total_taxable += $payroll->taxable_income;
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ config('enums.Months')[$payroll->period_month].', '.$payroll->period_year }}</a></td>
                                    <td>{{ $currency.' '.number_format($payroll->basic_pay)}}</td>
                                    <td>{{ $currency.' '.number_format($payroll->gross_pay) }}</td>
                                    <td>{{ $currency.' '.number_format($payroll->taxable_income) }}</td>
                                    <td>{{ $currency.' '.number_format($payroll->paye) }}</td>
                                    <td>{{ $currency.' '.number_format($payroll->net_pay) }}</td>
                                    <td>{{ $payroll->createdBy->first_name.' '. $payroll->createdBy->other_names}}</td>
                                    <td>
                                        @if($canDownload)
                                            <a href="{{ route('hr.employee.payslip.pdf', $payroll->id) }}"
                                               class="btn btn-info btn-sm">
                                                <span class="mdi mdi-download"></span>
                                                Download
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty

                            @endforelse
                            </tbody>
                            <tr>
                                <th colspan="2">Total</th>
                                <th colspan="1">{{ $currency }} {{ number_format($total_basic,2) }}</th>
                                <th colspan="1">{{ $currency }} {{ number_format($total_gross,2) }}</th>
                                <th colspan="1">{{ $currency }} {{ number_format($total_taxable,2) }}</th>
                                <th colspan="1">{{ $currency }} {{ number_format($total_paye,2) }}</th>
                                <th colspan="3">{{ $currency }} {{ number_format($total_net,2) }}</th>
                            </tr>
                            <tfoot>

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
    </script>
@endpush
