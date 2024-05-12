@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <h2 class="card-title">Role: {{$role->name}}</h2>
        <ul class="nav">
            <!-- <li class="nav-item">
                <a href="{{ route('admin.roles.show_permissions_tab', $id) }}" class="nav-link active">Permissions</a>
            </li> -->
            <li class="nav-item">
                <a href="{{ route('admin.roles.show_users_tab', $id) }}" class="nav-link">Users</a>
            </li>
        </ul>
        <div class="row mx-4 my-2">
            <div>Permissions</div>
        </div>
    </div>
</div>

@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush