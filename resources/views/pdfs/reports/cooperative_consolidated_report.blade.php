<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cooperative Consolidated Report</title>
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

        .border-bottom-1 {
            border-bottom: 1px solid #e8e8e8;
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
            <th style="text-align:center;padding:0.1rem">
                <p style="font-size:14px;margin:0;">{{ Auth::user()->cooperative->name }}</p>
            </th>
        </tr>
        <tr>
            <td style="text-align:center;padding:0.1rem">
                <p style="font-size:14px;margin:0;">Cooperative Consolidated Report</p>
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

            <tr style="background:#eaeaea;">
                <td class="border-0 pl-0">
                    <p style="font-weight:bold;font-size:11px;margin:0;padding:0 0 0 5px;text-transform:uppercase;">Item</p>
                </td>
                <td class="border-0 pl-0">
                    <p style="font-weight:bold;font-size:11px;margin:0;padding:0 0 0 5px;text-align:right;text-transform:uppercase;">Amount in Ksh</p>
                </td>
            </tr>
        
            @foreach($records as $record)

                @if($record['label'] ==  'Total inventory value:')
                    @php $inventorySum = 0; @endphp

                    <tr class="border-bottom-1">
                        <td class="border-0">
                            <p style="font-weight:bold;font-size:11px;margin:0;padding:0 0 0 5px;">{{ $record['label'] }}</p>
                        </td>
                        <td class="border-0">
                            <p style="font-size:11px;margin:0;padding:0 0 0 5px;text-align:right;"></p>
                        </td>
                    </tr>

                    @foreach($record['value'] as $value)
                        @php $inventorySum += str_replace(',', '', $value['value']); @endphp
                        <tr class="border-bottom-1">
                            <td class="border-0">
                                <p style="font-size:11px;margin:0;padding:0 0 0 25px;">{{ $value['label'] }}</p>
                            </td>
                            <td class="border-0">
                                <p style="font-size:11px;margin:0;padding:0 0 0 5px;text-align:right;">{{ number_format($value['value'], 2) }}</p>
                            </td>
                        </tr>
                    @endforeach               

                    <tr class="border-bottom-1">
                        <td class="border-0">
                            <p style="font-weight:bold;font-size:11px;margin:0;padding:0 0 0 25px;">Total Inventory</p>
                        </td>
                        <td class="border-0">
                            <p style="font-weight:bold;font-size:11px;margin:0;padding:0 0 0 5px;text-align:right;">{{ number_format($inventorySum, 2) }}</p>
                        </td>
                    </tr>

                @elseif($record['label'] == 'Total number of employees:')

                    <tr class="border-bottom-1">
                        <td class="border-0">
                            <p style="font-weight:bold;font-size:11px;margin:0;padding:0 0 0 5px;">{{ $record['label'] }}</p>
                        </td>
                        <td class="border-0">
                            <p style="font-size:11px;margin:0;padding:0 0 0 5px;text-align:right;">{{ $record['value'] }}</p>
                        </td>
                    </tr>

                @else

                    <tr class="border-bottom-1">
                        <td class="border-0">
                            <p style="font-weight:bold;font-size:11px;margin:0;padding:0 0 0 5px;">{{ $record['label'] }}</p>
                        </td>
                        <td class="border-0">
                            <p style="font-size:11px;margin:0;padding:0 0 0 5px;text-align:right;">{{ number_format($record['value'], 2) }}</p>
                        </td>
                    </tr>

                @endif
            @endforeach

        </tbody>
    </table>

</body>
</html>
