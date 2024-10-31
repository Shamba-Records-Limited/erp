@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
@if(auth()->user()->hasRole('admin'))
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse" data-target="#addUserAccordion" aria-expanded="@if ($errors->count() > 0) true @else false @endif" aria-controls="addUserAccordion"><span class="mdi mdi-plus"></span>Add User
                </button>
                <div class="collapse @if ($errors->count() > 0) show @endif" id="addUserAccordion">
                    <div class="row mt-5">
                        <div class="col-lg-12 grid-margin stretch-card col-12">
                            <h4>Add User</h4>
                        </div>
                    </div>

                    <form action="{{ route('admin.users.add') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-lg-4 col-md-6 col-12">
                                <label for="cooperative_id">Cooperative</label>
                                <select name="cooperative_id" id="cooperative_id" class="form-control form-select {{ $errors->has('cooperative_id') ? ' is-invalid' : '' }}">
                                    <option value="">-- Select Cooperative --</option>
                                    @foreach($cooperatives as $coop)
                                    <option value="{{$coop->id}}">{{$coop->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('cooperative_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('cooperative_id')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-4 col-md-6 col-12">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" id="username" placeholder="Enter username" value="{{ old('username')}}" required>

                                @if ($errors->has('username'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('username')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-4 col-md-6 col-12">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" class="form-control  {{ $errors->has('first_name') ? ' is-invalid' : '' }}" id="first_name" placeholder="John" value="{{ old('first_name')}}">

                                @if ($errors->has('first_name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('first_name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-4 col-md-6 col-12">
                                <label for="other_names">Other Names</label>
                                <input type="text" name="other_names" class="form-control  {{ $errors->has('other_names') ? ' is-invalid' : '' }}" value="{{ old('other_names')}}" id="other_names" placeholder="Doe" required>
                                @if ($errors->has('other_names'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('other_names')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-4 col-md-6 col-12">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control  {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email')}}" id="email" placeholder="username@mail.com" required>
                                @if ($errors->has('email'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('email')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="mainImage">Profile Picture</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('profile_picture') is-invalid @enderror" id="profile_picture" name="profile_picture" value="{{ old('profile_picture') }}">
                                        <label class="custom-file-label" for="profile_picture">Image</label>
                                    </div>
                                </div>
                                @if ($errors->has('profile_picture'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('profile_picture')  }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <button type="submit" class="btn btn-primary btn-fw btn-block">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
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
                    <div class="card shadow-sm">
                        <div class="card-body">
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
