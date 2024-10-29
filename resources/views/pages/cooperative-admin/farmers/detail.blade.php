@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
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
                            <h4 class="font-weight-bold">{{$farmer->total_collection_quantity}} KG</h4>
                        </div>
                    </div>
                </div>


                <div class="col-md-8">
                    <div class="bio-data detail-card mb-4">
                        <h4>Bio Data</h4>
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
                        <h4>Economic Data</h4>
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
                        <h4>Production Data</h4>
                        <div class="detail-item">
                            <span class="farmer-icon"><i class="fas fa-tractor"></i></span>
                            <span class="label">Total Collection Quantity:</span>
                            <span class="value">{{$farmer->total_collection_quantity}} KG</span>
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






        <ul class="nav nav-tabs mt-5">
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'collections'?'active':'' }}" href="?tab=collections">Collections</a>
            </li>
        </ul>
        @if ($tab == 'collections' || empty($tab))
        <div class="table-responsive p-2">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Collection Number</th>
                        <th>Lot Number</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Collection Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $totalQuantity = 0;
                    @endphp
                    @foreach($farmerCollections as $key => $collection)
                    @php
                    $totalQuantity += $collection->quantity;
                    @endphp
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $collection->collection_number }}</td>
                        <td>{{ $collection->lot_number }}</td>
                        <td>{{ $collection->name }}</td>
                        <td>{{ $collection->quantity }} KG</td>
                        <td>{{ $collection->date_collected }}</td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total:</th>
                        <th>{{ number_format($totalQuantity) }} KG</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
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