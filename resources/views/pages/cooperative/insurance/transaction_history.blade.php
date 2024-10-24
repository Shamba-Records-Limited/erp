@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#filterTransactions"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="filterTransactions">
                        <span class="mdi mdi-search-web"></span>Filter
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="filterTransactions">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Filter Transactions</h4>
                            </div>
                        </div>
                        <form action="{{ route('cooperative.insurance.trxn-hisory') }}" method="get">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="farmer">Farmer</label>
                                    <select name="farmer" id="farmer"
                                            class=" form-control form-select {{ $errors->has('farmer') ? ' is-invalid' : '' }}"
                                            onchange="getSubscriptionByFarmer('farmer', 'subscription')">
                                        <option value="">---Select Farmer---</option>
                                        @foreach($farmers as $farmer)
                                            <option value="{{$farmer->id}}" {{ old('farmer') == $farmer->id ? 'selected' : '' }}>
                                                {{ucwords(strtolower($farmer->first_name.' '.$farmer->other_names))}}
                                            </option>
                                        @endforeach

                                    </select>
                                    @if ($errors->has('farmer'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('farmer')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="subscription">Subscription</label>
                                    <select name="subscription" id="subscription"
                                            class=" form-control form-select {{ $errors->has('subscription') ? ' is-invalid' : '' }}">

                                    </select>
                                    @if ($errors->has('subscription'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('subscription')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="type">Transaction Type</label>
                                    <select name="type" id="type"
                                            class=" form-control form-select {{ $errors->has('type') ? ' is-invalid' : '' }}">
                                        <option value="">--Select Type--</option>
                                        <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>Claim</option>
                                        <option value="2" {{ old('type') == 2 ? 'selected' : '' }}>Installment</option>
                                        <option value="3" {{ old('type') == 3 ? 'selected' : '' }}>Reversed Claim
                                        </option>
                                    </select>
                                    @if ($errors->has('type'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('type')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="purpose">Date Range</label>
                                    <input type="text" name="dates"
                                           class="form-control date-range{{ $errors->has('start_date') ? ' is-invalid' : '' }}"
                                           id="dates" value="{{ old('dates') }}">
                                    @if ($errors->has('purpose'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('purpose')  }}</strong>
                                </span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-3 col-12">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block" id="submit-btn">
                                        Filter
                                    </button>
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
                    @if(has_right_permission(config('enums.system_modules')['Insurance Product']['reports'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('cooperative.insurance.trxn-hisory.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('cooperative.insurance.trxn-hisory.download','xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('cooperative.insurance.trxn-hisory.download', 'pdf') }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Transaction History</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Farmer</th>
                                <th>Product</th>
                                <th>Policy No</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Comments</th>
                                <th>Date</th>
                                <th>Created By</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_amount = 0;
                            @endphp
                            @foreach($trxns as $key => $trxn)
                                @php $total_amount += $trxn->amount @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ ucwords(strtolower($trxn->subscription->farmer->user->first_name.' '.$trxn->subscription->farmer->user->other_names)) }}</td>
                                    <td>{{ $trxn->subscription->insurance_product->name }}</td>
                                    <td>
                                        <a href="{{ route('cooperative.insurance.subscription.installments', $trxn->subscription->id) }}">{{ sprintf('%03d', $trxn->subscription->id) }}</a>
                                    </td>
                                    <td>
                                        @if($trxn->type == \App\InsuranceTransactionHistory::TYPE_REJECT_CLAIM)
                                            <badge class="badge badge-danger text-white">Claim Rejected</badge>
                                        @elseif($trxn->type == \App\InsuranceTransactionHistory::TYPE_INSTALLMENT)
                                            <badge class="badge badge-success text-white">Paid Installment</badge>
                                        @elseif($trxn->type == \App\InsuranceTransactionHistory::TYPE_CLAIM)
                                            <badge class="badge badge-info text-white">New Claim</badge>
                                        @endif
                                    </td>
                                    <td> {{ $currency.' '.number_format($trxn->amount) }}</td>
                                    <td> {{ $trxn->comments }}</td>
                                    <td> {{ $trxn->date }}</td>
                                    <td> {{ $trxn->createdBy->first_name.' '.$trxn->createdBy->other_names}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="5">Total</th>
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
    <script>
        function getSubscriptionByFarmer(fieldA, fieldB) {
            const farmer_id = $("#" + fieldA).val()
            let htmlCode = '';
            $("#" + fieldB).text('')

            if (farmer_id) {
                let url = "<?php echo e(route('cooperative.subscription-by-farmer', ':farmer_id')); ?>"
                url = url.replace(':farmer_id', farmer_id);
                axios.post(url).then(res => {
                    const data = res.data
                    if (data.subscriptions.length > 0) {
                        htmlCode += `<option value="">---Select Subscription---</option>`;
                        data.subscriptions.forEach(s => {
                            htmlCode += `<option value="${s.id}">${s.insurance_product.name}</option>`;
                        });
                    } else {
                        htmlCode += `<option value="">---No Subscription---</option>`;
                    }

                    $("#" + fieldB).append(htmlCode)

                }).catch((e) => {
                    console.log(e)
                    htmlCode += `<option value="">---No Subscription---</option>`;
                    $("#" + fieldB).append(htmlCode);
                })

            } else {
                htmlCode += `<option value="">---No Subscription---</option>`;
                $("#" + fieldB).append(htmlCode);
            }
        }
    </script>
    @include('pages.charts.mini-dashboards.financial-products')
    <script>
      dateRangePickerFormats("dates")
    </script>
@endpush
