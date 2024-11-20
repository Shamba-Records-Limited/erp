@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <h2 class="card-title">Role: {{$role->name}}</h2>
        <ul class="nav">
            <li class="nav-item">
                <a href="{{ route('admin.roles.show_permissions_tab', $id) }}" class="nav-link active">Permissions</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.roles.show_users_tab', $id) }}" class="nav-link">Users</a>
            </li>
        </ul>
        <div class="row mx-4 my-2">
            <div class="col-12">
                <h4>Permissions Assigned:</h4>
                @if ($permissions && count($permissions) > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Permission Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $permission->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No permissions are assigned to this role.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush
