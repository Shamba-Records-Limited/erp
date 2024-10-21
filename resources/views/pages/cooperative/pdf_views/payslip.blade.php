<!DOCTYPE html>
<html lang="en">
<head>
    <title>Employee Payslip</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style type="text/css" media="screen">
      html {
        font-family: sans-serif;
        line-height: 1.15;
        margin: 0;
      }

      body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        text-align: left;
        background-color: #fff;
        font-size: 9px;
        margin: 36pt;
      }

      h4 {
        margin-top: 0;
        margin-bottom: 0.5rem;
      }

      p {
        margin-top: 0;
        margin-bottom: 1rem;
      }

      strong {
        font-weight: bolder;
      }

      img {
        vertical-align: middle;
        border-style: none;
      }

      table {
        border-collapse: collapse;
      }

      th {
        text-align: inherit;
      }

      h4 {
        margin-bottom: 0.5rem;
        font-weight: 500;
        line-height: 1.2;
      }

      h4 {
        font-size: 1.5rem;
      }

      .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
      }

      .table th,
      .table td {
        padding: 0.75rem;
        vertical-align: top;
      }

      .table.table-items td {
        border-top: 1px solid #dee2e6;
      }

      .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
      }

      .mt-5 {
        margin-top: 3rem !important;
      }

      .pl-0{
        padding-left: 0 !important;
      }

      .text-uppercase {
        text-transform: uppercase !important;
      }

      * {
        font-family: "DejaVu Sans";
      }

      body, h1, h2, h3, h4, h5, h6, table, th, tr, td, p, div {
        line-height: 1.1;
      }

      .border-0 {
        border: none !important;
      }
    </style>
</head>

<body>
{{-- Header --}}
@php $user = Auth::user(); $currency = $user->cooperative->currency; @endphp
@if($user->cooperative->logo)
    <img src="{{ $user->cooperative->logo }}" alt="logo" height="100">
@else
    <img src="{{ public_path('assets/images/favicon.png') }}" alt="logo" height="100">
@endif


<table class="table mt-5">
    <tbody>
    <tr>
        <td class="border-0 pl-0" width="70%">
            <h4 class="text-uppercase">
                <strong>{{ $user->cooperative->name }} {{$employeeDetails['period']}} {{ $title }}</strong>
            </h4>
            <p><b>Employee: </b>{{$employeeDetails['names'] }}</p>
            <p><b>Bank: </b>{{ $employeeDetails['bank'] }}</p>
            <p><b>Account: </b>{{ $employeeDetails['account'] }}</p>
            <p><b>Phone Number: </b>{{$employeeDetails['phone']}}</p>
        </td>
        <td class="border-0 pl-0">
            <strong>Date Generated</strong>
            <p>{{ $period }}</p>
            <p><b>Employee Number: </b>{{ $employeeDetails['emp_no'] }}</p>
            <p><b>KRA: </b>{{$employeeDetails['kra'] }}</p>
            <p><b>NHIF: </b>{{$employeeDetails['nhif'] }}</p>
            <p><b>NSSF: </b>{{$employeeDetails['nssf'] }}</p>
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
            <td>{{ $currency }} {{number_format($value,2) }}</td>
        </tr>
    @endforeach
    <tr>
        <td><b>Gross Pay</b></td>
        <td><b>{{ $currency }} {{number_format($gross_pay,2) }}</b>
        </td>
    </tr>
    <tr>
        <td><b>Deductions</b></td>
        <td></td>
    </tr>
    @php $afterPayeDeductions = 0;@endphp
    @php $beforePayeDeductions = 0;@endphp
    @foreach($beforePAYEDeductions as $key => $value)
        @php $beforePayeDeductions+=$value @endphp
        <tr>
            <td>{{ strtoupper($key) }}</td>
            <td>{{ $currency }} {{number_format($value,2) }}</td>
        </tr>
    @endforeach

    @if($beforePayeDeductions > 0)
        <tr>
            <td></td>
            <td><b>{{ $currency }} {{ number_format($beforePayeDeductions,2) }}</b></td>
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

    @php $afterPayeDeductions += $paye @endphp

    @foreach($afterPAYEDeductions as $key => $value)
        @php $afterPayeDeductions+=$value @endphp
        <tr>
            <td>{{ strtoupper($key) }}</td>
            <td>
                {{ $currency }} {{number_format($value,2) }}
            </td>
        </tr>
    @endforeach

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
        <td><b>{{ $currency }} {{number_format($afterPayeDeductions,2) }}</b></td>
    </tr>

    <tr>
        <td><b>Net Pay</b></td>
        <td>{{ $currency }} {{number_format($net_pay,2)}}</td>
    </tr>
    </tbody>
</table>
</body>
</html>
