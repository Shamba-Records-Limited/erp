@extends('layout.master-mini')

@section('content')
    <div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one"
         style="background-image: url({{ url('assets/images/auth/login_1.jpg') }}); background-size: cover;">

        <div class="row w-100">
            <div class="col-lg-4 mx-auto">
                <div class="auto-form-wrapper">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group">
                            <label class="label">Email</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                       name="email" value="{{ old('email') }}" required autofocus
                                       placeholder="Email">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                      <i class="mdi mdi-email-alert-outline"></i>
                                    </span>
                                </div>
                            </div>

                            @if ($errors->has('email'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="label">Password</label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       placeholder="*********" name="password">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                      <i class="mdi mdi-lock"></i>
                                    </span>
                                </div>
                            </div>

                            @if ($errors->has('password'))
                                <span class="help-block text-danger">
                                        <span>{{ $errors->first('password') }}</span>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                       placeholder="*********" name="password_confirmation">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                      <i class="mdi mdi-lock"></i>
                                    </span>
                                </div>
                            </div>

                            @if ($errors->has('password_confirmation'))
                                <span class="help-block text-danger">
                                        <span>{{ $errors->first('password_confirmation') }}</span>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary submit-btn btn-block">Reset Password</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
