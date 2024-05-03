@extends('layout.master')

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
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if(has_right_permission(config('enums.system_modules')['Financial Products']['interest'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('cooperative.interest.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('cooperative.interest.download','xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('cooperative.interest.download', 'pdf') }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Loan Interest</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Loan ID</th>
                                <th>Farmer</th>
                                <th>Amount</th>
                                <th>Interest</th>
                                <th>Penalty</th>
                                <th>Due Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_amount = 0;
                                $total_balance = 0;
                                $total_interest = 0;
                                $total_penalties = 0;
                            @endphp
                            @foreach($loans as $key => $loan)
                                @php
                                    $total_amount += $loan->amount;
                                    $interest = $loan->amount * ($loan->interest/100);
                                    $total_interest += $interest;
                                    $loanId = $loan->id;
                                    if(\Carbon\Carbon::parse($loan->due_date)->lt(\Carbon\Carbon::now())){
                                        $penalty = $loan->amount * ($loan->penalty/100);
                                    }else{
                                        $penalty = 0;
                                    }
                                    $total_penalties += $penalty;
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>
                                        <a href="{{route('cooperative.farmer-loan_installments', $loan->id)}}">{{ sprintf("%03d", $loan->id) }}</a>
                                    </td>
                                    <td>{{ucwords(strtolower($loan->first_name .' '.$loan->other_names)) }}</td>
                                    <td>{{ $currency.' '.number_format($loan->amount, 2) }}</td>
                                    <td>{{ $currency.' '.number_format($interest, 2) }}</td>
                                    <td>{{ $currency.' '.number_format($penalty, 2) }}</td>
                                    <td>{{$loan->due_date }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th colspan="1">{{ $currency.' '.number_format($total_amount, 2, '.',',')}}</th>
                                <th colspan="1">{{ $currency.' '.number_format($total_interest, 2, '.',',')}}</th>
                                <th colspan="2">{{ $currency.' '.number_format($total_penalties, 2, '.',',')}}</th>
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
