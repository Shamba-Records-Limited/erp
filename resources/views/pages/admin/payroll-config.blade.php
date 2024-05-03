@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                            data-toggle="collapse"
                            data-target="#payrollConfig"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="payrollConfig">
                        <span class="mdi mdi-plus">Add Payroll Config</span>
                    </button>
                    <div class="collapse @if($errors->count() > 0) show @endif" id="payrollConfig">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Payroll Config</h4>
                            </div>
                        </div>


                        <form action="{{ route('cooperative.payroll-config.add') }}" method="post">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <h6 class="mb-3">Payroll Config</h6>
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="country">Country</label>
                                    <select name="country" id="country"
                                            class=" form-control select2bs4 {{ $errors->has('address') ? ' is-invalid' : '' }}">
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}"> {{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('country'))
                                        <span class="help-block text-danger">
                                                <strong>{{ $errors->first('country')  }}</strong>
                                            </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="name">Deduction Name</label>
                                    <input type="text" name="name"
                                           class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           id="name" placeholder="PAYE" value="{{ old('name')}}"
                                    >

                                    @if ($errors->has('name'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('name')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="min_amount">Minimum Gross Pay</label>
                                    <input type="text" name="min_amount"
                                           class="form-control  {{ $errors->has('min_amount') ? ' is-invalid' : '' }}"
                                           id="min_amount" placeholder="100000"
                                           value="{{ old('min_amount')}}">

                                    @if ($errors->has('min_amount'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('min_amount')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="max_amount">Maximum Gross Pay</label>
                                    <input type="text" name="max_amount"
                                           class="form-control  {{ $errors->has('max_amount') ? ' is-invalid' : '' }}"
                                           id="max_amount" placeholder="500000"
                                           value="{{ old('max_amount')}}">

                                    @if ($errors->has('max_amount'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('max_amount')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="amount">Amount</label>
                                    <input type="text" name="amount"
                                           class="form-control  {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                           value="{{ old('amount')}}" id="amount"
                                           placeholder="50000">
                                    @if ($errors->has('amount'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('amount')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="rate">Rate (%)</label>
                                    <input type="text" name="rate"
                                           class="form-control  {{ $errors->has('rate') ? ' is-invalid' : '' }}"
                                           id="rate" placeholder="10" value="{{ old('rate')}}">
                                    @if ($errors->has('rate'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('rate')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="deduction_stage">Deduction Stage</label>
                                    <select name="deduction_stage" id="deduction_stage"
                                            class=" form-control select2bs4 {{ $errors->has('deduction_stage') ? ' is-invalid' : '' }}">
                                        <option value="{{\App\PayrollDeduction::BEFORE_PAYE_DEDUCTION}}">
                                            Before PAYE
                                        </option>
                                        <option value="{{\App\PayrollDeduction::AFTER_PAYE_PAYE_DEDUCTION}}">
                                            After
                                            PAYE
                                        </option>
                                    </select>
                                    @if ($errors->has('deduction_stage'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('deduction_stage') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="base_amount">Base Amount</label>
                                    <select name="base_amount" id="base_amount"
                                            class=" form-control select2bs4 {{ $errors->has('base_amount') ? ' is-invalid' : '' }}">
                                        <option value=""></option>
                                       @foreach(config('enums.payroll_deduction_base_value') as $k => $v)
                                           <option value="{{$k}}">{{ $v }}</option>
                                       @endforeach
                                    </select>
                                    @if ($errors->has('base_amount'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('base_amount') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">
                                        Add
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
                    <h4 class="card-title">Registered Cooperatives</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Country</th>
                                <th>Deduction Name</th>
                                <th>Deduction Stage</th>
                                <th>Range</th>
                                <th>Rate</th>
                                <th>Amount</th>
                                <th>Base Amount</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($configs as $key => $config)

                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$config->country->name }}</td>
                                    <td>{{ strtoupper($config->name) }}</td>
                                    <td>{{$config->deduction_stage == \App\PayrollDeduction::BEFORE_PAYE_DEDUCTION ? "Before PAYE" : "After PAYE" }}</td>
                                    <td>{{number_format($config->min_amount).' - '.number_format($config->max_amount)}}</td>
                                    <td>{{ ($config->rate??"0").'%' }}</td>
                                    <td>{{ (number_format($config->amount??"0")) }}</td>
                                    <td>{{ config('enums.payroll_deduction_base_value')[$config->on_gross_pay] }}</td>
                                    <td>
                                        <form method="post" action="{{ route('cooperative.payroll-config.delete', $config->id) }}">
                                            @csrf

                                            <button type="button"
                                                    class="btn btn-info btn-rounded btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#editModal_{{$config->id}}">
                                                <span class="mdi mdi-file-edit"></span>
                                            </button>

                                            <button type="submit"
                                                    class="btn btn-danger btn-rounded btn-sm"
                                            >
                                                <span class="mdi mdi-trash-can"></span>
                                            </button>
                                        </form>


                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$config->id}}"
                                             tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$config->id}}"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered"
                                                 role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="modalLabel_{{$config->id}}">
                                                            Edit {{$config->name}}</h5>
                                                        <button type="button" class="close"
                                                                data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.payroll-config.edit', $config->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <h6 class="mb-3">Payroll
                                                                        Config</h6>
                                                                </div>
                                                                <div class="form-group col-12">
                                                                    <label for="country_{{$config->id}}">Country</label>
                                                                    <select name="country"
                                                                            id="country_{{$config->id}}"
                                                                            class=" form-control select2bs4">
                                                                        @foreach($countries as $country)
                                                                            <option value="{{$country->id}}"
                                                                                    {{$country->id==$config->country_id ? 'selected' :''}}>
                                                                                {{ $country->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="name_{{$config->id}}">Deduction
                                                                        Name</label>
                                                                    <input type="text" name="name"
                                                                           class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                                           id="name_{{$config->id}}"
                                                                           placeholder="PAYE"
                                                                           value="{{ $config->name}}"
                                                                    >

                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="min_amount">Minimum
                                                                        Gross Pay</label>
                                                                    <input type="text"
                                                                           name="min_amount"
                                                                           class="form-control  {{ $errors->has('min_amount') ? ' is-invalid' : '' }}"
                                                                           id="min_amount"
                                                                           placeholder="100000"
                                                                           value="{{ $config->min_amount}}">
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="max_amount_{{$config->id}}">Maximum
                                                                        Gross Pay</label>
                                                                    <input type="text"
                                                                           name="max_amount"
                                                                           class="form-control  {{ $errors->has('max_amount') ? ' is-invalid' : '' }}"
                                                                           id="max_amount_{{$config->id}}"
                                                                           placeholder="500000"
                                                                           value="{{ $config->max_amount}}">
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="amount_{{$config->id}}">Amount</label>
                                                                    <input type="text" name="amount"
                                                                           id="amount_{{$config->id}}"
                                                                           class="form-control  {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                                                           value="{{ $config->amount}}"
                                                                           placeholder="50000">
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="rate">Rate
                                                                        (%)</label>
                                                                    <input type="text" name="rate"
                                                                           class="form-control  {{ $errors->has('rate') ? ' is-invalid' : '' }}"
                                                                           id="rate"
                                                                           placeholder="10"
                                                                           value="{{ $config->rate}}">
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="deduction_stage_{{$config->id}}">Deduction
                                                                        Stage</label>
                                                                    <select name="deduction_stage"
                                                                            id="deduction_stage_{{$config->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('deduction_stage') ? ' is-invalid' : '' }}">
                                                                        <option value="{{\App\PayrollDeduction::BEFORE_PAYE_DEDUCTION}}"
                                                                                {{\App\PayrollDeduction::BEFORE_PAYE_DEDUCTION == $config->deduction_stage ? 'selected':''}}>
                                                                            Before PAYE
                                                                        </option>
                                                                        <option value="{{\App\PayrollDeduction::AFTER_PAYE_PAYE_DEDUCTION}}"
                                                                                {{\App\PayrollDeduction::AFTER_PAYE_PAYE_DEDUCTION == $config->deduction_stage ? 'selected':''}}
                                                                        >
                                                                            After PAYE
                                                                        </option>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="base_amount_{{$config->id}}">Base Amount</label>
                                                                    <select name="base_amount" id="base_amount_{{$config->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('base_amount') ? ' is-invalid' : '' }}">
                                                                        <option value=""></option>
                                                                        @foreach(config('enums.payroll_deduction_base_value') as $k => $v)
                                                                            <option value="{{$k}}" {{ $k == $config->on_gross_pay ? 'selected' : '' }}>{{ $v }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('base_amount'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('base_amount') }}</strong>
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
                                                                    class="btn btn-primary">Save
                                                                changes
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        {{--  modal end   --}}
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
@endpush
