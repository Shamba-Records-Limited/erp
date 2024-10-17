@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Insurance Product']['insurance_subscription'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addInsuranceSubscription"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addInsuranceSubscription">
                            <span class="mdi mdi-plus"></span>Add a Subscription
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addInsuranceSubscription">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add a Subscription</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.insurance.subscription.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="farmer">Farmer</label>
                                        <select name="farmer" id="farmer"
                                                class=" form-control select2bs4 {{ $errors->has('farmer') ? ' is-invalid' : '' }}"
                                                onchange="getValuationsByFarmer('farmer', 'valuation')">
                                            <option value="">---Select Farmer---</option>
                                            @foreach($farmers as $farmer)
                                                <option value="{{$farmer->id}}">{{ucwords(strtolower($farmer->first_name.' '.$farmer->other_names))}}</option>
                                            @endforeach

                                        </select>
                                        @if ($errors->has('farmer'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('farmer')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="product">Product</label>
                                        <select name="product" id="product"
                                                class=" form-control select2bs4 {{ $errors->has('product') ? ' is-invalid' : '' }}">
                                            <option value="">---Select Product---</option>
                                            @foreach($products as $product)
                                                <option value="{{$product->id}}">{{ $product->name }}</option>
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
                                            <option value="{{\App\InsuranceSubscriber::MODE_WEEKLY}}" {{ old('payment_mode') == \App\InsuranceSubscriber::MODE_WEEKLY ? 'selected' : '' }}>
                                                Weekly
                                            </option>
                                            <option value="{{\App\InsuranceSubscriber::MODE_MONTHLY}}" {{ old('payment_mode') == \App\InsuranceSubscriber::MODE_MONTHLY ? 'selected' : '' }}>
                                                Monthly
                                            </option>
                                            <option value="{{\App\InsuranceSubscriber::MODE_QUARTERLY}}" {{ old('payment_mode') == \App\InsuranceSubscriber::MODE_QUARTERLY ? 'selected' : '' }}>
                                                Quarterly
                                            </option>
                                            <option value="{{\App\InsuranceSubscriber::MODE_ANNUALLY}}" {{ old('payment_mode') == \App\InsuranceSubscriber::MODE_ANNUALLY ? 'selected' : '' }}>
                                                Annually
                                            </option>
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
                                        <button type="submit" class="btn btn-primary btn-fw btn-block">Add</button>
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
                    @if(has_right_permission(config('enums.system_modules')['Insurance Product']['insurance_subscription'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('cooperative.insurance.subscriptions.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('cooperative.insurance.subscriptions.download','xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('cooperative.insurance.subscriptions.download', 'pdf') }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Farmer Subscriptions</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Policy No.</th>
                                <th>Farmer</th>
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
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $canEdit = has_right_permission(config('enums.system_modules')['Insurance Product']['insurance_subscription'], config('enums.system_permissions')['edit']);
                            @endphp
                            @foreach($subscriptions as $key => $subscription)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>
                                        <a href="{{ route('cooperative.insurance.subscription.installments', $subscription->id) }}">{{ sprintf('%03d', $subscription->id) }} </a>
                                    </td>
                                    <td>{{ ucwords(strtolower($subscription->farmer->user->first_name.' '.$subscription->farmer->user->other_names)) }}</td>
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
                                        <a href="{{ route('cooperative.insurance.subscription.installments', $subscription->id) }}">
                                            Installments</a>
                                        {{-- Edit modal--}}
                                        @if($canEdit)
                                            <button type="button" class="btn btn-primary btn-rounded btn-sm"
                                                    data-toggle="modal" data-target="#editModal_{{$subscription->id}}">
                                                <i class="mdi mdi-file-edit"></i>
                                                Edit
                                            </button>

                                            <button type="button" class="btn btn-info btn-rounded btn-sm"
                                                    data-toggle="modal" data-target="#viewModal_{{$subscription->id}}">
                                                @php $count = $subscription->dependants ? $subscription->dependants->count() : 0 @endphp
                                                {{ $count > 0 ? "Add Dependants ($count) " : "Add Dependants" }}
                                            </button>
                                        @endif
                                        <div class="modal fade" id="editModal_{{$subscription->id}}" tabindex="-1"
                                             role="dialog" aria-labelledby="editModalLabel_{{$subscription->id}}"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="editModalLabel_{{$subscription->id}}">
                                                            Edit Subscription
                                                        </h5>

                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <form action="{{ route('cooperative.insurance.subscription.edit',$subscription->id ) }}"
                                                              method="post">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <div class="form-group col-12">
                                                                        <label for="farmer_{{$subscription->id}}">Farmer</label>
                                                                        <select name="farmer"
                                                                                id="farmer_{{$subscription->id}}"
                                                                                class=" form-control select2bs4 {{ $errors->has('farmer') ? ' is-invalid' : '' }}"
                                                                                onchange="getValuationsByFarmer('farmer_{{$subscription->id}}', 'valuation_{{$subscription->id}}')">
                                                                            <option value="">---Select Farmer---
                                                                            </option>
                                                                            @foreach($farmers as $farmer)
                                                                                <option value="{{$farmer->id}}" {{ $farmer->id == $subscription->farmer_id ? 'selected' : '' }}>
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

                                                                    <div class="form-group col-12">
                                                                        <label for="product_{{$subscription->id}}">Product</label>
                                                                        <select name="product"
                                                                                id="product_{{$subscription->id}}"
                                                                                class=" form-control select2bs4 {{ $errors->has('product') ? ' is-invalid' : '' }}">
                                                                            <option value="">---Select Product---
                                                                            </option>
                                                                            @foreach($products as $product)
                                                                                <option value="{{$product->id}}" {{ $product->id == $subscription->insurance_product_id ? 'selected' : '' }}>
                                                                                    {{ $product->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        @if ($errors->has('product'))
                                                                            <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('product')  }}</strong>
                                                                    </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="valuation_{{$subscription->id}}">Valuation</label>
                                                                        <label for="valuation">Valuation</label>
                                                                        <select name="valuation"
                                                                                id="valuation_{{$subscription->id}}"
                                                                                class=" form-control select2bs4 {{ $errors->has('valuation') ? ' is-invalid' : '' }}">

                                                                        </select>
                                                                        @if ($errors->has('valuation'))
                                                                            <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('valuation')  }}</strong>
                                                                    </span>
                                                                        @endif

                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="payment_mode_{{$subscription->id}}">Mode</label>
                                                                        <select name="payment_mode"
                                                                                id="payment_mode_{{$subscription->id}}"
                                                                                class=" form-control select2bs4 {{ $errors->has('payment_mode') ? ' is-invalid' : '' }}">
                                                                            <option value="">---Select Mode---</option>
                                                                            <option value="1" {{$subscription->payment_mode == '1' ? 'selected' : ''}}>
                                                                                Monthly
                                                                            </option>
                                                                            <option value="2" {{$subscription->payment_mode == '2' ? 'selected' : '' }}>
                                                                                Quarterly
                                                                            </option>
                                                                            <option value="3" {{$subscription->payment_mode == '3' ? 'selected' : '' }}>
                                                                                Annually
                                                                            </option>
                                                                        </select>
                                                                        @if ($errors->has('payment_mode'))
                                                                            <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('payment_mode')  }}</strong>
                                                                    </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="period_{{$subscription->id}}">Period
                                                                            (in years)</label>
                                                                        <input type="number" name="period"
                                                                               class="form-control {{ $errors->has('period') ? ' is-invalid' : '' }}"
                                                                               id="period_{{$subscription->id}}"
                                                                               placeholder="2"
                                                                               value="{{ $subscription->period }}"
                                                                               required>

                                                                        @if ($errors->has('period'))
                                                                            <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('period')  }}</strong>
                                                                    </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="grace_period_{{$subscription->id}}">Grace
                                                                            Period (days)</label>
                                                                        <input type="number" name="grace_period"
                                                                               id="grace_period_{{$subscription->id}}"
                                                                               class="form-control {{ $errors->has('period') ? ' is-invalid' : '' }}"
                                                                               value="{{ $subscription->grace_period }}">
                                                                        @if ($errors->has('grace_period'))
                                                                            <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('grace_period')  }}</strong>
                                                                    </span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="form-group col-12">
                                                                        <label for="penalty_{{$subscription->id}}">Penalty
                                                                            Rate</label>
                                                                        <input name="penalty" type="text"
                                                                               id="penalty_{{$subscription->id}}"
                                                                               class="form-control {{ $errors->has('period') ? ' is-invalid' : '' }}"
                                                                               value="{{ $subscription->penalty }}"
                                                                               required>
                                                                        @if ($errors->has('penalty'))
                                                                            <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('penalty')}}</strong>
                                                                    </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Save
                                                                    changes
                                                                </button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="viewModal_{{$subscription->id}}" tabindex="-1"
                                             role="dialog" aria-labelledby="modalLabel_{{$subscription->id}}"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$subscription->id}}">
                                                            Add Dependants
                                                        </h5>

                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <form action="{{ route('cooperative.subscription.dependant',$subscription->id ) }}"
                                                              method="post">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <div class="form-group col-12">
                                                                        <label for="name_{{$subscription->id}}">Dependant
                                                                            Names</label>
                                                                        <input type="text" name="name"
                                                                               id="name_{{$subscription->id}}"
                                                                               class="form-control"
                                                                               value="{{ old('name') }}">
                                                                        @if ($errors->has('name'))
                                                                            <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('name')  }}</strong>
                                                                    </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="relationship_{{$subscription->id}}">Relationship</label>
                                                                        <select name="relationship"
                                                                                id="relationship_{{$subscription->id}}"
                                                                                class=" form-control select2bs4 {{ $errors->has('relationship') ? ' is-invalid' : '' }}">
                                                                            <option value="">---Select Dependant---
                                                                            </option>
                                                                            <option value="1">Spouse</option>
                                                                            <option value="2">Child</option>
                                                                        </select>
                                                                        @if ($errors->has('relationship'))
                                                                            <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('relationship')  }}</strong>
                                                                    </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="idno_{{$subscription->id}}">ID/Birth
                                                                            certificate No.</label>
                                                                        <input type="text" name="idno"
                                                                               id="idno_{{$subscription->id}}"
                                                                               class="form-control"
                                                                               value="{{ old('idno') }}">
                                                                        @if ($errors->has('idno'))
                                                                            <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('idno')  }}</strong>
                                                                    </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="dob_{{$subscription->id}}">Date of
                                                                            Birth</label>
                                                                        <input type="date" name="dob"
                                                                               id="dob_{{$subscription->id}}"
                                                                               class="form-control"
                                                                               value="{{ old('dob') }}">
                                                                        @if ($errors->has('dob'))
                                                                            <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('dob')  }}</strong>
                                                                    </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close
                                                                </button>
                                                                @if($count > 0)
                                                                    <a href="{{route('cooperative.subscription.dependants',$subscription->id )}}"
                                                                       class="btn btn-info btn">View Dependants</a>
                                                                @endif
                                                                <button type="submit" class="btn btn-primary">Save
                                                                    changes
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
        function getValuationsByFarmer(fieldA, fieldB) {
            const farmer_id = $("#" + fieldA).val()
            let htmlCode = '';
            $("#" + fieldB).text('')
            if (farmer_id) {
                let url = "<?php echo e(route('cooperative.subscription.farmer.valuations', ':farmer_id')); ?>"
                url = url.replace(':farmer_id', farmer_id);
                axios.post(url).then(res => {
                    const data = res.data
                    if (data.length > 0) {
                        htmlCode += `<option value="">---Select Valuation---</option>`;
                        data.forEach(d => {
                            htmlCode += `<option value="${d.id}">${d.type}</option>`;
                        });
                    } else {
                        htmlCode += `<option value="">---No Valuations---</option>`;
                    }
                    $("#" + fieldB).append(htmlCode)

                }).catch(() => {
                    htmlCode += `<option value="">---No valuations---</option>`;
                    $("#" + fieldB).append(htmlCode);
                })

            } else {
                htmlCode += `<option value="">---No valuations---</option>`;
                $("#" + fieldB).append(htmlCode);
            }
        }
    </script>
@endpush
