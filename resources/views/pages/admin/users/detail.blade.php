@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="card-title">User Details</div>
        <div class="container user-profile">
            <div class="row mt-4">
                <div class="col-md-4 profile-info">
                    <div class="profile-picture mb-3 position-relative">
                        @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                         alt="Profile Picture" class="img-fluid rounded-circle">

                        @else
                        <img src="{{ url('assets/images/avatar.png') }}" alt="Default Avatar"
                            class="img-fluid rounded-circle">
                        @endif
                        <div class="online-status"></div> <!-- Add online status indicator -->
                    </div>
                    <div class="info-card text-center p-3 shadow">
                        <h3 class="user-name">{{$user->first_name}} {{$user->other_names}}</h3>
                        <div class="location-info">
                            <i class="fas fa-map-marker-alt"></i>
                           @if (!is_null($user->employee_country_name) && !is_null($user->employee_county))
                             {{$user->employee_county}}, {{$user->employee_country_name}}
                         @elseif (!is_null($user->official_country_name) && !is_null($user->official_county))
                              {{$user->official_county}}, {{$user->official_country_name}}
                         @else
                             <span>No location info available</span>
                         @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-8">

                    <div class="bio-data detail-card mb-4">
                        <h4 class="card-heading"><i class="fas fa-user-circle"></i> Bio Data</h4>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-envelope"></i></span>
                            <span class="label">Email:</span>
                            <span class="value">{{$user->email}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-user"></i></span>
                            <span class="label">Username:</span>
                            <span class="value">{{$user->username}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-users"></i></span>
                            <span class="label">Cooperative:</span>
                            <span class="value">{{$user->coop_name}}</span>
                        </div>
                    </div>

                    @if (!is_null($user->employee_id))
                    <div class="employee-data detail-card mb-4">
                        <h4 class="card-heading"><i class="fas fa-briefcase"></i> Employee Details</h4>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-map"></i></span>
                            <span class="label">Country:</span>
                            <span class="value">{{$user->employee_country_name}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-city"></i></span>
                            <span class="label">County:</span>
                            <span class="value">{{$user->employee_county}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-home"></i></span>
                            <span class="label">Residence Area:</span>
                            <span class="value">{{$user->employee_residence_area}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-user-friends"></i></span>
                            <span class="label">Marital Status:</span>
                            <span class="value">{{$user->employee_marital_status}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-birthday-cake"></i></span>
                            <span class="label">Date of Birth:</span>
                            <span class="value">{{$user->employee_dob}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-id-card"></i></span>
                            <span class="label">ID Number:</span>
                            <span class="value">{{$user->employee_id_no}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-phone"></i></span>
                            <span class="label">Phone Number:</span>
                            <span class="value">{{$user->employee_phone_no}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-user-tag"></i></span>
                            <span class="label">Employee Number:</span>
                            <span class="value">{{$user->employee_employee_no}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-landmark"></i></span>
                            <span class="label">Department:</span>
                            <span class="value">{{$user->employee_department_name}}</span>
                        </div>
                    </div>
                    @endif

                    @if (!is_null($user->official_id))
                    <div class="official-data detail-card mb-4">
                        <h4 class="card-heading"><i class="fas fa-university"></i> County Government Official Details</h4>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-map"></i></span>
                            <span class="label">Country:</span>
                            <span class="value">{{$user->official_country_name}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-city"></i></span>
                            <span class="label">County:</span>
                            <span class="value">{{$user->official_county}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-venus-mars"></i></span>
                            <span class="label">Gender:</span>
                            <span class="value">{{$user->official_gender}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-id-card"></i></span>
                            <span class="label">ID Number:</span>
                            <span class="value">{{$user->official_id_no}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-phone"></i></span>
                            <span class="label">Phone Number:</span>
                            <span class="value">{{$user->official_phone_no}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-user-tag"></i></span>
                            <span class="label">Employee Number:</span>
                            <span class="value">{{$user->official_employee_no}}</span>
                        </div>
                    </div>
                    @endif

                    <div class="bio-data detail-card mb-4">
                        <h4 class="card-heading"><i class="fas fa-user-circle"></i> Roles and Permissions</h4>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-user"></i></span>
                            <span class="label">Roles:</span>
                            @foreach ($roles as $role) 
                            <span class="value">{{$role}}</span>
                            @endforeach
                        </div>
                        <div class="detail-item">
                            <span class="user-icon"><i class="fas fa-user"></i></span>
                            <span class="label">Permissions:</span>
                            @foreach ($permissions as $permission) 
                            <span class="value">{{$permission}}</span>
                            @endforeach
                        </div>
                    </div>


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
