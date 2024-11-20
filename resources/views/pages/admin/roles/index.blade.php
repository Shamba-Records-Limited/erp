@extends('layouts.app')

@push('plugin-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush

@section('content')

<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-lg-12">
            <h4 class=" card-title text-center">Roles</h4>
        </div>
    </div>

    <div class="list-group">
        @php
            $perPage = 5; // Number of roles per page
            $currentPage = request()->get('page', 1); // Get current page from query string
            $offset = ($currentPage - 1) * $perPage; // Calculate offset
            $rolesSlice = array_slice($roles, $offset, $perPage); // Slice the roles array
        @endphp

        @foreach($rolesSlice as $key => $role)
        <div class="list-group-item d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="mb-1">Role #{{ $key + 1 + $offset }}: 
                    <a href="{{ route('admin.roles.show_users_tab', $role->id) }}" class="text-decoration-none text-dark">{{ $role->name }}</a>
                </h2>
                <small class="text-muted">Manage users associated with this role.</small>
            </div>
            <div>
                <a href="{{ route('admin.roles.show_users_tab', $role->id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-users"></i> View Users
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Manual Pagination Links -->
    <div class="d-flex justify-content-center mt-4">
        @php
            $totalPages = ceil(count($roles) / $perPage); // Calculate total pages
        @endphp

        @for ($i = 1; $i <= $totalPages; $i++)
            <a href="{{ request()->url() }}?page={{ $i }}" class="btn btn-outline-primary btn-sm {{ $i == $currentPage ? 'active' : '' }}">
                {{ $i }}
            </a>
        @endfor
    </div>
</div>

@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush