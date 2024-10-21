@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <form action="{{ route('admin.users.make-county-govt-official') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <h6 class="mb-3">Make County Govt Official</h6>
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="cooperative">Cooperative</label>
                            <input type="text" name="cooperative" class="form-control {{ $errors->has('cooperative') ? ' is-invalid' : '' }}" id="cooperative" placeholder="John" value="{{ $user->coop_name }}" required readonly>
                            @if ($errors->has('cooperative'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('cooperative')  }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" id="first_name" placeholder="John" value="{{ $user->first_name }}" required readonly>
                            @if ($errors->has('first_name'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('first_name')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="other_name">Other Names</label>
                            <input type="text" name="other_names" value="{{ $user->other_names }}" class="form-control {{ $errors->has('other_names') ? ' is-invalid' : '' }}" id="other_name" placeholder="Doe" required readonly>
                            @if ($errors->has('other_names'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('other_names')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="user_name">User Name</label>
                            <input type="text" name="user_name" class="form-control {{ $errors->has('user_name') ? ' is-invalid' : '' }}" id="user_name" placeholder="j_doe" value="{{ $user->username }}" required readonly>
                            @if ($errors->has('user_name'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('user_name')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" placeholder="johndoe@abc.com" value="{{ $user->email }}" required readonly>
                            @if ($errors->has('email'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('email')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="country">Country</label>
                            <select name="country" id="country" class=" form-control select2bs4 {{ $errors->has('country') ? ' is-invalid' : '' }}">
                                <option value=""> -Select Country-</option>
                                @foreach($countries as $country)
                                <option value="{{$country->id}}"> {{ $country->name }}</option>
                                @endforeach

                                @if ($errors->has('country'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('country')  }}</strong>
                                </span>
                                @endif
                            </select>
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="county">County</label>
                            <input type="text" name="county" class="form-control {{ $errors->has('county') ? ' is-invalid' : '' }}" id="county" placeholder="Nairobi" value="{{ old('county')}}" required>

                            @if ($errors->has('county'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('county')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="id_no">Id No./Passport</label>
                            <input type="text" name="id_no" class="form-control  {{ $errors->has('id_no') ? ' is-invalid' : '' }}" value="{{ old('id_no')}}" id="id_no" placeholder="12345678">
                            @if ($errors->has('id_no'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('id_no')  }}</strong>
                            </span>
                            @endif
                        </div>
                        
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" class=" form-control select2bs4 {{ $errors->has('gender') ? ' is-invalid' : '' }}">
                                <option value=""> -Select Gender-</option>
                                @foreach($gender_options as $key => $option)
                                <option value="{{$option}}"> {{ $option}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('gender'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('gender')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="phone_no">Phone No.</label>
                            <input type="text" name="phone_no" class="form-control  {{ $errors->has('phone_no') ? ' is-invalid' : '' }}" id="phone_no" placeholder="2547..." value="{{ old('phone_no')}}">
                            @if ($errors->has('phone_no'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('phone_no')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="employee_number">Employee No.</label>
                            <input type="text" name="employee_number" class="form-control {{ $errors->has('employee_number') ? ' is-invalid' : '' }}" id="employee_number" placeholder="EM1234" value="{{ old('employee_number')}}">

                            @if ($errors->has('employee_number'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('employee_number')  }}</strong>
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
                        </div>
                        @if ($errors->has('profile_picture'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('profile_picture')  }}</strong>
                        </span>
                        @endif

                        <div class="d-none" id="imagePreviewContainer">
                            <div class="imageHolder pl-2">
                                <img id="picturePreview" src="#" alt="pic" height="150px" width="150px" />
                            </div>
                        </div>

                    </div>
                    <hr class="mt-1 mb-1">
                    <div class="form-row">
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <button type="submit" class="btn btn-primary btn-fw btn-block">Apply
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
