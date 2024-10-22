@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-start">
            <h4 class="card-title">View Ticket: {{$ticket->number}}</h4>
            @if ($ticket->status == "open")
            <span class="badge badge-warning">Open</span>
            @elseif ($ticket->status == "solved")
            <span class="badge badge-success">Solved</span>
            @elseif ($ticket->status == "closed")
            <span class="badge badge-secondary">Closed</span>
            @endif
        </div>
        <div>
            @foreach (json_decode($ticket->labels) as $label)
            <span class="border rounded p-1 mr-2">{{$label}}</span>
            @endforeach
        </div>
        <div class="card-subtitle">{{$ticket->title}}</div>
        <div class="card-text">
            <p>{{$ticket->description}}</p>
        </div>
        @if ($ticket->status == "open")
        <div>
            <a href="{{route('admin.support.resolve-ticket', $ticket->number )}}" class="btn btn-primary">Resolve</a>
        </div>
        @endif
        <div class="card-title mt-4">
            Comments
        </div>
        <hr />
        @if (count($comments) > 0)
        @foreach ($comments as $comment)
        <div class="border rounded p-2 mb-2">
            <div class="d-flex justify-content-between align-items-start border-bottom p-2 text-muted">
                <div>{{$comment->user_name}}</div>
                <div>{{$comment->created_at}}</div>
            </div>
            <div class="p-2">{{$comment->comment}}</div>
        </div>
        @endforeach
        @else
        <div class="alert alert-info">
            No comments yet.
        </div>
        @endif
        <hr />
        <form action="{{route('admin.support.add-ticket-comment')}}" method="POST">
            @csrf
            <input type="hidden" name="ticket_id" value="{{$ticket->id}}" />
            <div class="form-group">
                <label for="comment">Comment</label>
                <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" rows="3"></textarea>

                @if ($errors->has('comment'))
                <span class="text-danger">{{$errors->first('comment')}}</span>
                @endif
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush