@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Applications for {{ $recruitment->role }}</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Qualification</th>
                                <th>Skills</th>
                                <th>Documents</th>
                                <th>Residence</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($applications as $key => $application)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$application->surname }} {{$application->othernames }} </td>
                                    <td>
                                        <b>Phone:</b>{{$application->phone }}</br>
                                        <b>Email:</b>{{$application->email }}
                                    </td>
                                    <td>{{$application->qualification }}</td>
                                    <td>{{$application->top_skills }}</td>
                                    <td>@if($application->resume) <a href="{{$application->resume }}">Resume</a></br> @endif
                                        @if($application->cover_letter) <a href="{{$application->cover_letter }}">CoverLetter</a></br> @endif
                                    </td>
                                    <td>{{ $application->area_of_residence }} </td>
                                    <td>{{ $application->status }} </td>
                                    <td>
                                    <div class="btn-group dropdown">
                                        <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions </button>
                                        <div class="dropdown-menu">
                                            <a class="text-info dropdown-item" href="#">
                                                <i class="fa fa-edit"></i>Edit</a>
                                            <a class="text-success dropdown-item" href="{{ route('hr.recruitment.applications',  $application->id) }}">
                                                <i class="fa fa-eye"></i>View Applications</a>
                                            @METHOD('DELETE')
                                            <a onclick="return confirm('Sure to Delete?')" href="{{ route('hr.recruitment.delete',  $application->id) }}" class="text-danger dropdown-item" >
                                                <i class="fa fa-trash-alt"></i>Delete</a>
                                            <a onclick="return confirm('Sure to Close?')" href="{{ route('hr.recruitment.close',  $application->id) }}" class="text-danger dropdown-item">
                                                <i class="fa fa-trash-alt"></i>Close</a>
                                        </div>
                                    </div>
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