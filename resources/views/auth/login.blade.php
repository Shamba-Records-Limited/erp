@extends('layout.master-mini')
@section('content')

    <div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one"
         style="background-image: url({{ url('assets/images/auth/login_1.jpg') }}); background-size: cover;">
        <div class="row w-100">
            <div class="col-lg-4 mx-auto">
                <div class="auto-form-wrapper">
                    <form method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="label">Username or Email</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control {{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}"
                                       name="login" value="{{ old('username') ?: old('email') }}" required autofocus
                                       placeholder="Username or Email">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                      <i class="mdi mdi-email-alert-outline"></i>
                                    </span>
                                </div>
                            </div>

                            @if ($errors->has('username') || $errors->has('email'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('username') ?: $errors->first('email') }}</strong>
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
                            <button class="btn btn-primary submit-btn btn-block">Login</button>
                        </div>
                        <div class="form-group d-flex">
                            <div class="form-check mt-0">
{{--                                                                <label class="form-check-label">--}}
{{--                                                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me--}}
{{--                                                                </label>--}}
                            </div>
                            <a href="{{ route('password.request') }}" class="text-small forgot-password text-black">Forgot Password</a>
                            <span class="text-small forgot-password pl-1 pr-1">|</span>
                            <a href="{{ route('farmer.register') }}" class="text-small forgot-password text-black pl-1">Farmer Register</a>
                        </div>

                    </form>
                </div>
                <p class="footer-text text-center mt-5">copyright © @php echo date('Y') @endphp Shamba Equity. All
                    rights reserved.</p>
            </div>
        </div>
    </div>

@endsection
