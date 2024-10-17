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

      .mt-5 {
        margin-top: 4rem !important;
      }

      .pr-0,
      .px-0 {
        padding-right: 0 !important;
      }

      .pl-0 {
        padding-left: 0 !important;
      }

      .text-right {
        text-align: right !important;
      }

      .text-center {
        text-align: center !important;
      }

      .text-uppercase {
        text-transform: uppercase !important;
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
        text-align: center;
        vertical-align: top;

      }

      #content .text-left {
        text-align: left;

      }

      #content th, tfoot {
        background-color: #eee;
      }

      .center {
        text-align: top;
      }

      .right {
        text-align: right;
      }

      .vertical-border {
        border-left: 1px solid #eee; /* Add a left border to the right cells */
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
        <td class="border-0 pl-0" width="35%">
            <div class="section_a">
                <p>Employer Name: <b>{{ ucwords(strtolower($user->cooperative->name))}}</b></p>
                <p>Employee Name: <b>{{$records['employee']}}</b></p>
            </div>
        </td>
        <td class="border-0 pl-0 centred" width="40%">
            <div class="kra">
                <img src="{{ public_path('assets/images/kra_logo.png') }}" alt="logo">
                <h4>Tax Deduction Year: <b>{{$records['year']}}</b></h4>
            </div>
        </td>
        <td class="border-0 pl-0" width="25%">
            <div class="section_c">
                <p>Employer PIN: <b>P0000000001K</b></p>
                <p>Employee PIN: <b> {{ $records['pin']}}</b></p>
            </div>
        </td>
    </tr>
    </tbody>
</table>


@php
    $totalA= 0;
    $totalB= 0;
    $totalC= 0;
    $totalD= 0;
    $totalE1= 0;
    $totalE2= 0;
    $totalE3= 0;
    $totalF= 0;
    $totalG= 0;
    $totalH= 0;
    $totalJ= 0;
    $totalK= 0;
    $totalL= 0;
    $totalM= 0;
@endphp
{{-- Table --}}
<table id="content">
    <thead>
    <tr>
        <th rowspan="2">MONTH</th>
        <th rowspan="2">A <br/> Basic Salary</th>
        <th rowspan="2">B <br/> Benefit Non-Cash</th>
        <th rowspan="2">C <br/> Value of Quaters</th>
        <th rowspan="2">D <br/> Total Grosspay</th>
        <th colspan="3">E<br> Defined Contribution Retirement Scheme</th>
        <th rowspan="2">F <br/> Owner Occupied Interest Standard amount of Interest</th>
        <th rowspan="2">G <br/> Retirement Contribution And Owner Occupied Interest</th>
        <th rowspan="2">H <br/> Liable</th>
        <th rowspan="2">J <br/> Tax on H</th>
        <th rowspan="2">K <br/> Insurance</th>
        <th rowspan="2">L <br/> Relieve Monthly</th>
        <th rowspan="2">M <br/> P.A.Y.E Tax (K-L)</th>
    </tr>
    <tr>
        <th>E1 <br/> 30%</th>
        <th>E2 <br> Actual</th>
        <th>E3 <br> Fixed</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="vertical-border"></td>
        <td class="vertical-border">{{ $currency }}</td>
        <td class="vertical-border">{{ $currency }}</td>
        <td class="vertical-border">{{ $currency }}</td>
        <td class="vertical-border">{{ $currency }}</td>
        <td class="vertical-border">{{ $currency }}</td>
        <td class="vertical-border">{{ $currency }}</td>
        <td class="vertical-border">{{ $currency }}</td>
        <td class="vertical-border">{{ $currency }}</td>
        <td class="vertical-border">{{ $currency }}</td>
        <td class="vertical-border">{{ $currency }}</td>
        <td class="vertical-border">{{ $currency }}</td>
        <td class="vertical-border">{{ $currency }}</td>
        <td class="vertical-border">{{ $currency }}</td>
        <td class="vertical-border">{{ $currency }}</td>
    </tr>
    @foreach($records['p9Data'] as $r)

        @php
            $totalA += $r['A'];
            $totalB += $r['B'];
            $totalC += $r['C'];
            $totalD += $r['D'];
            $totalE1 += $r['E1'];
            $totalE2 += $r['E2'];
            $totalE3 += $r['E3'];
            $totalF += $r['F'];
            $totalG += $r['G'];
            $totalH += $r['H'];
            $totalJ += $r['J'];
            $totalK += $r['K'];
            $totalL += $r['L'];
            $totalM += $r['M'];
        @endphp
        <tr class="text-left">
            <td class="vertical-border">{{ $r["month"] }}</td>
            <td class="vertical-border">{{ number_format($r["A"], 2) }}</td>
            <td class="vertical-border">{{ number_format($r["B"], 2) }}</td>
            <td class="vertical-border">{{ number_format($r["C"], 2) }}</td>
            <td class="vertical-border">{{ number_format($r["D"], 2) }}</td>
            <td class="vertical-border">{{ number_format($r["E1"],2) }}</td>
            <td class="vertical-border">{{ number_format($r["E2"],2) }}</td>
            <td class="vertical-border">{{ number_format($r["E3"],2) }}</td>
            <td class="vertical-border">{{ number_format($r["F"], 2) }}</td>
            <td class="vertical-border">{{ number_format($r["G"], 2) }}</td>
            <td class="vertical-border">{{ number_format($r["H"], 2) }}</td>
            <td class="vertical-border">{{ number_format($r["J"], 2) }}</td>
            <td class="vertical-border">{{ number_format($r["K"], 2) }}</td>
            <td class="vertical-border">{{ number_format($r["L"], 2) }}</td>
            <td class="vertical-border">{{ number_format($r["M"], 2) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th class="vertical-border">{{ 'Total' }}</th>
        <th class="vertical-border">{{ number_format($totalA,2) }}</th>
        <th class="vertical-border">{{ number_format($totalB,2) }}</th>
        <th class="vertical-border">{{ number_format($totalC,2) }}</th>
        <th class="vertical-border">{{ number_format($totalD,2) }}</th>
        <th class="vertical-border">{{ number_format($totalE1,2) }}</th>
        <th class="vertical-border">{{ number_format($totalE2,2) }}</th>
        <th class="vertical-border">{{ number_format($totalE3,2) }}</th>
        <th class="vertical-border">{{ number_format($totalF,2) }}</th>
        <th class="vertical-border">{{ number_format($totalG,2) }}</th>
        <th class="vertical-border">{{ number_format($totalH,2) }}</th>
        <th class="vertical-border">{{ number_format($totalJ,2) }}</th>
        <th class="vertical-border">{{ number_format($totalK,2) }}</th>
        <th class="vertical-border">{{ number_format($totalL,2) }}</th>
        <th class="vertical-border">{{ number_format($totalM,2) }}</th>
    </tr>
    </tfoot>
</table>


<div>
    <table style="width: 900px;">
        <tr>
            <td colspan="5">{{'To be completed by Employer at end of year'}}</td>
        </tr>

        <tr>
            <td> {{'Total chargable pay (Col. J) : '.number_format($totalJ)}}</td>
            <td> {{'Total Insurance : '.number_format($totalJ)}}</td>
            <td> {{'Total Insurance relief: '.number_format($totalK)}}</td>
            <td> {{'Total Tax: '.number_format($totalM)}}</td>
        </tr>
        <br>
        <tr>
            <td> {{'Information required from employer as end of year'}}</td>
        </tr>
    </table>
    <br>
    <table>
        <tr>
            <td>(1) Date employee commenced if during year :_______________________________<br><br>
                Name and address of old Employer: _______________________________
            </td>
            <td>
                (2) Date employee left if during year :_______________________________<br><br>
                Name and address of new Employer: _______________________________<br><br>
                L.R. No. of owner occupied property: ___________________________________________<br><br>
                Names of Financial Institution advancing mortgage Loan:
                ___________________________________________<br><br>
                Date of occupation of house: ______________
            </td>
        </tr>
        <tr>
            <td colspan="2">
                (3) Where housing provided state monthly rent charged (shs) per month : 0.0
            </td>
        </tr>
        <tr>
            <td>
                (4) Where any of the pay relates to a period other than this year, e.g. gratuity
                give amounts, year and total.
                <table style="width: 300px;">
                    <tr>
                        <td width="100px">Year</td>
                        <td width="100px">Amount ({{$currency}})</td>
                        <td width="100px">Tax ({{$currency}})</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <table style="border: 2px solid #0b0d14; width: 300px;">
                    <tr style="line-height: 20px;">
                        <td style="border: 1px solid gray;" width="100px">For Official Use</td>
                        <td style="border: 1px solid gray;"width="100px"></td>
                        <td style="border: 1px solid gray;"width="100px"> ({{$currency}})</td>
                    </tr>
                    <tr style="line-height: 20px; border: 1px solid gray;">
                        <td style="border: 1px solid gray;">Tax Due</td>
                        <td style="border: 1px solid gray;"></td>
                        <td style="border: 1px solid gray;"></td>
                    </tr>
                    <tr style="line-height: 20px">
                        <td style="border: 1px solid gray;">Over Paid</td>
                        <td style="border: 1px solid gray;"></td>
                        <td style="border: 1px solid gray;"></td>
                    </tr>
                    <tr style="line-height: 20px">
                        <td style="border: 1px solid gray;">Under Paid</td>
                        <td style="border: 1px solid gray;"></td>
                        <td style="border: 1px solid gray;"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
