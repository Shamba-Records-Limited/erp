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
    <table class="table" style="width: 100%">
        <tbody>
            <tr style="vertical-align: center;">
                <td style="width: 5.2em;">
                    <img src="{{ asset('storage/logos/' . $logo) }}" alt="Logo" style="height: 5em; width: 5em;">
                </td>
            </tr>
        </tbody>
    </table>
 <div class="d-flex align-items-center">
       @php $user = Auth::user();
                        
        @endphp

         <p class="profile-name">
            @if($user)
            @if ($user->cooperative)
            <strong>{{ ucwords(strtolower($user->cooperative->name)) }}</strong><br>
            @elseif ($user->miller_admin && $user->miller_admin->miller)
            <strong>{{ ucwords(strtolower($user->miller_admin->miller->name)) }}</strong><br>
            @endif
            <!-- <p class="semi-bold">
            {{ ucwords(strtolower($user->first_name)) }}
            {{ ucwords(strtolower($user->other_names)) }}
            </p> -->
            @endif
        </p>
    </div>
    <h4 class="text-uppercase">
        <strong>{{ $title }}</strong>
    </h4>

    <table class="table table-items">
        <thead>
            <tr>
            @if(!empty($records) && is_array($records))
                    @foreach(array_keys($records[0]) as $header)
                        <th scope="col" class="border-0">{{ $header }}</th>
                    @endforeach
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($records as $item)
                <tr>
                    @foreach($item as $key => $value) 
                        <td>{{ $value }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
{!! QrCode::size(200)->generate($title) !!}
    </div>
</body>
</html>