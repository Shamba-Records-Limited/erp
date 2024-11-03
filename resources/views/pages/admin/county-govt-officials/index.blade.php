@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

@if(auth()->user()->hasRole('admin'))

@php
$gender_options = config('enums.employee_configs')['gender'];
$marital_status_options = config('enums.employee_configs')['marital_status'];
$countries = get_countries();


// Convert array to collection if needed
$officials = collect($officials);

// Stats calculations
$total_officials = $officials->count();
$active_officials = $officials->where('status', \App\CoopEmployee::STATUS_ACTIVE)->count();
$inactive_officials = $officials->where('status', \App\CoopEmployee::STATUS_DEACTIVATED)->count();
$suspended_officials = $officials->whereIn('status', [
\App\CoopEmployee::STATUS_SUSPENDED_WITH_PAY,
\App\CoopEmployee::STATUS_SUSPENSION_WITHOUT_PAY
])->count();

// New stats
$officials_by_gender = $officials->groupBy('gender')->map(function($group) {
return $group->count();
})->toArray();

$officials_by_county = $officials->groupBy('county_name')->map(function($group) {
return $group->count();
})->toArray();

$total_counties = count($officials_by_county);
$total_cooperatives = $officials->groupBy('cooperative_id')->count();

// Find max county
$max_county = 0;
$max_county_name = '';
foreach ($officials_by_county as $county => $count) {
if ($count > $max_county) {
$max_county = $count;
$max_county_name = $county;
}
}
@endphp
<div class="header bg-custom-green pb-5 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Gender Distribution</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        Male: {{ $officials_by_gender['Male'] ?? 0 }} <br>
                                        Female: {{ $officials_by_gender['Female'] ?? 0 }}
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-genderless"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">County Coverage</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $total_counties }}/47</span>
                                    <p class="text-muted mb-0">Counties Represented</p>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Cooperatives Coverage</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $total_cooperatives }}</span>
                                    <p class="text-muted mb-0">Total Cooperatives</p>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                        <i class="fas fa-building"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Highest Officials Count</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $max_county }}</span>
                                    <p class="text-muted mb-0">{{ $max_county_name }}</p>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Stats Section -->
            <div class="row mt-3">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Total Officials</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $total_officials }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                        <i class="fas fa-user-friends"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Active Officials</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $active_officials }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Inactive Officials</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $inactive_officials }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-user-times"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Suspended Officials</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $suspended_officials }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-user-lock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                    data-target="#addEmployeeAccordion"
                    aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                    aria-controls="addEmployeeAccordion">
                    <span class="mdi mdi-plus"></span>Add County Govt Official
                </button>
                <div class="collapse @if ($errors->count() > 0) show @endif " id="addEmployeeAccordion">

                    <form action="{{ route('admin.county-govt-officials.add') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-12">
                                <h3 class="mb-3">County Govt Official Details</h3>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="cooperative_id">Cooperative</label>
                                <select name="cooperative_id" id="cooperative_id"
                                    class="form-control form-select {{ $errors->has('cooperative_id') ? ' is-invalid' : '' }}">
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
                                <input type="text" name="first_name"
                                    class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                                    id="first_name" placeholder="John" value="{{ old('first_name')}}" required>
                                @if ($errors->has('first_name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('first_name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="other_name">Other Names</label>
                                <input type="text" name="other_names" value="{{ old('other_names')}}"
                                    class="form-control {{ $errors->has('other_names') ? ' is-invalid' : '' }}"
                                    id="other_name" placeholder="Doe" required>
                                @if ($errors->has('other_names'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('other_names')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="user_name">User Name</label>
                                <input type="text" name="username"
                                    class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}"
                                    id="username" placeholder="j_doe" value="{{ old('username')}}" required>
                                @if ($errors->has('username'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('username')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="email">Email</label>
                                <input type="email" name="email"
                                    class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email"
                                    placeholder="johndoe@abc.com" value="{{ old('email')}}" required>
                                @if ($errors->has('email'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('email')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="country_code">Country</label>
                                <select name="country_code" id="country_code"
                                    class=" form-control form-select {{ $errors->has('country_code') ? ' is-invalid' : '' }}"
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
                                    class=" form-control form-select {{ $errors->has('county_id') ? ' is-invalid' : '' }}">
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
                                    class=" form-control form-select {{ $errors->has('sub_county_id') ? ' is-invalid' : '' }}">
                                    <option value=""> -Select Sub County-</option>

                                    @if ($errors->has('sub_county_id'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('sub_county_id')  }}</strong>
                                    </span>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="ministry">Ministry</label>
                                <input type="text" name="ministry"
                                    class="form-control  {{ $errors->has('ministry') ? ' is-invalid' : '' }}"
                                    value="{{ old('ministry')}}" id="ministry" placeholder="Enter govt ministry...">
                                @if ($errors->has('ministry'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('ministry')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="ministry">Designation</label>
                                <input type="text" name="designation"
                                    class="form-control  {{ $errors->has('designation') ? ' is-invalid' : '' }}"
                                    value="{{ old('designation')}}" id="designation"
                                    placeholder="Enter ministry designation...">
                                @if ($errors->has('designation'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('designation')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="id_no">Id No./Passport</label>
                                <input type="text" name="id_no"
                                    class="form-control  {{ $errors->has('id_no') ? ' is-invalid' : '' }}"
                                    value="{{ old('id_no')}}" id="id_no" placeholder="12345678">
                                @if ($errors->has('id_no'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('id_no')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender"
                                    class=" form-control form-select {{ $errors->has('gender') ? ' is-invalid' : '' }}">
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
                                <input type="text" name="phone_no"
                                    class="form-control  {{ $errors->has('phone_no') ? ' is-invalid' : '' }}"
                                    id="phone_no" placeholder="2547..." value="{{ old('phone_no')}}">
                                @if ($errors->has('phone_no'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('phone_no')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="employee_no">Employee No.</label>
                                <input type="text" name="employee_no"
                                    class="form-control {{ $errors->has('employee_no') ? ' is-invalid' : '' }}"
                                    id="employee_no" placeholder="EM1234" value="{{ old('employee_no')}}">

                                @if ($errors->has('employee_no'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('employee_no')  }}</strong>
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
        </div>
    </div>
</div>
@endif

@php $uploadErrors = Session::get('uploadErrors');@endphp
@if(has_right_permission(config('enums.system_modules')['HR Management']['employees'],
config('enums.system_permissions')['create']))
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                    data-target="#bulkUploadEmployeeAccordion"
                    aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                    aria-controls="bulkUploadEmployeeAccordion">
                    <span class="mdi mdi-plus">Bulk Import</span>
                </button>
                <div class="collapse @if ($errors->count() > 0 || isset($uploadErrors)) show @endif "
                    id="bulkUploadEmployeeAccordion">
                    <form action="{{ route('hr.employee.bulk.import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-12">
                                <h6 class="mb-3">Bulk Import Employees</h6>
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
                                    href="{{ route('download-upload-employee-template') }}">
                                    Download Template</a>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('employees') is-invalid @enderror"
                                            id="employees" name="employees" value="{{ old('employees') }}">
                                        <label class="custom-file-label" for="exampleInputFile">Employees File</label>

                                        @if ($errors->has('employees'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('employees')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <button type="submit" class="btn btn-primary btn-fw btn-block">Submit
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
                @if(has_right_permission(config('enums.system_modules')['HR Management']['employees'],
                config('enums.system_permissions')['download']))
                <a class="btn btn-sm btn-info float-right text-white"
                    href="{{ route('hr.employee.downloads', ['type'=>'csv', "department" =>$departmentId??null]) }}">
                    <i class="mdi mdi-download"></i> CSV
                </a>

                <a class="btn btn-sm btn-github float-right text-white"
                    href="{{ route('hr.employee.downloads',['type'=>'xlsx', "department" =>$departmentId??null]) }}"
                    style="margin-right: -5px!important;">
                    <i class="mdi mdi-download"></i> Excel
                </a>
                <a class="btn btn-sm btn-success float-right text-white"
                    href="{{ route('hr.employee.downloads',['type'=>'pdf', "department" =>$departmentId??null]) }}"
                    style="margin-right: -8px!important;">
                    <i class="mdi mdi-download"></i> PDF
                </a>
                @endif
                <h4 class="card-title">Registered County Govt Officials</h4>
                <div class="table-responsive">
                    <table class="table table-hover dt clickable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Country</th>
                                <th>County</th>
                                <th>Employee No.</th>
                                <th>Id/Passport No.</th>
                                <th>Phone No.</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($officials as $key => $official)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    <a>{{ ucwords(strtolower($official->username)) }}</a>
                                </td>
                                <td>
                                    {{ $official->country_code }}
                                    <span class="mr-2 table-flag"></span>
                                </td>
                                <td>{{ $official->county_name }} - {{ $official->sub_county_name }}</td>
                                <td>{{ $official->employee_no }}</td>
                                <td>{{ $official->id_no }}</td>
                                <td>{{ $official->phone_no }}</td>
                                <td>
                                    @if($official->status == \App\CoopEmployee::STATUS_ACTIVE)
                                    <span class="badge badge-success badge-status">
                                        {{ config('enums.employment_status')[$official->status] }}
                                    </span>
                                    @elseif($official->status == \App\CoopEmployee::STATUS_DEACTIVATED)
                                    <span class="badge badge-danger badge-status">
                                        {{ config('enums.employment_status')[$official->status] }}
                                    </span>
                                    @elseif($official->status == \App\CoopEmployee::STATUS_SUSPENDED_WITH_PAY)
                                    <span class="badge badge-warning badge-status">
                                        {{ config('enums.employment_status')[$official->status] }}
                                    </span>
                                    @elseif($official->status == \App\CoopEmployee::STATUS_SUSPENSION_WITHOUT_PAY)
                                    <span class="badge badge-dark badge-status">
                                        {{ config('enums.employment_status')[$official->status] }}
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group dropdown">
                                        <button type="button" class="btn btn-default dropdown-toggle btn-sm"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="text-warning dropdown-item"
                                                href="{{ route('admin.county-govt-officials.edit', $official->id) }}">
                                                <i class="fa fa-edit"></i>Edit
                                            </a>
                                            <a onclick="return confirm('Sure to Delete?')"
                                                href="/admin/county-govt-officials/delete/{{ $official->id }}"
                                                class="text-danger dropdown-item">
                                                <i class="fa fa-trash-alt"></i>Delete
                                            </a>
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
<script>
const generatedCodes = new Set();
$('#employee_number').on('click', function() {
    const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let shortcode = "";
    do {
        shortcode = "";
        for (let i = 0; i < 6; i++) {
            shortcode += characters.charAt(Math.floor(Math.random() * characters.length));
        }
    } while (generatedCodes.has(shortcode));
    generatedCodes.add(shortcode);
    $('#employee_number').val(shortcode.toUpperCase());
})

document.addEventListener("DOMContentLoaded", () => {
    const rows = document.querySelectorAll("tr[data-href]");
    rows.forEach(row => {
        row.addEventListener("click", () => {
            window.location.href = row.dataset.href
        })
    })
})

$('#profile_picture').change(function() {
    previewImage(this, 'picturePreview', 'imagePreviewContainer');
});

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