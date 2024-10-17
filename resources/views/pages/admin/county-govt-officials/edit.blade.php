@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
@php
$gender_options = config('enums.employee_configs')['gender'];
$marital_status_options = config('enums.employee_configs')['marital_status'];
@endphp
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <div class="">
                    <div class="row mt-5">
                        <div class="col-lg-12 grid-margin stretch-card col-12">
                            <h4>Update County Govt Official</h4>
                        </div>
                    </div>

                    <form action="{{ route('admin.county-govt-officials.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$id}}">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <h6 class="mb-3">County Govt Official Details</h6>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="cooperative_id">Cooperative</label>
                                <select name="cooperative_id" id="cooperative_id" class="form-control select2bs4 {{ $errors->has('cooperative_id') ? ' is-invalid' : '' }}" readonly>
                                    @foreach($cooperatives as $coop)
                                    <option value="{{$coop->id}}">{{$coop->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('cooperative_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('cooperative_id')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" id="first_name" placeholder="John" value="{{ $official->first_name }}" required readonly>
                                @if ($errors->has('first_name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('first_name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="other_name">Other Names</label>
                                <input type="text" name="other_names" value="{{ $official->other_names }}" class="form-control {{ $errors->has('other_names') ? ' is-invalid' : '' }}" id="other_name" placeholder="Doe" required readonly>
                                @if ($errors->has('other_names'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('other_names')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="user_name">User Name</label>
                                <input type="text" name="username" class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" id="username" placeholder="j_doe" value="{{ $official->username }}" required readonly>
                                @if ($errors->has('username'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('username')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" placeholder="johndoe@abc.com" value="{{ $official->email }}" required readonly>
                                @if ($errors->has('email'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('email')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="country_id">Country</label>
                                <select name="country_id" id="country_id" class=" form-control select2bs4 {{ $errors->has('country_id') ? ' is-invalid' : '' }}">
                                    @foreach($countries as $country)
                                    <option value="{{$country->id}}" @if($country->name == "Kenya") selected @endif> {{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('country_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('country_id')  }}</strong>
                                </span>
                                @endif
                            </div>


                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="county_id">Select County</label>
                                <select name="county_id" id="county_id" class=" form-control select2bs4 {{ $errors->has('county_id') ? ' is-invalid' : '' }}">
                                    <option value=""> -Select County-</option>
                                    @foreach($counties as $county)
                                    <option value="{{$county->id}}" @if($county->id == $official->county_id) selected @endif> {{ $county->name }}</option>
                                    @endforeach

                                    @if ($errors->has('county_id'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('county_id')  }}</strong>
                                    </span>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="sub_county">Select Sub County</label>
                                <select data-subcounties="{{$sub_counties}}" name="sub_county_id" id="sub_county_id" class=" form-control select2bs4 {{ $errors->has('sub_county_id') ? ' is-invalid' : '' }}">
                                    <option value=""> -Select Sub County-</option>
                                    <option value="{{$official->sub_county_id}}" selected>{{$official->sub_county_name}}</option>

                                    @if ($errors->has('sub_county_id'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('sub_county_id')  }}</strong>
                                    </span>
                                    @endif
                                </select>
                            </div>

<div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="ministry">Ministry</label>
                                <input type="text" name="ministry" class="form-control  {{ $errors->has('ministry') ? ' is-invalid' : '' }}" value="{{ $official->ministry}}" id="ministry" placeholder="Enter govt ministry...">
                                @if ($errors->has('ministry'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('ministry')  }}</strong>
                                </span>
                                @endif
                            </div>

<div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="ministry">Designation</label>
                                <input type="text" name="designation" class="form-control  {{ $errors->has('designation') ? ' is-invalid' : '' }}" value="{{ $official->designation}}" id="designation" placeholder="Enter ministry designation...">
                                @if ($errors->has('designation'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('designation')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="id_no">Id No./Passport</label>
                                <input type="text" name="id_no" class="form-control  {{ $errors->has('id_no') ? ' is-invalid' : '' }}" value="{{ $official->id_no }}" id="id_no" placeholder="12345678">
                                @if ($errors->has('id_no'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('id_no')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender" class=" form-control select2bs4 {{ $errors->has('gender') ? ' is-invalid' : '' }}">
                                    <option value=""> -Select Gender-</option>
                                    @foreach($gender_options as $key => $option)
                                    <option value="{{$option}}" @if($official->gender == $option) selected @endif> {{ $option}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('gender'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('gender')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="phone_no">Phone No.</label>
                                <input type="text" name="phone_no" class="form-control  {{ $errors->has('phone_no') ? ' is-invalid' : '' }}" id="phone_no" placeholder="2547..." value="{{ $official->phone_no }}">
                                @if ($errors->has('phone_no'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('phone_no')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="employee_no">Employee No.</label>
                                <input type="text" name="employee_no" class="form-control {{ $errors->has('employee_no') ? ' is-invalid' : '' }}" id="employee_no" placeholder="EM1234" value="{{ $official->employee_no }}">

                                @if ($errors->has('employee_no'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('employee_no')  }}</strong>
                                </span>
                                @endif
                            </div>

                        </div>
                        <hr class="mt-1 mb-1">
                        <div class="form-row">
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <button type="submit" class="btn btn-primary btn-fw btn-block">Update
                                </button>
                            </div>
                        </div>
                    </form>
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