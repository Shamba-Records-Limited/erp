@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
           <div class="card-title">Farmer Details</div>
        <div class="container farmer-profile">
            <div class="row mt-4">
                <div class="col-md-4 profile-info">
                    <div class="profile-picture mb-3 position-relative">
                        @if($farmer->profile_picture)
                        <img src="{{ url('storage/' . $farmer->profile_picture) }}" alt="Profile Picture"
                            class="img-fluid rounded-circle">
                        @else
                        <img src="{{ url('assets/images/avatar.png') }}" alt="Default Avatar"
                            class="img-fluid rounded-circle">
                        @endif
                        <div class="online-status"></div> <!-- Add online status indicator -->
                    </div>
                    <div class="info-card text-center p-3 shadow">
                        <h3 class="farmer-name">{{$farmer->first_name}} {{$farmer->other_names}}</h3>
                        <div class="location-info">
                            <i class="fas fa-map-marker-alt"></i> {{$farmer->county_name}}, {{$farmer->country_code}}
                        </div>
                        <div class="total-collection mt-3">
                            <p>Total Collection</p>
                            <h4 class="font-weight-bold">{{ isset($farmer->total_collection_quantity) ? $farmer->total_collection_quantity . ' KG' : 'No Data' }}</h4>
                        </div>
                    </div>
                </div>


                <div class="col-md-8">
                    <div class="bio-data detail-card mb-4">
                        <h4 class="card-heading"><i class="fas fa-user-circle"></i> Bio Data</h4>
                        <div class="detail-item">
                            <span class="farmer-icon"><i class="fas fa-envelope"></i></span>
                            <span class="label">Email:</span>
                            <span class="value">{{$farmer->email}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="farmer-icon"><i class="fas fa-phone"></i></span>
                            <span class="label">Phone:</span>
                            <span class="value">{{$farmer->phone_no}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="farmer-icon"><i class="fas fa-flag"></i></span>
                            <span class="label">Country:</span>
                            <span class="value">{{$farmer->country_code}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="farmer-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <span class="label">County:</span>
                            <span class="value">{{$farmer->county_name}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="farmer-icon"><i class="fas fa-map-signs"></i></span>
                            <span class="label">Sub-County:</span>
                            <span class="value">{{$farmer->sub_county_name}}</span>
                        </div>
                    </div>

                    <div class="economic-data detail-card mb-4">
                        <h4 class="card-heading"><i class="fas fa-chart-line"></i> Identification Data</h4>
                        <div class="detail-item">
                            <span class="farmer-icon"><i class="fas fa-id-card"></i></span>
                            <span class="label">ID Number:</span>
                            <span class="value">{{$farmer->id_no}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="farmer-icon"><i class="fas fa-user-tag"></i></span>
                            <span class="label">Member Number:</span>
                            <span class="value">{{$farmer->member_no}}</span>
                        </div>
                        <div class="detail-item">
                            <span class="farmer-icon"><i class="fas fa-venus-mars"></i></span>
                            <span class="label">Gender:</span>
                            <span class="value">{{$farmer->gender}}</span>
                        </div>

                    </div>

                    <div class="production-data detail-card">
                        <h4 class="card-heading"><i class="fas fa-seedling"></i> Production Data</h4>
                        <div class="detail-item">
                            <span class="farmer-icon"><i class="fas fa-tractor"></i></span>
                            <span class="label">Total Collection Quantity:</span>
                            <span class="value">{{ isset($farmer->total_collection_quantity) ? $farmer->total_collection_quantity . ' KG' : 'No Data' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="farmer-icon"><i class="fas fa-leaf"></i></span>
                            <span class="label">Crop Types:</span>
                            <!-- Assuming you have crop types data -->
                        </div>
                        <div class="detail-item">
                            <span class="farmer-icon"><i class="fas fa-map"></i></span>
                            <span class="label">Production Area:</span>
                            <!-- Assuming you have production area data -->
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <hr class="my-4">

        <!-- Tabs for Cooperatives and Collections -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'cooperatives' ? 'active' : '' }}" href="?tab=cooperatives">
                    <i class="fas fa-people-carry mr-1"></i> Cooperatives
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'collections' ? 'active' : '' }}" href="?tab=collections">
                    <i class="fas fa-box-open mr-1"></i> Collections
                </a>
            </li>
        </ul>

        <!-- Cooperatives Tab Content -->
        @if ($tab == 'cooperatives' || empty($tab))
        <div class="table-responsive p-4 bg-white rounded shadow-sm mt-3">
            <h5 class="text-primary font-weight-bold mb-3">Cooperatives</h5>
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Added On</th>
                        <th>Added By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($farmerCooperatives as $key => $coop)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $coop->coop_name }}</td>
                        <td>{{ $coop->created_at ? \Carbon\Carbon::parse($coop->created_at)->format('d M Y h:i A') : 'N/A' }}</td>
                        <td>{{ $coop->added_by_first_name ?? 'N/A' }} {{ $coop->added_by_other_names ?? '' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Collections Tab Content -->
        @if ($tab == 'collections')
        <div class="table-responsive p-4 bg-white rounded shadow-sm mt-3">
            <h5 class="text-primary font-weight-bold mb-3">Collections</h5>
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Cooperative</th>
                        <th>Collection Number</th>
                        <th>Lot Number</th>
                        <th>Name</th>
                        <th>Collection Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($farmerCollections as $key => $collection)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $collection->coop_name }}</td>
                        <td>{{ $collection->collection_number }}</td>
                        <td>{{ $collection->lot_number }}</td>
                        <td>{{ $collection->name }}</td>
                        <td>{{ $collection->date_collected }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush
