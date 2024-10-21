@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#farmerPaymentAccordion"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="farmerPaymentAccordion">
                        <span class="mdi mdi-plus"></span>Filter Payments
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="farmerPaymentAccordion">
                        <form action="{{ route('cooperative.wallet.show_payment_histories', $farmer_id) }}" method="get">
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <h6 class="mb-3">Filter Details</h6>
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="from">From</label>
                                    <input type="date" name="from"
                                           class="form-control {{ $errors->has('From') ? ' is-invalid' : '' }}"
                                           id="from" value="{{request()->from}}"
                                           required>

                                    @if ($errors->has('from'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('from')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="to">To</label>
                                    <input type="date" name="to"
                                           class="form-control {{ $errors->has('To') ? ' is-invalid' : '' }}"
                                           id="to" value="{{request()->to}}"
                                           required>

                                    @if ($errors->has('to'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('to')  }}</strong>
                                </span>
                                    @endif
                                </div>
                            </div>
                            <hr class="mt-1 mb-1">
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">

                <div class="card-body">
                    <a class="btn btn-sm btn-info float-right text-white"
                       href="{{ route('cooperative.wallet.download_payment_histories',['csv',$farmer_id]) }}">
                        <i class="mdi mdi-download"></i> CSV
                    </a>

                    <a class="btn btn-sm btn-github float-right text-white"
                       href="{{ route('cooperative.wallet.download_payment_histories',['xlsx',$farmer_id]) }}"
                       style="margin-right: -5px!important;">
                        <i class="mdi mdi-download"></i> Excel
                    </a>
                    <a class="btn btn-sm btn-success float-right text-white"
                       href="{{ route('cooperative.wallet.download_payment_histories',['pdf',$farmer_id]) }}"
                       style="margin-right: -5px!important;">
                        <i class="mdi mdi-download"></i> PDF
                    </a>
                    <h4 class="card-title">Farmer Payment Statements</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Reference</th>
                                <th>Initiator</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $total_amount = 0;
                                $currency = Auth::user()->cooperative->currency;
                            @endphp
                            @foreach($payments as $key=>$payment)
                                @php $total_amount += $payment->amount @endphp
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{ucwords(strtolower($payment->type))}}</td>
                                    <td>{{$payment->reference}}</td>
                                    <td>{{ucwords(strtolower($payment->initiator->first_name.' '.$payment->initiator->other_names))}}</td>
                                    <td>{{$payment->description}}</td>
                                    <td>{{$payment->updated_at}}</td>
                                    <td>{{ $currency }} {{ number_format($payment->amount, 2, '.', ',')}}</td>
                                    <td>
                                        <a href="{{route('farmer-receipt',$payment->id)}}"
                                           class="btn btn-primary btn-rounded btn-sm">
                                            <span class="mdi mdi-printer"></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="6">Total</th>
                                <th colspan="2">{{ $currency }} {{ number_format($total_amount, 2, '.',',')}}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
