<!DOCTYPE html>
<html lang="en">

<head>
    <title>Ledger</title>
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
        font-size: 7px;
        margin: 20pt;
      }

      p {
        margin-top: 0;
        margin-bottom: 1rem;
      }

      strong {
        font-weight: bolder;
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
        font-size: 1.5rem;
      }

      .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
      }

      .table th,
      .table td {
        padding: 0.5rem;
        vertical-align: top;
      }

      .table.table-items td {
        border-top: 1px solid #dee2e6;
      }

      .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
      }

      * {
        font-family: "DejaVu Sans";
      }

      body,
      h1,
      h2,
      h3,
      h4,
      h5,
      h6,
      table,
      p,
      div {
        line-height: 1.1;
      }

      .kra img {
        width: 250px;
        height: 50px;
        margin: 0 auto;
      }

      .table-header {
        width: 100%;
      }

      .table-header .centred {
        width: 100%;
        padding: 8px;
        text-align: center;
        vertical-align: middle;
      }

      #content th, #content tr {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        vertical-align: top;

      }


      #content th, tfoot {
        background-color: #eee;
      }

    </style>
</head>

<body>
{{-- Header --}}
@php
    $user = Auth::user();
    $currency = $user->cooperative->currency;
@endphp


<table class="table-header">
    <tbody>
    <tr>
        <td class="border-0 pl-0 centred" width="100%">
            <div class="kra">
                <img src="{{ public_path('assets/images/kra_logo.png') }}" alt="logo">
                <h4>P.A.Y.E Supporting List For End Of Year Certificate</h4>
                <h4>Year: <b>{{$records['year']}}</b></h4>
            </div>
        </td>
    </tr>
    </tbody>
</table>


@php
    $totalEmoluments= 0;
    $totalTax= 0;
@endphp


<table class="table-header">
    <tr>
        <td> {{"Employer's Name: ".$records['employer']}}</td>
        <td> {{'PIN : '.'P00000000G'}}</td>
    </tr>
</table>
<br>
<table id="content" class="table">
    <thead>
    <tr>
        <th>PIN</th>
        <th>Employee</th>
        <th>Total Emolument</th>
        <th>Fringe Benefit Tax</th>
        <th>Tax Deducted</th>
    </tr>
    </thead>

    <tbody>
    @foreach($records['p10Data'] as $data)
        @php
            $totalEmoluments += $data->gross_pay;
            $totalTax += $data->paye;
        @endphp
        <tr>
            <td>{{ $data->kra }}</td>
            <td> {{ ucwords(strtolower($data->name)) }}</td>
            <td> {{$currency.' '.number_format($data->gross_pay) }}</td>
            <td> {{ $currency }} 0.00</td>
            <td>{{ $currency.' '.number_format($data->paye) }}</td>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr>
        <th colspan="2">Total Emoluments</th>
        <th colspan="3">{{ $currency.' '.number_format($totalEmoluments) }}</th>
    </tr>
    <tr>
        <th colspan="4">TOTAL P.A.Y.E TAX</th>
        <th>{{ $currency.' '.number_format($totalTax) }}</th>
    </tr>
    <tr>
        <th colspan="4">TAX ON LUMP SUM/AUDIT/INTEREST/PENALTY</th>
        <th> {{ $currency }} 0.00</th>
    </tr>

    <tr>
        <th colspan="4"> TOTAL TAX REMITTED</th>
        <th>
            {{ $currency.' '.number_format($totalTax) }}
        </th>
    </tr>

    </tfoot>
</table>

<h2 class="mt-2"> NOTE TO EMPLOYER: ATTACH THIS LIST TO END OF YEAR CERTIFICATE, FORM P10 .</h2>
</body>
</html>
