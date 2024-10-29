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
                <div class="collapse @if ($errors->count() > 0) show @endif " id="addUserAccordion">
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

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Registered Users</h4>
                <div class="table-responsive">
                    <table class="table table-hover dt">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cooperative</th>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $key => $user)
                            <tr>
                                <td>{{++$key }}</td>
                                <td>{{$user->coop_name}}</td>
                                <td>{{$user->username}}</td>
                                <td>{{$user->first_name }} {{$user->other_names}} </td>
                                <td>{{$user->email }}</td>
                                <td>
                                    <div class="btn-group dropdown">
                                        <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="text-info dropdown-item" href="{{ route('admin.users.detail', $user->id) }}">
                                                <i class="fa fa-edit"></i>View Details
                                            </a>
                                            <a class="text-warning dropdown-item" href="{{ route('admin.users.edit', $user->id) }}">
                                                <i class="fa fa-edit"></i>Edit
                                            </a>
                                            <a onclick="return confirm('Sure to Delete?')" href="/admin/users/delete/{{ $user->id }}" class="text-danger dropdown-item">
                                                <i class="fa fa-trash-alt"></i>Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
    function deleteBranch(id) {
        shouldDelete = confirm("Are you sure you want to delete this cooperative branch?")
        if (!shouldDelete) {
            return
        }


        window.location = "/branches/delete/" + id
    }
</script>
@endpush