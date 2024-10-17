@extends('layout.master')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/override.css') }}" type="text/css">
@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Vet & Extension Services']['vets'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addCategoryAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addCategoryAccordion"><span class="mdi mdi-plus"></span>Add Vets
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addCategoryAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Vets</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.vet.add') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="first_name">First Name</label>
                                        <input type="text" name="first_name"
                                               class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                                               id="first_name" placeholder="John" value="{{ old('first_name')}}"
                                               required>

                                        @if ($errors->has('first_name'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('first_name')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="other_names">Other Name</label>
                                        <input type="text" name="other_names"
                                               class="form-control {{ $errors->has('other_names') ? ' is-invalid' : '' }}"
                                               id="other_names" placeholder="Doe" value="{{ old('other_names')}}"
                                               required>

                                        @if ($errors->has('other_names'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('other_names')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="email">Email</label>
                                        <input type="email" name="email"
                                               class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                               id="email" placeholder="johndoe@gmail.com" value="{{ old('email')}}"
                                               required>

                                        @if ($errors->has('email'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('email')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="id_no">ID/Passport</label>
                                        <input type="text" name="id_no"
                                               class="form-control {{ $errors->has('id_no') ? ' is-invalid' : '' }}"
                                               id="id_no" placeholder="12345678" value="{{ old('id_no')}}" required>

                                        @if ($errors->has('id_no'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('id_no')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="categoryName">Phone Number</label>
                                        <input type="text" name="phone_no"
                                               class="form-control {{ $errors->has('phone_no') ? ' is-invalid' : '' }}"
                                               id="categoryName" placeholder="07...." value="{{ old('phone_no')}}"
                                               required>

                                        @if ($errors->has('phone_no'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('phone_no')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="">Gender</label>
                                        <div class="form-row">
                                            <div class="form-check form-check-inline ml-2">
                                                <input class="form-check-input" type="radio" name="gender" id="Male"
                                                       value="M" @if( old('gender') == "M") checked @endif>
                                                Male
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" id="Female"
                                                       value="F" @if( old('gender') == "F") checked @endif>
                                                Female
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" id="Other"
                                                       value="X" @if( old('gender') == "X") checked @endif>
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

                                        <label for="mainImage">Profile Image</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file"
                                                       class="custom-file-input @error('image') is-invalid @enderror"
                                                       id="image" name="image"
                                                       value="{{ old('image') }}"
                                                       accept="image/png,image/jpg,image/jpeg"
                                                >
                                                <label class="custom-file-label" for="exampleInputFile">Profile
                                                    Image</label>

                                            </div>
                                        </div>
                                        @if ($errors->has('image'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('image')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="category">Service Category</label>
                                        <select name="category" id="category"
                                                class=" form-control select2bs4 {{ $errors->has('category') ? ' is-invalid' : '' }}">
                                            <option value="">---Select Category---</option>
                                            @foreach(config('enums.vet_service_types')[0] as $type)
                                                <option value="{{$type}}"> {{$type}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('category'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('category')  }}</strong>
                                </span>
                                        @endif
                                    </div>


                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-3 col-12">
                                        <label for=""></label>
                                        <button type="submit" class="btn btn-primary btn-fw btn-block">Add</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if(has_right_permission(config('enums.system_modules')['Vet & Extension Services']['vets'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('cooperative.vets.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('cooperative.vets.download','xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('cooperative.vets.download', 'pdf') }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif

                    <h4 class="card-title">Registered Vets</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Service Category</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vets as $key => $vet)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>
                                        @if(isset($booking->vet) && isset($booking->vet->profile_image))
                                            @if($vet->vet->profile_image and file_exists('storage/'.$vet->vet->profile_image))
                                                <img src="{{ asset('storage/'.$vet->vet->profile_image )}}"
                                                     alt="profile" class="rounded-circle t-image">
                                            @else
                                                <img src="{{ url('assets/images/avatar.png') }}" alt="profile"
                                                     class="rounded-circle t-image">
                                            @endif
                                        @else
                                            <img src="{{ url('assets/images/avatar.png') }}" alt="profile"
                                                 class="rounded-circle t-image">
                                        @endif
                                    </td>
                                    <td>{{ ucwords(strtolower($vet->first_name).' '.strtolower($vet->other_names) )}}</td>
                                    <td>{{ $vet->username }}</td>
                                    <td>{{ $vet->email }}</td>
                                    <td>{{ $vet->vet->gender }}</td>
                                    <td>{{ $vet->vet->category }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
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
