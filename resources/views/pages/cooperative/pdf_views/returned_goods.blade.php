<!DOCTYPE html>
<html lang="en">

<head>
    <title>Returned Goods</title>
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

      h4,
      .h4 {
        margin-bottom: 0.5rem;
        font-weight: 500;
        line-height: 1.2;
      }

      h4,
      .h4 {
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

      body,
      h1,
      h2,
      h3,
      h4,
      h5,
      h6,
      table,
      th,
      tr,
      td,
      p,
      div {
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
@php
    $user = Auth::user();
     $total_amount = 0;
     $total_quantity = 0;
@endphp

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
                <strong>{{ $user->cooperative->name }} {{ $title }}</strong>
            </h4>
        </td>
        <td class="border-0 pl-0">
            <h4 class="text-uppercase cool-gray">
                <strong>Date Generated</strong>
            </h4>
            <p>{{ $period }}</p>
        </td>
    </tr>
    </tbody>
</table>


{{-- Table --}}
<table class="table table-items">
    <thead>
    <tr>
        <th scope="col" class="border-0">{{ '#' }}</th>
        <th scope="col" class="border-0">{{ 'Invoice No.' }}</th>
        <th scope="col" class="border-0">{{ 'Customer' }}</th>
        <th scope="col" class="border-0">{{ 'Product' }}</th>
        <th scope="col" class="border-0">{{ 'Quantity' }}</th>
        <th scope="col" class="border-0">{{ 'Amount' }}</th>
        <th scope="col" class="border-0">{{ 'Notes' }}</th>
        <th scope="col" class="border-0">{{ 'Date' }}</th>
        <th scope="col" class="border-0">{{ 'Served By' }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($records as $key => $item)

        @php
            $itemName = $item->manufactured_product_id ? $item->manufactured_product->finalProduct->name : $item->collection->product->name;
            $total_amount += $item->amount;
            $total_quantity += $item->quantity;
            $date = \Carbon\Carbon::parse($item->date);
        @endphp
        <tr>
            <td class="text-left">
                {{ ++$key }}
            </td>
            <td class="text-left">
                {{ $item->sale->invoices->invoice_number.'-'.$item->sale->invoices->invoice_count }}
            </td>
            <td class="text-left">
                {{ $item->sale->farmer_id ? '(Farmer) '.(ucwords(strtolower($item->sale->farmer->user->first_name.' '.$item->sale->farmer->user->other_names))) : $item->sale->customer->name }}
            </td>
            <td class="text-left">
                {{ $itemName }}
            </td>
            <td class="text-left">
                {{number_format($item->quantity) }}
            </td>
            <td class="text-left">
                {{$user->cooperative->currency}} {{ number_format($item->amount) }}
            </td>
            <td class="text-left">
                {{ $item->notes  }}
            </td>
            <td class="text-left">
                {{ $date->format('d M Y') }}
            </td>
            <td class="text-left">
                {{ ucwords(strtolower($item->served_by->first_name.' '.$item->served_by->other_names)) }}
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th colspan="4">Total</th>
        <th colspan="1">{{ number_format($total_quantity)}}</th>
        <th colspan="4">{{ $user->cooperative->currency.' '.number_format($total_amount)}}</th>
    </tr>
    </tfoot>
</table>


<p>
    {{ '' }}
</p>
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
