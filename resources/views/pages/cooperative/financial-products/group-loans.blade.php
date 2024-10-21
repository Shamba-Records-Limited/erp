@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Financial Products']['loan_application'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#requestLoanAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="requestLoanAccordion">Request Group Loan
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="requestLoanAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Request for Group Loan</h4>
                                </div>
                            </div>


                            <form method="post" action="{{ route('admin.group.loan.request')}}">
                                @csrf
                                <div class="form-row">

                                    <div class="form-group col-lg-4 col-md-6 col-12">
                                        <label for="period">Type</label>
                                        <select name="type" id="type" required
                                                class=" form-control select2bs4 {{ $errors->has('type') ? ' is-invalid' : '' }}">
                                            <option value=""> Select Loan Type</option>
                                            @foreach($loan_types as $type)
                                                <option value="{{$type->id}}"> {{ $type->name }}</option>
                                            @endforeach

                                            @if ($errors->has('type'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('type')  }}</strong>
                                                </span>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-6 col-12">
                                        <label for="farmer">Farmer <small>(leave blank for all farmers)</small></label>
                                        <input id="all_farmers" type="hidden" value="{{count($farmers)}}">
                                        <select name="farmers[]" id="farmer" multiple
                                                class=" form-control select2bs4 {{ $errors->has('type') ? ' is-invalid' : '' }}">
                                            @foreach($farmers as $farmer)
                                                <option value="{{$farmer->id}}"> {{ $farmer->user->first_name.' '.$farmer->user->other_names }}</option>
                                            @endforeach

                                            @if ($errors->has('type'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('type')  }}</strong>
                                                </span>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-6 col-12">
                                        <label for="amount">Amount <small>(total amount to be divided equally)</small></label>
                                        <input type="number" name="amount"
                                               class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                               id="amount" placeholder="2000" value="{{ old('amount')}}" required>

                                        <span class="help-block text-danger" id="show-limit">
                                        <strong>{{ $errors->first('amount')  }}</strong>
                                    </span>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-6">
                                        <button type="submit" class="btn btn-primary btn-fw btn-block">Request</button>
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
{{--                        <a class="btn btn-sm btn-info float-right text-white"--}}
{{--                           href="{{ route('download.loan.report', 'csv') }}">--}}
{{--                            <i class="mdi mdi-download"></i> CSV--}}
{{--                        </a>--}}

{{--                        <a class="btn btn-sm btn-github float-right text-white"--}}
{{--                           href="{{ route('download.loan.report', 'xlsx') }}" style="margin-right: -5px!important;">--}}
{{--                            <i class="mdi mdi-download"></i> Excel--}}
{{--                        </a>--}}
{{--                        <a class="btn btn-sm btn-success float-right text-white"--}}
{{--                           href="{{ route('download.loan.report', 'PDF') }}" style="margin-right: -8px!important;">--}}
{{--                            <i class="mdi mdi-download"></i> PDF--}}
{{--                        </a>--}}
                    @endif
                    <h4 class="card-title">Group Loans Summary</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Loan ID</th>
                                <th>Number of Farmers</th>
                                <th>Total Amount</th>
                                <th>Loan Type</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_amount = 0;
                            @endphp
                            @foreach($loans as $key => $loan)
                                @php $total_amount += $loan->total_amount @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td> {{ $loan->id }}</td>
                                    <td> {{ $loan->number_of_farmers }}</td>
                                    <td> {{ $currency.' '.number_format($loan->total_amount) }}</td>
                                    <td> {{ $loan->group_loan_type->name }}</td>
                                    <td> {{ \Carbon\Carbon::parse($loan->created_at)->format('d F, Y') }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-warning btn-rounded" href="{{route('admin.group.loan.details', $loan->id)}}">Loan Details</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th colspan="4">{{$currency.' '.number_format($total_amount)}}</th>
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
