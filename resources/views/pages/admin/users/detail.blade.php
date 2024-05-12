@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
<div class="row">
    <div class="d-flex align-items-end p-4">
        <!-- <div class="badge badge-primary">Employee</div> -->
        <!-- <div>Tag 2</div> -->
        @if (is_null($user->employee_id))
        <a href="{{route('admin.users.view-make-employee', $user->id)}}" class="btn btn-primary">Make Employee</a>
        @endif
        <!-- <a href="{{route('admin.users.view-make-employee', $user->id)}}" class="btn btn-primary">Make Farmer</a> -->
        @if (is_null($user->official_id))
        <!-- <a href="{{route('admin.users.view-make-employee', $user->id)}}" class="btn btn-primary">Make County Government Official</a> -->
        @endif
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="card-title">User Details</div>
                <div class="row">
                    <div class="col-3 border m-2 rounded">Cooperative: {{$user->coop_name}}</div>
                    <div class="col-3 border m-2 rounded">Username: {{$user->username}}</div>
                    <div class="col-3 border m-2 rounded">Name: {{$user->first_name}} {{$user->other_names}}</div>
                    <div class="col-3 border m-2 rounded">Email: {{$user->email}}</div>
                </div>
            </div>
        </div>

        @if (!is_null($user->employee_id))
        <div class="card mt-2">
            <div class="card-body">
                <div class="card-title">Employee Details</div>
                <div class="row">
                    <div class="col-3 border m-2 rounded">Country: {{$user->employee_country_name}}</div>
                    <div class="col-3 border m-2 rounded">County: {{$user->employee_county}}</div>
                    <div class="col-3 border m-2 rounded">Residence Area: {{$user->employee_residence_area}}</div>
                    <div class="col-3 border m-2 rounded">Marital Status: {{$user->employee_marital_status}}</div>
                    <div class="col-3 border m-2 rounded">Date of Birth: {{$user->employee_dob}}</div>
                    <div class="col-3 border m-2 rounded">Gender: {{$user->employee_gender}}</div>
                    <div class="col-3 border m-2 rounded">Id No: {{$user->employee_id_no}}</div>
                    <div class="col-3 border m-2 rounded">Phone Number: {{$user->employee_phone_no}}</div>
                    <div class="col-3 border m-2 rounded">Employee Number: {{$user->employee_employee_no}}</div>
                    <div class="col-3 border m-2 rounded">Kra Pin: {{$user->employee_kra}}</div>
                    <div class="col-3 border m-2 rounded">Nhif: {{$user->employee_nhif}}</div>
                    <div class="col-3 border m-2 rounded">Nssf: {{$user->employee_nssf}}</div>
                    <div class="col-3 border m-2 rounded">Department: {{$user->employee_department_name}}</div>
                </div>
            </div>
        </div>
        @endif

        <!-- <div class="card mt-2">
            <div class="card-body">
                <div class="card-title">Farmer Details</div>
            </div>
        </div> -->

        @if (!is_null($user->official_id))
        <div class="card mt-2">
            <div class="card-body">
                <div class="card-title">County Govt Official Details</div>
                <div class="row">
                    <div class="col-3 border m-2 rounded">Country: {{$user->official_country_name}}</div>
                    <div class="col-3 border m-2 rounded">County: {{$user->official_county}}</div>
                    <div class="col-3 border m-2 rounded">Gender: {{$user->official_gender}}</div>
                    <div class="col-3 border m-2 rounded">Id Number: {{$user->official_id_no}}</div>
                    <div class="col-3 border m-2 rounded">Phone Number: {{$user->official_phone_no}}</div>
                    <div class="col-3 border m-2 rounded">Employee Number: {{$user->official_employee_no}}</div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush