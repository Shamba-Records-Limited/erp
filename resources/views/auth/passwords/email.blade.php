@extends('layout.master-mini')
@section('content')

    <div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one"
         style="background-image: url({{ url('assets/images/auth/login_1.jpg') }}); background-size: cover;">
        <div class="row w-100">
            <div class="col-lg-4 mx-auto">
                <div class="auto-form-wrapper">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                        <div class="form-group">
                            <label class="label">Email</label>
                            <div class="input-group">
                                <input  id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                      <i class="mdi mdi-email-alert-outline"></i>
                                    </span>
                                </div>
                            </div>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary submit-btn btn-block">Send Password Reset Link</button>
                        </div>

                    </form>
                </div>
                <p class="footer-text text-center mt-5">copyright © @php echo date('Y') @endphp Shamba Equity. All
                    rights reserved.</p>
            </div>
        </div>
    </div>

@endsection
