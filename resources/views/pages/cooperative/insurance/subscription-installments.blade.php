@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        Farmer Subscription Policy No. {{ sprintf('%03d', $subscription->id) }}
                        Installments. Current Limit: {{ number_format($subscription->current_limit) ?? '-' }}.
                        total wallet balance : {{ number_format($total_wallet_balance) }}
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Farmer</th>
                                <th>Product</th>
                                <th>Payment Mode</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_amount = 0;
                                $canEdit = has_right_permission(config('enums.system_modules')['Insurance Product']['insurance_subscription'], config('enums.system_permissions')['edit']);
                            @endphp

                            @foreach($installments as $key => $i)
                                @php $total_amount += $i->amount;  @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ ucwords(strtolower($i->subscription->farmer->user->first_name.' '.$i->subscription->farmer->user->other_names)) }}</td>
                                    <td>{{ $i->subscription->insurance_product->name }}</td>
                                    <td>{{ $i->subscription->payment_mode == \App\InsuranceSubscriber::MODE_MONTHLY ? 'Monthly' : ($i->subscription->payment_mode == \App\InsuranceSubscriber::MODE_QUARTERLY ? 'Quarterly' : 'Annually') }}</td>
                                    <td>{{ $currency.' '.number_format($i->amount,2)}}</td>
                                    <td>{{ $i->due_date }}</td>
                                    <td>
                                        @if($i->status == \App\InsuranceInstallment::STATUS_PENDING)
                                            <badge class="badge badge-danger text-white">Pending</badge>
                                        @elseif($i->status == \App\InsuranceInstallment::STATUS_PAID)
                                            <badge class="badge badge-success text-white">Paid</badge>
                                        @elseif($i->status == \App\InsuranceInstallment::STATUS_PARTIALLY_PAID)
                                            <badge class="badge badge-warning text-white">Partially Paid</badge>
                                        @endif
                                    </td>
                                    <td>
                                        @if($canEdit)
                                            <button type="button" class="btn btn-info btn-rounded btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#payModal_{{$i->id}}">
                                                <i class="mdi mdi-cash"></i>
                                                Pay
                                            </button>
                                        @endif
                                        <div class="modal fade" id="payModal_{{$i->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$i->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$i->id}}">
                                                            Pay Installment ({{ number_format($i->amount) }})
                                                            Wallet Balance: {{ number_format($total_wallet_balance)}}
                                                        </h5>

                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <form action="{{ route('cooperative.subscription.installment.pay',$i->id ) }}"
                                                              method="post">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <div class="form-group col-12">
                                                                        <label for="source_{{$i->id}}">Source</label>
                                                                        <select name="source"
                                                                                id="source_{{$i->id}}"
                                                                                class=" form-control select2bs4 {{ $errors->has('source') ? ' is-invalid' : '' }}">
                                                                            <option value="">---Select Source---
                                                                            </option>
                                                                            <option value="{{\App\InsuranceInstallment::SOURCE_WALLET}}">
                                                                                Wallet
                                                                            </option>
                                                                            <option value="{{\App\InsuranceInstallment::SOURCE_MPESA}}">
                                                                                M-PESA <small>(coming soon)</small>
                                                                            </option>
                                                                        </select>
                                                                        @if ($errors->has('source'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('source')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="amount_{{$i->id}}">Amount</label>
                                                                        <input type="number" name="amount"
                                                                               id="amount_{{$i->id}}"
                                                                               class="form-control"
                                                                               placeholder="{{ceil($i->amount)}}"
                                                                               value="{{ old('amount') }}"
                                                                               min="1"
                                                                               max="{{ceil($i->amount)}}"
                                                                               required
                                                                        >
                                                                        @if ($errors->has('amount'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('amount')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Make
                                                                    Payment
                                                                </button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="4">Total</th>
                                <th colspan="4">{{ $currency.' '.number_format($total_amount, 2, '.',',')}}</th>
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
