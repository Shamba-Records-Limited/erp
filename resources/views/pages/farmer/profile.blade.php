@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
            <div class="card-body">

<div class="card-title">Farmer Details</div>
<div class="container farmer-profile">
    <div class="row mt-4">
        <!-- Profile Image and Summary Info -->
        <div class="col-md-4 profile-info">
            <div class="profile-picture mb-3 position-relative">
                @if($farmer->profile_picture)
                    <img src="{{ url('storage/' . $farmer->profile_picture) }}" alt="Profile Picture"
                         class="img-fluid rounded-circle">
                @else
                    <img src="{{ url('assets/images/avatar.png') }}" alt="Default Avatar"
                         class="img-fluid rounded-circle">
                @endif
                <div class="online-status"></div> <!-- Optional online status indicator -->
            </div>
            <div class="info-card text-center p-3 shadow">
                <h3 class="farmer-name">{{ ucwords(strtolower($farmer->first_name) . ' ' . strtolower($farmer->other_names)) }}</h3>
                <div class="location-info">
                    <i class="fas fa-map-marker-alt"></i> {{ optional($farmer->farmer->county)->name }}, {{ optional($farmer->farmer->country)->name }}
                </div>
                <div class="total-collection mt-3">
                    <p>Total Collection</p>
                    <h4 class="font-weight-bold">{{$collection_quantity}} KG</h4>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="col-md-8">
            <!-- Bio Data -->
            <div class="bio-data detail-card mb-4">
                <h4 class="card-heading"><i class="fas fa-user-circle"></i> Bio Data</h4>
                <div class="detail-item">
                    <span class="farmer-icon"><i class="fas fa-envelope"></i></span>
                    <span class="label">Email:</span>
                    <span class="value">{{ strtolower($farmer->email) }}</span>
                </div>
                <div class="detail-item">
                    <span class="farmer-icon"><i class="fas fa-phone"></i></span>
                    <span class="label">Phone:</span>
                    <span class="value">{{ $farmer->farmer->phone_no }}</span>
                </div>
                <div class="detail-item">
                    <span class="farmer-icon"><i class="fas fa-user"></i></span>
                    <span class="label">Username:</span>
                    <span class="value">{{ $farmer->username }}</span>
                </div>
                <div class="detail-item">
                    <span class="farmer-icon"><i class="fas fa-birthday-cake"></i></span>
                    <span class="label">DOB:</span>
                    <span class="value">{{ Carbon\Carbon::parse($farmer->farmer->dob)->format('d F Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="farmer-icon"><i class="fas fa-mars"></i></span>
                    <span class="label">Gender:</span>
                    <span class="value">{{ $farmer->farmer->gender == 'M' ? 'Male' : ($farmer->farmer->gender == 'F' ? 'Female' : 'Other') }}</span>
                </div>
                <div class="detail-item">
                    <span class="farmer-icon"><i class="fas fa-id-card"></i></span>
                    <span class="label">ID No:</span>
                    <span class="value">{{ $farmer->farmer->id_no }}</span>
                </div>
            </div>

            <!-- Economic Data -->
            <div class="economic-data detail-card mb-4">
                <h4 class="card-heading"><i class="fas fa-chart-line"></i> Economic Data</h4>
                <div class="detail-item">
                    <span class="farmer-icon"><i class="fas fa-id-card"></i></span>
                    <span class="label">Member Number:</span>
                    <span class="value">{{ $farmer->farmer->member_no }}</span>
                </div>
                <div class="detail-item">
                    <span class="farmer-icon"><i class="fas fa-user-tag"></i></span>
                    <span class="label">Customer Type:</span>
                    <span class="value">{{ $farmer->farmer->customer_type }}</span>
                </div>
                <div class="detail-item">
                    <span class="farmer-icon"><i class="fas fa-passport"></i></span>
                    <span class="label">KRA Pin:</span>
                    <span class="value">{{ $farmer->farmer->kra }}</span>
                </div>
            </div>

            <!-- Production Data -->
            <div class="production-data detail-card">
                <h4 class="card-heading"><i class="fas fa-seedling"></i> Production Data</h4>
                <div class="detail-item">
                    <span class="farmer-icon"><i class="fas fa-tractor"></i></span>
                    <span class="label">Total Collection Quantity:</span>
                    <span class="value">{{ $farmer->total_collection_quantity }} KG</span>
                </div>
                <div class="detail-item">
                    <span class="farmer-icon"><i class="fas fa-map"></i></span>
                    <span class="label">Route:</span>
                    <span class="value">{{ optional($farmer->farmer->route)->name ?? 'Route not available' }}</span>
                </div>
            </div>

            <!-- Edit Button -->
            <div class="text-center mt-4">
                <a class="btn btn-info btn-sm" href="{{ route('cooperative.farmer.edit', $farmer->farmer->id) }}">
                    Edit Profile <span class="mdi mdi-file-edit"></span>
                </a>
            </div>
        </div>
    </div>
</div>
</div>



        
@endsection

@push('plugin-scripts')
    <script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
@endpush

@push('custom-scripts')

    <script src="{{ asset('/assets/js/chart.js') }}"></script>

    <script>
      if ($("#revenue-chart").length) {
        $("#revenue-chart").sparkline("html", {
          enableTagOptions: true,
          width: "100%",
          height: "70",
          fillColor: "false",
          barWidth: 2,
          barSpacing: 10,
          chartRangeMin: 0
        });
      }
    </script>
@endpush
