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
                    <!-- Number of Millers Card -->
                    <div class="col-md-4 col-12 mb-4">
                        <div class="card shadow-lg border-0 rounded">
                            <div class="card-header text-center bg-gradient-success text-white">
                                <h5 class="font-weight-bold mb-0">Total Number of Millers</h5>
                            </div>
                            <div class="card-body text-center">
                                <h2 id="miller-count" class="font-weight-bold text-success display-4">0</h2>
                                <p class="font-weight-bold text-muted">Registered Millers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <button type="button" class="btn btn-primary btn-fw btn-sm mb-4" data-toggle="collapse" data-target="#addMillerAccordion" aria-expanded="@if ($errors->count() > 0) true @else false @endif" aria-controls="addMillerAccordion">
                    <span class="mdi mdi-plus"></span> Add Miller
                </button>
                
                <div class="collapse @if($errors->count() > 0) show @endif" id="addMillerAccordion">
                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h4 class="text-primary font-weight-bold mb-4">Register Miller</h4>

                        <form action="{{ route('admin.millers.add') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Miller Details Section -->
                            <div class="section-header bg-light p-2 mb-3 rounded">
                                <h6 class="text-muted mb-0">Miller Details</h6>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="miller_name">Name</label>
                                    <input type="text" name="miller_name" class="form-control rounded" id="miller_name" placeholder="ABC" value="{{ old('miller_name') }}" required>
                                    @error('miller_name') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="abbreviation">Abbreviation</label>
                                    <input type="text" name="abbreviation" class="form-control rounded" id="abbreviation" placeholder="A.B.C" value="{{ old('abbreviation') }}">
                                    @error('abbreviation') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="location">Location</label>
                                    <input type="text" name="location" class="form-control rounded" id="location" placeholder="Nairobi" value="{{ old('location') }}" required>
                                    @error('location') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="address">Address</label>
                                    <input type="text" name="address" class="form-control rounded" id="address" placeholder="Nairobi" value="{{ old('address') }}" required>
                                    @error('address') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="country_code">Country</label>
                                    <select name="country_code" id="country_code" class="form-control rounded">
                                        <option value="">- Select Country -</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country['code'] }}" @if($country['name'] == 'Kenya') selected @endif>{{ $country['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_code') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="county_id">Select County</label>
                                    <select name="county_id" id="county_id" class="form-control rounded">
                                        <option value="">- Select County -</option>
                                        @foreach($counties as $county)
                                            <option value="{{ $county->id }}">{{ $county->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('county_id') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="miller_email">Miller Email</label>
                                    <input type="email" name="miller_email" class="form-control rounded" id="miller_email" placeholder="info@abc.com" value="{{ old('miller_email') }}" required>
                                    @error('miller_email') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="phone_no">Phone Number</label>
                                    <input type="text" name="phone_no" class="form-control rounded" id="phone_no" placeholder="2547....." value="{{ old('phone_no') }}" required>
                                    @error('phone_no') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="miller_logo">Logo</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="miller_logo" name="miller_logo">
                                        <label class="custom-file-label" for="miller_logo">Choose file</label>
                                    </div>
                                    @error('miller_logo') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Contact Person Section -->
                            <div class="section-header bg-light p-2 mb-3 rounded">
                                <h6 class="text-muted mb-0">Contact Person</h6>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="f_name">First Name</label>
                                    <input type="text" name="f_name" class="form-control rounded" id="f_name" placeholder="John" value="{{ old('f_name') }}" required>
                                    @error('f_name') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="o_names">Other Names</label>
                                    <input type="text" name="o_names" class="form-control rounded" id="o_name" placeholder="Doe" value="{{ old('o_names') }}" required>
                                    @error('o_names') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="u_name">Username</label>
                                    <input type="text" name="u_name" class="form-control rounded" id="u_name" placeholder="j_doe" value="{{ old('u_name') }}" required>
                                    @error('u_name') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="user_email">Email</label>
                                    <input type="email" name="user_email" class="form-control rounded" id="user_email" placeholder="johndoe@abc.com" value="{{ old('user_email') }}" required>
                                    @error('user_email') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary mt-3">Add Miller</button>
                            </div>
                        </form>
                    </div>
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

// Update Number of Millers dynamically from DataTable
$(document).ready(function() {
    const millersCount = $('.dt').DataTable().page.info().recordsTotal;
    $('#miller-count').text(millersCount);
});
</script>
@endpush
