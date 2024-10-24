@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Insurance Product']['product_limit'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addInsuranceClaimLimit"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addInsuranceClaimLimit">
                            <span class="mdi mdi-plus"></span>Add Claim Limit
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addInsuranceClaimLimit">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Claim Limit</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.claim-limit.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="product">Product</label>
                                        <select name="product" id="product"
                                                class=" form-control form-select {{ $errors->has('product') ? ' is-invalid' : '' }}">
                                            <option value="">---Select Product---</option>
                                            @foreach($products as $product)
                                                <option value="{{$product->id}}" {{ $product->id == old('product') ? 'selected' : '' }}>{{ $product->name.' ('. $product->premium.')' }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('product'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('product')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="limit_rate">Credit/Loan Score (%)</label>
                                        <input type="text" name="limit_rate"
                                               class="form-control {{ $errors->has('limit_rate') ? ' is-invalid' : '' }}"
                                               id="limit_rate" placeholder="10" value="{{ old('limit_rate')}}">

                                        @if ($errors->has('limit_rate'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('limit_rate')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="amount">Amount</label>
                                        <input type="text" name="amount"
                                               class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                               id="amount" placeholder="250000" value="{{ old('amount')}}">

                                        @if ($errors->has('amount'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('amount')  }}</strong>
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
                    <h4 class="card-title">Limit Configurations</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Premium (Annual)</th>
                                <th>Limit</th>
                                <th>Rate</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $canEdit = has_right_permission(config('enums.system_modules')['Insurance Product']['product_limit'], config('enums.system_permissions')['edit']);
                            @endphp
                            @foreach($claim_limits as $key => $cl)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $cl->product->name }}</td>
                                    <td>{{ $currency.' '.number_format($cl->product->premium) }}</td>
                                    <td> {{$currency.' '.number_format($cl->amount)}}</td>
                                    <td>{{ $cl->limit_rate.'%' }}</td>
                                    <td>
                                        @if($canEdit)
                                            <button type="button" class="btn btn-info btn-rounded btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#editModal_{{$cl->id}}">
                                                <span class="mdi mdi-account-edit">Edit</span>
                                            </button>
                                        @endif
                                        <div class="modal fade" id="editModal_{{$cl->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$cl->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$cl->id}}">
                                                            Edit {{$cl->product->name}} Limit Configuration
                                                        </h5>

                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <form action="{{ route('cooperative.claim-limit.edit',$cl->id ) }}"
                                                              method="post">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-row">
                                                                    <div class="form-group col-12">
                                                                        <label for="product_{{$cl->id}}">Product</label>
                                                                        <select name="product" id="product_{{$cl->id}}"
                                                                                class=" form-control form-select {{ $errors->has('product') ? ' is-invalid' : '' }}">
                                                                            <option value="">---Select Product---
                                                                            </option>
                                                                            @foreach($products as $product)
                                                                                <option value="{{$product->id}}" {{ $product->id == $cl->product_id ? 'selected' : '' }}>{{ $product->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @if ($errors->has('product'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('product')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="limit_rate_{{$cl->id}}">Limit
                                                                            Rate</label>
                                                                        <input type="text" name="limit_rate"
                                                                               class="form-control {{ $errors->has('limit_rate') ? ' is-invalid' : '' }}"
                                                                               id="limit_rate_{{$cl->id}}"
                                                                               value="{{ $cl->limit_rate}}">

                                                                        @if ($errors->has('limit_rate'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('limit_rate')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="amount_{{$cl->id}}">Amount</label>
                                                                        <input type="text" name="amount"
                                                                               class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                                                               id="amount_{{$cl->id}}"
                                                                               value="{{ $cl->amount }}">

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
@endpush
