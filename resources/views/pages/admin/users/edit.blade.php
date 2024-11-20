@extends('layouts.app')

@push('plugin-styles')
    <!-- Include additional CSS for better styling if needed -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
@endpush

@section('content')

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title text-primary">Update User</h4>
                <p class="card-description text-muted">Edit the user's details below. You can also update the profile picture.</p>

                <form action="{{ route('admin.users.update', ['id' => $user->id]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <!-- User Details Section -->
                    <div class="form-row">
                        <div class="form-group col-lg-4 col-md-6 col-12">
                            <label for="cooperative" class="font-weight-bold">Cooperative</label>
                            <input type="text" name="cooperative" class="form-control {{ $errors->has('cooperative') ? 'is-invalid' : '' }}" id="cooperative" value="{{ $user->coop_name }}" readonly>

                            @if ($errors->has('cooperative'))
                                <div class="invalid-feedback">{{ $errors->first('cooperative') }}</div>
                            @endif
                        </div>

                        <div class="form-group col-lg-4 col-md-6 col-12">
                            <label for="username" class="font-weight-bold">Username</label>
                            <input type="text" name="username" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" id="username" value="{{ $user->username }}" required>

                            @if ($errors->has('username'))
                                <div class="invalid-feedback">{{ $errors->first('username') }}</div>
                            @endif
                        </div>

                        <div class="form-group col-lg-4 col-md-6 col-12">
                            <label for="first_name" class="font-weight-bold">First Name</label>
                            <input type="text" name="first_name" class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}" id="first_name" value="{{ $user->first_name }}">

                            @if ($errors->has('first_name'))
                                <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-lg-4 col-md-6 col-12">
                            <label for="other_names" class="font-weight-bold">Other Names</label>
                            <input type="text" name="other_names" class="form-control {{ $errors->has('other_names') ? 'is-invalid' : '' }}" id="other_names" value="{{ $user->other_names }}" required>

                            @if ($errors->has('other_names'))
                                <div class="invalid-feedback">{{ $errors->first('other_names') }}</div>
                            @endif
                        </div>

                        <div class="form-group col-lg-4 col-md-6 col-12">
                            <label for="email" class="font-weight-bold">Email</label>
                            <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" value="{{ $user->email }}" required>

                            @if ($errors->has('email'))
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>

                        <div class="form-group col-lg-4 col-md-6 col-12">
                            <label for="profile_picture" class="font-weight-bold">Profile Picture</label>
                            <div class="text-center mb-2">
                                <img 
                                    src="{{ $user->profile_picture ? '/storage/' . $user->profile_picture : '/images/default-avatar.png' }}" 
                                    alt="Profile Picture" 
                                    class="rounded-circle" 
                                    style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #ddd;">
                            </div>
                            <input type="file" name="profile_picture" class="form-control {{ $errors->has('profile_picture') ? 'is-invalid' : '' }}" id="profile_picture">

                            @if ($errors->has('profile_picture'))
                                <div class="invalid-feedback">{{ $errors->first('profile_picture') }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-row mt-4">
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <a href="{{ route('admin.users.show') }}" class="btn btn-danger btn-block">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('plugin-scripts')
    <!-- Include additional JS if needed -->
@endpush

@push('custom-scripts')
@endpush
<style>
    .card img.rounded-circle {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border: 2px solid #ddd;
        margin-bottom: 10px;
    }

    .btn i {
        margin-right: 5px;
    }

</style>