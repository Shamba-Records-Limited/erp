@extends('layouts.app')

@push('plugin-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
@endpush

@section('content')
<!-- <div class="container mt-4"> -->
<div class="card shadow-sm border-light">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="card-title font-weight-bold">View Ticket: <span class="text-primary">{{ $ticket->number }}</span>
            </h4>
            <span
                class="badge @if($ticket->status == 'open') badge-warning @elseif($ticket->status == 'solved') badge-success @elseif($ticket->status == 'closed') badge-secondary @endif">
                {{ ucfirst($ticket->status) }}
            </span>
        </div>
        <div class="mb-3">
            @foreach (json_decode($ticket->labels) as $label)
            <span class="badge badge-light border border-secondary  mr-2">{{ $label }}</span>
            @endforeach
        </div>
        <div class="card-subtitle mb-2 text-muted font-italic">{{ $ticket->title }}</div>
        <div class="card-text">
            <p>{{ $ticket->description }}</p>
        </div>
        @if ($ticket->status == "solved")
        <div class="mt-3">
            <a href="{{ route('cooperative-admin.support.confirm-ticket-resolved', $ticket->number) }}"
                class="btn btn-success">Confirm Resolved</a>
        </div>
        @endif
        <div class="card-title mt-4 font-weight-bold">
            Comments
        </div>
        <hr />
        @if (count($comments) > 0)
        @foreach ($comments as $comment)
        <div class="border rounded p-3 mb-3 shadow-sm">
            <div class="d-flex justify-content-between align-items-start border-bottom pb-2 text-muted">
                <div class="font-weight-bold">{{ $comment->user_name }}</div>
                <div>{{ \Carbon\Carbon::parse($comment->created_at)->format('d M Y, H:i') }}</div>
            </div>
            <div class="p-2 mt-2">{{ $comment->comment }}</div>
        </div>
        @endforeach
        @else
        <div class="alert alert-info">
            <i class="fas fa-comments"></i> No comments yet.
        </div>
        @endif
        <hr />
        <form action="{{ route('cooperative-admin.support.add-ticket-comment') }}" method="POST">
            @csrf
            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}" />
            <div class="form-group">
                <label for="comment">Add a Comment</label>
                <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" rows="3"
                    placeholder="Write your comment here..."></textarea>

                @if ($errors->has('comment'))
                <span class="text-danger">{{ $errors->first('comment') }}</span>
                @endif
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Submit</button>
            </div>
        </form>
    </div>
</div>
<!-- </div> -->
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush