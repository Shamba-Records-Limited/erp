@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    @php
        $hasPaid = $sale->balance == 0;
        $currency = $sale->cooperative->currency;
        $total_balance = 0;
        $total_amount = 0;
        $phone = $sale->farmer_id ? '0'.substr($sale->farmer->phone_no, -9) : '0'.substr($sale->customer->phone_number,-9);

    @endphp

    @if(has_right_permission(config('enums.system_modules')['Sales']['invoice'], config('enums.system_permissions')['edit']) && !$hasPaid)
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                                data-toggle="collapse"
                                data-target="#makeSale"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addVetItems"><span class="mdi mdi-plus"></span>Add
                            Payment
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="makeSale">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Payment</h4>
                                </div>
                            </div>
                            <form action="{{ route('sales.invoice.pay') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="mode">Payment Mode</label>
                                        <select name="mode" class="form-control select2bs4 "
                                                id="mode"
                                                required
                                                onchange="alterDisplay('mode', 'mpesa-1', 'mpesa-2', '{{$phone}}')">
                                            <option value=""> {{ '- Select Mode-'}}</option>
                                            <option value="{{\App\InvoicePayment::PAYMENT_MODE_MPESA_OFFLINE}}">{{'MPESA Offline'}}</option>
                                            <option value="{{\App\InvoicePayment::PAYMENT_MODE_MPESA_STK_PUSH}}">{{'MPESA STK Push'}}</option>
                                            <option value="{{\App\InvoicePayment::PAYMENT_MODE_BANK}}"> {{'Bank'}}</option>
                                            <option value="{{\App\InvoicePayment::PAYMENT_MODE_CASH}}"> {{'Cash'}}</option>
                                            @if($sale->farmer_id)
                                                <option value="{{\App\InvoicePayment::PAYMENT_MODE_WALLET}}">{{ 'Wallet'}}</option>
                                            @endif
                                        </select>
                                        @if ($errors->has('mode'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('mode')  }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="amount">Amount</label>
                                        <input type="number" class="form-control" id="amount"
                                               placeholder="Enter Amount" name="amount"
                                               value="{{ old('amount') }}"
                                               min="1"
                                               required>
                                        <small class="text-danger text-small d-none"
                                               id="wallet_hint">
                                            @if($wallet)
                                                @php $maxPayable = max($wallet->current_balance, $wallet->available_balance);@endphp
                                                {{ 'Current Balance: '.number_format($wallet->current_balance).' Available Balance: '.number_format($wallet->available_balance).' Maximum payable: '.number_format($maxPayable) }}
                                            @else
                                                {{ 'Wallet balance is 0. Use another method' }}
                                            @endif
                                        </small>
                                        @if ($errors->has('amount'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('amount')  }}</strong>
                                            </span>
                                        @endif
                                        <input type="hidden" value="{{ $invoice_id }}"
                                               class="form-control"
                                               name="invoice_id" required>
                                        <input type="hidden" value="{{ $sale_id }}"
                                               class="form-control" name="sale_id"
                                               required>
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 mpesa-2 {{ !$errors->has('phone') ? 'd-none' : ''}}">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" id="phone"
                                               placeholder="Enter Phone"
                                               name="phone"
                                        >
                                        @if ($errors->has('phone'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('phone')  }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12 mpesa-1 {{ !$errors->has('reference') ? 'd-none' : ''}}">
                                        <label for="reference">Reference Code</label>
                                        <input type="text" class="form-control" id="reference"
                                               placeholder="Enter Ref Code"
                                               name="reference"
                                               value="{{old('reference')}}"
                                        >
                                        @if ($errors->has('reference'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('reference')  }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12 mpesa-1 {{ !$errors->has('instructions') ? 'd-none' : ''}}">
                                        <label for="instructions">Instructions</label>
                                        <textarea class="form-control" id="instructions"
                                                  placeholder="Instructions"
                                                  name="instructions">{{old('instructions')}}</textarea>
                                        @if ($errors->has('instructions'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('instructions')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <button type="submit"
                                                class="btn btn-primary btn-fw btn-block">Add
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="float-left mt-2">
                        @if(has_right_permission(config('enums.system_modules')['Sales']['invoice'], config('enums.system_permissions')['download']))
                            @if($payments->count() > 0)
                                <a href="{{ route('payment.receipt.pdf', $sale_id)}}"
                                   target="_blank"
                                   class="btn btn-success   btn-sm">
                                    <span class="mdi mdi-printer"></span> Print Receipt
                                </a>
                            @endif
                        @endif
                        <div class="mt-2 mb-2">
                            <p>
                                <b>Customer:</b> {{ $sale->farmer_id ? $sale->farmer->user->first_name." ".$sale->farmer->user->other_names : $sale->customer->name }}
                            </p>
                            <p>
                                <b>Email:</b> {{ $sale->farmer_id ? $sale->farmer->user->email : $sale->customer->email }}
                            </p>
                            <p>
                                <b>Sale Batch
                                    Number:</b> {{ $sale->sale_batch_number.'-'.$sale->sale_count}}
                            </p>

                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Txn code</th>
                                <th>Paid via</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($payments as $key=>$payment)
                                @php
                                    $total_amount += $payment->amount;
                                @endphp
                                <tr>
                                    <td>{{ ++$key}}</td>
                                    <td>{{ $currency }} {{ $payment->amount}}</td>
                                    <td>{{ $payment->transaction_number}}</td>
                                    <td>{{ \App\InvoicePayment::paymentModsDisplay[$payment->payment_platform]}}</td>
                                    <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('Y M d H:i:s')}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="1">Total</th>
                                <th colspan="4">Paid: {{$currency}} {{$total_amount}}</th>
                            </tr>
                            <tr>
                                <th colspan="1">Total</th>
                                <th colspan="4">
                                    Balance: {{$currency}} {{ number_format($sale->balance)}}</th>
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
    <script>
      const alterDisplay = (parentId, targetClass1, targetClass2, phone) => {
        const mode = $('#mode').val();
        if (mode == '{{\App\InvoicePayment::PAYMENT_MODE_MPESA_STK_PUSH}}') {

          $('.' + targetClass1).addClass('d-none')
          $('.' + targetClass2).removeClass('d-none')
          $('#phone').val(phone)
        } else if (mode === "") {
          $('.' + targetClass1).addClass('d-none')
          $('.' + targetClass2).addClass('d-none')
          $('#phone').val('')
        } else {
          $('.' + targetClass1).removeClass('d-none')
          $('.' + targetClass2).addClass('d-none')
          $('#phone').val('')
        }

        if (mode == '{{\App\InvoicePayment::PAYMENT_MODE_WALLET}}') {
          $("#wallet_hint").removeClass('d-none')
        } else {
          $("#wallet_hint").addClass('d-none')
        }
      }
    </script>
@endpush
