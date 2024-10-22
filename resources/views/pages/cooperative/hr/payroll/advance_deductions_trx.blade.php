@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    @php
        $user = Auth::user();
        $currency = $user->cooperative->currency;
        $canDownload = has_right_permission(config('enums.system_modules')['HR Management']['payroll'], config('enums.system_permissions')['download']);
    @endphp

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if($canDownload)
                        <form action="{{ route('hr.employees.payroll.advance.deduction.details.download',['csv', $advance_deduction_id])}}"
                              method="post">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('hr.employees.payroll.advance.deduction.details.download',['xlsx',$advance_deduction_id])}}"
                              method="post">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>

                        <form action="{{ route('hr.employees.payroll.advance.deduction.details.download',['pdf',$advance_deduction_id])}}"
                              method="post">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm btn-success float-right text-white">
                                <i class="mdi mdi-download"></i> PDF
                            </button>
                        </form>
                    @endif
                    <h4 class="card-title">{{ ucwords(strtolower($deduction->employee->user->first_name.' '.$deduction->employee->user->other_names.' '.config('enums.advance_deduction_types')[$deduction->type].' Deductions')) }}</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Payroll Period</th>
                                <th>Amount</th>
                                <th>Balance</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $total_amount = 0;
                            @endphp
                            @forelse($deduction_transactions as $key => $trx)
                                @php
                                    $total_amount += $trx->amount;
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ config('enums.Months')[$trx->month].' '.$trx->year }}</td>
                                    <td> {{$currency}} {{ number_format($trx->amount,2) }} </td>
                                    <td> {{$currency}} {{ number_format($trx->balance,2) }} </td>
                                </tr>
                            @empty

                            @endforelse
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="2">Total</th>
                                <th colspan="2">{{$currency}} {{number_format($total_amount)}}</th>

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
