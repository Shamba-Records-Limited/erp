@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    @php $user = Auth::user(); $currency = $user->cooperative->currency;@endphp
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $names }} Payslip</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Payslip Allowances/Deductions</h4>
                    <div class="table-responsive">
                        <table class="table mt-5">
                            <tbody>
                            <tr>
                                <td class="border-0 pl-0" width="70%">
                                    <h4 class="text-uppercase">
                                        <strong>{{ $user->cooperative->name }}</strong>
                                    </h4>
                                </td>
                                <td class="border-0 pl-0">
                                    <strong>Date Generated</strong>
                                    <p>{{ $period }}</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>


                        {{-- Table --}}
                        <table class="table table-items">

                            <tbody>
                            <tr>
                                <td><b>Basic</b></td>
                                <td>{{ $currency }} {{number_format($basic_pay,2)}}</td>
                            </tr>
                            <tr>
                                <td><b>Allowances</b></td>
                                <td></td>
                            </tr>
                            @foreach($empAllowances as $key => $value)
                                <tr>
                                    <td>{{strtoupper($key) }}</td>
                                    <td>
                                        {{ $currency }} {{number_format($value,2) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td><b>Gross Pay</b></td>
                                <td><b>{{ $currency }} {{number_format($gross_pay,2) }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Deductions</b></td>
                                <td>&nbsp;</td>
                            </tr>

                            @php $afterPayeDeductions = 0;@endphp
                            @php $beforePayeDeductions = 0;@endphp
                            @foreach($beforePAYEDeductions as $key => $value)
                                @php $beforePayeDeductions+=$value @endphp
                                <tr>
                                    <td>{{ strtoupper($key) }}</td>
                                    <td>
                                        {{ $currency }} {{number_format($value,2) }}
                                    </td>
                                </tr>
                            @endforeach
                            @if($beforePayeDeductions > 0)
                                <tr>
                                    <td></td>
                                    <th>&nbsp;{{ $currency }} {{ number_format($beforePayeDeductions,2) }}</th>
                                </tr>
                            @endif
                            <tr>
                                <td>Taxable Income</td>
                                <td>{{ $currency }} {{number_format($taxable_income,2)}}</td>
                            </tr>

                            <tr>
                                <td>P.A.Y.E</td>
                                <td>{{ $currency }} {{number_format($paye_before_deduction,2)}}</td>
                            </tr>
                            @foreach($payeDeductions as $key => $value)
                                <tr>
                                    <td>{{ strtoupper($key) }}</td>
                                    <td>
                                        {{ $currency }} {{number_format($value,2) }}
                                    </td>
                                </tr>
                            @endforeach

                            <tr>
                                <td></td>
                                <td><b>{{ $currency }} {{number_format($paye,2)}}</b></td>
                            </tr>

                            @foreach($afterPAYEDeductions as $key => $value)
                                @php $afterPayeDeductions+=$value @endphp
                                <tr>
                                    <td>{{ strtoupper($key) }}</td>
                                    <td>
                                        {{ $currency }} {{number_format($value,2) }}
                                    </td>
                                </tr>
                            @endforeach

                            @php $afterPayeDeductions += $paye @endphp

                            @foreach($advanceDeductions as $deduction)
                                @php $afterPayeDeductions+=$deduction['amount'] @endphp
                                <tr>
                                    <td>{{ strtoupper($deduction['type']) }}</td>
                                    <td>
                                        {{ $currency }} {{number_format($deduction['amount'],2) }}
                                    </td>
                                </tr>
                            @endforeach

                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <b>{{ $currency }} {{number_format($afterPayeDeductions,2) }}</b>
                                </td>
                            </tr>


                            <tr>
                                <td><b>Net Pay</b></td>
                                <td>{{ $currency }} {{number_format($net_pay,2)}}</td>
                            </tr>
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
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        const rows = document.querySelectorAll("tr[data-href]");
        rows.forEach(row => {
          row.addEventListener("click", () => {
            window.location.href = row.dataset.href
          })
        })
      })
    </script>
@endpush
