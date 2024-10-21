@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">

                <div class="card-body">
                    <a class="btn btn-sm btn-info float-right text-white"
                       href="{{ route('cooperative.farmer.purchases.download',[$farmer->id,'csv']) }}">
                        <i class="mdi mdi-download"></i> CSV
                    </a>

                    <a class="btn btn-sm btn-github float-right text-white"
                       href="{{ route('cooperative.farmer.purchases.download', [$farmer->id,'xlsx']) }}"
                       style="margin-right: -5px!important;">
                        <i class="mdi mdi-download"></i> Excel
                    </a>
                    <h4 class="card-title">{{ ucwords(strtolower($farmer->user->first_name.' '.$farmer->user->other_names))}} Purchases</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Sale Batch #</th>
                                <th>Amount</th>
                                <th>Discount</th>
                                <th>Total Payable</th>
                                <th>Paid Amount</th>
                                <th>Balance</th>
                                <th>Returns</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $total_amount = 0;
                                $total_balance = 0;
                                $total_discount = 0;
                                $total_sales_amount = 0;
                                $total_paid_amount = 0;
                                $total_returns = 0;
                                $currency = $farmer->user->cooperative->currency;
                            @endphp
                            @foreach($purchases as $key => $p)
                                @php
                                    $payable_amount = $p->amount - $p->discount;
                                    $total_amount += $p->amount;
                                    $total_balance += $p->balance + $p->returns_value;
                                    $total_discount += $p->discount;
                                    $total_sales_amount += $payable_amount;
                                    $total_paid_amount += $p->paid_amount;
                                    $total_returns += $p->returns_value;

                                @endphp
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $p->sale_batch_number}}</td>
                                    <td> {{ $currency.' '.number_format($p->amount,2) }}</td>
                                    <td> {{ $currency.' '.number_format($p->discount, 2) }}</td>
                                    <td> {{ $currency.' '.number_format(($payable_amount),2) }}</td>
                                    <td> {{ $currency.' '.number_format($p->paid_amount,2) }}</td>
                                    <td> {{ $currency.' '.number_format($p->balance + $p->returns_value,2) }}</td>
                                    <td> {{ $currency.' '.number_format($p->returns_value,2) }}</td>
                                    <td>
                                        @if($p->status === \App\Invoice::STATUS_UNPAID)
                                            <span class="badge badge-outline badge-danger">Unpaid</span>
                                        @elseif($p->status === \App\Invoice::STATUS_PARTIAL_PAID)
                                            <span class="badge badge-info">Partially Paid</span>
                                        @elseif($p->status === \App\Invoice::STATUS_PAID)
                                            <span class="badge badge-success text-white"> Paid</span>
                                        @elseif($p->status === \App\Invoice::STATUS_RETURNS_RECORDED)
                                            <span class="badge badge-dark text-white"> Returns Recorded</span>
                                        @else
                                            <span class="badge badge-outline badge-warning">Pending </span>
                                        @endif
                                    </td>
                                    <td> {{ $p->date }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="2">Totals</th>
                                <th colspan="1"> {{ $currency.' '.number_format($total_amount, 2) }}</th>
                                <th colspan="1"> {{ number_format($total_discount, 2) }}</th>
                                <th colspan="1"> {{ $currency.' '.number_format($total_sales_amount, 2) }}</th>
                                <th colspan="1"> {{ $currency.' '.number_format($total_paid_amount, 2) }}</th>
                                <th colspan="1"> {{ $currency.' '.number_format($total_balance, 2) }}</th>
                                <th colspan="3"> {{ $currency.' '.number_format($total_returns, 2) }}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
