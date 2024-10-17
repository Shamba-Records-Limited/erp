<!DOCTYPE html>
<html lang="en">
<head>
    <title>Account Payables Summary</title>
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

        h4, .h4 {
            margin-bottom: 0.5rem;
            font-weight: 500;
            line-height: 1.2;
        }

        h4, .h4 {
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
            margin-top: 3rem !important;
        }

        .pr-0,
        .px-0 {
            padding-right: 0 !important;
        }

        .pl-0,
        .px-0 {
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

        body, h1, h2, h3, h4, h5, h6, table, th, tr, td, p, div {
            line-height: 1.1;
        }

        .party-header {
            font-size: 1.5rem;
            font-weight: 400;
        }

        .total-amount {
            font-size: 12px;
            font-weight: 700;
        }

        .border-0 {
            border: none !important;
        }

        .cool-gray {
            color: #6B7280;
        }
    </style>
</head>

<body>

    {{-- Header --}}

    <div style="text-align:center;">
        @if(Auth::user()->cooperative->logo)
            <img src="{{ Auth::user()->cooperative->logo }}" alt="logo" height="48">
        @else
            <img src="{{ public_path('assets/images/favicon.png') }}" alt="logo" height="48">
        @endif
    </div>

    <table class="table mt-5">
        <tr>
            <td style="text-align:center;padding:0.1rem">
                <p style="font-size:14px;margin:0;">{{ Auth::user()->cooperative->name }}</p>
            </td>
        </tr>
        <tr>
            <td style="text-align:center;padding:0.1rem">
                <p style="font-size:14px;margin:0;">Account Payables Summary</p>
            </td>
        </tr>
        <tr>
            <td style="text-align:center;padding:0.1rem">
                <p style="font-size:14px;margin:0;">{{ $startDate }} - {{ $endDate }}</p>
            </td>
        </tr>
    </table>

    <table class="table mt-5">
        <tbody>

            @php
                $sum = 0;
            @endphp

            <tr style="background:#eaeaea;">
                <td class="border-0 pl-0" width="70%">
                    <p style="font-weight:bold;font-size:12px;margin:0;padding:0 0 0 5px;text-transform:uppercase;">Account Payables</p>
                </td>
                <td class="border-0 pl-0" width="30%"></td>
            </tr>
          
            @foreach($records as $record)
                @php $sum += $record->amount; @endphp
                <tr>
                    <td class="border-0" width="70%">
                        <p style="font-size:12px;margin:0;padding:0 0 0 5px;">{{ $record->ledger }}</p>
                    </td>
                    <td class="border-0" width="30%">
                        <p style="font-size:12px;margin:0;padding:0 0 0 5px;text-align:right;">{{ number_format($record->amount, 2) }}</p>
                    </td>
                </tr>
            @endforeach

            <tr>
                <td class="border-0" width="70%">
                    <p style="font-weight:bold;font-size:10px;margin:0;padding:0 0 0 5px;text-transform:uppercase;">Total Account Payables</p>
                </td>
                <td class="border-0" width="30%">
                    <p style="font-weight:bold;font-size:11px;margin:0;padding:0 0 0 5px;text-align:right;">{{ number_format($sum, 2) }}</p>
                </td>
            </tr>
        </tbody>
    </table>

</body>
</html>
