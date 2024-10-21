@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#addInsuranceSubscription"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="addInsuranceSubscription">
                        <span class="mdi mdi-plus"></span>Send a  Subscription Request
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="addInsuranceSubscription">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Send a Subscription Request</h4>
                            </div>
                        </div>


                        <form action="{{ route('insurance.subscription') }}" method="post">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="product">Product</label>
                                    <select name="product" id="product"
                                            class=" form-control select2bs4 {{ $errors->has('product') ? ' is-invalid' : '' }}">
                                        <option value="">---Select Product---</option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}" {{ old('valuation') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('product'))
                                        <span class="help-block text-danger">
                                                <strong>{{ $errors->first('product')  }}</strong>
                                            </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="valuation">Valuation</label>
                                    <select name="valuation" id="valuation"
                                            class=" form-control select2bs4 {{ $errors->has('valuation') ? ' is-invalid' : '' }}">
                                        <option value="">---Select Valuation---</option>
                                        @foreach($valuations as $v)
                                            <option value="{{$v->id}}" {{ old('valuation') == $v->id ? 'selected' : '' }}>{{ $v->type }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('valuation'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('valuation')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="payment_mode">Mode</label>
                                    <select name="payment_mode" id="payment_mode"
                                            class=" form-control select2bs4 {{ $errors->has('payment_mode') ? ' is-invalid' : '' }}">
                                        <option value="">---Select Mode---</option>
                                        <option value="{{\App\InsuranceSubscriber::MODE_WEEKLY}}" {{ old('payment_mode') == \App\InsuranceSubscriber::MODE_WEEKLY ? 'selected' : '' }}>Weekly</option>
                                        <option value="{{\App\InsuranceSubscriber::MODE_MONTHLY}}" {{ old('payment_mode') == \App\InsuranceSubscriber::MODE_MONTHLY ? 'selected' : '' }}>Monthly</option>
                                        <option value="{{\App\InsuranceSubscriber::MODE_QUARTERLY}}" {{ old('payment_mode') == \App\InsuranceSubscriber::MODE_QUARTERLY ? 'selected' : '' }}>Quarterly</option>
                                        <option value="{{\App\InsuranceSubscriber::MODE_ANNUALLY}}" {{ old('payment_mode') == \App\InsuranceSubscriber::MODE_ANNUALLY ? 'selected' : '' }}>Annually</option>
                                    </select>
                                    @if ($errors->has('payment_mode'))
                                        <span class="help-block text-danger">
                                                <strong>{{ $errors->first('payment_mode')  }}</strong>
                                            </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="period">Period (in years)</label>
                                    <input type="number" name="period"
                                           class="form-control {{ $errors->has('period') ? ' is-invalid' : '' }}"
                                           id="period" placeholder="2" value="{{ old('period')}}" required>

                                    @if ($errors->has('period'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('period')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="grace_period">Grace Period (days)</label>
                                    <input type="number" name="grace_period"
                                           class="form-control {{ $errors->has('period') ? ' is-invalid' : '' }}"
                                           value="{{ 'grace_period' }}">
                                    @if ($errors->has('grace_period'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('grace_period')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="penalty">Penalty Rate</label>
                                    <input name="penalty" type="text"
                                           class="form-control {{ $errors->has('period') ? ' is-invalid' : '' }}"
                                           value="{{ old('penalty') }}" id="penalty" required>
                                    @if ($errors->has('penalty'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('penalty')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-3 col-12">
                                    <label for="RouteName"></label>
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Request</button>
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
                    <h4 class="card-title">My Subscriptions</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Policy No.</th>
                                <th>Product</th>
                                <th>Payment Mode</th>
                                <th>Period</th>
                                <th>Expiry Date</th>
                                <th>Valuated</th>
                                <th>Grace period</th>
                                <th>Penalty</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $currency = Auth::user()->cooperative->currency @endphp
                            @foreach($subscriptions as $key => $subscription)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>
                                        <a href="{{ route('insurance.subscription.installments', $subscription->id) }}">{{ sprintf('%03d', $subscription->id) }} </a>
                                    </td>
                                    <td>{{ $subscription->insurance_product->name }}</td>
                                    <td>
                                        {{$subscription->payment_mode == \App\InsuranceSubscriber::MODE_MONTHLY ? 'Monthly' :
                                            ($subscription->payment_mode == \App\InsuranceSubscriber::MODE_QUARTERLY ? 'Quarterly' :
                                             ($subscription->payment_mode == \App\InsuranceSubscriber::MODE_WEEKLY ? 'Weekly' : 'Annually'))}}
                                    </td>
                                    <td>{{ $subscription->period.' Years'  }}</td>
                                    <td>{{ $subscription->expiry_date }}</td>
                                    <td>{{ $subscription->insurance_valuation_id ? 'Yes' : 'No' }}</td>
                                    <td>{{ $subscription->grace_period.' days' }}</td>
                                    <td>{{ $subscription->penalty.'%'}}</td>
                                    <td>
                                        @if($subscription->status == \App\InsuranceSubscriber::STATUS_CANCELLED)
                                            <badge class="badge badge-danger text-white">Cancelled</badge>
                                        @elseif($subscription->status == \App\InsuranceSubscriber::STATUS_ACTIVE)
                                            <badge class="badge badge-success text-white">Active</badge>
                                        @elseif($subscription->status == \App\InsuranceSubscriber::STATUS_REDEEMED)
                                            <badge class="badge badge-info text-white">Redeemed</badge>
                                        @elseif($subscription->status == \App\InsuranceSubscriber::STATUS_DEFAULTED_GRACE_PERIOD)
                                            <badge class="badge badge-warning text-white">Defaulted: In grace period
                                            </badge>
                                        @elseif($subscription->status == \App\InsuranceSubscriber::STATUS_REDEEMED_PENALTY)
                                            <badge class="badge badge-danger text-white">Redeemed with penalty</badge>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('insurance.subscription.installments', $subscription->id) }}">
                                            Installments</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
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
