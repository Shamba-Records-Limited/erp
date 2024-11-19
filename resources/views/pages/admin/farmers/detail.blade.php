@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h4 class="card-title text-primary font-weight-bold">Farmer Details</h4>
        <p class="text-muted mb-4">Basic information and details of the farmer.</p>

        <div class="row">
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="shadow-sm p-3 border rounded bg-light">
                    <strong>Member Number:</strong> 
                    <span class="font-weight-bold text-dark">{{ $farmer->member_no }}</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="shadow-sm p-3 border rounded bg-light">
                    <strong>Name:</strong> 
                    <span class="font-weight-bold text-dark">{{ $farmer->first_name }} {{ $farmer->other_names }}</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="shadow-sm p-3 border rounded bg-light">
                    <strong>Username:</strong> 
                    <span class="font-weight-bold text-dark">{{ $farmer->username }}</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="shadow-sm p-3 border rounded bg-light">
                    <strong>Email:</strong> 
                    <span class="font-weight-bold text-dark">{{ $farmer->email }}</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="shadow-sm p-3 border rounded bg-light">
                    <strong>Phone:</strong> 
                    <span class="font-weight-bold text-dark">{{ $farmer->phone_no }}</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="shadow-sm p-3 border rounded bg-light">
                    <strong>Country:</strong> 
                    <span class="font-weight-bold text-dark">{{ $farmer->country_code }}</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="shadow-sm p-3 border rounded bg-light">
                    <strong>County:</strong> 
                    <span class="font-weight-bold text-dark">{{ $farmer->county_name }}</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="shadow-sm p-3 border rounded bg-light">
                    <strong>Sub County:</strong> 
                    <span class="font-weight-bold text-dark">{{ $farmer->sub_county_name }}</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="shadow-sm p-3 border rounded bg-light">
                    <strong>ID Number:</strong> 
                    <span class="font-weight-bold text-dark">{{ $farmer->id_no }}</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="shadow-sm p-3 border rounded bg-light">
                    <strong>Gender:</strong> 
                    <span class="font-weight-bold text-dark">{{ $farmer->gender }}</span>
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
                        <td>{{ $coop->created_at ?? 'N/A' }}</td>
                        <td>{{ $coop->added_by ?? 'N/A' }}</td>
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
