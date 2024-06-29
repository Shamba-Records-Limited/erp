@extends('layout.master-mini')
@push('plugin-styles')
@toastr_css
<link rel="stylesheet" href=" {{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href=" {{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush
@section('content')
@php
$gender_options = config('enums.employee_configs')['gender'];
$marital_status_options = config('enums.employee_configs')['marital_status'];

@endphp
<div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one" style="background-image: url({{ url('assets/images/auth/login_1.jpg') }}); background-size: cover;">
    <div class="row w-100">
        <div class="col-lg-10 mx-auto">
            <div class="auto-form-wrapper">
                <h4 class="card-title">Register as a Farmer</h4>
                <hr class="mb-5">
                <form method="POST" action="{{ route('farmer.register') }}" enctype="multipart/form-data">
                    @csrf
                    {{$errors}}
                    <div class="form-row">
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="f_name">First Name</label>
                            <input type="text" name="f_name" class="form-control {{ $errors->has('f_name') ? ' is-invalid' : '' }}" id="f_name" placeholder="John" value="{{ old('f_name')}}" required>
                            @if ($errors->has('f_name'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('f_name')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="o_name">Sur Name</label>
                            <input type="text" name="o_names" value="{{ old('o_names')}}" class="form-control {{ $errors->has('o_names') ? ' is-invalid' : '' }}" id="o_name" placeholder="Doe" required>
                            @if ($errors->has('o_names'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('o_names')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="u_name">User Name</label>
                            <input type="text" name="u_name" class="form-control {{ $errors->has('u_name') ? ' is-invalid' : '' }}" id="u_name" placeholder="j_doe" value="{{ old('u_name')}}" required>
                            @if ($errors->has('u_name'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('u_name')  }}</strong>
                            </span>
                            @endif
                        </div>


                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="user_email">Email</label>
                            <input type="email" name="user_email" class="form-control {{ $errors->has('user_email') ? ' is-invalid' : '' }}" id="user_email" placeholder="johndoe@abc.com" value="{{ old('user_email')}}" required>
                            @if ($errors->has('user_email'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('user_email')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="country_code">Country</label>
                            <select name="country_code" id="country_code" class=" form-control select2bs4 {{ $errors->has('country_code') ? ' is-invalid' : '' }}" value="Kenya">
                                <option value=""> -Select Country-</option>
                                @foreach($countries as $country)
                                <option value="{{$country['code']}}" @if($country['name']=='Kenya' ) selected @endif> {{ $country['name'] }}</option>
                                @endforeach

                                @if ($errors->has('country_code'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('country_code')  }}</strong>
                                </span>
                                @endif
                            </select>
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="county_id">Select County</label>
                            <select name="county_id" id="county_id" class=" form-control select2bs4 {{ $errors->has('county_id') ? ' is-invalid' : '' }}">
                                <option value=""> -Select County-</option>
                                @foreach($counties as $county)
                                <option value="{{$county->id}}" @if($county->id == old('county_id', '')) selected @endif> {{ $county->name }}</option>
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
                            <label for="member_no">Member No</label>
                            <input type="text" name="member_no" class="form-control {{ $errors->has('member_no') ? ' is-invalid' : '' }}" id="member_no" placeholder="A236...Z" value="{{ old('member_no')}}">

                            @if ($errors->has('member_no'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('member_no')  }}</strong>
                            </span>
                            @endif
                        </div>


                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="id_no">Id No./Passport</label>
                            <input type="text" name="id_no" class="form-control  {{ $errors->has('id_no') ? ' is-invalid' : '' }}" value="{{ old('id_no')}}" id="id_no" placeholder="12345678" required>
                            @if ($errors->has('id_no'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('id_no')  }}</strong>
                            </span>
                            @endif
                        </div>


                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="dob">Date of Birth</label>
                            <input type="date" name="dob" class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" id="dob" value="{{ old('dob')}}" required>

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
                                <option value="{{$option}}" @if($option == old('gender', '')) selected @endif> {{ $option}}</option>
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
                            <input type="text" name="phone_no" class="form-control  {{ $errors->has('phone_no') ? ' is-invalid' : '' }}" id="phone_no" placeholder="07..." value="{{ old('phone_no')}}" required>
                            @if ($errors->has('phone_no'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('phone_no')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" value="{{ old('password')}}" required>

                            @if ($errors->has('password'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('password')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="c_password">Confirm Password</label>
                            <input type="password" name="c_password" class="form-control {{ $errors->has('c_password') ? ' is-invalid' : '' }}" id="c_password" value="{{ old('c_password')}}" required>

                            @if ($errors->has('c_password'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('c_password')  }}</strong>
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

                    <div class="form-row">
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <button type="submit" class="btn btn-primary btn-fw btn-block">Register</button>
                        </div>
                    </div>

                    <div class="form-group d-flex">
                        <a href="{{ route('password.request') }}" class="text-small forgot-password text-black">Forgot
                            Password</a>
                        <span class="text-small forgot-password pl-1 pr-1">|</span>
                        <a href="{{ route('login') }}" class="text-small forgot-password text-black pl-1">Login</a>
                    </div>

                </form>
            </div>
            <p class="footer-text text-center mt-5">copyright © @php echo date('Y') @endphp Shamba Equity. All
                rights reserved.</p>
        </div>
    </div>
</div>

@endsection
@push('plugin-scripts')
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $('.select2').select2();

    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });
</script>
@toastr_js
@toastr_render
@endpush
@push('custom-scripts')
<script>
    function set_subcounty_list(is_initial = false) {
        let county_value = $("#county_id").val();
        alert(county_value);


        $("#sub_county_id").value = "";
        $("#sub_county_id").empty();

        $("#sub_county_id").append("<option> -- Select Sub County -- </option>");

        if (county_value == "") {
            return;
        }
        let subCounties = JSON.parse($("#sub_county_id").attr("data-subcounties"))
        let filteredSubCounties = []
        for (let subCounty of subCounties) {
            if (subCounty.county_id == county_value) {
                let elem = '';
                if (is_initial && subCounty.id == "{{ old('sub_county_id', '') }}") {
                    elem = `<option value='${subCounty.id}' selected>${subCounty.name}</option>`
                } else {
                    elem = `<option value='${subCounty.id}'>${subCounty.name}</option>`
                }
                $("#sub_county_id").append(elem)
            }
        }
    }

    $("#county_id").change(function(e) {
        set_subcounty_list();
    });

    // on page load
    set_subcounty_list(true);
</script>
@endpush