@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3">Cooperative Details: {{ $cooperative->name }}</h4>
                <!-- Display cooperative information -->
                <p><strong>Cooperative ID:</strong> {{ $cooperative->id }}</p>

                <!-- Display Cooperative Admin Information -->
                <h4 class="mt-4">Cooperative Admin Information</h4>
                <p><strong>Name:</strong> {{ $admin->first_name }} {{ $admin->other_names }}</p>
                <p><strong>Email:</strong> {{ $admin->email }}</p>
                <p><strong>Username:</strong> {{ $admin->username }}</p>
                
                <h4 class="mt-5">Farmers in Cooperative</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Member Number</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($farmers as $key => $farmer)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    <a href="{{ route('admin.farmers.detail', $farmer->id) }}">{{ $farmer->username }}</a>
                                </td>
                                <td>{{ $farmer->member_no }}</td>
                                <td>{{ $farmer->first_name }} {{ $farmer->other_names }}</td>
                                <td>
                                    <div class="btn-group dropdown">
                                        <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="text-info dropdown-item" href="{{ route('admin.farmers.detail', $farmer->id) }}">
                                                <i class="fa fa-edit"></i> View Details
                                            </a>
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <form style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <a onclick="return confirm('Sure to Delete?')" href="#" class="text-danger dropdown-item" onclick="this.closest('form').submit();">
                                                    <i class="fa fa-trash-alt"></i> Delete
                                                </a>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">No farmers found for this cooperative.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
