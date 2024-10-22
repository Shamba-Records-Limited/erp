@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">

                <div class="card-body">
                    <a class="btn btn-sm btn-info float-right text-white"
                       href="{{ route('cooperative.farmer.loans.download',[$farmer->id,'csv']) }}">
                        <i class="mdi mdi-download"></i> CSV
                    </a>

                    <a class="btn btn-sm btn-github float-right text-white"
                       href="{{ route('cooperative.farmer.loans.download',[$farmer->id,'xlsx']) }}"
                       style="margin-right: -5px!important;">
                        <i class="mdi mdi-download"></i> Excel
                    </a>
                    <h4 class="card-title">{{ ucwords(strtolower($farmer->user->first_name.' '.$farmer->user->other_names))}} Commercial Loans</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Loan ID</th>
                                <th>Loan Type</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $total_loans = 0;

                            @endphp
                            @foreach($loans as $key => $l)
                                @php $total_loans += $l->balance @endphp
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $l->id }}</td>
                                    <td>{{ $l->type }}</td>
                                    <td>{{ $farmer->user->cooperative->currency.' '.number_format($l->balance, 2) }}</td>
                                    <td>{{$l->due_date}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th colspan="2">{{ number_format($total_loans, 2, '.',',')}}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">

                <div class="card-body">
{{--                    <a class="btn btn-sm btn-info float-right text-white"--}}
{{--                       href="{{ route('cooperative.farmer.loans.download',[$farmer->id,'csv']) }}">--}}
{{--                        <i class="mdi mdi-download"></i> CSV--}}
{{--                    </a>--}}

{{--                    <a class="btn btn-sm btn-github float-right text-white"--}}
{{--                       href="{{ route('cooperative.farmer.loans.download',[$farmer->id,'xlsx']) }}"--}}
{{--                       style="margin-right: -5px!important;">--}}
{{--                        <i class="mdi mdi-download"></i> Excel--}}
{{--                    </a>--}}
                    <h4 class="card-title">{{ ucwords(strtolower($farmer->user->first_name.' '.$farmer->user->other_names))}} Group Loans</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Loan ID</th>
                                <th>Loan Type</th>
                                <th>Amount</th>
                                <th>Balance</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $total_balance = 0;
                                $total_amount = 0;

                            @endphp
                            @foreach($group_loans as $key => $l)
                                @php
                                    $total_balance += $l->balance;
                                    $total_amount += $l->amount
                                @endphp
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $l->id }}</td>
                                    <td>{{ $l->group_loan_summery->group_loan_type->name }}</td>
                                    <td>{{ $farmer->user->cooperative->currency.' '.number_format($l->amount, 2) }}</td>
                                    <td>{{ $farmer->user->cooperative->currency.' '.number_format($l->balance, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th colspan="1">{{ $farmer->user->cooperative->currency.' '.number_format($total_amount, 2, '.',',')}}</th>
                                <th colspan="1">{{ $farmer->user->cooperative->currency.' '.number_format($total_balance, 2, '.',',')}}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
