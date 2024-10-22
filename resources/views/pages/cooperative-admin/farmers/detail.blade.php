@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Farmer Details</div>

        <div class="row custom-border mt-4">
            <div class="col-md-4 d-flex flex-column align-items-center">

                <div class="mb-3">
                    @if($farmer->profile_picture)
                    <img src="{{url('storage/'.$farmer->profile_picture)}}" height="150px" width="150px"
                        class="d-block" />
                    @else
                    <img src="{{ url('assets/images/avatar.png') }}" height="150px" width="150px" alt="profile image"
                        class="d-block">
                    @endif
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
                            <span class="semi-bold mr-2">Email: </span>{{$farmer->email}}
                        </div>
                        <div class="m-4">
                            <span class="semi-bold mr-2">Phone: </span>{{$farmer->phone_no}}
                        </div>
                        <div class="m-4">
                            <span class="semi-bold mr-2">Country: </span>{{$farmer->country_code}}
                        </div>
                        <div class="m-4">
                            <span class="semi-bold mr-2">County: </span>{{$farmer->county_name}}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="m-4">
                            <span class="semi-bold mr-2">Sub-County: </span>{{$farmer->sub_county_name}}
                        </div>
                        <div class="m-4">
                            <span class="semi-bold mr-2">Id Number: </span>{{$farmer->id_no}}
                        </div>
                        <div class="m-4">
                            <span class="semi-bold mr-2">Member Number: </span>{{$farmer->member_no}}
                        </div>
                        <div class="m-4">
                            <span class="semi-bold mr-2">Gender: </span>{{$farmer->gender}}
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