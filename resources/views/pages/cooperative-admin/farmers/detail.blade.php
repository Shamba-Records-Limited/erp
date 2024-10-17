@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Farmer Details</div>
        <div class="row">
            <div class="col-3 border m-2 rounded">Username: {{$farmer->username}}</div>
            <div class="col-3 border m-2 rounded">Name: {{$farmer->first_name}} {{$farmer->other_names}}</div>
            <div class="col-3 border m-2 rounded">Email: {{$farmer->email}}</div>
            <div class="col-3 border m-2 rounded">Phone: {{$farmer->phone_no}}</div>
            <div class="col-3 border m-2 rounded">Country: {{$farmer->country_code}}</div>
            <div class="col-3 border m-2 rounded">County: {{$farmer->county_name}}</div>
            <div class="col-3 border m-2 rounded">sub County: {{$farmer->sub_county_name}}</div>
            <div class="col-3 border m-2 rounded">Id Number: {{$farmer->id_no}}</div>
            <div class="col-3 border m-2 rounded">Member Number: {{$farmer->member_no}}</div>
            <div class="col-3 border m-2 rounded">Gender: {{$farmer->gender}}</div>
        </div>
        <div class="row">
            <div class="col-md-4 col-12 border rounded m-2">
                <div>Total Collection Quantity</div>
                <div class="font-weight-bold">{{$farmer->total_collection_quantity}} KG</div>
            </div>
        </div>
        <ul class="nav nav-tabs">
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