@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
    @php $user = Auth::user();@endphp
    <div class="row">
        <div class="col-lg-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Change Password</h4>

                    <form id="allow" name="allow"
                          action="{{ route('update-password') }}"
                          method="post">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="old_password">Old Password</label>
                                <input type="password" name="old_password"
                                       class="form-control {{ $errors->has('old_password') ? ' is-invalid' : '' }}"
                                       id="old_password"
                                       value="{{ old('old_password')}}" required>
                                @if ($errors->has('old_password'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('old_password')  }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-12">
                                <label for="password">New Password</label>
                                <input type="password" name="password"
                                       class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       id="password"
                                       value="{{ old('password')}}" required>
                                @if ($errors->has('password'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('password')  }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-12">
                                <label for="password_confirmation">Repeat New Password</label>
                                <input type="password" name="password_confirmation"
                                       class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                       id="password_confirmation"
                                       value="{{ old('password_confirmation')}}" required>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('password_confirmation')  }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <button type="submit" class="btn btn-primary btn-fw btn-block">
                                    Change Password
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
@endpush

@push('custom-scripts')
@endpush
