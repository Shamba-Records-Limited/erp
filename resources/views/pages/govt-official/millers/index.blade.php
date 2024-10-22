@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Miller</h4>
                <div class="table-responsive">
                    <table class="table table-hover dt">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Miller Name</th>
                                <th>Email</th>
                                <th>Contact person</th>
                                <th>Contact No</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($millers as $key => $miller)
                            <tr>
                                <td>{{++$key }}</td>
                                <td>{{$miller->name }}</td>
                                <td>{{$miller->email }}</td>
                                <td>{{$miller->first_name}} {{$miller->other_names}}</td>
                                <td>{{$miller->phone_no }}</td>
                                <td>
                                    <a href="{{ route('govt-official.cooperatives.details', $miller->id) }}" class="btn btn-primary">View</a>
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