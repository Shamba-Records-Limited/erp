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
            @if (count($comments) > 0)
                @foreach ($comments as $comment)
                    <div class="border rounded p-3 mb-3  shadow-lg"> <!-- Added bg-light and shadow for distinction -->
                        <div class="d-flex">
                            <!-- Left Section for User Info -->
                            <div class="flex-shrink-0 me-3 p-5">
                                <div class="font-weight-bold mb-3">{{ $comment->user_name }}</div>
                                <div class="text-muted" style="font-size: 0.85rem; margin-bottom: 1rem;"> <!-- Increased margin-bottom -->
                                    <span class="badge mb-3 text-white" style="background-color:#EF6883;">User Role</span> <!-- Added badge with color -->
                                </div>
                                <div class="text-muted" style="font-size: 0.85rem; margin-bottom: 0.5rem;">
                                    {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
                                </div>
                            </div>
                            
                            <!-- Vertical Divider -->
                            <div class="vr mx-3"></div> <!-- This adds a vertical line -->

                            <!-- Right Section for Comment -->
                            <div class="flex-grow-1">
                                <div class="p-2 text-wrap">
                                    <p class="m-0">{{ $comment->comment }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    No comments yet.
                </div>
            @endif




       
        <hr />
       
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush
<style>
 .vr {
    border-left: 2px solid #ddd; /* Adjust the color and width as needed */
    height: auto; /* Let it adjust automatically */
    margin: 0 15px; /* Spacing around the line */
}
</style>