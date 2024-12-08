@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
@php
$countries = get_countries();
@endphp
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="container my-5">
                <div class="row">
                    <!-- Number of Miller Branches Card -->
                    <div class="col-md-4 col-12 mb-4">
                        <div class="card shadow-lg border-0 rounded">
                            <div class="card-header text-center bg-gradient-success text-white">
                                <h5 class="font-weight-bold mb-0">Number of Registered Millers</h5>
                            </div>
                            <div class="card-body text-center">
                                <h2 id="miller-count" class="font-weight-bold text-success display-4">0</h2>
                                <p class="font-weight-bold text-muted">Miller Branches</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
<div class="card shadow-sm">
    <div class="card-body">
        <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="collapse" data-target="#addMillerAccordion"
                aria-expanded="@if ($errors->count() > 0) true @else false @endif" aria-controls="addMillerAccordion">
            <span class="mdi mdi-plus"></span> Add Miller
        </button>

        <div class="collapse @if($errors->count() > 0) show @endif" id="addMillerAccordion">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="text-primary font-weight-bold mb-4">Register Miller</h4>

                <form action="{{ route('admin.millers.add') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <!-- Miller Details Section -->
                    <div class="section-header bg-light p-2 mb-3 rounded">
                        <h6 class="text-muted mb-0">Miller Details</h6>
                    </div>
                    <div class="form-row">
                        <!-- Name -->
                        <div class="form-group col-md-4">
                            <label for="miller_name" class="font-weight-bold">Name</label>
                            <input type="text" name="miller_name" class="form-control rounded {{ $errors->has('miller_name') ? ' is-invalid' : '' }}" 
                                   id="miller_name" placeholder="ABC" value="{{ old('miller_name')}}" required>
                            @error('miller_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Abbreviation -->
                        <div class="form-group col-md-4">
                            <label for="abbreviation" class="font-weight-bold">Abbreviation</label>
                            <input type="text" name="abbreviation" class="form-control rounded {{ $errors->has('abbreviation') ? ' is-invalid' : '' }}" 
                                   id="abbreviation" placeholder="A.B.C" value="{{ old('abbreviation')}}">
                            @error('abbreviation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Location -->
                        <div class="form-group col-md-4">
                            <label for="location" class="font-weight-bold">Location</label>
                            <input type="text" name="location" class="form-control rounded {{ $errors->has('location') ? ' is-invalid' : '' }}" 
                                   id="location" placeholder="Nairobi" value="{{ old('location')}}" required>
                            @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Address -->
                        <div class="form-group col-md-4">
                            <label for="address" class="font-weight-bold">Address</label>
                            <input type="text" name="address" class="form-control rounded {{ $errors->has('address') ? ' is-invalid' : '' }}" 
                                   id="address" placeholder="1234 Street, Nairobi" value="{{ old('address')}}" required>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Country -->
                        <div class="form-group col-md-4">
                            <label for="country_code" class="font-weight-bold">Country</label>
                            <select name="country_code" id="country_code" class="form-control rounded {{ $errors->has('country_code') ? ' is-invalid' : '' }}">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country['code'] }}" @if(old('country_code') == $country['code']) selected @endif>{{ $country['name'] }}</option>
                                @endforeach
                            </select>
                            @error('country_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- County -->
                        <div class="form-group col-md-4">
                            <label for="county_id" class="font-weight-bold">Select County</label>
                            <select name="county_id" id="county_id" class="form-control rounded {{ $errors->has('county_id') ? ' is-invalid' : '' }}">
                                <option value="">Select County</option>
                                @foreach($counties as $county)
                                    <option value="{{ $county->id }}" @if(old('county_id') == $county->id) selected @endif>{{ $county->name }}</option>
                                @endforeach
                            </select>
                            @error('county_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Sub County -->
                        <div class="form-group col-md-4">
                            <label for="sub_county_id" class="font-weight-bold">Select Sub County</label>
                            <select name="sub_county_id" id="sub_county_id" class="form-control rounded {{ $errors->has('sub_county_id') ? ' is-invalid' : '' }}" data-subcounties="{{ json_encode($sub_counties) }}">
                                <option value="">Select Sub County</option>
                            </select>
                            @error('sub_county_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Miller Email -->
                        <div class="form-group col-md-4">
                            <label for="miller_email" class="font-weight-bold">Email</label>
                            <input type="email" name="miller_email" class="form-control rounded {{ $errors->has('miller_email') ? ' is-invalid' : '' }}" 
                                   id="miller_email" placeholder="info@abc.com" value="{{ old('miller_email')}}" required>
                            @error('miller_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="form-group col-md-4">
                            <label for="phone_no" class="font-weight-bold">Phone Number</label>
                            <input type="text" name="phone_no" class="form-control rounded {{ $errors->has('phone_no') ? ' is-invalid' : '' }}" 
                                   id="phone_no" placeholder="2547..." value="{{ old('phone_no')}}" required>
                            @error('phone_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Logo Upload -->
                        <div class="form-group col-md-4">
                            <label for="miller_logo" class="font-weight-bold">Logo</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input {{ $errors->has('miller_logo') ? ' is-invalid' : '' }}" id="miller_logo" name="miller_logo">
                                <label class="custom-file-label" for="miller_logo">Choose file</label>
                            </div>
                            @error('miller_logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Contact Person Section -->
                    <hr class="mt-4 mb-4">
                    <h6 class="text-muted mb-3">Contact Person</h6>

                    <div class="form-row">
                        <!-- First Name -->
                        <div class="form-group col-md-4">
                            <label for="f_name" class="font-weight-bold">First Name</label>
                            <input type="text" name="f_name" class="form-control rounded {{ $errors->has('f_name') ? ' is-invalid' : '' }}" 
                                   id="f_name" placeholder="John" value="{{ old('f_name')}}" required>
                            @error('f_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Surname -->
                        <div class="form-group col-md-4">
                            <label for="o_names" class="font-weight-bold">Surname</label>
                            <input type="text" name="o_names" class="form-control rounded {{ $errors->has('o_names') ? ' is-invalid' : '' }}" 
                                   id="o_names" placeholder="Doe" value="{{ old('o_names')}}" required>
                            @error('o_names') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- User Name -->
                        <div class="form-group col-md-4">
                            <label for="u_name" class="font-weight-bold">User Name</label>
                            <input type="text" name="u_name" class="form-control rounded {{ $errors->has('u_name') ? ' is-invalid' : '' }}" 
                                   id="u_name" placeholder="j_doe" value="{{ old('u_name')}}" required>
                            @error('u_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group col-md-4">
                            <label for="user_email" class="font-weight-bold">Email</label>
                            <input type="email" name="user_email" class="form-control rounded {{ $errors->has('user_email') ? ' is-invalid' : '' }}" 
                                   id="user_email" placeholder="johndoe@abc.com" value="{{ old('user_email')}}" required>
                            @error('user_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="mdi mdi-check"></i> Register Miller
                        </button>
                    </div>
                </form>
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
                                <th>County</th>
                                <th>Sub County</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($millers as $key => $miller)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <a href="{{ route('millers.detail', $miller->id) }}" class="text-primary">
                                        {{ $miller->name }} ({{ $miller->abbreviation }})
                                    </a>
                                </td>
                                <td>{{ $miller->email }}</td>
                                <td>{{ $miller->county_name ?? 'N/A' }}</td>
                                <td>{{ $miller->sub_county_name ?? 'N/A' }}</td>
                                <td>
                                    <!-- You can add action buttons here if needed, like Edit or Delete -->
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
    // Update Number of Millers dynamically from DataTable
    $(document).ready(function() {
        const millerCount = $('.dt').DataTable().page.info().recordsTotal;
        $('#miller-count').text(millerCount);
    });
</script>
@endpush