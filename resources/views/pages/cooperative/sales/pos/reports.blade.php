@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
    @php $currency = Auth::user()->cooperative->currency; $paid = 0;@endphp
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-cube text-danger icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Total Payments</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">
                                    {{ $currency }} {{ number_format(($payments ?? 0)) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> Total Payments
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-receipt text-warning icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Pending Payments</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">
                                    {{ $currency.' '.number_format(($pending_payments??0)) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i> Total
                        pending amounts </p>
                </div>
            </div>
        </div>
        <!-- invoice total -->
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-receipt text-warning icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Returned Goods</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">

                                    {{ $currency.' '. number_format(($returned_goods??0))}}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i> Total
                        Returned Goods  Value </p>
                </div>
            </div>
        </div>
        <!-- quotes total -->
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-receipt text-warning icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Quotations</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">
                                    {{ $currency.' '.number_format(($quotes??0)) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i> Total
                        Quotation Amounts </p>
                </div>
            </div>
        </div>
    </div>
    @php
        $canDownloadQuote = has_right_permission(config('enums.system_modules')['Sales']['quotation'], config('enums.system_permissions')['download']);
        $canDownloadInvoice = has_right_permission(config('enums.system_modules')['Sales']['invoice'], config('enums.system_permissions')['download']);
    @endphp
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Latest Invoices

                    </h2>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice #</th>
                                <th>Products</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Discount</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $overall_total_amount = 0;
                                $overall_total_discount = 0;
                                $overall_total_balance = 0;
                            @endphp
                            @foreach($invoices as $key => $item)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>
                                        @if($canDownloadInvoice)
                                            <a class="text-info"
                                               href="{{ route('sales.pos.invoice.pdf', $item->id)}}">
                                                {{ $item->sale_batch_number.'-'.$item->sale_count}}
                                            </a>
                                        @else
                                            {{ $item->sale_batch_number.'-'.$item->sale_count}}
                                        @endif
                                    </td>
                                    <td>{{ $item->saleItems->count() }}</td>
                                    <td>{{ $item->farmer_id ? $item->farmer->user->first_name : $item->customer->name }}</td>
                                    <td>
                                        @php $tot_amt = 0;$tot_disc = $item->discount+$item->saleItems->sum('discount');@endphp
                                        @if($item->saleItems)
                                            @foreach($item->saleItems as $sale_item)
                                                @php
                                                    $tot_amt += $sale_item->amount*$sale_item->quantity;
                                                @endphp
                                            @endforeach
                                        @endif
                                        @php
                                            $amount = $tot_amt - $tot_disc;
                                            $overall_total_amount += $amount;
                                            $overall_total_discount = $tot_disc;
                                            $balance = $amount-$item->invoices->invoice_payments->sum('amount') - $tot_disc;
                                            $overall_total_balance += $balance;
                                        @endphp
                                        {{$currency.' '.number_format($amount) }}
                                    </td>
                                    <td>
                                        @if($item->invoices && $item->invoices->invoice_payments)
                                            {{ $currency.' '. number_format($balance) }}
                                        @endif
                                    </td>
                                    <td>{{$currency.' '. number_format($tot_disc) }}</td>
                                    <td>{{ \Carbon\Carbon::create($item->date)->format('d M Y') }}</td>
                                    <td>
                                        @if($item->invoices->status === \App\Invoice::STATUS_UNPAID)
                                            <span class="badge badge-outline badge-danger">Unpaid</span>
                                        @elseif($item->invoices->status === \App\Invoice::STATUS_PARTIAL_PAID)
                                            <span class="badge badge-info">Partially Paid</span>
                                        @elseif($item->invoices->status === \App\Invoice::STATUS_PAID)
                                            <span class="badge badge-success text-white"> Paid</span>
                                        @elseif($item->invoices->status === \App\Invoice::STATUS_RETURNS_RECORDED)
                                            <span class="badge badge-dark text-white"> Returns Recorded</span>
                                        @else
                                            <span class="badge badge-outline badge-warning">Pending </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="4">Total</th>
                                <th colspan="1">{{$currency}} {{number_format($overall_total_amount)}}</th>
                                <th colspan="1">{{$currency}} {{number_format($overall_total_balance)}}</th>
                                <th colspan="3">{{$currency}} {{number_format($overall_total_discount)}}</th>
                            </tr>

                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Latest Quotations

                    </h2>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Quote #</th>
                                <th>Products</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Discount</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $overall_total_discount = 0;
                                $overall_total_amount = 0;
                            @endphp
                            @foreach($quotations as $key => $item)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>
                                        @if($canDownloadQuote)
                                            <a class="text-info"
                                               href="{{ route('sales.pos.invoice.pdf', $item->id)}}">
                                                {{ $item->sale_batch_number.'-'.$item->sale_count}}
                                            </a>
                                        @else
                                            {{ $item->sale_batch_number.'-'.$item->sale_count}}
                                        @endif
                                    </td>
                                    <td>{{ $item->saleItems->count() }}</td>
                                    <td>{{ $item->farmer_id ? $item->farmer->user->first_name : $item->customer->name }}</td>
                                    <td>
                                        @php $tot_amt = 0;$tot_disc = $item->discount+$item->saleItems->sum('discount');@endphp
                                        @if($item->saleItems)
                                            @foreach($item->saleItems as $sale_item)
                                                @php
                                                    $tot_amt += $sale_item->amount*$sale_item->quantity;
                                                @endphp
                                            @endforeach
                                        @endif
                                        @php $amount = $tot_amt - $tot_disc; $overall_total_amount +=$amount; $overall_total_discount += $tot_disc; @endphp
                                        {{$currency.' '.number_format($amount) }}
                                    </td>
                                    <td>{{$currency.' '.number_format($tot_disc) }}</td>
                                    <td>{{ \Carbon\Carbon::create($item->date)->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="4">Total</th>
                                <th colspan="1">{{$currency}} {{$overall_total_amount}}</th>
                                <th colspan="2">{{$currency}} {{$overall_total_discount}}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush
