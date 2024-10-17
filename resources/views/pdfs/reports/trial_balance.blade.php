<!DOCTYPE html>
<html lang="en">
<head>
    <title>Trial Balance</title>
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
                <p style="font-size:14px;margin:0;">Trial Balance</p>
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
                $debitSum = 0; 
                $creditSum = 0;
                $compareDebitSum = 0;
                $compareCreditSum = 0;
                $hasCompare = count($compare_records) > 0 ? true : false;
            @endphp

            @if($hasCompare)
                <tr style="background:#eaeaea;">
                    <td class="border-0 pl-0" width="20%">
                        <p style="font-weight:bold;font-size:12px;margin:0;padding:0 0 0 5px;text-transform:uppercase;"></p>
                    </td>
                    <td class="border-0 pl-0" colspan="2">
                        <p style="font-weight:bold;font-size:12px;margin:0;padding:0 0 0 5px;text-transform:uppercase;text-align:center;">
                            {{ date('Y', strtotime($startDate)) }}    
                        </p>
                    </td>
                    <td class="border-0 pl-0" colspan="2">
                        <p style="font-weight:bold;font-size:12px;margin:0;padding:0 0 0 5px;text-transform:uppercase;text-align:center;">
                            {{ date('Y', strtotime($compareStartDate)) }}    
                        </p>
                    </td>
                </tr>

                <tr style="background:#eaeaea;">
                    <td class="border-0 pl-0" width="20%">
                        <p style="font-weight:bold;font-size:12px;margin:0;padding:0 0 0 5px;text-transform:uppercase;">Account</p>
                    </td>
                    <td class="border-0 pl-0" width="20%">
                        <p style="font-weight:bold;font-size:12px;margin:0;padding:0 0 0 5px;text-transform:uppercase;text-align:right;">Debit</p>
                    </td>
                    <td class="border-0 pl-0" width="20%">
                        <p style="font-weight:bold;font-size:12px;margin:0;padding:0 0 0 5px;text-transform:uppercase;text-align:right;">Credit</p>
                    </td>
                    <td class="border-0 pl-0" width="20%">
                        <p style="font-weight:bold;font-size:12px;margin:0;padding:0 0 0 5px;text-transform:uppercase;text-align:right;">Debit</p>
                    </td>
                    <td class="border-0 pl-0" width="20%">
                        <p style="font-weight:bold;font-size:12px;margin:0;padding:0 0 0 5px;text-transform:uppercase;text-align:right;">Credit</p>
                    </td>
                </tr>
            @else
                <tr style="background:#eaeaea;">
                    <td class="border-0 pl-0" width="60%">
                        <p style="font-weight:bold;font-size:12px;margin:0;padding:0 0 0 5px;text-transform:uppercase;">Account</p>
                    </td>
                    <td class="border-0 pl-0" width="20%">
                        <p style="font-weight:bold;font-size:12px;margin:0;padding:0 0 0 5px;text-transform:uppercase;text-align:right;">Debit</p>
                    </td>
                    <td class="border-0 pl-0" width="20%">
                        <p style="font-weight:bold;font-size:12px;margin:0;padding:0 0 0 5px;text-transform:uppercase;text-align:right;">Credit</p>
                    </td>
                </tr>
            @endif

            <tr class="border-bottom-1">
                <td class="border-0" colspan="3">
                    <p style="font-weight:bold;font-size:10px;margin:0;padding:0 0 0 5px;text-transform:uppercase;">Assets</p>
                </td>
            </tr>
            
            {{-- assets --}}
            @foreach($records['assets'] as $ledger)
                @php $debitSum += $ledger->debit; @endphp
                <tr class="border-bottom-1">
                    <td class="border-0" width="{{ $hasCompare ? '20%' : '60%' }}">
                        <p style="font-size:12px;margin:0;padding:0 0 0 5px;">{{ $ledger->name }}</p>
                    </td>
                    <td class="border-0" width="20%">
                        <p style="font-size:12px;margin:0;padding:0 0 0 5px;text-align:right;">{{ number_format($ledger->debit, 2) }}</p>
                    </td>
                    <td class="border-0" width="20%">
                        <p style="font-size:12px;margin:0;padding:0 0 0 5px;text-align:right;">---</p>
                    </td>
                    @if($hasCompare)
                        @php 
                            $compareDebitSum += count($compare_records['assets']) ? $compare_records['assets'][$loop->index]->debit : 0; 
                        @endphp
                        <td class="border-0" width="20%">
                            <p style="font-size:12px;margin:0;padding:0 0 0 5px;text-align:right;">
                                {{ count($compare_records['assets']) ? number_format($compare_records['assets'][$loop->index]->debit, 2) : '0.00' }}
                            </p>
                        </td>
                        <td class="border-0" width="20%">
                            <p style="font-size:12px;margin:0;padding:0 0 0 5px;text-align:right;">---</p>
                        </td>
                    @endif
                </tr>
            @endforeach
            
            <tr class="border-bottom-1">
                <td class="border-0" colspan="3">
                    <p style="font-weight:bold;font-size:10px;margin:0;padding:0 0 0 5px;text-transform:uppercase;">Expenses</p>
                </td>
            </tr>

            {{-- expenses --}}
            @foreach($records['expenses'] as $ledger)
                @php $debitSum += $ledger->debit; @endphp
                <tr class="border-bottom-1">
                    <td class="border-0" width="{{ $hasCompare ? '20%' : '60%' }}">
                        <p style="font-size:12px;margin:0;padding:0 0 0 5px;">{{ $ledger->name }}</p>
                    </td>
                    <td class="border-0" width="20%">
                        <p style="font-size:12px;margin:0;padding:0 0 0 5px;text-align:right;">{{ number_format($ledger->debit, 2) }}</p>
                    </td>
                    <td class="border-0" width="20%">
                        <p style="font-size:12px;margin:0;padding:0 0 0 5px;text-align:right;">---</p>
                    </td>
                    @if($hasCompare)
                        @php 
                            $compareDebitSum += count($compare_records['expenses']) ? $compare_records['expenses'][$loop->index]->debit : 0; 
                        @endphp
                        <td class="border-0" width="20%">
                            <p style="font-size:12px;margin:0;padding:0 0 0 5px;text-align:right;">
                                {{ count($compare_records['expenses']) ? number_format($compare_records['expenses'][$loop->index]->debit, 2) : '0.00' }}
                            </p>
                        </td>
                        <td class="border-0" width="20%">
                            <p style="font-size:12px;margin:0;padding:0 0 0 5px;text-align:right;">---</p>
                        </td>
                    @endif
                </tr>
            @endforeach

            <tr class="border-bottom-1">
                <td class="border-0" colspan="3">
                    <p style="font-weight:bold;font-size:10px;margin:0;padding:0 0 0 5px;text-transform:uppercase;">Liabilities</p>
                </td>
            </tr>
            
            @foreach($records['liabilities'] as $ledger)
                @php $creditSum += $ledger->credit; @endphp
                <tr class="border-bottom-1">
                    <td class="border-0" width="{{ $hasCompare ? '20%' : '60%' }}">
                        <p style="font-size:12px;margin:0;padding:0 0 0 5px;">{{ $ledger->name }}</p>
                    </td>
                    <td class="border-0" width="20%">
                        <p style="font-size:12px;margin:0;padding:0 0 0 5px;text-align:right;">---</p>
                    </td>
                    <td class="border-0" width="20%">
                        <p style="font-size:12px;margin:0;padding:0 0 0 5px;text-align:right;">{{ number_format($ledger->credit, 2) }}</p>
                    </td>
                    @if($hasCompare)
                        @php
                            $compareCreditSum += count($compare_records['liabilities']) ? $compare_records['liabilities'][$loop->index]->credit : 0;
                        @endphp
                        <td class="border-0" width="20%">
                            <p style="font-size:12px;margin:0;padding:0 0 0 5px;text-align:right;">---</p>
                        </td>
                        <td class="border-0" width="20%">
                            <p style="font-size:12px;margin:0;padding:0 0 0 5px;text-align:right;">
                                {{ count($compare_records['liabilities']) ? number_format($compare_records['liabilities'][$loop->index]->credit, 2) : '0.00' }}
                            </p>
                        </td>      
                    @endif
                </tr>
            @endforeach

            <tr class="border-bottom-1">
                <td class="border-0" colspan="3">
                    <p style="font-weight:bold;font-size:10px;margin:0;padding:0 0 0 5px;text-transform:uppercase;">Revenue</p>
                </td>
            </tr>
            
            @foreach($records['revenue'] as $ledger)
                @php $creditSum += $ledger->credit; @endphp
                <tr class="border-bottom-1">
                    <td class="border-0" width="{{ $hasCompare ? '20%' : '60%' }}">
                        <p style="font-size:12px;margin:0;padding:0 0 0 5px;">{{ $ledger->name }}</p>
                    </td>
                    <td class="border-0" width="20%">
                        <p style="font-size:12px;margin:0;padding:0 0 0 5px;text-align:right;">---</p>
                    </td>
                    <td class="border-0" width="20%">
                        <p style="font-size:12px;margin:0;padding:0 0 0 5px;text-align:right;">{{ number_format($ledger->credit, 2) }}</p>
                    </td>
                    @if($hasCompare)
                        @php
                            $compareCreditSum += count($compare_records['revenue']) ? $compare_records['revenue'][$loop->index]->credit : 0;
                        @endphp
                        <td class="border-0" width="20%">
                            <p style="font-size:12px;margin:0;padding:0 0 0 5px;text-align:right;">---</p>
                        </td>
                        <td class="border-0" width="20%">
                            <p style="font-size:12px;margin:0;padding:0 0 0 5px;text-align:right;">
                                {{ count($compare_records['revenue']) ? number_format($compare_records['revenue'][$loop->index]->credit, 2) : '0.00' }}
                            </p>
                        </td>      
                    @endif
                </tr>
            @endforeach
            
            <tr class="border-bottom-1">
                <td class="border-0" width="{{ $hasCompare ? '20%' : '60%' }}"></td>
                <td class="border-0" width="20%">
                    <p style="font-weight:bold;font-size:11px;margin:0;padding:0 0 0 5px;text-align:right;">{{ number_format($debitSum, 2) }}</p>
                </td>
                <td class="border-0"  width="20%">
                    <p style="font-weight:bold;font-size:11px;margin:0;padding:0 0 0 5px;text-align:right;">{{ number_format($creditSum, 2) }}</p>
                </td>
                @if($hasCompare)
                    <td class="border-0"  width="20%">
                        <p style="font-weight:bold;font-size:11px;margin:0;padding:0 0 0 5px;text-align:right;">{{ number_format($compareDebitSum, 2) }}</p>
                    </td>
                    <td class="border-0"  width="20%">
                        <p style="font-weight:bold;font-size:11px;margin:0;padding:0 0 0 5px;text-align:right;">{{ number_format($compareCreditSum, 2) }}</p>
                    </td>
                @endif
            </tr>

        </tbody>
    </table>

    <p>{{ '' }}</p>

    <script type="text/php">
        $text = "Page 1/1";
        $size = 10;
        $font = $fontMetrics->getFont("Verdana");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        $x = ($pdf->get_width() - $width);
        $y = $pdf->get_height() - 35;
        $pdf->page_text($x, $y, $text, $font, $size);
    </script>

</body>
</html>
