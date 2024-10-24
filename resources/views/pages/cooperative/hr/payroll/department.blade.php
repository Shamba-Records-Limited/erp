@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    @php
        $user = Auth::user();
        $currency = $user->cooperative->currency;
        $canDownload = has_right_permission(config('enums.system_modules')['HR Management']['employees'], config('enums.system_permissions')['download']);
    @endphp

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">


                        <button type="button" class="btn btn-info btn-fw btn-sm float-right mr-3"
                                data-toggle="collapse"
                                data-target="#filterPayrollAccordion"
                                aria-expanded="@if(
                                                   request()->year
                                                    or request()->month
                                                    or request()->department
                                                ) true @else false @endif"
                                aria-controls="filterPayrollAccordion">
                            <span class="mdi mdi-database-search">Filter</span>
                        </button>


                    <div class="collapse @if(
                            request()->year
                            or request()->month
                            or request()->department
                        ) show @endif "
                         id="filterPayrollAccordion">
                        <form action="{{ route('hr.employees.payroll.department') }}" method="get">
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="year">Year</label>
                                    <select name="year" id="filter_year"
                                            class=" form-control form-select">
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
                                            class=" form-control form-select">
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
                                            class=" form-control form-select">
                                        <option value=""></option>
                                        @foreach($departments as $d)
                                            <option value="{{$d->id}}"
                                                    {{$d->id == request()->department ? 'selected' : '' }}>
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
                                    <a href="{{route('hr.employees.payroll.department') }}"
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
                        <form action="{{ route('hr.employees.payroll.department.download','csv') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('hr.employees.payroll.department.download','xlsx') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>

                        <form action="{{ route('hr.employees.payroll.department.download','pdf') }}"
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
                                <th>Department</th>
                                <th>Month</th>
                                <th>Basic Pay</th>
                                <th>Taxable Income</th>
                                <th>P.A.Y.E</th>
                                <th>Net Pay</th>
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
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $payroll->department }}</td>
                                    <td>{{ config('enums.Months')[$payroll->period_month].', '.$payroll->period_year }}</td>
                                    <td> {{$currency}} {{ number_format($payroll->basic_pay,2) }} </td>
                                    <td> {{$currency}} {{ number_format($payroll->taxable_income,2) }} </td>
                                    <td> {{$currency}} {{ number_format($payroll->paye,2) }} </td>
                                    <td> {{$currency}} {{ number_format($payroll->net_pay,2) }} </td>
                                </tr>
                            @empty

                            @endforelse
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th colspan="1">{{$currency}} {{number_format($total_basic)}}</th>
                                <th colspan="1">{{$currency}} {{number_format($total_taxable_income)}}</th>
                                <th colspan="1">{{$currency}} {{number_format($total_paye)}}</th>
                                <th colspan="1">{{$currency}} {{number_format($total_net)}}</th>
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
