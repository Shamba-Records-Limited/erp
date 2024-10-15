@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
@php
$countries = get_countries();
@endphp

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                    data-target="#addComapnyAccordion"
                    aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                    aria-controls="addComapnyAccordion"><span class="mdi mdi-plus"></span>Add Miller
                </button>
                <div class="collapse @if($errors->count() > 0) show @endif" id="addComapnyAccordion">
                    <div class="row mt-5">
                        <div class="col-lg-12 grid-margin stretch-card col-12">
                            <h4>Register Miller</h4>
                        </div>
                    </div>


                    <form action="{{ route('admin.millers.add') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <!-- {{ $errors }} -->
                        <div class="form-row">
                            <div class="form-group col-12">
                                <h6 class="mb-3">Miller Details</h6>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="miller_name">Name</label>
                                <input type="text" name="miller_name"
                                    class="form-control {{ $errors->has('miller_name') ? ' is-invalid' : '' }}"
                                    id="miller_name" placeholder="ABC" value="{{ old('miller_name')}}" required>

                                @if ($errors->has('miller_name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('miller_name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="abbreviation">Abbreviation</label>
                                <input type="text" name="abbreviation"
                                    class="form-control  {{ $errors->has('abbreviation') ? ' is-invalid' : '' }}"
                                    id="abbreviation" placeholder="A.B.C" value="{{ old('abbreviation')}}">

                                @if ($errors->has('abbreviation'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('abbreviation')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="location">Location</label>
                                <input type="text" name="location"
                                    class="form-control  {{ $errors->has('location') ? ' is-invalid' : '' }}"
                                    value="{{ old('location')}}" id="location" placeholder="Nairobi" required>
                                @if ($errors->has('location'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('location')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="address">Address</label>
                                <input type="text" name="address"
                                    class="form-control  {{ $errors->has('address') ? ' is-invalid' : '' }}"
                                    id="address" placeholder="Nairobi" value="{{ old('address')}}" required>
                                @if ($errors->has('address'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('address')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="country_code">Country</label>
                                <select name="country_code" id="country_code"
                                    class=" form-control select2bs4 {{ $errors->has('country_code') ? ' is-invalid' : '' }}"
                                    value="Kenya">
                                    <option value=""> -Select Country-</option>
                                    @foreach($countries as $country)
                                    <option value="{{$country['code']}}" @if($country['name']=='Kenya' ) selected
                                        @endif> {{ $country['name'] }}</option>
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
                                <select name="county_id" id="county_id"
                                    class=" form-control select2bs4 {{ $errors->has('county_id') ? ' is-invalid' : '' }}">
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
                                <select data-subcounties="{{$sub_counties}}" name="sub_county_id" id="sub_county_id"
                                    class=" form-control select2bs4 {{ $errors->has('sub_county_id') ? ' is-invalid' : '' }}">
                                    <option value=""> -Select Sub County-</option>

                                    @if ($errors->has('sub_county_id'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('sub_county_id')  }}</strong>
                                    </span>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="miller_email">Miller Email</label>
                                <input type="email" name="miller_email"
                                    class="form-control {{ $errors->has('miller_email') ? ' is-invalid' : '' }}"
                                    id="miller_email" placeholder="info@abc.com" value="{{ old('miller_email')}}"
                                    required>


                                @if ($errors->has('miller_email'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('miller_email')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="phone_no">Phone Number</label>
                                <input type="text" name="phone_no"
                                    class="form-control {{ $errors->has('phone_no') ? ' is-invalid' : '' }}"
                                    id="phone_no" placeholder="2547....." value="{{ old('phone_no')}}" required>

                                @if ($errors->has('phone_no'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('phone_no')  }}</strong>
                                </span>
                                @endif
                            </div>


                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="miller_logo">Logo</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('miller_logo') is-invalid @enderror"
                                            id="miller_logo" name="miller_logo" value="{{ old('miller_logo') }}">
                                        <label class="custom-file-label" for="exampleInputFile">Logo</label>

                                        @if ($errors->has('miller_logo'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('miller_logo')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                        <hr class="mt-1 mb-1">
                        <h6 class="h6 mt-2">Contact Person</h6>
                        <div class="form-row">

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="f_name">First Name</label>
                                <input type="text" name="f_name"
                                    class="form-control {{ $errors->has('f_name') ? ' is-invalid' : '' }}" id="f_name"
                                    placeholder="John" value="{{ old('f_name')}}" required>
                                @if ($errors->has('f_name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('f_name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="o_name">Other Names</label>
                                <input type="text" name="o_names" value="{{ old('o_names')}}"
                                    class="form-control {{ $errors->has('o_names') ? ' is-invalid' : '' }}" id="o_name"
                                    placeholder="Doe" required>
                                @if ($errors->has('o_names'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('o_names')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="u_name">User Name</label>
                                <input type="text" name="u_name"
                                    class="form-control {{ $errors->has('u_name') ? ' is-invalid' : '' }}" id="u_name"
                                    placeholder="j_doe" value="{{ old('u_name')}}" required>
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
                                    id="user_email" placeholder="johndoe@abc.com" value="{{ old('user_email')}}"
                                    required>
                                @if ($errors->has('user_email'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('user_email')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <button type="submit" class="btn btn-primary btn-fw btn-block">Add</button>
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
                <h4 class="card-title">Registered Millers</h4>
                <div class="table-responsive">
                    <table class="table table-hover dt">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Country</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($millers as $key => $miller)
                            <tr>
                                <td>{{++$key }}</td>
                                <td>{{$miller->name }} ({{$miller->abbreviation}})</td>
                                <td>{{$miller->email }}</td>
                                <td> {{$miller->country_name }}
                                </td>
                                <td>
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
function deleteCoop(id) {
    shouldDelete = confirm("Are you sure you want to delete this cooperative?")
    if (!shouldDelete) {
        return
    }

    window.location = "/admin/cooperative/setup/delete/" + id
}
$("#county_id").change(function(e) {
    $("#sub_county_id").value = "";
    $("#sub_county_id").empty();

    $("#sub_county_id").append("<option> -- Select Sub County -- </option>");

    let subCounties = JSON.parse($("#sub_county_id").attr("data-subcounties"))
    let filteredSubCounties = []
    for (let subCounty of subCounties) {
        console.log(subCounty)
        if (subCounty.county_id == e.target.value) {
            elem = `<option value='${subCounty.id}'>${subCounty.name}</option>`
            $("#sub_county_id").append(elem)
        }
    }
});
</script>
@endpush