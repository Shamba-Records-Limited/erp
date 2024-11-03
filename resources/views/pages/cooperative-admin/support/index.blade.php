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
                    <a href="{{ route('cooperative-admin.support.view_add_ticket') }}" class="btn btn-primary">Add
                        Ticket</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover dt">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Number</th>
                                <th>Ticket</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $key => $ticket)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $ticket->number }}</td>
                                <td>
                                    <div>{{ Str::words($ticket->title, 5, '...') }}</div> <!-- Title truncated -->
                                    <div>{{ Str::words($ticket->description, 9, '...') }}</div>
                                    <!-- Description truncated -->
                                </td>
                              <td>
                                <div class="btn-group dropdown">
                                    <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="text-info dropdown-item" href="{{ route('cooperative-admin.support.view-ticket', $ticket->number) }}">
                                            <i class="fa fa-eye"></i> View Details
                                        </a>

                                        <form action="{{ route('cooperative-admin.support.delete_ticket', $ticket->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure you want to delete this ticket?')" class="text-danger dropdown-item">
                                                <i class="fa fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
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