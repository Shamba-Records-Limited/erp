@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
@if(auth()->user()->hasRole('admin'))
<div class="card shadow-sm">
    <div class="card-body">
        <button type="button" class="btn btn-primary btn-sm mb-4 float-left" data-toggle="collapse" 
                data-target="#addUserAccordion" aria-expanded="@if ($errors->count() > 0) true @else false @endif" 
                aria-controls="addUserAccordion">
            <span class="mdi mdi-plus"></span> Add User
        </button>

        <div class="collapse @if ($errors->count() > 0) show @endif" id="addUserAccordion">
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h4 class="text-primary font-weight-bold mb-4">Add a New User</h4>

                <form action="{{ route('admin.users.add') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- User Details Section -->
                    <div class="section-header bg-light p-2 mb-3 rounded">
                        <h6 class="text-muted mb-0">User Details</h6>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="cooperative_id" class="font-weight-bold">Cooperative</label>
                            <select name="cooperative_id" id="cooperative_id" class="form-control rounded {{ $errors->has('cooperative_id') ? ' is-invalid' : '' }}">
                                <option value="">-- Select Cooperative --</option>
                                @foreach($cooperatives as $coop)
                                    <option value="{{ $coop->id }}" @if(old('cooperative_id') == $coop->id) selected @endif>{{ $coop->name }}</option>
                                @endforeach
                            </select>
                            @error('cooperative_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="username" class="font-weight-bold">Username</label>
                            <input type="text" name="username" class="form-control rounded {{ $errors->has('username') ? ' is-invalid' : '' }}" 
                                   id="username" placeholder="Enter username" value="{{ old('username') }}" required>
                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="first_name" class="font-weight-bold">First Name</label>
                            <input type="text" name="first_name" class="form-control rounded {{ $errors->has('first_name') ? ' is-invalid' : '' }}" 
                                   id="first_name" placeholder="John" value="{{ old('first_name') }}">
                            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="other_names" class="font-weight-bold">Other Names</label>
                            <input type="text" name="other_names" class="form-control rounded {{ $errors->has('other_names') ? ' is-invalid' : '' }}" 
                                   id="other_names" placeholder="Doe" value="{{ old('other_names') }}" required>
                            @error('other_names') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="email" class="font-weight-bold">Email</label>
                            <input type="email" name="email" class="form-control rounded {{ $errors->has('email') ? ' is-invalid' : '' }}" 
                                   id="email" placeholder="username@mail.com" value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="profile_picture" class="font-weight-bold">Profile Picture</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input {{ $errors->has('profile_picture') ? ' is-invalid' : '' }}" 
                                       id="profile_picture" name="profile_picture">
                                <label class="custom-file-label" for="profile_picture">Choose file</label>
                            </div>
                            @error('profile_picture') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="mdi mdi-check"></i> Complete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Search Input with Icon -->
<div class="row mb-3 pl-4">
    <div class="col-2">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-search"></i> <!-- Font Awesome search icon -->
                </span>
            </div>
            <input type="text" id="searchInput" class="form-control" placeholder="Search">
        </div>
    </div>
</div>

<!-- Card Layout for Registered Users with Pagination -->
<div class="row pl-4" id="userCardsContainer"></div>

<!-- Pagination Controls -->
<div class="row">
    <div class="col-12 d-flex justify-content-center">
        <nav>
            <ul class="pagination" id="paginationControls"></ul>
        </nav>
    </div>
</div>
@else
<div>
    You are not authorized to access this page
</div>
@endif
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    // JavaScript for search and pagination
    const users = @json($users); // Load users data from Blade
    const itemsPerPage = 8;
    let currentPage = 1;

    function renderCards(usersToDisplay) {
        const container = document.getElementById("userCardsContainer");
        container.innerHTML = "";
        usersToDisplay.forEach((user, index) => {
            const cardHtml = `
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card shadow-sm" style="height: 320px;"> <!-- Fixed height for uniform size -->
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3"></div>
                                    <div>
                                        <h5 class="card-title mb-1">${user.username}</h5>
                                        <p class="card-text"><small class="text-muted">${user.coop_name}</small></p>
                                    </div>
                                </div>
                                <hr>
                                <p class="mb-1"><strong>Name:</strong> ${user.first_name} ${user.other_names}</p>
                                <p class="mb-1"><strong>Email:</strong> ${user.email}</p>
                            </div>
                            <div>
                                <div class="dropdown mt-3">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item text-info" href="/admin/users/detail/${user.id}"><i class="fa fa-eye"></i> View Details</a>
                                        <a class="dropdown-item text-warning" href="/admin/users/edit/${user.id}"><i class="fa fa-edit"></i> Edit</a>
                                        <a class="dropdown-item text-danger" href="#" onclick="deleteUser(${user.id})"><i class="fa fa-trash"></i> Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.innerHTML += cardHtml;
        });
    }

    function renderPaginationControls(totalItems) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const paginationControls = document.getElementById("paginationControls");
        paginationControls.innerHTML = "";

        for (let i = 1; i <= totalPages; i++) {
            paginationControls.innerHTML += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a href="#" class="page-link" onclick="changePage(${i})">${i}</a>
                </li>
            `;
        }
    }

    function changePage(page) {
        currentPage = page;
        const filteredUsers = filterUsers(document.getElementById("searchInput").value);
        displayPage(filteredUsers);
    }

    function displayPage(usersArray) {
        const start = (currentPage - 1) * itemsPerPage;
        const paginatedUsers = usersArray.slice(start, start + itemsPerPage);
        renderCards(paginatedUsers);
        renderPaginationControls(usersArray.length);
    }

    function filterUsers(query) {
        return users.filter(user =>
            user.username.toLowerCase().includes(query.toLowerCase()) ||
            user.first_name.toLowerCase().includes(query.toLowerCase()) ||
            user.other_names.toLowerCase().includes(query.toLowerCase()) ||
            user.coop_name.toLowerCase().includes(query.toLowerCase()) ||
            user.email.toLowerCase().includes(query.toLowerCase())
        );
    }

    document.getElementById("searchInput").addEventListener("input", function() {
        const query = this.value;
        const filteredUsers = filterUsers(query);
        currentPage = 1; // Reset to first page on new search
        displayPage(filteredUsers);
    });

    // Initial load
    displayPage(users);

    function deleteUser(id) {
        if (confirm("Are you sure you want to delete this user?")) {
            window.location.href = "/admin/users/delete/" + id;
        }
    }
</script>
@endpush
