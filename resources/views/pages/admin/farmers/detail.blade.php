@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Farmer Details</div>
        <div class="row">
            <div class="col-3 shadow p-2 border m-2 rounded">Member Number: <span class="font-weight-bold">{{$farmer->member_no}}</span></div>
            <div class="col-3 shadow p-2 border m-2 rounded">Name: <span class="font-weight-bold">{{$farmer->first_name}} {{$farmer->other_names}}<span></div>
            <div class="col-3 shadow p-2 border m-2 rounded">Username: <span class="font-weight-bold">{{$farmer->username}}<span></div>
            <div class="col-3 shadow p-2 border m-2 rounded">Email: <span class="font-weight-bold">{{$farmer->email}}</span></div>
            <div class="col-3 shadow p-2 border m-2 rounded">Phone: <span class="font-weight-bold">{{$farmer->phone_no}}</span></div>
            <div class="col-3 shadow p-2 border m-2 rounded">Country: <span class="font-weight-bold">{{$farmer->country_code}}</span></div>
            <div class="col-3 shadow p-2 border m-2 rounded">County: <span class="font-weight-bold">{{$farmer->county_name}}</span></div>
            <div class="col-3 shadow p-2 border m-2 rounded">sub County: <span class="font-weight-bold">{{$farmer->sub_county_name}}</span></div>
            <div class="col-3 shadow p-2 border m-2 rounded">Id Number: <span class="font-weight-bold">{{$farmer->id_no}}</span></div>
            <div class="col-3 shadow p-2 border m-2 rounded">Gender: <span class="font-weight-bold">{{$farmer->gender}}</span></div>
        </div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'cooperatives'?'active':'' }}" href="?tab=cooperatives">Cooperatives</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'collections'?'active':'' }}" href="?tab=collections">Collections</a>
            </li>
        </ul>
        @if ($tab == 'cooperatives' || empty($tab))
        <div class="table-responsive p-2">
            <table class="table table-hover dt clickable">
                <thead>
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
                        <td>{{$key++}}</td>
                        <td>{{$coop->coop_name}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif ($tab == 'collections')
        <div class="table-responsive p-2">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cooperative</th>
                        <th>Collection Number</th>
                        <th>Lot Number</th>
                        <th>Name</th>
                        <th>Collection Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($farmerCollections as $key => $collection)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$collection->coop_name}}</td>
                        <td>{{$collection->collection_number}}</td>
                        <td>{{$collection->lot_number}}</td>
                        <td>{{$collection->name}}</td>
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