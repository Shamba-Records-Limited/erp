@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="coop-info container-fluid mt-5">
                                            <p class="coop-logo">{{ $cooperative->logo }}</p>

                    <div class="coop-header text-center">
                        <h4 class="coop-title">Cooperative Details</h4>
                        <p class="coop-name">{{ $cooperative->name }}</p>
                    </div>
                    
                    <div class="coop-details card shadow mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><i class="fas fa-id-badge"></i> Cooperative ID</h5>
                            <p class="card-text"><strong class="key-label">Country:</strong>{{ $cooperative->country_code }}</p>
                            <p class="card-text"><strong class="key-label">Contact:</strong>{{ $cooperative->contact_details }}</p>
                            <p class="card-text"><strong class="key-label">Location:</strong>{{ $cooperative->location }}</p>
                            <!-- <p class="card-text"><strong class="key-label">Main Product:</strong>{{ $cooperative->main_product_id }}</p> -->
                        </div>
                    </div>

                    <div class="admin-header text-center">
                        <h4 class="admin-title">Cooperative Admin Information</h4>
                    </div>
                    
                    <div class="admin-info card shadow">
                        <div class="card-body">
                            <h5 class="card-title text-success"><i class="fas fa-user"></i> Admin Details</h5>
                            <p class="card-text"><strong class="key-label">Name:</strong> {{ $admin->first_name }} {{ $admin->other_names }}</p>
                            <p class="card-text"><strong class="key-label">Email:</strong> {{ $admin->email }}</p>
                            <p class="card-text"><strong class="key-label">Username:</strong> {{ $admin->username }}</p>
                        </div>
                    </div>
                </div>

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
<style>
.coop-info {
    max-width: 600px;
    background: linear-gradient(135deg, #e2e2e2 30%, #ffffff 90%);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    margin: auto;
}

.coop-title {
    font-size: 1rem;
    color: #3e50b4;
    margin-bottom: 5px;
}

.coop-name {
    font-size: 1rem;
    color: #1976d2;
    font-weight: bold;
}

.card {
    border: none;
    border-radius: 10px;
    transition: transform 0.3s, box-shadow 0.3s;
    background-color: #ffffff;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 50px rgba(0, 0, 0, 0.2);
}

.card-body {
    padding: 10px;
}

.admin-header, .coop-header {
    margin-bottom: 10px;
}

.admin-title, .coop-title {
    font-weight: bold;
    font-size: 1rem;
    color: #3e50b4;
}

.card-title {
    font-size: 1rem;
}

.card-text {
    font-size: 1rem;
    color: #555;
}

.key-label {
    font-family: 'Arial', sans-serif;
    font-weight: bold;
}

.card-title i {
    margin-right: 8px;
}



</style>