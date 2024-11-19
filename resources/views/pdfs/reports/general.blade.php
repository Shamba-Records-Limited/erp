<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{$title}}</title>
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
    </style>
</head>

<body>
    <div>
        <h6 class="text-uppercase cool-gray text-right">
            Date Generated:
            <strong>{{ $period }}</strong>
        </h6>
    </div>

    <table class="table" style="width: 100%">
        <tbody>
            <tr style="vertical-align: center;">
                <td style="width: 5.2em;">
                    <!-- <img src="{{ public_path('assets/images/shamba_records_logo.jpeg') }}" alt="logo" style="height: 5em; width: 5em;" /> -->
                    <img src="{{ public_path('storage/' . $logo) }}" alt="Logo" style="height: 5em; width: 5em;" />
                </td>
                <!-- <td class="text-left">
                    <div style="font-size: 3em;">Shamba Records</div>
                </td> -->
            </tr>
        </tbody>
    </table>

    <h4 class="text-uppercase">
        <strong>{{ $title }}</strong>
    </h4>

    {{-- Table --}}
    <table class="table table-items">
        <thead>
            <tr>
                <th scope="col" class="border-0">{{ '#' }}</th>
                @foreach($columns as $column)
                <th scope="col" class="border-0">{{ $column['name'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($records as $key => $item)
            <tr>
                <td class="text-left">
                    {{ ++$key }}
                </td>
                @foreach($columns as $column)
                <td class="text-left">
                    {{ $item[$column['key']] }}
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    {{-- /Table --}}

    @if($summation)
    <div style="text-align: right; font-size: 1.5em; font-weight: bold;">
        <strong>Total: {{$summation}}</strong>
    </div>
    @endif

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