@extends('layout.master')

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
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact Details</th>
                                <th>Country</th>
                                <th>Location</th>
                                <th>Address</th>
                                <th>Currency</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cooperatives as $key => $cop)
                            <tr data-href="{{ route('govt-official.cooperatives.details', $cop->id) }}">
                                <td>{{++$key }}</td>
                                <td>{{$cop->name }} ({{$cop->abbreviation}})</td>
                                <td>{{$cop->email }}</td>
                                <td>{{$cop->contact_details }}</td>
                                <td> {{$cop->country->name }}
                                    <span class="mr-2 table-flag">
                                        <img src="{{ asset(get_country_flag($cop->country->iso_code)) }}" />
                                    </span>
                                </td>
                                <td>{{$cop->location }}</td>
                                <td>{{$cop->address }}</td>
                                <td>{{$cop->currency }} {{ $cop->deactivated_at}}</td>
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
<script>
    function deleteCoop(id) {
        shouldDelete =  confirm("Are you sure you want to delete this cooperative?")
        if (!shouldDelete){
            return
        }

        window.location = "/admin/cooperative/setup/delete/"+id
    }
</script>
@endpush