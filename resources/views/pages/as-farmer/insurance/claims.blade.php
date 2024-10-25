@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
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


                        <form action="{{ route('insurance.claim.add') }}" method="post">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="subscription">Subscription</label>
                                    <select name="subscription" id="subscription"
                                            class=" form-control select2bs4 {{ $errors->has('subscription') ? ' is-invalid' : '' }}">
                                        <option value="">---Select Subscription---</option>
                                        @foreach($subscriptions as $subscription)
                                            <option value="{{$subscription->id}}">{{ucwords(strtolower($subscription->insurance_product->name))}}</option>
                                        @endforeach
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
                                            class=" form-control select2bs4 {{ $errors->has('dependant') ? ' is-invalid' : '' }}">
                                        @foreach($dependants as $d)
                                            <option value="{{$d->id}}">{{ucwords(strtolower($d->name))}}</option>
                                        @endforeach
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
                                    <button type="submit" class="btn btn-primary btn-fw btn-block" id="submit-btn">Register
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
                    <h4 class="card-title">Register Claim</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
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

                            @php $currency = Auth::user()->cooperative->currency; $total_amount = 0; @endphp
                            @foreach($claims as $key => $c)
                                @php $total_amount += $c->amount@endphp
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td> {{ $c->name }}</td>
                                    <td> {{ $c->dependant ? $c->dependant : '-' }}</td>
                                    <td> {{$currency.' '.number_format($c->amount) }}</td>
                                    <td> {{$currency.' '.number_format($c->current_limit) }}</td>
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
                                            @if($c->status == \App\InsuranceClaim::STATUS_PENDING)
                                                <button type="button" class="btn btn-primary btn-rounded btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#editModal_{{$c->id}}">
                                                    <span class="mdi mdi-comment-edit">Edit</span>
                                                </button>
                                            @endif

                                            <a href="{{ route('insurance.status-transitions', $c->id) }}">
                                                Status Transition
                                            </a>

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
                                                            <form action="{{ route('insurance.claim.edit',$c->id ) }}"
                                                                  method="post">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="form-row">

                                                                        <div class="form-group col-12">
                                                                            <label for="subscription_{{$c->id}}">Subscription</label>
                                                                            <select name="subscription" id="subscription_{{$c->id}}"
                                                                                    class=" form-control select2bs4 {{ $errors->has('subscription') ? ' is-invalid' : '' }}">
                                                                                <option value="">---Select Subscription---</option>
                                                                                @foreach($subscriptions as $subscription)
                                                                                    <option value="{{$subscription->id}}" {{ $subscription->id == $c->subscription_id ? 'selected' : '' }}>{{ucwords(strtolower($subscription->insurance_product->name))}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            @if ($errors->has('subscription'))
                                                                                <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('subscription')  }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>


                                                                        <div class="form-group col-12">
                                                                            <label for="dependant_{{$c->id}}">Dependant</label>
                                                                            <select name="dependant" id="dependant_{{$c->id}}"
                                                                                    class=" form-control select2bs4 {{ $errors->has('dependant') ? ' is-invalid' : '' }}">
                                                                                @foreach($dependants as $d)
                                                                                    <option value="{{$d->id}}" {{ $d->id == $c->dependant_id ? 'selected' : ''}}>{{ucwords(strtolower($d->name))}}</option>
                                                                                @endforeach
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
                                                                                   id="amount_{{$c->id}}" placeholder="25000" value="{{ $c->amount}}">

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
                                                                                   id="description_{{$c->id}}" placeholder="Text..." value="{{ $c->description}}">

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
                                <th colspan="3"> Total</th>
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

@endpush
