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
        margin-bottom: 0.1rem;
        font-weight: 300;
        line-height: 1;
        font-size: 1.2rem;
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
            <div>
                @if($user->cooperative->logo)
                    <img src="{{ $user->cooperative->logo }}" alt="logo" height="100">
                @else
                    <img src="{{ public_path('assets/images/favicon.png') }}" alt="logo"
                         height="100">
                @endif
                <h4>{{ ucwords(strtolower($user->cooperative->name))}}.<br>{{$records['title']}}
                </h4>
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

<br>
<table id="content" class="table">
    <thead>
    <tr>
        <th>Employee</th>
        <th>Employee No.</th>
        @foreach(config('enums.Months') as $month)
            <th>{{$month}} ({{$currency}})</th>
        @endforeach
        <th> Total ({{$currency}})</th>
    </tr>
    </thead>

    <tbody>

    @php
        $monthly_total = [
            "0" => 0,
            "1" => 0,
            "2" => 0,
            "3" => 0,
            "4" => 0,
            "5" => 0,
            "6" => 0,
            "7" => 0,
            "8" => 0,
            "9" => 0,
            "10" => 0,
            "11" => 0,
]
    @endphp

    @foreach($records['payData'] as $data)
        <tr>

            @php
                $total = 0;
            @endphp

            <td> {{ $data["name"] }}</td>
            <td> {{ $data["emp_no"] }}</td>
            @foreach(config('enums.Months') as $key => $month)
                <td>
                    @if(array_key_exists(--$key,$data["data"] ))
                        @php
                            if($data['report_type'] == \App\EmployeeSalary::REPORT_TYPE_GROSS_PAY){
                                $total += $data["data"][$key]->gross_pay;
                                $monthly_total[$key] += $data["data"][$key]->gross_pay;
                            }else{
                                $total += $data["data"][$key]->net_pay;
                                $monthly_total[$key] += $data["data"][$key]->net_pay;
                            }
                        @endphp
                        {{ $data['report_type'] == \App\EmployeeSalary::REPORT_TYPE_GROSS_PAY ?
                            number_format($data["data"][$key]->gross_pay, 2) :
                             number_format($data["data"][$key]->net_pay, 2)
                            }}
                    @else

                        {{ number_format(0,2) }}
                    @endif
                </td>
            @endforeach
            <td><b>{{ number_format($total,2) }} </b></td>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr>

        @php $overall_total_balance = 0;@endphp
        <th colspan="2">Total ({{ $currency }})</th>
        @foreach($monthly_total as $total)
            @php $overall_total_balance += $total;@endphp
            <th> {{ number_format($total,2) }}</th>
        @endforeach
        <th> {{ number_format($overall_total_balance,2) }}</th>
    </tr>

    </tfoot>
</table>
</body>
</html>
