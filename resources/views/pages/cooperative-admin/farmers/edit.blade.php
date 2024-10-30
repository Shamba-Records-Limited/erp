@extends('layouts.app')

@push('plugin-styles')


@endpush

@php
$coopId = Auth::user()->cooperative_id;
$location = $farmer->location_id;
@endphp

@section('content')

@if(has_right_permission(config('enums.system_modules')['Farmer CRM']['farmers'],
config('enums.system_permissions')['edit']))
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div>
                    <form action="{{ route('cooperative.farmer.profile.update', $farmer->user->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-12">
                                <h4 class="mb-3">Farmer Details</h6>
                            </div>

                            @if($farmer->user->profile_picture)
                            <div class="form-group col-12">
                                <div class="imageHolder pl-2">
                                    <img src="{{url('storage/'.$farmer->user->profile_picture)}}"
                                        style="width:80px;height:80px;" />
                                </div>
                            </div>
                            @endif
                            <div class=" form-group col-lg-3 col-md-6 col-12">
                                <label for="f_name">First Name</label>
                                <input type="text" name="f_name"
                                    class="form-control {{ $errors->has('f_name') ? ' is-invalid' : '' }}"
                                    id="first_name" placeholder="John" value="{{ $farmer->user->first_name}}" required>
                                @if ($errors->has('first_name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('first_name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="o_name">Other Names</label>
                                <input type="text" name="other_names" value="{{ $farmer->user->other_names }}"
                                    class="form-control {{ $errors->has('o_names') ? ' is-invalid' : '' }}"
                                    id="other_name" placeholder="Doe" required>
                                @if ($errors->has('other_names'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('o_names')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="u_name">User Name</label>
                                <input type="text" name="user_name"
                                    class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}"
                                    id="username" placeholder="j_doe" value="{{ $farmer->user->username }}" required>
                                @if ($errors->has('username'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('username')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="user_email">Email</label>
                                <input type="email" name="user_email"
                                    class="form-control {{ $errors->has('user_email') ? ' is-invalid' : '' }}"
                                    id="user_email" placeholder="johndoe@abc.com" value="{{ $farmer->user->email}}"
                                    required>
                                @if ($errors->has('user_email'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('user_email')  }}</strong>
                                </span>
                                @endif
                            </div>


                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="">Gender</label>
                                <div class="form-row">
                                    <div class="form-check form-check-inline ml-2">
                                        <input class="form-check-input" type="radio" name="gender" id="Male" value="M"
                                            @if( $farmer->gender == "M") checked @endif>
                                        Male
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="Female" value="F"
                                            @if( $farmer->gender == "F") checked @endif>
                                        Female
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="Other" value="X"
                                            @if( $farmer->gender == "X") checked @endif>
                                        Other
                                    </div>
                                </div>

                                @if ($errors->has('gender'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('gender')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="dob">Date of Birth</label>
                                <input type="date" name="dob"
                                    class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" id="dob"
                                    value="{{ $farmer->dob}}" required>

                                @if ($errors->has('dob'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('dob')  }}</strong>
                                </span>
                                @endif
                            </div>






                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="id_no">Id No./Passport</label>
                                <input type="text" name="id_no"
                                    class="form-control  {{ $errors->has('id_no') ? ' is-invalid' : '' }}"
                                    value="{{ $farmer->id_no}}" id="id_no" placeholder="12345678" required>
                                @if ($errors->has('id_no'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('id_no')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="phone_no">Phone No.</label>
                                <input type="text" name="phone_no"
                                    class="form-control  {{ $errors->has('phone_no') ? ' is-invalid' : '' }}"
                                    id="phone_no" placeholder="2547..." value="{{$farmer->phone_no}}">
                                @if ($errors->has('phone_no'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('phone_no')  }}</strong>
                                </span>
                                @endif
                            </div>




                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="member_no">Member No.</label>
                                <input type="text" name="member_no"
                                    class="form-control {{ $errors->has('member_no') ? ' is-invalid' : '' }}"
                                    id="member_no" placeholder="123" value="{{ $farmer->member_no}}" required readonly>

                                @if ($errors->has('member_no'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('member_no')  }}</strong>
                                </span>
                                @endif
                            </div>









                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="mainImage">Profile Picture</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('profile_picture') is-invalid @enderror"
                                            id="profile_picture" name="profile_picture"
                                            value="{{ old('profile_picture') }}">
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
                                <button type="submit" class="btn btn-primary btn-fw btn-block">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')

@endpush