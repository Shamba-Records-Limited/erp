@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h4 class="card-title mb-4">Roles</h4>
    </div>
</div>

<div class="row">
    @foreach($roles as $key => $role)
    <div class="col-md-6 col-lg-4 mb-4"> <!-- Adjust columns as needed -->
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <h5 class="card-title">Role #{{ $key + 1 }}</h5>
                    <p class="card-text">
                        <a href="{{ route('admin.roles.show_users_tab', $role->id) }}">{{ $role->name }}</a>
                    </p>
                </div>
                <div class="text-right">
                    <!-- Optional Action Buttons if needed -->
                    <a href="{{ route('admin.roles.show_users_tab', $role->id) }}" class="btn btn-primary btn-sm">View Users</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush
