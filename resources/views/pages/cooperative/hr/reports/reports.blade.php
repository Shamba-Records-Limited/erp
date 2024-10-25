@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['HR Management']['branches'], config('enums.system_permissions')['view']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="collapse  show "
                             id="generateReportAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Generate Salary Reports</h4>
                                </div>
                            </div>

                            <form action="{{ route('cooperative.hr.report.download') }}"
                                  method="get">
                                @csrf
                                <div class="form-row">

                                    <div class="form-group col-lg-3 col-md-4  col-12">
                                        <label for="report_type">Report Type</label>
                                        <select name="report_type" id="report_type" class=" form-control select2bs4
                                        {{ $errors->has('report_type') ? ' is-invalid' : '' }}"
                                                onchange="toggleEmployeeOptions()"
                                        >
                                            <option value=""></option>
                                            @foreach(config('enums.hr_reports') as $key =>$report)
                                                <option value="{{$key}}"
                                                        {{ $key == old('report_type') ? 'selected' : '' }}>
                                                    {{ $report }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('report_type'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('report_type')  }}</strong>
                                            </span>
                                        @endif

                                        @if ($errors->has('deduction_type'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('deduction_type')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-4 col-12  d-none" id="deduction_type_div">
                                        <label for="deduction_type">Deduction Type</label>
                                        <select name="deduction_type" id="deduction_type"
                                                class=" form-control select2bs4
                                        {{ $errors->has('deduction_type') ? ' is-invalid' : '' }}"
                                        >
                                            @foreach(config('enums.deduction_types') as $key => $v)
                                                <option value="{{$key}}">
                                                    {{ $v }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('deduction_type'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('deduction_type')  }}</strong>
                                            </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-3 col-md-4 col-12  d-none" id="deduction_period_div">
                                        <label for="deduction_period">Period</label>
                                        <select name="deduction_period" id="deduction_period"
                                                class=" form-control select2bs4
                                        {{ $errors->has('deduction_period') ? ' is-invalid' : '' }}"
                                        >
                                            @foreach(config('enums.deduction_report_period') as $key => $v)
                                                <option value="{{$key}}">
                                                    {{ $v }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('deduction_period'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('deduction_period')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-4 col-12 d-none"
                                         id="employeeToShow">
                                        <label for="employee">Employee</label>
                                        <select name="employee" id="employee" class=" form-control select2bs4
                                        {{ $errors->has('employee') ? ' is-invalid' : '' }}">
                                            <option></option>
                                            @foreach($employees as $emp)
                                                <option value="{{ $emp->id }}"
                                                        {{ $emp->id == old('employee') ? 'selected' : '' }}>
                                                    {{ ucwords(strtolower($emp->first_name.' '.$emp->other_names)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('employee'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('employee')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-4 col-12">
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

                                    <div class="form-group col-lg-3 col-md-4col-12 d-none" id="monthToShow">
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
                                    <div class="form-group col-lg-3 col-md-4 col-12">
                                        <button type="submit"
                                                class="btn btn-primary btn-fw btn-block">
                                            <span class="mdi mdi-download"></span>
                                            Download
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
    <div class="row">

    </div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
    <script>
      const toggleEmployeeOptions = () => {
        const reportType = $('#report_type').val()
        if (reportType != "" || reportType != null) {
          const requireEmployee = ["p9"]
          if (requireEmployee.includes(reportType)) {
            $("#employeeToShow").removeClass('d-none')
          } else {
            $("#employeeToShow").addClass('d-none')
          }

          if(reportType === "deductions"){
            $("#deduction_type_div").removeClass('d-none')
            // $("#deduction_period_div").removeClass('d-none')
          }else{
            $("#deduction_type_div").addClass('d-none')
            // $("#deduction_period_div").addClass('d-none')
          }

        } else {
          $("#employeeToShow").addClass('d-none')
        }


      }
    </script>
@endpush
