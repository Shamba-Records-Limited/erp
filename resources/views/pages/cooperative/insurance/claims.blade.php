@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Insurance Product']['claims'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addInsuranceClaim"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addInsuranceClaim">
                            <span class="mdi mdi-plus"></span>Register Claim
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addInsuranceClaim">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Register Claim</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.insurance.claim.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="farmer">Farmer</label>
                                        <select name="farmer" id="farmer"
                                                class=" form-control form-select {{ $errors->has('farmer') ? ' is-invalid' : '' }}"
                                                onchange="getSubscriptionByFarmer('farmer', 'subscription', 'dependant')">
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
                                        <label for="dependant">Dependant</label>
                                        <select name="dependant" id="dependant"
                                                class=" form-control form-select {{ $errors->has('dependant') ? ' is-invalid' : '' }}">

                                        </select>
                                        @if ($errors->has('dependant'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('dependant')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="amount">Amount</label>
                                        <input type="text" name="amount"
                                               class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                               id="amount" placeholder="25000" value="{{ old('amount')}}">

                                        @if ($errors->has('amount'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('amount')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="description">Description</label>
                                        <input type="text" name="description"
                                               class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                               id="description" placeholder="Text..." value="{{ old('description')}}">

                                        @if ($errors->has('description'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('description')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-3 col-12">
                                        <button type="submit" class="btn btn-primary btn-fw btn-block" id="submit-btn">
                                            Add
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
                    @if(has_right_permission(config('enums.system_modules')['Insurance Product']['claims'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                        href="{{ route('cooperative.insurance.claims.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                        href="{{ route('cooperative.insurance.claims.download','xlsx') }}"
                        style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                        href="{{ route('cooperative.insurance.claims.download', 'pdf') }}"
                        style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Register Claim</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Farmer</th>
                                <th>Product</th>
                                <th>Dependant</th>
                                <th>Amount</th>
                                <th>Remaining Limit</th>
                                <th>Status</th>
                                <th>Description</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $currency = Auth::user()->cooperative->currency; $total_amount = 0;

                            @endphp
                            @foreach($claims as $key => $c)
                                @php $total_amount += $c->amount@endphp
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td> {{ ucwords(strtolower($c->subscription->farmer->user->first_name.' '.$c->subscription->farmer->user->other_names)) }}</td>
                                    <td> {{ $c->subscription->insurance_product->name }}</td>
                                    <td> {{ $c->dependant_id ? $c->dependant->name : '-' }}</td>
                                    <td> {{$currency.' '.number_format($c->amount) }}</td>
                                    <td> {{$currency.' '.number_format($c->subscription->current_limit) }}</td>
                                    <td>
                                        @if($c->status == \App\InsuranceClaim::STATUS_PENDING)
                                            <badge class="badge badge-warning text-white">Pending</badge>
                                        @elseif($c->status == \App\InsuranceClaim::STATUS_APPROVED)
                                            <badge class="badge badge-success text-white">Approved</badge>
                                        @elseif($c->status == \App\InsuranceClaim::STATUS_REJECTED)
                                            <badge class="badge badge-danger text-white">Rejected</badge>
                                        @elseif($c->status == \App\InsuranceClaim::STATUS_SETTLED)
                                            <badge class="badge badge-info text-white">Settled</badge>
                                        @endif
                                    </td>
                                    <td> {{ $c->description }}</td>
                                    <td>
                                        @if($c->status == \App\InsuranceClaim::STATUS_PENDING || $c->status == \App\InsuranceClaim::STATUS_APPROVED)
                                            <button type="button" class="btn btn-info btn-rounded btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#updateStatusModal_{{$c->id}}">
                                                <span class="mdi mdi-border-color">Update Status</span>
                                            </button>
                                        @endif

                                        @if($c->status == \App\InsuranceClaim::STATUS_PENDING)
                                            <button type="button" class="btn btn-primary btn-rounded btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#editModal_{{$c->id}}">
                                                <span class="mdi mdi-comment-edit">Edit</span>
                                            </button>
                                        @endif

                                        @if($c->status_trackers->count() > 0)
                                            <a href="{{ route('cooperative.insurance.claim.status_transitions', $c->id) }}">
                                                Status Transition
                                            </a>
                                        @endif

                                        <div class="modal fade" id="updateStatusModal_{{$c->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$c->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$c->id}}">
                                                            Update Claim Status
                                                        </h5>

                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <form action="{{ route('cooperative.insurance.claim.updated-status',$c->id ) }}"
                                                              method="post">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-row">

                                                                    <div class="form-group col-12">
                                                                        <label for="status_{{$c->id}}">Relationship</label>
                                                                        <select name="status" id="status_{{$c->id}}"
                                                                                class=" form-control form-select {{ $errors->has('status') ? ' is-invalid' : '' }}">
                                                                            <option value="">---Select Status---
                                                                            </option>
                                                                            @if($c->status == \App\InsuranceClaim::STATUS_PENDING)
                                                                                <option value="{{\App\InsuranceClaim::STATUS_APPROVED}}" {{ $c->status == \App\InsuranceClaim::STATUS_APPROVED ? 'selected' : '' }}>
                                                                                    Approve
                                                                                </option>
                                                                                <option value="{{\App\InsuranceClaim::STATUS_REJECTED}}" {{ $c->status == \App\InsuranceClaim::STATUS_REJECTED ? 'selected' : '' }}>
                                                                                    Reject
                                                                                </option>
                                                                            @elseif($c->status == \App\InsuranceClaim::STATUS_APPROVED)
                                                                                <option value="{{\App\InsuranceClaim::STATUS_SETTLED}}" {{ $c->status == \App\InsuranceClaim::STATUS_SETTLED ? 'selected' : '' }}>
                                                                                    Settled
                                                                                </option>
                                                                            @endif
                                                                        </select>
                                                                        @if ($errors->has('status'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('status')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="comment_{{$c->id}}">Comment</label>
                                                                        <input type="text" name="comment"
                                                                               id="comment_{{$c->id}}"
                                                                               class="form-control">
                                                                        @if ($errors->has('comment'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('comment')  }}</strong>
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
                                        <div class="modal fade" id="editModal_{{$c->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$c->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$c->id}}">
                                                            Update Claim Status
                                                        </h5>

                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <form action="{{ route('cooperative.insurance.claim.edit',$c->id ) }}"
                                                              method="post">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <div class="form-group col-12">
                                                                        <label for="farmer_{{$c->id}}">Farmer</label>
                                                                        <select name="farmer" id="farmer_{{$c->id}}"
                                                                                class=" form-control form-select {{ $errors->has('farmer') ? ' is-invalid' : '' }}"
                                                                                onchange="getSubscriptionByFarmer('farmer_{{$c->id}}', 'subscription_{{$c->id}}', 'dependant_{{$c->id}}')">
                                                                            <option value="">---Select Farmer---
                                                                            </option>
                                                                            @foreach($farmers as $farmer)
                                                                                <option value="{{$farmer->id}}" {{ $farmer->id == $c->subscription->farmer_id ? 'selected' : '' }}>{{ucwords(strtolower($farmer->first_name.' '.$farmer->other_names))}}</option>
                                                                            @endforeach

                                                                        </select>
                                                                        @if ($errors->has('farmer'))
                                                                            <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('farmer')  }}</strong>
                                                                                </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="subscription_{{$c->id}}">Subscription</label>
                                                                        <select name="subscription"
                                                                                id="subscription_{{$c->id}}"
                                                                                class=" form-control form-select {{ $errors->has('subscription') ? ' is-invalid' : '' }}">

                                                                        </select>
                                                                        @if ($errors->has('subscription'))
                                                                            <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('subscription')  }}</strong>
                                                                                </span>
                                                                        @endif
                                                                    </div>


                                                                    <div class="form-group col-12">
                                                                        <label for="dependant_{{$c->id}}">Dependant</label>
                                                                        <select name="dependant"
                                                                                id="dependant_{{$c->id}}"
                                                                                class=" form-control form-select {{ $errors->has('dependant') ? ' is-invalid' : '' }}">

                                                                        </select>
                                                                        @if ($errors->has('dependant'))
                                                                            <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('dependant')  }}</strong>
                                                                                </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="amount_{{$c->id}}">Amount</label>
                                                                        <input type="text" name="amount"
                                                                               class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                                                               id="amount_{{$c->id}}"
                                                                               placeholder="25000"
                                                                               value="{{ $c->amount}}">

                                                                        @if ($errors->has('amount'))
                                                                            <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('amount')  }}</strong>
                                                                                </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="description_{{$c->id}}">Description</label>
                                                                        <input type="text" name="description"
                                                                               class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                                                               id="description_{{$c->id}}"
                                                                               placeholder="Text..."
                                                                               value="{{ $c->description}}">

                                                                        @if ($errors->has('description'))
                                                                            <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('description')  }}</strong>
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
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="4"> Total</th>
                                <th colspan="5"> {{ $currency.' '.number_format($total_amount)}}</th>
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
        function getSubscriptionByFarmer(fieldA, fieldB, fieldC) {
            const farmer_id = $("#" + fieldA).val()
            let htmlCode = '';
            let htmlCode1 = '';
            $("#" + fieldB).text('')
            $("#" + fieldC).text('')
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

                    if (data.dependants.length > 0) {
                        htmlCode1 += `<option value="">---Select Dependant---</option>`;
                        data.dependants.forEach(d => {
                            htmlCode1 += `<option value="${d.id}">${d.name}</option>`;
                        });
                    } else {
                        htmlCode1 += `<option value="">---No Dependants---</option>`;
                    }

                    $("#" + fieldB).append(htmlCode)
                    $("#" + fieldC).append(htmlCode1)

                }).catch((e) => {
                    console.log(e)
                    htmlCode += `<option value="">---No Subscription---</option>`;
                    htmlCode1 += `<option value="">---No Dependants---</option>`;
                    $("#" + fieldB).append(htmlCode);
                    $("#" + fieldC).append(htmlCode1);
                })

            } else {
                htmlCode += `<option value="">---No Subscription---</option>`;
                htmlCode1 += `<option value="">---No Dependants---</option>`;
                $("#" + fieldB).append(htmlCode);
                $("#" + fieldC).append(htmlCode1);
            }
        }
    </script>
@endpush
