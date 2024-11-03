@extends('layouts.app')

@push('plugin-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Main Ticket Card -->
            <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                <!-- Ticket Header -->
                <div class="card-header  text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title">
                            <h4 class="mb-1">#{{ $ticket->number }}</h4>
                            <h5 class="mb-0 opacity-8">{{ $ticket->title }}</h5>
                        </div>
                        <div class="ticket-status">
                            <span class="px-4 py-2 rounded-pill @if($ticket->status == 'open') bg-warning @elseif($ticket->status == 'solved') bg-success @else bg-secondary @endif text-white">
                                <i class="fas @if($ticket->status == 'open') fa-exclamation-circle @elseif($ticket->status == 'solved') fa-check-circle @else fa-times-circle @endif"></i>
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Labels -->
                    <div class="mb-4">
                            @php
                                $labels = json_decode($ticket->labels);
                            @endphp

                            @if (is_array($labels) && !empty($labels))
                                @foreach ($labels as $label)
                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill mr-2">
                                        <i class="fas fa-tag mr-1"></i> {{ $label }}
                                    </span>
                                @endforeach
                            @else
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill mr-2">
                                    <i class="fas fa-tag mr-1"></i> No Labels
                                </span>
                            @endif
                        </div>

                    <!-- Ticket Details -->
                    <div class="ticket-details  p-4 rounded mb-4">
                        <p class=" mb-4">{{ $ticket->description }}</p>
                        
                        <div class="row">
                            @if($ticket->module)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-cube text-primary mr-2"></i>
                                    <span class="font-weight-bold mr-2">Module:</span>
                                    {{ $ticket->module }}
                                </div>
                            </div>
                            @endif

                            @if($ticket->submodule)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-cubes text-primary mr-2"></i>
                                    <span class="font-weight-bold mr-2">Submodule:</span>
                                    {{ $ticket->submodule }}
                                </div>
                            </div>
                            @endif

                            @if($ticket->link)
                            <div class="col-12 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-link text-primary mr-2"></i>
                                    <span class="font-weight-bold mr-2">Link:</span>
                                    <a href="{{ $ticket->link }}" class="text-primary">{{ $ticket->link }}</a>
                                </div>
                            </div>
                            @endif
                        </div>

                        @if($ticket->image)
                        <div class="mt-4">
                            <h6 class="font-weight-bold mb-3"><i class="fas fa-image text-primary mr-2"></i>Attached Image</h6>
                            <img src="{{ asset('storage/' . $ticket->image) }}" alt="Ticket Image" class="img-fluid rounded-lg shadow-sm" style="max-width: 100%; max-height: 400px; object-fit: contain;">
                        </div>
                        @endif
                    </div>

                    @if ($ticket->status == "solved")
                    <div class="text-center mb-4">
                        <a href="{{ route('cooperative-admin.support.confirm-ticket-resolved', $ticket->number) }}" 
                           class="btn btn-success btn-lg px-5 rounded-pill shadow-sm">
                            <i class="fas fa-check-circle mr-2"></i>Confirm Resolution
                        </a>
                    </div>
                    @endif

                    <!-- Comments Section -->
                    <div class="comments-section mt-5">
                        <h5 class="d-flex align-items-center mb-4">
                            <i class="fas fa-comments text-primary mr-2"></i>
                            <span>Discussion</span>
                            <span class="badge badge-primary ml-2">{{ count($comments) }}</span>
                        </h5>

                        <div class="comments-container custom-scrollbar mb-4">
                            @if (count($comments) > 0)
                                @foreach ($comments as $comment)
                                <div class="comment-card @if($loop->first) first-comment @endif mb-4">
                                    <div class="comment-header d-flex align-items-center mb-3">
                                        <div class="comment-avatar">
                                            <div class="avatar-circle">
                                                {{ strtoupper(substr($comment->user_name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="comment-meta ml-3">
                                            <h6 class="mb-0 font-weight-bold">{{ $comment->user_name }}</h6>
                                            <small class="text-muted">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ \Carbon\Carbon::parse($comment->created_at)->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="comment-body">
                                        {{ $comment->comment }}
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-comments text-muted fa-3x mb-3"></i>
                                    <p class="text-muted">No comments yet. Be the first to comment!</p>
                                </div>
                            @endif
                        </div>

                        <!-- Comment Form -->
                        <div class="comment-form bg-light p-4 rounded-lg">
                            <form action="{{ route('cooperative-admin.support.add-ticket-comment') }}" method="POST">
                                @csrf
                                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}" />
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold mb-2">
                                        <i class="fas fa-reply text-primary mr-2"></i>Add Your Response
                                    </label>
                                    <textarea name="comment" 
                                              class="form-control @error('comment') is-invalid @enderror" 
                                              rows="4" 
                                              placeholder="Type your message here..."
                                              style="border-radius: 15px;"></textarea>
                                    @if ($errors->has('comment'))
                                    <div class="invalid-feedback">{{ $errors->first('comment') }}</div>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary px-4 rounded-pill">
                                        <i class="fas fa-paper-plane mr-2"></i>Send Response
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #4e73df 0%, #224abe 100%);
}

.ticket-status .rounded-pill {
    font-size: 0.9rem;
    font-weight: 500;
}

.custom-scrollbar {
    max-height: 600px;
    overflow-y: auto;
    padding-right: 10px;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.comment-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    transition: transform 0.2s;
}

.comment-card:hover {
    transform: translateY(-2px);
}

.first-comment {
    border-left: 4px solid #4e73df;
}

.avatar-circle {
    width: 40px;
    height: 40px;
    background: #4e73df;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.comment-body {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    margin-left: 53px;
}

.ticket-details {
    border-left: 4px solid #4e73df;
    background-color: #ced4da54;
}

.opacity-8 {
    opacity: 0.8;
}

.badge {
    font-weight: 500;
}

textarea.form-control:focus {
    box-shadow: none;
    border-color: #4e73df;
}

.btn-primary {
    background: #4e73df;
    border: none;
    transition: all 0.3s;
}

.btn-primary:hover {
    background: #224abe;
    transform: translateY(-1px);
}
</style>
@endpush