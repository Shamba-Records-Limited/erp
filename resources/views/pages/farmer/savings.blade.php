@extends('layout.master')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">

                <div class="card-body">
                    <a class="btn btn-sm btn-info float-right text-white"
                       href="{{ route('cooperative.farmer.savings.download',[$farmer->id,'csv']) }}">
                        <i class="mdi mdi-download"></i> CSV
                    </a>

                    <a class="btn btn-sm btn-github float-right text-white"
                       href="{{ route('cooperative.farmer.savings.download',[$farmer->id,'xlsx']) }}"
                       style="margin-right: -5px!important;">
                        <i class="mdi mdi-download"></i> Excel
                    </a>
                    <h4 class="card-title">{{ ucwords(strtolower($farmer->user->first_name.' '.$farmer->user->other_names))}} Savings</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Saving ID</th>
                                <th>Saving Type</th>
                                <th>Amount</th>
                                <th>Maturity Date</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $total_savings = 0;

                            @endphp
                            @foreach($savings as $key => $s)
                                @php $total_savings += $s->amount @endphp
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $s->id }}</td>
                                    <td>{{ $s->type }}</td>
                                    <td>{{ $farmer->user->cooperative->currency.' '.number_format($s->amount, 2) }}</td>
                                    <td>{{$s->maturity_date}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th colspan="2">{{ number_format($total_savings, 0, '.',',')}}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
