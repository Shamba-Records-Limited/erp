@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Farmer Details</div>

        <div class="row custom-border mt-4">
            <div class="col-md-4 d-flex flex-column align-items-center">

                <!-- fetch the images from user db -->
                <div class="mb-3">
                    <img src="{{ asset('assets/images/avatar.png') }}" alt="Farmer's Profile Picture"
                        class="img-fluid rounded-circle" width="150" height="150">
                </div>
                <div class="border rounded p-3 text-center w-100 mt-3">
                    <div>Total Collection Quantity</div>
                    <div class="font-weight-bold">{{$farmer->total_collection_quantity}} KG</div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="m-4">
                            <span class="semi-bold">Email: </span>{{$farmer->email}}
                        </div>
                        <div class="m-4">
                            <span class="semi-bold">Phone: </span>{{$farmer->phone_no}}
                        </div>
                        <div class="m-4">
                            <span class="semi-bold">Country: </span>{{$farmer->country_code}}
                        </div>
                        <div class="m-4">
                            <span class="semi-bold">County: </span>{{$farmer->county_name}}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="m-4">
                            <span class="semi-bold">Sub-County: </span>{{$farmer->sub_county_name}}
                        </div>
                        <div class="m-4">
                            <span class="semi-bold">Id Number: </span>{{$farmer->id_no}}
                        </div>
                        <div class="m-4">
                            <span class="semi-bold">Member Number: </span>{{$farmer->member_no}}
                        </div>
                        <div class="m-4">
                            <span class="semi-bold">Gender: </span>{{$farmer->gender}}
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
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Collection Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($farmerCollections as $key => $collection)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$collection->collection_number}}</td>
                        <td>{{$collection->lot_number}}</td>
                        <td>{{$collection->name}}</td>
                        <td>{{$collection->quantity}} KG</td>
                        <td>{{$collection->date_collected}}</td>
                        <td></td>
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