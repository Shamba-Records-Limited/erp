@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
@php
$gender_options = config('enums.employee_configs')['gender'];
$marital_status_options = config('enums.employee_configs')['marital_status'];
@endphp
<div class="card">
    <div class="card-body">
        <div class="card-title">Add New Farmer</div>
        <form action="{{ route('cooperative-admin.farmers.add') }}" method="post" enctype="multipart/form-data">
            @csrf
            {{$errors}}
            <div class="form-row">
                <div class="form-group col-12">
                    <h6 class="mb-3">Farmer Details</h6>
                </div>
                <div class="form-group col-lg-3 col-md-6 col-12">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" id="first_name" placeholder="John" value="{{ old('first_name')}}" required>
                    @if ($errors->has('first_name'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('first_name')  }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group col-lg-3 col-md-6 col-12">
                    <label for="other_name">Other Names</label>
                    <input type="text" name="other_names" value="{{ old('other_names')}}" class="form-control {{ $errors->has('other_names') ? ' is-invalid' : '' }}" id="other_name" placeholder="Doe" required>
                    @if ($errors->has('other_names'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('other_names')  }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group col-lg-3 col-md-6 col-12">
                    <label for="username">User Name</label>
                    <input type="text" name="username" class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" id="username" placeholder="j_doe" value="{{ old('username')}}" required>
                    @if ($errors->has('username'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('username')  }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group col-lg-3 col-md-6 col-12">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" placeholder="johndoe@abc.com" value="{{ old('email')}}" required>
                    @if ($errors->has('email'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('email')  }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group col-lg-3 col-md-6 col-12">
                    <label for="country_id">Country</label>
                    <select name="country_id" id="country_id" class=" form-control select2bs4 {{ $errors->has('country_id') ? ' is-invalid' : '' }}" value="Kenya">
                        <option value=""> -Select Country-</option>
                        @foreach($countries as $country)
                        <option value="{{$country->id}}" @if($country->name == 'Kenya') selected @endif> {{ $country->name }}</option>
                        @endforeach

                        @if ($errors->has('country_id'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('country_id')  }}</strong>
                        </span>
                        @endif
                    </select>
                </div>

                <div class="form-group col-lg-3 col-md-6 col-12">
                    <label for="county_id">Select County</label>
                    <select name="county_id" id="county_id" class=" form-control select2bs4 {{ $errors->has('county_id') ? ' is-invalid' : '' }}">
                        <option value=""> -Select County-</option>
                        @foreach($counties as $county)
                        <option value="{{$county->id}}"> {{ $county->name }}</option>
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

                        @if ($errors->has('sub_county_id'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('sub_county_id')  }}</strong>
                        </span>
                        @endif
                    </select>
                </div>

                <div class="form-group col-lg-3 col-md-6 col-12">
                    <label for="id_no">Id No./Passport</label>
                    <input type="text" name="id_no" class="form-control  {{ $errors->has('id_no') ? ' is-invalid' : '' }}" value="{{ old('id_no')}}" id="id_no" placeholder="12345678">
                    @if ($errors->has('id_no'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('id_no')  }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group col-lg-3 col-md-6 col-12">
                    <label for="dob">D.o.B</label>
                    <input type="date" name="dob" class="form-control  {{ $errors->has('dob') ? ' is-invalid' : '' }}" value="{{ old('dob')}}" id="dob">
                    @if ($errors->has('dob'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('dob')  }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group col-lg-3 col-md-6 col-12">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" class=" form-control select2bs4 {{ $errors->has('gender') ? ' is-invalid' : '' }}">
                        <option value=""> -Select Gender-</option>
                        @foreach($gender_options as $key => $option)
                        <option value="{{$option}}"> {{ $option}}</option>
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
                    <input type="text" name="phone_no" class="form-control  {{ $errors->has('phone_no') ? ' is-invalid' : '' }}" id="phone_no" placeholder="2547..." value="{{ old('phone_no')}}">
                    @if ($errors->has('phone_no'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('phone_no')  }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group col-lg-3 col-md-6 col-12">
                    <label for="mainImage">Profile Picture</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('profile_picture') is-invalid @enderror" id="profile_picture" name="profile_picture" value="{{ old('profile_picture') }}">
                            <label class="custom-file-label" for="profile_picture">Image</label>
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
                        <img id="picturePreview" src="#" alt="pic" height="150px" width="150px" />
                    </div>
                </div>

            </div>
            <hr class="mt-1 mb-1">
            <div class="form-row">
                <div class="form-group col-lg-3 col-md-6 col-12">
                    <button type="submit" class="btn btn-primary btn-fw btn-block">Add
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    $("#county_id").change(function(e) {
        $("#sub_county_id").value = "";
        $("#sub_county_id").empty();

        $("#sub_county_id").append("<option> -- Select Sub County -- </option>");

        let subCounties = JSON.parse($("#sub_county_id").attr("data-subcounties"))
        let filteredSubCounties = []
        for(let subCounty of subCounties) {
            console.log(subCounty)
            if (subCounty.county_id == e.target.value){
                elem = `<option value='${subCounty.id}'>${subCounty.name}</option>`
                $("#sub_county_id").append(elem)
            }
        }
    });
</script>
@endpush