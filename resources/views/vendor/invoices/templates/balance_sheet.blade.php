<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ $invoice->name }}</title>
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
                font-size: 10px;
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
        @if($invoice->logo)
            <img src="{{ $invoice->getLogo() }}" alt="logo" height="100">
        @endif

        <table class="table mt-5">
            <tbody>
                <tr>
                    <td class="border-0 pl-0" width="70%">
                        <h4 class="text-uppercase">
                            <strong>{{ $invoice->name }}</strong>
                        </h4>
                    </td>
                    <td class="border-0 pl-0">
                        @if($invoice->status)
                            <h4 class="text-uppercase cool-gray">
                                <strong>{{ $invoice->status }}</strong>
                            </h4>
                        @endif
                        <!-- <p>{{ __('invoices::invoice.serial') }} <strong>{{ $invoice->getSerialNumber() }}</strong></p> -->
                        <p>{{ __('Date') }}: <strong>{{ $invoice->getDate() }}</strong></p>
                        @foreach($invoice->seller->custom_fields as $key => $value)
                            <p class="seller-custom-field">
                                {{ ucfirst($key) }} : {{ $value }}
                            </p>
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Seller - Buyer --}}
        {{--<table class="table">
            <thead>
                <tr>
                    <th class="border-0 pl-0 party-header" width="48.5%">
                        {{ __('invoices::invoice.seller') }}
                    </th>
                    <th class="border-0" width="3%"></th>
                    <th class="border-0 pl-0 party-header">
                        {{ __('invoices::invoice.buyer') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-0">
                        @if($invoice->seller->name)
                            <p class="seller-name">
                                <strong>{{ $invoice->seller->name }}</strong>
                            </p>
                        @endif

                        @if($invoice->seller->address)
                            <p class="seller-address">
                                {{ __('invoices::invoice.address') }}: {{ $invoice->seller->address }}
                            </p>
                        @endif

                        @if($invoice->seller->code)
                            <p class="seller-code">
                                {{ __('invoices::invoice.code') }}: {{ $invoice->seller->code }}
                            </p>
                        @endif

                        @if($invoice->seller->vat)
                            <p class="seller-vat">
                                {{ __('invoices::invoice.vat') }}: {{ $invoice->seller->vat }}
                            </p>
                        @endif

                        @if($invoice->seller->phone)
                            <p class="seller-phone">
                                {{ __('invoices::invoice.phone') }}: {{ $invoice->seller->phone }}
                            </p>
                        @endif

                        @foreach($invoice->seller->custom_fields as $key => $value)
                            <p class="seller-custom-field">
                                {{ ucfirst($key) }} : {{ $value }}
                            </p>
                        @endforeach
                    </td>
                    <td class="border-0"></td>
                    <td class="px-0">
                        @if($invoice->buyer->name)
                            <p class="buyer-name">
                                <strong>{{ $invoice->buyer->name }}</strong>
                            </p>
                        @endif

                        @if($invoice->buyer->address)
                            <p class="buyer-address">
                                {{ __('invoices::invoice.address') }}: {{ $invoice->buyer->address }}
                            </p>
                        @endif

                        @if($invoice->buyer->code)
                            <p class="buyer-code">
                                {{ __('invoices::invoice.code') }}: {{ $invoice->buyer->code }}
                            </p>
                        @endif

                        @if($invoice->buyer->vat)
                            <p class="buyer-vat">
                                {{ __('invoices::invoice.vat') }}: {{ $invoice->buyer->vat }}
                            </p>
                        @endif

                        @if($invoice->buyer->phone)
                            <p class="buyer-phone">
                                {{ __('invoices::invoice.phone') }}: {{ $invoice->buyer->phone }}
                            </p>
                        @endif

                        @foreach($invoice->buyer->custom_fields as $key => $value)
                            <p class="buyer-custom-field">
                                {{ ucfirst($key) }}: {{ $value }}
                            </p>
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>--}}

        {{-- Table --}}
        @if($invoice->items)
        <table class="table table-items">
            
            <tbody>
                {{-- Items --}}
                @php $assets = 0;$liabilities = 0; @endphp
                <tr>
                    <th colspan="2" class="border-0">Assets</th>
                </tr>
                @foreach($invoice->buyer->custom_fields as $key => $value)
                    @if($key  == 'Balance B/F' && $value > 0)
                        <tr>
                            @php $assets += $value; @endphp
                            <td class="">
                                {{ ucfirst($key) }}
                            </td>
                        
                            <td class="text-right">
                                {{ $invoice->formatCurrency($value) }}
                            </td>                    
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <th colspan="2" class="border-0">Current Assets</th>
                </tr>
                @foreach($invoice->items as $item)
                    @php 
                        $desc = explode('::', $item->description);
                        $account = $desc[0];
                        $type = $desc[1];
                    @endphp
                    @if($account == 'Assets' && $type == 'current')
                        <tr>
                            @php $assets += $item->price_per_unit ?? $item->discount; @endphp
                            <td class="">
                                {{ $item->title }}
                            </td>
                            <td class="text-right">
                                {{ $invoice->formatCurrency($item->discount) }}
                            </td>               
                        </tr>                       
                    @endif
                @endforeach
                <tr>
                    <th colspan="2" class="border-0">Long Term Assets</th>
                </tr>
                @foreach($invoice->items as $item)
                    @php 
                        $desc = explode('::', $item->description);
                        $account = $desc[0];
                        $type = $desc[1];
                    @endphp
                    @if($account == 'Assets' && $type == 'long term')
                        <tr>
                            @php $assets += $item->price_per_unit ?? $item->discount; @endphp
                            <td class="">
                                {{ $item->title }}
                            </td>                        
                            <td class="text-right">
                                {{ $invoice->formatCurrency($item->discount) }}
                            </td>                        
                        </tr>                       
                    @endif
                @endforeach
                <tr>
                    <th class="">Total Assets</th>
                    <th class="text-right pr-0 total-amount">{{ $invoice->formatCurrency($assets) }}</th>
                </tr>
                 <tr>
                    <th colspan="2" class="border-0">Liabilities</th>
                </tr>
                <tr>
                    <th colspan="2" class="border-0">Current Liabilities</th>
                </tr>
                @foreach($invoice->items as $item)
                    @php
                        $desc = explode('::', $item->description);
                        $account = $desc[0];
                        $type = $desc[1];
                    @endphp
                    @if($account == 'Liabilities' && $type == 'current')
                       
                        <tr>
                            @php $liabilities += $item->price_per_unit ?? $item->discount; @endphp
                            <td class="">
                                {{ $item->title }}
                            </td>                        
                            <td class="text-right">
                                {{ $invoice->formatCurrency($item->price_per_unit ?? $item->discount) }}
                            </td>                    
                        </tr>                        
                    @endif
                @endforeach

                <tr>
                    <th colspan="2" class="border-0">Long Term Liabilities</th>
                </tr>
                @foreach($invoice->items as $item)
                    @php
                        $desc = explode('::', $item->description);
                        $account = $desc[0];
                        $type = $desc[1];
                    @endphp
                    @if($account == 'Liabilities' && $type == 'long term')                       
                        <tr>
                            @php $liabilities += $item->price_per_unit ?? $item->discount; @endphp
                            <td class="">
                                {{ $item->title }}
                            </td>                        
                            <td class="text-right">
                                {{ $invoice->formatCurrency($item->price_per_unit ?? $item->discount) }}
                            </td>                    
                        </tr>                        
                    @endif
                @endforeach
                
                @php $bf = 0; @endphp
                @foreach($invoice->buyer->custom_fields as $key => $value)
                    @if($key  == 'Balance C/F' && $value == 0)
                        @php $bf = $value; @endphp
                    @endif
                @endforeach
                <!-- //ad/d c/f or balance -->
                @if($bf > 0)
                    <tr>
                    
                        <td class="">
                        {{ ucfirst('Balance C/F') }}
                        </td>
                    
                        <td class="text-right">
                            {{ $invoice->formatCurrency($bf) }}
                        </td>
                    </tr>
                @elseif($liabilities > $assets)
                    <tr>
                        <td class="">
                            Balance C/F
                        </td>
                        
                        <td class="text-right">
                            {{ $invoice->formatCurrency($liabilities-$assets) }}
                            @php $assets += $liabilities - $assets;@endphp
                        </td>
                    </tr>
                @elseif($assets > $liabilities)
                    <tr>
                        <td class="">
                            Balance C/F
                        </td>
                        <td class="text-right">
                            {{ $invoice->formatCurrency($assets - $liabilities) }}
                            @php $liabilities += $assets - $liabilities; @endphp
                        </td>
                    </tr>
                @endif
                <tr>
                    <td class="">Total Liabilities</td>
                    <td class="text-right pr-0 total-amount">{{ $invoice->formatCurrency($liabilities) }}</td>
                </tr>
                {{-- Summary --}}
                
                
            </tbody>
        </table>

        @if($invoice->notes)
            <p>
                {{ trans('invoices::invoice.notes') }}: {!! $invoice->notes !!}
            </p>
        @endif
        @else
        <p>No Data Found!</p>
        @endif

        <p>
        </p>

        <script type="text/php">
            if (isset($pdf) && $PAGE_COUNT > 1) {
                $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width);
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
    </body>
</html>
