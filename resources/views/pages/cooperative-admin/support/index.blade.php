@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Support</div>
                <div class="d-flex justify-content-end mb-2">
                    <a href="{{route('cooperative-admin.support.view_add_ticket')}}" class="btn btn-primary">Add Ticket</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover dt">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Number</th>
                                <th>Ticket</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $key => $ticket)
                            <tr>
                                <td>{{++$key }}</td>
                                <td>{{$ticket->number}}</td>
                                <td>
                                    <div>{{$ticket->title}}</div>
                                    <div>{{$ticket->description}}</div>
                                </td>
                                <td>
                                    <a href="{{route('cooperative-admin.support.view-ticket', $ticket->number)}}" class="btn btn-sm btn-info"><i class="mdi mdi-eye"></i></a>
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