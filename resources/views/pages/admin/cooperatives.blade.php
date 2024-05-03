@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#addComapnyAccordion"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="addComapnyAccordion"><span class="mdi mdi-plus"></span>Add Company
                    </button>
                    <div class="collapse @if($errors->count() > 0) show @endif" id="addComapnyAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Register Cooperative</h4>
                            </div>
                        </div>


                        <form action="{{ route('cooperative.setup') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <h6 class="mb-3">Cooperative Details</h6>
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="CompanyName">Name</label>
                                    <input type="text" name="cooperative_name"
                                           class="form-control {{ $errors->has('cooperative_name') ? ' is-invalid' : '' }}"
                                           id="CompanyName" placeholder="ABC" value="{{ old('cooperative_name')}}"
                                           required>

                                    @if ($errors->has('cooperative_name'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('cooperative_name')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="abbreviation">Abbreviation</label>
                                    <input type="text" name="abbr"
                                           class="form-control  {{ $errors->has('abbr') ? ' is-invalid' : '' }}"
                                           id="abbreviation" placeholder="A.B.C" value="{{ old('abbr')}}">

                                    @if ($errors->has('abbr'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('abbr')  }}</strong>
                                </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="location">Location</label>
                                    <input type="text" name="location"
                                           class="form-control  {{ $errors->has('location') ? ' is-invalid' : '' }}"
                                           value="{{ old('location')}}" id="location" placeholder="Nairobi" required>
                                    @if ($errors->has('location'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('location')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="address">Address</label>
                                    <input type="text" name="address"
                                           class="form-control  {{ $errors->has('address') ? ' is-invalid' : '' }}"
                                           id="address" placeholder="Nairobi" value="{{ old('address')}}" required>
                                    @if ($errors->has('address'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('address')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="country">Country</label>
                                    <select name="country" id="country"
                                            class=" form-control select2bs4 {{ $errors->has('address') ? ' is-invalid' : '' }}">
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}"> {{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('country'))
                                        <span class="help-block text-danger">
                                                <strong>{{ $errors->first('country')  }}</strong>
                                            </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="company_email">Cooperative Email</label>
                                    <input type="email" name="cooperative_email"
                                           class="form-control {{ $errors->has('cooperative_email') ? ' is-invalid' : '' }}"
                                           id="company_email" placeholder="info@abc.com"
                                           value="{{ old('cooperative_email')}}" required>


                                    @if ($errors->has('cooperative_email'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('cooperative_email')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="company_contact">Cooperative Contacts</label>
                                    <input type="text" name="cooperative_contact"
                                           class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}"
                                           id="company_contact" placeholder="07....."
                                           value="{{ old('cooperative_contact')}}" required>

                                    @if ($errors->has('cooperative_contact'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('cooperative_contact')  }}</strong>
                                        </span>
                                    @endif
                                </div>


                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="company_currency">Currency</label>
                                    <input type="text" name="cooperative_currency"
                                           class="form-control {{ $errors->has('cooperative_currency') ? ' is-invalid' : '' }}"
                                           id="company_currency" placeholder="Ksh."
                                           value="{{ old('cooperative_currency')}}" required>

                                    @if ($errors->has('cooperative_currency'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('cooperative_currency')  }}</strong>
                                </span>
                                    @endif
                                </div>


                                <div class="form-group col-lg-3 col-md-6 col-12">

                                    <label for="mainImage">Logo</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file"
                                                   class="custom-file-input @error('company_logo') is-invalid @enderror"
                                                   id="company_logo" name="company_logo"
                                                   value="{{ old('company_logo') }}">
                                            <label class="custom-file-label" for="exampleInputFile">Logo</label>

                                            @if ($errors->has('company_logo'))
                                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('company_logo')  }}</strong>
                                </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <hr class="mt-1 mb-1">
                            <h6 class="h6 mt-2">Contact Person</h6>
                            <div class="form-row">

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="f_name">First Name</label>
                                    <input type="text" name="f_name"
                                           class="form-control {{ $errors->has('f_name') ? ' is-invalid' : '' }}"
                                           id="f_name" placeholder="John" value="{{ old('f_name')}}" required>
                                    @if ($errors->has('f_name'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('f_name')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="o_name">Other Names</label>
                                    <input type="text" name="o_names" value="{{ old('o_names')}}"
                                           class="form-control {{ $errors->has('o_names') ? ' is-invalid' : '' }}"
                                           id="o_name" placeholder="Doe" required>
                                    @if ($errors->has('o_names'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('o_names')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="u_name">User Name</label>
                                    <input type="text" name="u_name"
                                           class="form-control {{ $errors->has('u_name') ? ' is-invalid' : '' }}"
                                           id="u_name" placeholder="j_doe" value="{{ old('u_name')}}" required>
                                    @if ($errors->has('u_name'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('u_name')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="user_email">Email</label>
                                    <input type="email" name="user_email"
                                           class="form-control {{ $errors->has('user_email') ? ' is-invalid' : '' }}"
                                           id="user_email" placeholder="johndoe@abc.com" value="{{ old('user_email')}}"
                                           required>
                                    @if ($errors->has('user_email'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('user_email')  }}</strong>
                                </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Registered Cooperatives</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact Details</th>
                                <th>Country</th>
                                <th>Location</th>
                                <th>Address</th>
                                <th>Currency</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cooperatives as $key => $cop)

                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$cop->name }} ({{$cop->abbreviation}})</td>
                                    <td>{{$cop->email }}</td>
                                    <td>{{$cop->contact_details }}</td>
                                    <td> {{$cop->country->name }}
                                        <span class="mr-2 table-flag">
                                            <img src="{{ asset(get_country_flag($cop->country->iso_code)) }}"/>
                                        </span>
                                    </td>
                                    <td>{{$cop->location }}</td>
                                    <td>{{$cop->address }}</td>
                                    <td>{{$cop->currency }}</td>
                                </tr>
                            @endforeach
                            {{--              <tr>--}}
                            {{--                <td>Jacob</td>--}}
                            {{--                <td>Photoshop</td>--}}
                            {{--                <td class="text-danger"> 28.76% <i class="mdi mdi-arrow-down"></i>--}}
                            {{--                </td>--}}
                            {{--                <td>--}}
                            {{--                  <label class="badge badge-danger">Pending</label>--}}
                            {{--                </td>--}}
                            {{--              </tr>--}}
                            {{--              <tr>--}}
                            {{--                <td>Messsy</td>--}}
                            {{--                <td>Flash</td>--}}
                            {{--                <td class="text-danger"> 21.06% <i class="mdi mdi-arrow-down"></i>--}}
                            {{--                </td>--}}
                            {{--                <td>--}}
                            {{--                  <label class="badge badge-warning">In progress</label>--}}
                            {{--                </td>--}}
                            {{--              </tr>--}}
                            {{--              <tr>--}}
                            {{--                <td>John</td>--}}
                            {{--                <td>Premier</td>--}}
                            {{--                <td class="text-danger"> 35.00% <i class="mdi mdi-arrow-down"></i>--}}
                            {{--                </td>--}}
                            {{--                <td>--}}
                            {{--                  <label class="badge badge-info">Fixed</label>--}}
                            {{--                </td>--}}
                            {{--              </tr>--}}
                            {{--              <tr>--}}
                            {{--                <td>Peter</td>--}}
                            {{--                <td>After effects</td>--}}
                            {{--                <td class="text-success"> 82.00% <i class="mdi mdi-arrow-up"></i>--}}
                            {{--                </td>--}}
                            {{--                <td>--}}
                            {{--                  <label class="badge badge-success">Completed</label>--}}
                            {{--                </td>--}}
                            {{--              </tr>--}}
                            {{--              <tr>--}}
                            {{--                <td>Dave</td>--}}
                            {{--                <td>53275535</td>--}}
                            {{--                <td class="text-success"> 98.05% <i class="mdi mdi-arrow-up"></i>--}}
                            {{--                </td>--}}
                            {{--                <td>--}}
                            {{--                  <label class="badge badge-warning">In progress</label>--}}
                            {{--                </td>--}}
                            {{--              </tr>--}}
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
