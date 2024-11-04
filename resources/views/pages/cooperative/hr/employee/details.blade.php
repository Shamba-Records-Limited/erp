@extends('layouts.app')

@push('plugin-styles')

@endpush

@php
    if($errors->count() > 0){
        foreach ($errors->all() as $error){
             toastError($error);
        }

    }
@endphp
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @php $names = ucwords(strtolower($employee->user->first_name.' '.$employee->user->other_names)) @endphp
                    <form action="#" method="post">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-12">
                                <h6 class="mb-3">Employee Details</h6>
                            </div>

                            <div class="form-group col-12 mb-3">
                                @if($employee->user->profile_picture)
                                    <img src="{{url('storage/'.$employee->user->profile_picture)}}"
                                         height="150px" width="150px" class="d-block"/>
                                @else
                                    <img src="{{ url('assets/images/avatar.png') }}" height="150px"
                                         width="150px"
                                         alt="profile image" class="d-block">
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="first_name">First Name:</label></b>
                                <p> {{ $employee->user->first_name }}</p>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="other_name">Other Names:</label></b>
                                <p>{{ $employee->user->other_names }}</p>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="user_name">User Name</label></b>
                                <p>{{ $employee->user->username }}</p>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="email">Email</label></b>
                                <p>{{ $employee->user->email }}</p>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="country">Country</label></b>
                                <p>{{ $employee->country->name }}</p>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="county">County</label></b>
                                <p>{{ $employee->county_of_residence }}</p>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="area_of_residence">Area of Residence</label></b>
                                <p>{{ $employee->area_of_residence }}</p>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="id_no">Id No./Passport</label></b>
                                <p>{{ $employee->id_no }}</p>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="dob">D.o.B</label></b>
                                <p>{{ $employee->dob }}
                                    ({{ \Carbon\Carbon::create($employee->dob)->diff(now())->format('%y') }}
                                    yrs.)</p>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="marital_status">Marital Status</label></b>
                                <p>{{ $employee->marital_status }}</p>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="phone_no">Phone No.</label></b>
                                <p>{{ $employee->phone_no }}</p>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="bank_account">Bank Account No. </label></b>
                                <p>{{ $employee->bankDetails->account_number}}</p>

                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="bank_account_name">Bank </label></b>
                                <p>{{ $employee->bankDetails->bank ? $employee->bankDetails->bank->name : '' }}</p>

                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="bank_account_name">Bank Branch</label></b>
                                <p>{{ $employee->bankDetails->bankBranch->name }}</p>

                            </div>


                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="bank_account_name">Bank Account Name. </label></b>
                                <p>{{ $employee->bankDetails->account_name }}</p>

                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="kra">KRA PIN</label></b>
                                <p>{{ $employee->kra }}</p>

                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="nssf">NSSF</label></b>
                                <p>{{ $employee->nssf_no }}</p>

                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="nhif">NHIF</label></b>
                                <p>{{ $employee->nhif_no }}</p>

                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="employee_number">Employee No.</label></b>
                                <p>{{ $employee->employee_no }}</p>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="employee_number">Employee Type</label></b>
                                <p>{{ $employee->employmentType->employeeType->type }}</p>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="employee_number">Position</label></b>
                                <p>{{ $employee->position->position->position }}</p>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="employee_number">Department</label></b>
                                <p>{{ $employee->department->name }}</p>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <b><label for="employee_number">Employment Status</label></b>
                                @if($employee->status == \App\CoopEmployee::STATUS_ACTIVE)

                                    <p>
                                        <badge class="badge badge-success text-white">
                                            {{ config('enums.employment_status')[$employee->status] }}
                                        </badge>
                                    </p>
                                @elseif($employee->status == \App\CoopEmployee::STATUS_DEACTIVATED)
                                    <p>
                                        <badge class="badge badge-danger text-white">
                                            {{ config('enums.employment_status')[$employee->status] }}
                                        </badge>
                                    </p>
                                @elseif($employee->status == \App\CoopEmployee::STATUS_SUSPENDED_WITH_PAY)
                                    <p>
                                        <badge class="badge badge-warning text-white">
                                            {{ config('enums.employment_status')[$employee->status] }}
                                        </badge>
                                    </p>
                                @elseif($employee->status == \App\CoopEmployee::STATUS_SUSPENSION_WITHOUT_PAY)
                                    <p>
                                        <badge class="badge badge-dark text-white">
                                            {{ config('enums.employment_status')[$employee->status] }}
                                        </badge>
                                    </p>
                                @endif

                            </div>

                        </div>
                    </form>
                    <hr class="mt-1 mb-1">
                    <a href="{{ route('hr.employees.edit.view', $employee->id) }}"
                       class="btn btn-primary btn-fw">
                        <span class="mdi mdi-account-edit"></span> Modify
                    </a>
                    <a href="{{ route('hr.employees.salary', $employee->id) }}"
                       class="btn btn-success btn-fw">
                        <span class="mdi mdi-cash"></span> Payroll Summary
                    </a>
                    <button type="button" class="btn btn-info btn-fw text-white"
                            data-toggle="modal"
                            data-target="#appraisalModal"
                    >
                        <span class="mdi mdi-chart-line"></span> Appraisal
                    </button>
                    <button type="button" class="btn btn-dark btn-fw text-white"
                            data-toggle="modal"
                            data-target="#disciplinaryModal"
                    >
                        <span class="mdi mdi-account-off"></span> Disciplinary Action
                    </button>

                    <button type="button" class="btn btn-warning btn-fw"
                            data-toggle="modal"
                            data-target="#advanceDeductionModal"
                    >
                        <span class="mdi mdi-minus-circle-outline"></span> Advance Deduction
                    </button>

                    <a href="{{ route('hr.employees.show') }}"
                       class="btn btn-danger btn-fw pull-right">
                        <span class="mdi mdi-chevron-double-left"></span> Cancel
                    </a>


                    <!-- modal appraisal -->
                    <div class="modal fade" id="appraisalModal"
                         tabindex="-1"
                         role="dialog"
                         aria-labelledby="appraisalModal"
                         aria-modal="true">
                        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable"
                             role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <p class="modal-title"><b>Appraisal Form: </b></p>
                                    <p class="modal-title pl-1"> {{$names}}
                                        ({{ $employee->employee_no }})</p>
                                    <button type="button" class="close"
                                            data-dismiss="modal"
                                            aria-label="Close">
                                                            <span aria-hidden="true"
                                                                  class="text-danger">×</span>
                                    </button>
                                </div>

                                <form method="post"
                                      action="{{ route('hr.employee.appraisal', $employee->id) }}">
                                    <div class="modal-body">
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-12">
                                                <label for="appraisal_type">Appraisal
                                                    Type</label>
                                                <select name="appraisal_type"
                                                        id="appraisal_type"
                                                        required
                                                        class=" form-control form-select {{ $errors->has('appraisal_type') ? ' is-invalid' : '' }}">

                                                    <option value=""></option>
                                                    @foreach(config('enums.appraisal_types') as $key => $type)
                                                        <option value="{{$key}}"> {{$type}}</option>
                                                    @endforeach

                                                </select>
                                                @if ($errors->has('appraisal_type'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('appraisal_type')  }}</strong>
                                                        </span>
                                                @endif
                                            </div>

                                            <div class="form-group col-12">
                                                <label for="employment_type">Employment
                                                    Type</label>
                                                <select name="employment_type"
                                                        id="employment_type"
                                                        class=" form-control form-select {{ $errors->has('employment_type') ? ' is-invalid' : '' }}">

                                                    @foreach($employment_types as $type)
                                                        <option value="{{$type->id}}"
                                                                {{ $type->id == $employee->employmentType->employeeType->id ? 'selected' : ''  }}
                                                        > {{ ucwords(strtolower($type->type)) }}</option>
                                                    @endforeach

                                                </select>
                                                @if ($errors->has('employment_type'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('employment_type')  }}</strong>
                                                        </span>
                                                @endif
                                            </div>

                                            <div class="form-group col-12">
                                                <label for="job_group">Job Group</label>
                                                <input type="text" name="job_group"
                                                       class="form-control {{ $errors->has('job_group') ? ' is-invalid' : '' }}"
                                                       id="job_group" placeholder="B3"
                                                       value="{{ $salary ? $salary->job_group : ''}}"
                                                       required>
                                                @if ($errors->has('job_group'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('job_group') }}</strong>
                                                        </span>
                                                @endif
                                            </div>

                                            <div class="form-group col-12">
                                                <label for="salary">Salary</label>
                                                <input type="text" name="salary"
                                                       class="form-control {{ $errors->has('salary') ? ' is-invalid' : '' }}"
                                                       id="salary" placeholder="10000"
                                                       value="{{ $salary ? $salary->amount : ''}}"
                                                       required>
                                                @if ($errors->has('salary'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('salary') }}</strong>
                                                        </span>
                                                @endif
                                            </div>

                                            <div class="form-group col-12">
                                                <label for="department">Department</label>
                                                <select name="department"
                                                        id="department"
                                                        class=" form-control form-select {{ $errors->has('department') ? ' is-invalid' : '' }}">

                                                    @foreach($departments as $department)
                                                        <option value="{{$department->id}}"
                                                                {{ $department->id == $employee->department_id ? 'selected' : ''  }}
                                                        > {{ ucwords(strtolower($department->name)) }}</option>
                                                    @endforeach

                                                </select>
                                                @if ($errors->has('department'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('department')  }}</strong>
                                                        </span>
                                                @endif
                                            </div>

                                            <div class="form-group col-12">
                                                <label for="position">Position</label>
                                                <select name="position"
                                                        id="position"
                                                        class=" form-control form-select {{ $errors->has('position') ? ' is-invalid' : '' }}">

                                                    @foreach($positions as $position)
                                                        <option value="{{$position->id}}"
                                                                {{ $position->id == $employee->position->position_id ? 'selected' : ''  }}
                                                        > {{ ucwords(strtolower($position->position)) }}</option>
                                                    @endforeach

                                                </select>
                                                @if ($errors->has('position'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('position')  }}</strong>
                                                        </span>
                                                @endif
                                            </div>

                                            <div class="form-group col-12">
                                                <label for="comments">Comments</label>
                                                <input type="text" name="comments"
                                                       class="form-control {{ $errors->has('comments') ? ' is-invalid' : '' }}"
                                                       id="comments"
                                                       placeholder="Reasons for the appraisal"
                                                       required>
                                                @if ($errors->has('comments'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('comments') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button"
                                                class="btn btn-secondary"
                                                data-dismiss="modal">Close
                                        </button>

                                        <button type="submit"
                                                class="btn btn-info">
                                            Submit
                                        </button>

                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- ./ modal appraisal -->

                    <!-- modal disciplinary -->
                    <div class="modal fade" id="disciplinaryModal"
                         show="true"
                         tabindex="-1"
                         role="dialog"
                         aria-labelledby="disciplinaryModal"
                         aria-modal="true">
                        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable"
                             role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <p class="modal-title"><b>Disciplinary Form: </b></p>
                                    <p class="modal-title pl-1"> {{$names}}
                                        ({{ $employee->employee_no }})</p>
                                    <button type="button" class="close"
                                            data-dismiss="modal"
                                            aria-label="Close">
                                                            <span aria-hidden="true"
                                                                  class="text-danger">×</span>
                                    </button>
                                </div>
                                <form method="POST"
                                      action="{{ route('hr.employee.disciplinary-action', $employee->id) }}">
                                    <div class="modal-body">
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-12">
                                                <label for="disciplinary_type">Effective
                                                    Date</label>
                                                <input type="date" name="effective_date"
                                                       class="form-control"
                                                       id="effective_date"
                                                       required
                                                >

                                                @if ($errors->has('effective_date'))
                                                    <span class="help-block text-danger">
                                                        <strong>{{ $errors->first('effective_date')  }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group col-12">
                                                <label for="end_date">End Date</label>
                                                <small class="text-danger"> (If termination leave
                                                    blank)</small>
                                                <input type="date" name="end_date"
                                                       class="form-control"
                                                       id="end_date"
                                                >
                                                @if ($errors->has('end_date'))
                                                    <span class="help-block text-danger">
                                                        <strong>{{ $errors->first('end_date')  }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="form-group col-12">
                                                <label for="disciplinary_types">Disciplinary Action
                                                    Type</label>
                                                <select name="disciplinary_type"
                                                        id="disciplinary_type"
                                                        required
                                                        class=" form-control form-select {{ $errors->has('disciplinary_type') ? ' is-invalid' : '' }}">

                                                    <option value=""></option>
                                                    @foreach(config('enums.disciplinary_types') as $key => $type)
                                                        <option value="{{$key}}"> {{$type}}</option>
                                                    @endforeach

                                                </select>
                                                @if ($errors->has('disciplinary_type'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('disciplinary_type')  }}</strong>
                                                        </span>
                                                @endif
                                            </div>

                                            <div class="form-group col-12">
                                                <label for="reason">Reason</label>
                                                <input type="text" name="reason"
                                                       class="form-control {{ $errors->has('reason') ? ' is-invalid' : '' }}"
                                                       id="reason"
                                                       placeholder="Reasons for the disciplinary action"
                                                       required>
                                                @if ($errors->has('reason'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('reason') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button"
                                                    class="btn btn-secondary"
                                                    data-dismiss="modal">Close
                                            </button>

                                            <button type="submit"
                                                    class="btn btn-dark">
                                                Submit
                                            </button>

                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- ./ modal disciplinary -->

                    <!-- modal appraisal -->
                    <div class="modal fade" id="advanceDeductionModal"
                         tabindex="-1"
                         role="dialog"
                         aria-labelledby="advanceDeductionModal"
                         aria-modal="true">
                        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable"
                             role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <p class="modal-title"><b>Advance Deduction Form: </b></p>
                                    <p class="modal-title pl-1"> {{$names}}
                                        ({{ $employee->employee_no }})</p>
                                    <button type="button" class="close"
                                            data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true"
                                                  class="text-danger">×</span>
                                    </button>
                                </div>

                                <form method="post"
                                      action="{{ route('hr.employees.payroll.advance.deduction') }}">
                                    <div class="modal-body">
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-12">
                                                <label for="appraisal_type">Deduction Type</label>
                                                <select name="deduction_type"
                                                        id="deduction_type"
                                                        required
                                                        class=" form-control form-select {{ $errors->has('deduction_type') ? ' is-invalid' : '' }}">

                                                    <option value=""></option>
                                                    @foreach(config('enums.advance_deduction_types') as $key => $type)
                                                        <option value="{{$key}}" {{ $key == old('deduction_type') ? 'selected' : '' }}> {{$type}}</option>
                                                    @endforeach

                                                </select>
                                                @if ($errors->has('deduction_type'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('deduction_type')  }}</strong>
                                                        </span>
                                                @endif
                                            </div>

                                            <div class="form-group col-12">
                                                <label for="deduction_amount">Deduction
                                                    Amount </label>
                                                (<small class="text-danger">Amount to be deducted
                                                    monthly</small>)
                                                <input type="text" name="deduction_amount"
                                                       class="form-control {{ $errors->has('deduction_amount') ? ' is-invalid' : '' }}"
                                                       id="deduction_amount" placeholder="10000"
                                                       value="{{ old('deduction_amount') }}"
                                                       required>
                                                @if ($errors->has('deduction_amount'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('deduction_amount') }}</strong>
                                                        </span>
                                                @endif
                                            </div>

                                            <div class="form-group col-12">
                                                <label for="principal_amount">Principal
                                                    Amount </label>
                                                (<small class="text-danger">Amount given to
                                                    employee</small>)
                                                <input type="text" name="principal_amount"
                                                       class="form-control {{ $errors->has('principal_amount') ? ' is-invalid' : '' }}"
                                                       id="principal_amount" placeholder="100000"
                                                       value="{{old('principal_amount')}}"
                                                       required>
                                                @if ($errors->has('principal_amount'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('principal_amount') }}</strong>
                                                        </span>
                                                @endif
                                            </div>

                                            <div class="form-group col-12">
                                                <label for="balance">Balance</label>
                                                (<small class="text-danger">Incase there is any
                                                    amount initially paid else leave blank</small>)
                                                <input type="text" name="balance"
                                                       class="form-control {{ $errors->has('balance') ? ' is-invalid' : '' }}"
                                                       id="balance" placeholder="100000"
                                                       value="{{old('balance')}}"
                                                >
                                                @if ($errors->has('balance'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('balance') }}</strong>
                                                        </span>
                                                @endif
                                            </div>

                                            <div class="form-group col-12">
                                                <label for="start_month">Start Month</label>
                                                (<small class="text-danger">Month to start
                                                    deduction</small>)
                                                <select name="start_month"
                                                        id="start_month"
                                                        required
                                                        class=" form-control form-select {{ $errors->has('start_month') ? ' is-invalid' : '' }}">

                                                    <option value=""></option>
                                                    @foreach(config('enums.Months') as $key => $type)
                                                        <option value="{{$key}}" {{ $key == old('start_month') ? 'selected' : '' }}> {{$type}}</option>
                                                    @endforeach

                                                </select>
                                                @if ($errors->has('start_month'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('start_month')  }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                            <div>
                                                <input type="hidden" name="employee"
                                                       value="{{$employee->id}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button"
                                                class="btn btn-secondary"
                                                data-dismiss="modal">Close
                                        </button>

                                        <button type="submit"
                                                class="btn btn-warning">
                                            Submit
                                        </button>

                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- ./ modal appraisal -->

                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title"> Appraisal History</h2>
                    <table class="table table-hover table-responsive dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Appraisal Type</th>
                            <th>Date</th>
                            <th>Old Position</th>
                            <th>New Position</th>
                            <th>Old Job Group</th>
                            <th>New Job Group</th>
                            <th>Old Department</th>
                            <th>New Department</th>
                            <th>Old Salary</th>
                            <th>New Salary</th>
                            <th>Old Employment Type</th>
                            <th>New Employment Type</th>
                            <th>Comments</th>
                            <th>Appraised By</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($appraisalHistories as $key => $appraisal)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{ config('enums.appraisal_types')[$appraisal->appraisal_type]}}</td>
                                <td>{{ \Carbon\Carbon::parse($appraisal->effective_date)->format('F d, Y') }}</td>
                                <td>{{ $appraisal->oldPosition->position->position }}</td>
                                <td>{{ $appraisal->newPosition->position->position }}</td>
                                <td>{{ $appraisal->old_job_group }}</td>
                                <td>{{ $appraisal->new_job_group }}</td>
                                <td>{{ $appraisal->oldDepartment->name }}</td>
                                <td>{{ $appraisal->newDepartment->name }}</td>
                                <td>{{ number_format($appraisal->old_salary) }}</td>
                                <td>{{ number_format($appraisal->new_salary) }}</td>
                                <td>{{ ucwords(strtolower($appraisal->oldEmploymentType->employeeType->type)) }}</td>
                                <td>{{  ucwords(strtolower($appraisal->newEmploymentType->employeeType->type)) }}</td>
                                <td>{{ $appraisal->comments }}</td>
                                <td>{{ ucwords(strtolower($appraisal->actionedBy->first_name.' '.$appraisal->actionedBy->other_names)) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title"> Disciplinary History</h2>
                    <table class="table table-hover dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Disciplinary Type</th>
                            <th>Effective Date</th>
                            <th>End Date</th>
                            <th>Days</th>
                            <th>With Pay</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th>Actioned By</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($disciplinaries as $key => $d)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{ config('enums.disciplinary_types')[$d->disciplinary_type] }}</td>
                                <td>{{ Carbon\Carbon::parse($d->effective_date)->format('F d, Y') }}</td>
                                <td> {{ $d->end_date ? Carbon\Carbon::parse($d->end_date)->format('F d, Y') : 'Termination' }}</td>
                                <td> {{ $d->days  }}</td>
                                <td> {{ $d->with_pay == \App\EmployeeDisciplinary::WITH_PAY ? 'Yes' : 'No' }}</td>
                                <td> {{ $d->status == \App\EmployeeDisciplinary::STATUS_ACTIVE ? 'Active' : 'Completed' }}</td>
                                <td>{{ $d->reason }}</td>
                                <td>{{ $d->actionedBy->first_name.' '.$d->actionedBy->other_names }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title"> Advance Deductions</h2>
                    <table class="table table-hover dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Deduction Type</th>
                            <th>Effective Period</th>
                            <th>Principal Amount</th>
                            <th>Monthly Deduction</th>
                            <th>Remaining Balance</th>
                            <th>Deduction Period</th>
                            <th>Remaining Period</th>
                            <th>Created By</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $currency = $employee->user->cooperative->currency; @endphp
                        @foreach($advance_deductions as $key => $d)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{ config('enums.advance_deduction_types')[$d->type] }}</td>
                                <td>{{ config('enums.Months')[$d->start_month].' '.$d->start_year }}</td>
                                <td> {{ $currency.' '.number_format($d->principal_amount)  }}</td>
                                <td> {{ $currency.' '.number_format($d->monthly_deductions)  }}</td>
                                <td> {{ $currency.' '.number_format($d->balance)  }}</td>
                                <td> {{ $d->deduction_period > 0 ? $d->deduction_period :  ceil($d->principal_amount/$d->monthly_deductions) }}
                                    Month(s)
                                </td>
                                <td> {{ ceil($d->balance/$d->monthly_deductions) }} Month(s)</td>
                                <td>{{ ucwords(strtolower($d->createdBy->first_name.' '.$d->createdBy->other_names)) }}</td>
                                <td>
                                    <a href="{{route('hr.employees.payroll.advance.deduction.details', $d->id)}}">
                                        Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
    <script>
      dateRangePickerFormats("date");
    </script>

@endpush
