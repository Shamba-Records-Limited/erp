@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <div class="" id="addBranchAccordion">
                    <div class="row mt-5">
                        <div class="col-lg-12 grid-margin stretch-card col-12">
                            <h4>Update User</h4>
                        </div>
                    </div>

                    <form action="{{ route('admin.users.update') }}" method="post">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="cooperative">Cooperative</label>
                                <input type="text" name="cooperative" class="form-control {{ $errors->has('cooperative') ? ' is-invalid' : '' }}" id="cooperative" placeholder="XYZ Branch" value="{{ $user->coop_name }}" required readonly>

                                @if ($errors->has('cooperative'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('cooperative')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="username">Username</label>
                                <input type="text" name="name" class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" id="username" placeholder="XYZ Branch" value="{{ $user->username }}" required>

                                @if ($errors->has('username'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('username')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" class="form-control  {{ $errors->has('first_name') ? ' is-invalid' : '' }}" id="first_name" placeholder="AB12#" value="{{ $user->first_name }}">

                                @if ($errors->has('first_name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('first_name')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="other_names">Other Names</label>
                                <input type="hidden" name="id" value="{{ $user->other_names }}" />
                                <input type="text" name="other_names" class="form-control  {{ $errors->has('other_names') ? ' is-invalid' : '' }}" value="{{ $user->other_names }}" id="other_names" placeholder="Uplands" required>
                                @if ($errors->has('other_names'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('other_names')  }}</strong>
                                </span>
                                @endif
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <button type="submit" class="btn btn-primary btn-fw btn-block">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush