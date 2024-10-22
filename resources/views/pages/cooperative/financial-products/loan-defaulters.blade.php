@extends('layouts.app')

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
                    @if(has_right_permission(config('enums.system_modules')['Financial Products']['loan_defaulters'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('download.loan.defaulters.report', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('download.loan.defaulters.report', 'xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('download.loan.defaulters.report', env('PDF_FORMAT')) }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif

                    <h4 class="card-title">Defaulted Loan Installment</h4>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>Loan ID</th>
                                <th>Loan Type</th>
                                <th>Loan Amount</th>
                                <th>Installment Amount</th>
                                <th>Loan Balance</th>
                                <th>Due Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_installments = 0;
                            @endphp
                            @foreach($defaulters as $key => $d)
                                @php
                                    $total_installments +=  $d->installment;
                                    $name = ucwords(strtolower($d->first_name.' '.$d->other_names))
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $d->installment_id }}</td>
                                    <td>{{ $name }}</td>
                                    <td>{{ $d->phone_no }}</td>
                                    <td>{{ sprintf("%03d", $d->id) }}</td>
                                    <td>{{ $d->type }}</td>
                                    <td>{{ $currency.' '.$d->amount }}</td>
                                    <td>{{ $currency.' '.$d->installment }}</td>
                                    <td>{{ $currency.' '.$d->balance }}</td>
                                    <td>{{ \Carbon\Carbon::parse($d->due_date)->format('d-m-Y') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="6">Total</th>
                                <th colspan="3">{{ $currency.' '.number_format($total_installments, 2, '.',',')}}</th>
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
