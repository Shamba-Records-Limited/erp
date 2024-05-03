<!DOCTYPE html>
<html lang="en">

<head>
    <title>Ledger</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

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

        .page_break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    {{-- Header --}}

    @if(Auth::user()->cooperative->logo)
    <img src="{{ Auth::user()->cooperative->logo }}" alt="logo" height="100">
    @else
    <img src="{{ public_path('assets/images/favicon.png') }}" alt="logo" height="100">
    @endif


    <table class="table mt-5">
        <tbody>
            <tr>
                <td class="border-0 pl-0" width="70%">
                    <h4 class="text-uppercase">
                        <strong>{{ Auth::user()->cooperative->name }} {{ $title }}</strong>
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
        <caption><strong>Farmer Crop Calendars</strong></caption>
        <thead>
            <tr>
                <th scope="col" class="border-0">{{ '#' }}</th>
                <th scope="col" class="border-0">{{ 'Farmer' }}</th>
                <th scope="col" class="border-0">{{ 'Crop' }}</th>
                <th scope="col" class="border-0">{{ 'Crop Variety' }}</th>
                <th scope="col" class="border-0">{{ 'Current Stage' }}</th>
                <th scope="col" class="border-0">{{ 'Start Date' }}</th>
                <th scope="col" class="border-0">{{ 'Last Date' }}</th>
                <th scope="col" class="border-0">{{ 'Next Stage' }}</th>
                <th scope="col" class="border-0">{{ 'Total Cost' }}</th>

            </tr>
        </thead>
        <tbody>
            @php
            $currency = Auth::user()->cooperative->currency;
            $key = 0;
            @endphp
            @foreach($records as $farmercrops)
            @if($farmercrops-> type == 1)
            <tr>
                <td class="text-left">
                    {{ ++$key }}
                </td>
                <td class="text-left">
                    {{ ucwords( strtolower($farmercrops->farmer->user->first_name).' '.strtolower($farmercrops->farmer->user->other_names)) }}
                </td>
                <td class="text-left">
                    {{ $farmercrops->crop->product_id ?  ucwords( strtolower($farmercrops->crop->product->name)) : '-' }}
                </td>
                <td class="text-left">
                    {{ ucwords( strtolower($farmercrops->crop->variety)) }}
                </td>
                <td class="text-left">
                    {{ ucwords( strtolower($farmercrops->stage->name)) }}
                </td>
                <td class="text-left">
                    {{ $farmercrops->start_date }}
                </td>
                <td class="text-left">
                    {{ $farmercrops->last_date }}
                </td>
                <td class="text-left">
                    {{ $farmercrops->next_stage ? ucwords( strtolower($farmercrops->next_stage->name)) : '-' }}
                </td>
                <td class="text-left">
                    {{ $currency.' '.$farmercrops->total_cost }}
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    <div class="page_break"></div>

    {{-- Table --}}
    <table class="table table-items">
        <caption><strong>Farmer Livestock/Poultry Calendars</strong></caption>
        <thead>
            <tr>
                <th scope="col" class="border-0">{{ '#' }}</th>
                <th scope="col" class="border-0">{{ 'Farmer' }}</th>
                <th scope="col" class="border-0">{{ 'Livestock' }}</th>
                <th scope="col" class="border-0">{{ 'Breed' }}</th>
                <th scope="col" class="border-0">{{ 'Current Stage' }}</th>
                <th scope="col" class="border-0">{{ 'Start Date' }}</th>
                <th scope="col" class="border-0">{{ 'Last Date' }}</th>
                <th scope="col" class="border-0">{{ 'Next Stage' }}</th>
                <th scope="col" class="border-0">{{ 'Total Cost' }}</th>
            </tr>
        </thead>
        <tbody>
            @php
            $currency = Auth::user()->cooperative->currency;
            $key = 0;
            @endphp
            @foreach($records as $farmercrops)
            @if($farmercrops->type == 2)
            <tr>
                <td class="text-left">
                    {{ ++$key }}
                </td>
                <td class="text-left">
                    {{ ucwords( strtolower($farmercrops->farmer->user->first_name).' '.strtolower($farmercrops->farmer->user->other_names)) }}
                </td>
                <td class="text-left">
                    {{ ucwords( strtolower($farmercrops->livestock->name.', '.$farmercrops->livestock->animal_type)) }}
                </td>
                <td class="text-left">
                    {{ ucwords( strtolower($farmercrops->livestock->breed->name)) }}
                </td>
                <td class="text-left">
                    {{ ucwords( strtolower($farmercrops->stage->name)) }}
                </td>
                <td class="text-left">
                    {{ $farmercrops->start_date }}
                </td>
                <td class="text-left">
                    {{ $farmercrops->last_date }}
                </td>
                <td class="text-left">
                    {{ $farmercrops->next_stage ? ucwords( strtolower($farmercrops->next_stage->name)) : '-' }}
                </td>
                <td class="text-left">
                    {{ $currency.' '.$farmercrops->total_cost }}
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
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