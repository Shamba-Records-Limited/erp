@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Registered Cooperatives</h4>
                <div class="table-responsive">
                    <table class="table table-hover dt">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cooperative Name</th>
                                <th>Email</th>
                                <th>Contact person</th>
                                <th>Contact No</th>
                                <th>Location</th>
                                <th>Address</th>
                                <th>No. of farmers</th>
                                <th>Season Qty</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cooperatives as $key => $cop)
                            <tr data-href="{{ route('govt-official.cooperatives.details', $cop->id) }}">
                                <td>{{++$key }}</td>
                                <td>{{$cop->name }} ({{$cop->abbreviation}})</td>
                                <td>{{$cop->email }}</td>
                                <td>{{$cop->first_name}} {{$cop->other_names}}</td>
                                <td>{{$cop->contact_details }}</td>
                                <td>{{$cop->location }}</td>
                                <td>{{$cop->address }}</td>
                                <td>{{$cop->num_of_farmers}}</td>
                                <td></td>
                                <td>
                                    <a href="{{ route('govt-official.cooperatives.details', $cop->id) }}" class="btn btn-primary">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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