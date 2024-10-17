@extends('layout.master')

@push('plugin-styles')

@endpush

@php
    $coopId = Auth::user()->cooperative_id;
    $oldLocation = old('location');
    $uploadErrors = Session::get('uploadErrors');
@endphp

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Farmer CRM']['farmers'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                                data-toggle="collapse"
                                data-target="#addFarmerAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addFarmerAccordion">
                            <span class="mdi mdi-plus"></span>Add Farmer
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif "
                             id="addFarmerAccordion">
                            <form action="{{ route('cooperative.farmer.add') }}"
                                  method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <h6 class="mb-3">Farmer Details</h6>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="f_name">First Name</label>
                                        <input type="text" name="f_name"
                                               class="form-control {{ $errors->has('f_name') ? ' is-invalid' : '' }}"
                                               id="f_name" placeholder="John"
                                               value="{{ old('f_name')}}" required>
                                        @if ($errors->has('f_name'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('f_name')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="o_name">Other Names</label>
                                        <input type="text" name="o_names"
                                               value="{{ old('o_names')}}"
                                               class="form-control {{ $errors->has('o_names') ? ' is-invalid' : '' }}"
                                               id="o_name" placeholder="Doe" required>
                                        @if ($errors->has('o_names'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('o_names')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="u_name">User Name</label>
                                        <input type="text" name="u_name"
                                               class="form-control {{ $errors->has('u_name') ? ' is-invalid' : '' }}"
                                               id="u_name" placeholder="j_doe"
                                               value="{{ old('u_name')}}" required>
                                        @if ($errors->has('u_name'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('u_name')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="user_email">Email</label>
                                        <input type="email" name="user_email"
                                               class="form-control {{ $errors->has('user_email') ? ' is-invalid' : '' }}"
                                               id="user_email" placeholder="johndoe@abc.com"
                                               value="{{ old('user_email')}}"
                                               required>
                                        @if ($errors->has('user_email'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('user_email')  }}</strong>
                                </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="">Gender</label>
                                        <div class="form-row">
                                            <div class="form-check form-check-inline ml-2">
                                                <input class="form-check-input" type="radio"
                                                       name="gender" id="Male"
                                                       value="M"
                                                       @if( old('gender') == "M") checked @endif>
                                                Male
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                       name="gender" id="Female"
                                                       value="F"
                                                       @if( old('gender') == "F") checked @endif>
                                                Female
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                       name="gender" id="Other"
                                                       value="X"
                                                       @if( old('gender') == "X") checked @endif>
                                                Other
                                            </div>
                                        </div>

                                        @if ($errors->has('gender'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('gender')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="dob">Date of Birth</label>
                                        <input type="date" name="dob"
                                               class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}"
                                               id="dob" value="{{ old('dob')}}"
                                               required>

                                        @if ($errors->has('dob'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('dob')  }}</strong>
                                </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="country_id">Country</label>
                                        <select name="country_id" id="country_id"
                                                class=" form-control select2bs4
                                                {{ $errors->has('country_id') ? ' is-invalid' : '' }}">
                                            @foreach($countries as $country)
                                                <option value="{{$country->id}}"
                                                        {{strtolower($country->name) == 'kenya' ? 'selected' : ''}}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('country_id'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('country_id')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="county">County/State/Province</label>
                                        <input type="text" name="county"
                                               class="form-control {{ $errors->has('county') ? ' is-invalid' : '' }}"
                                               id="county" placeholder="Nairobi"
                                               value="{{ old('county')}}"
                                               required>

                                        @if ($errors->has('county'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('county')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <x-location-picker label="Location" name="location"
                                                           :cooperativeId="$coopId"
                                                           :selectedValue="$oldLocation"/>
                                    </div>


                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="id_no">Id No./Passport</label>
                                        <input type="text" name="id_no"
                                               class="form-control  {{ $errors->has('id_no') ? ' is-invalid' : '' }}"
                                               value="{{ old('id_no')}}" id="id_no"
                                               placeholder="12345678" required>
                                        @if ($errors->has('id_no'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('id_no')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="phone_no">Phone No.</label>
                                        <input type="text" name="phone_no"
                                               class="form-control  {{ $errors->has('phone_no') ? ' is-invalid' : '' }}"
                                               id="phone_no" placeholder="07..."
                                               value="{{ old('phone_no')}}" required>
                                        @if ($errors->has('phone_no'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('phone_no')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="route_id">Route</label>
                                        <select name="route_id" id="route_id"
                                                class=" form-control select2bs4 {{ $errors->has('route_id') ? ' is-invalid' : '' }}">
                                            @foreach($routes as $route)
                                                <option value="{{$route->id}}"> {{ $route->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('route_id'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('route_id')  }}</strong>
                                            </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="bank_account">Bank Account No. </label>
                                        <input type="text" name="bank_account"
                                               class="form-control {{ $errors->has('bank_account') ? ' is-invalid' : '' }}"
                                               id="bank_account" placeholder="235965..."
                                               value="{{ old('bank_account')}}" required>

                                        @if ($errors->has('bank_account'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('bank_account')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="bank_id">Bank</label>
                                        <select name="bank_id" id="bank_id"
                                                class=" form-control select2bs4
                                                 {{ $errors->has('bank_id') ? ' is-invalid' : '' }}"
                                                onchange="loadBankBranches('bank_id','bank_branch_id', {{ old('bank_id') }})"
                                        >
                                            <option value="">---Select Bank---</option>
                                            @foreach($banks as $bank)
                                                <option value="{{$bank->id}}"> {{ $bank->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('bank_id'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('bank_id')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="bank_branch_id">Bank Branch</label>
                                        <select name="bank_branch_id" id="bank_branch_id"
                                                class=" form-control select2bs4 {{ $errors->has('bank_branch_id') ? ' is-invalid' : '' }}">

                                        </select>
                                        @if ($errors->has('bank_branch_id'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('bank_branch_id')  }}</strong>
                                            </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="member_no">Member No.</label>
                                        <input type="text" name="member_no"
                                               class="form-control {{ $errors->has('member_no') ? ' is-invalid' : '' }}"
                                               id="member_no" placeholder="123"
                                               value="{{ old('member_no')}}" required>

                                        @if ($errors->has('member_no'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('member_no')  }}</strong>
                                </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="customer_type">Customer Type</label>
                                        <select name="customer_type" id="customer_type"
                                                class=" form-control select2bs4 {{ $errors->has('customer_type') ? ' is-invalid' : '' }}">

                                            <option value=""></option>
                                            @foreach(config('enums.farmer_customer_types') as $key => $type)
                                                <option value="{{$key}}">{{$type}}</option>
                                            @endforeach

                                        </select>
                                        @if ($errors->has('customer_type'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('customer_type')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="products">Products</label>
                                        <select name="products[]" multiple="multiple" id="products"
                                                class=" form-control select2bs4 {{ $errors->has('products') ? ' is-invalid' : '' }}">
                                            @foreach($products as $product)
                                                <option value="{{$product->id}}"> {{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('products'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('products')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="farm_size">Farm Size
                                            (<small>Acres</small>)</label>
                                        <input type="number" name="farm_size"
                                               class="form-control {{ $errors->has('farm_size') ? ' is-invalid' : '' }}"
                                               id="farm_size" placeholder="10"
                                               value="{{ old('farm_size')}}"
                                               required>

                                        @if ($errors->has('farm_size'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('farm_size')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="mainImage">Profile Picture</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file"
                                                       class="custom-file-input @error('profile_picture') is-invalid @enderror"
                                                       id="profile_picture" name="profile_picture"
                                                       value="{{ old('profile_picture') }}">
                                                <label class="custom-file-label"
                                                       for="profile_picture">Image</label>
                                            </div>

                                        </div>
                                    </div>
                                    @if ($errors->has('profile_picture'))
                                        <span class="help-block text-danger">
                                        <strong>{{ $errors->first('profile_picture')  }}</strong>
                                    </span>
                                    @endif

                                    <div class="d-none" id="imagePreviewContainer">
                                        <div class="imageHolder pl-2">
                                            <img id="picturePreview" src="#" alt="pic" height="150px" width="150px"/>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mt-1 mb-1">
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

    @if(has_right_permission(config('enums.system_modules')['Farmer CRM']['farmers'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                                data-toggle="collapse" data-target="#bulkUploadAccordion"
                                aria-expanded="@if ($errors->count() > 0 || isset($uploadErrors)) true @else false @endif"
                                aria-controls="bulkUploadAccordion">
                            <span class="mdi mdi-plus">Bulk Import</span>
                        </button>
                        <div class="collapse @if ($errors->count() > 0 || isset($uploadErrors)) show @endif "
                             id="bulkUploadAccordion">
                            <form action="{{ route('farmer.bulk.import') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <h6 class="mb-3">Bulk Import Farmers</h6>
                                    </div>
                                    <div class="form-row col-12">
                                        @if(isset($uploadErrors))
                                            <div>
                                                @foreach($uploadErrors as $error)
                                                    <li class="list text-danger">{{ $error[0] }}</li>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <a download="employee_bulk_import"
                                           href="{{ route('download-upload-farmers-template') }}">
                                            Download Template</a>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file"
                                                       class="custom-file-input @error('farmers') is-invalid @enderror"
                                                       id="farmers" name="farmers"
                                                       value="{{ old('farmers') }}">
                                                <label class="custom-file-label"
                                                       for="exampleInputFile">Farmers File</label>

                                                @if ($errors->has('farmers'))
                                                    <span class="help-block text-danger">
                                                        <strong>{{ $errors->first('farmers')  }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <button type="submit"
                                                class="btn btn-primary btn-fw btn-block">Submit
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
                    <button type="button" class="btn btn-info btn-fw btn-sm float-right"
                            data-toggle="collapse"
                            data-target="#filterAccordion"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="filterAccordion">
                        <span class="mdi mdi-database-search"></span>Filter Farmers
                    </button>
                    <div class="collapse @if(
                            request()->dob
                            or request()->country
                            or request()->name
                            or request()->location
                            or request()->route
                            or request()->bank
                            or request()->bank_branch
                            or request()->gender
                            or request()->member_no
                            or request()->customer_type)
                             show @endif  "
                         id="filterAccordion">
                        <form action="{{ route('cooperative.farmers.show') }}" method="get">
                            @csrf
                            <div class="form-row">

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="filter_name">Name</label>
                                    <input type="text" name="name"
                                           class="form-control"
                                           id="filter_name" value="{{request()->name}}"
                                    >
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="filter_member_no">Member No</label>
                                    <input type="text" name="member_no"
                                           class="form-control"
                                           id="filter_member_no" value="{{request()->member_no}}"
                                    >
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="filter_dob">Date of Birth Range</label>
                                    <input type="text" name="dob"
                                           class="form-control"
                                           id="filter_dob" value="{{request()->dob}}"
                                    >
                                </div>


                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="filter_country">Country</label>
                                    <select name="country" id="filter_country"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}"
                                                    {{ request()->country == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="filter_location">Location</label>
                                    <select name="location" id="filter_location"
                                            class="form-control select2bs4">
                                        <option value=""></option>
                                        @foreach($locations as $location)
                                            <option value="{{$location->id}}"
                                                    {{ request()->location == $location->id ? 'selected' : '' }} >
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="filter_route">Route</label>
                                    <select name="route" id="filter_route"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach($routes as $route)
                                            <option value="{{$route->id}}"
                                                    {{ request()->route == $route->id ? 'selected' : '' }}>
                                                {{ $route->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="filter_bank">Bank</label>
                                    <select name="bank" id="filter_bank"
                                            class=" form-control select2bs4"
                                    >
                                        <option value=""></option>
                                        @foreach($banks as $bank)
                                            <option value="{{$bank->id}}"
                                                    {{ request()->bank == $bank->id ? 'selected' : '' }} >
                                                {{ $bank->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="filter_customer_type">Customer Type</label>
                                    <select name="customer_type" id="filter_customer_type"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach(config('enums.farmer_customer_types') as $key => $type)
                                            <option value="{{$key}}"
                                                    {{ $key == request()->customer_type ? 'selected' : '' }}>
                                                {{$type}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="">Gender</label>
                                    <div class="form-row">
                                        <div class="form-check form-check-inline ml-2">
                                            <input class="form-check-input" type="radio"
                                                   name="gender" id="Male"
                                                   value="M"
                                                   @if( request()->gender == "M") checked @endif>
                                            Male
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio"
                                                   name="gender" id="Female"
                                                   value="F"
                                                   @if( request()->gender == "F") checked @endif>
                                            Female
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio"
                                                   name="gender" id="Other"
                                                   value="X"
                                                   @if( request()->gender == "X") checked @endif>
                                            Other
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <hr class="mt-1 mb-1">
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <label for=""></label>
                                    <button type="submit"
                                            class="btn btn-primary btn-fw btn-block">
                                        Filter
                                    </button>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <label for=""></label>
                                    <a href="{{route('cooperative.farmers.show') }}"
                                       type="submit"
                                       class="btn btn-info btn-fw btn-block">
                                        Reset
                                    </a>
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
                    @if(has_right_permission(config('enums.system_modules')['Farmer CRM']['farmers'], config('enums.system_permissions')['download']))

                        <form action="{{ route('cooperative.farmer.download','csv') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('cooperative.farmer.download', 'xlsx') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>

                        <form action="{{ route('cooperative.farmer.download','pdf') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-success float-right text-white">
                                <i class="mdi mdi-download"></i> PDF
                            </button>
                        </form>

                    @endif
                    <h4 class="card-title">Registered Cooperatives</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Age</th>
                                <th>Country</th>
                                <th>Farm Size</th>
                                <th>Route</th>
                                <th>Member No.</th>
                                <th>Id/Passport No.</th>
                                <th>Phone No.</th>
                                <th>Customer Type</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            @php $count = 0; @endphp
                            @foreach($farmers as $farmer)
                                <tr>
                                    <td>{{++$count }}</td>
                                    <td>
                                        <a href="{{ route('cooperative.farmer.profile', $farmer->user->id) }}">
                                            {{ ucwords(strtolower($farmer->user->first_name).' '.strtolower($farmer->user->other_names) )}}
                                        </a>
                                    </td>
                                    <td>{{$farmer->gender == "M" ? "Male": ($farmer->gender == "F" ? "Female" : "Other")}}</td>
                                    <td>{{$farmer->age}} years</td>
                                    <td> {{$farmer->country->name }} <span class="mr-2 table-flag"> <img
                                                    src="{{ asset(get_country_flag($farmer->country->iso_code)) }}"/></span>
                                    </td>
                                    <td>{{$farmer->farm_size}} acres</td>
                                    <td>{{$farmer->route->name}}</td>
                                    <td>{{$farmer->member_no}}</td>
                                    <td>{{$farmer->id_no}}</td>
                                    <td>{{$farmer->phone_no}}</td>
                                    <td>{{config('enums.farmer_customer_types')[strtolower($farmer->customer_type)]}}</td>
                                    <td>
                                        <a class="btn btn-info btn-rounded btn-sm" href="{{ route('cooperative.farmer.edit', $farmer->id) }}">
                                            <span class="mdi mdi-file-edit"></span>
                                        </a>
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


      const loadBankBranches = (parentId, targetId, selectedValue) => {
        $("#" + targetId).empty()
        const bank = $("#" + parentId).val();
        let url = "{{ route('cooperative.farmer.bank_branch.get',':bank_id') }}";
        url = url.replace(':bank_id', bank);
        let htmlCode = '';
        axios.post(url).then(res => {
          const data = res.data
          htmlCode += `<option value="">---Select Bank Branch---</option>`;
          data.forEach(d => {
            htmlCode += `<option value="${d.id}"  ${selectedValue == d.id ? 'selected'
                : ''}>${d.name}</option>`;
          })

          $("#" + targetId).append(htmlCode)
        }).catch(() => {
          htmlCode += `<option value="">---Select Bank Branch---</option>`;
          $("#" + targetId).append(htmlCode);
        })
      }

      dateRangePickerFormats("dob")

      $('#profile_picture').change(function () {
        previewImage(this, 'picturePreview', 'imagePreviewContainer');
      });
    </script>
@endpush
