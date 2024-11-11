@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Hello') . ' '. auth()->user()->name,
        'description' => __('This is your profile page. You can edit your personal information and change your password for security purposes'),
        'class' => 'col-lg-7'
    ])   

    <div class="container-fluid mt--7">
        <div class="row" style="margin-top:180px">
            <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                <div class="card card-profile shadow">
                   
                <!-- Logged-In User Details Card -->
                <div class="row justify-content-center">
                    <div class="col-lg-1 order-lg-2">
                        <div class="card-profile-image">
                            <div class="rounded-full">
                                @if(Auth::user()->profile_picture)
                                    <!-- Display user's profile picture, fully rounded -->
                                    <img src="{{ url('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture"
                                        class="img-fluid rounded-circle bg-light">
                                @else
                                    <!-- Fallback to default avatar -->
                                    <img src="{{ url('assets/images/avatar.png') }}" alt="Default Avatar"
                                        class="img-fluid rounded-circle bg-light">
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- User Info Card -->
                    <div class="col-lg-11 order-lg-1">
                        <div class="info-card">
                            <div class="card-glass">
                                <div class="card-header justify-content-center">
                                    <div class="header-icon admin">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <h3 class="d-flex text-center">User Profile</h3>
                                </div>
                                <div class="card-content">
                                    <div class="admin-profile text-center">
                                        <h4>{{ Auth::user()->first_name }} {{ Auth::user()->other_names }}</h4>
                                        <span class="admin-role">{{ Auth::user()->role ?? 'User' }}</span>
                                    </div>
                                    <div class="detail-group mt-4">
                                        <div class="detail-item">
                                            <div class="detail-icon">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <div class="detail-info">
                                                <label>Email</label>
                                                <span>{{ Auth::user()->email }}</span>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-icon">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="detail-info">
                                                <label>Username</label>
                                                <span>{{ Auth::user()->username }}</span>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-icon">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                            <div class="detail-info">
                                                <label>Phone</label>
                                                <span>{{ Auth::user()->phone ?? 'N/A' }}</span> <!-- Display phone if available -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                    
                  
                </div>
            </div>
            <div class="col-xl-7 order-xl-2">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="col-12 mb-0">{{ __('Edit Profile') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('profile.update') }}" autocomplete="off">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('User information') }}</h6>
                            
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                     <div class="pl-lg-4">
                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                            <input type="text" name="name" id="input-name" 
                                class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" 
                                placeholder="{{ __('Name') }}" 
                                value="{{ old('name', auth()->user()->first_name . ' ' . auth()->user()->other_names) }}" 
                                required autofocus>

                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-email">{{ __('Email') }}</label>
                            <input type="email" name="email" id="input-email" 
                                class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" 
                                placeholder="{{ __('Email') }}" 
                                value="{{ old('email', auth()->user()->email) }}" 
                                required>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                        </div>
                    </div>

                        </form>
                        <hr class="my-4" />
                        <form method="post" action="{{ route('profile.password') }}" autocomplete="off">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Password') }}</h6>

                            @if (session('password_status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('password_status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('old_password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-current-password">{{ __('Current Password') }}</label>
                                    <input type="password" name="old_password" id="input-current-password" class="form-control form-control-alternative{{ $errors->has('old_password') ? ' is-invalid' : '' }}" placeholder="{{ __('Current Password') }}" value="" required>
                                    
                                    @if ($errors->has('old_password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('old_password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-password">{{ __('New Password') }}</label>
                                    <input type="password" name="password" id="input-password" class="form-control form-control-alternative{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('New Password') }}" value="" required>
                                    
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-password-confirmation">{{ __('Confirm New Password') }}</label>
                                    <input type="password" name="password_confirmation" id="input-password-confirmation" class="form-control form-control-alternative" placeholder="{{ __('Confirm New Password') }}" value="" required>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Change password') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection

<style>
    /* Info Cards */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.card-glass {
    background: var(--card-background);
    backdrop-filter: blur(10px);
    border-radius: var(--border-radius);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: var(--transition);
    margin-top: 9rem;
}

.card-glass:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.card-header {
    /* padding: 1.5rem; */
    display: flex;
    align-items: center;
    gap: 1rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.header-icon {
    width: 48px;
    height: 48px;
    background: var(--primary-color);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.header-icon.admin {
    background: var(--success-color);
}

.card-content {
    padding: 1.5rem;
}

.detail-group {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(0, 0, 0, 0.02);
    border-radius: 12px;
}

.detail-icon {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
}

.detail-info {
    flex: 1;
}

.detail-info label {
    display: block;
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin-bottom: 0.25rem;
}

.admin-profile {
    text-align: center;
    margin-bottom: 2rem;
}

.admin-avatar {
    width: 80px;
    height: 80px;
    background: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.5rem;
    font-weight: 600;
}

.admin-role {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: rgba(34, 197, 94, 0.1);
    color: var(--success-color);
    border-radius: 20px;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}
.card-profile-image {
    display: flex;
    justify-content: center;
    margin-left: -450px; 
}

.card-profile-image img {
    border: 3px solid white; 
}
.info-card{
        margin-left: 2rem;

}
</style>