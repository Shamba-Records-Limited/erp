@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Update Cooperative</h4>
                <form action="{{ route('cooperative.setup.update') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="id" value="{{$cooperative->id}}">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <h6 class="mb-3">Cooperative Details</h6>
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="CompanyName">Name</label>
                            <input type="text" value="{{$cooperative->name}}" name="cooperative_name" class="form-control {{ $errors->has('cooperative_name') ? ' is-invalid' : '' }}" id="CompanyName" placeholder="ABC" value="{{ old('cooperative_name')}}" required>

                            @if ($errors->has('cooperative_name'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('cooperative_name')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="abbreviation">Abbreviation</label>
                            <input type="text" value="{{$cooperative->abbreviation}}" name="abbr" class="form-control  {{ $errors->has('abbr') ? ' is-invalid' : '' }}" id="abbreviation" placeholder="A.B.C" value="{{ old('abbr')}}">

                            @if ($errors->has('abbr'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('abbr')  }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="location">Location</label>
                            <input type="text" name="location" value="{{$cooperative->location}}" class="form-control  {{ $errors->has('location') ? ' is-invalid' : '' }}" value="{{ old('location')}}" id="location" placeholder="Nairobi" required>
                            @if ($errors->has('location'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('location')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="address">Address</label>
                            <input type="text" name="address" value="{{$cooperative->address}}" class="form-control  {{ $errors->has('address') ? ' is-invalid' : '' }}" id="address" placeholder="Nairobi" value="{{ old('address')}}" required>
                            @if ($errors->has('address'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('address')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="country">Country</label>
                            <select name="country" id="country" class=" form-control select2bs4 {{ $errors->has('address') ? ' is-invalid' : '' }}">
                                @foreach($countries as $country)
                                <option value="{{$country->id}}" {{$country->id == $cooperative->country_id ? 'selected' : '' }}> {{ $country->name }}</option>
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
                            <input type="email" name="cooperative_email" value="{{$cooperative->email}}" class="form-control {{ $errors->has('cooperative_email') ? ' is-invalid' : '' }}" id="company_email" placeholder="info@abc.com" value="{{ old('cooperative_email')}}" required>


                            @if ($errors->has('cooperative_email'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('cooperative_email')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="company_contact">Cooperative Contacts</label>
                            <input type="text" name="cooperative_contact" value="{{$cooperative->contact_details}}" class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" id="company_contact" placeholder="07....." value="{{ old('cooperative_contact')}}" required>

                            @if ($errors->has('cooperative_contact'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('cooperative_contact')  }}</strong>
                            </span>
                            @endif
                        </div>


                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="company_currency">Currency</label>
                            <input type="text" name="cooperative_currency" value="{{$cooperative->currency}}" class="form-control {{ $errors->has('cooperative_currency') ? ' is-invalid' : '' }}" id="company_currency" placeholder="Ksh." value="{{ old('cooperative_currency')}}" required>

                            @if ($errors->has('cooperative_currency'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('cooperative_currency')  }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-lg-3 col-md-6 col-12">
                        <button type="submit" class="btn btn-primary btn-fw btn-block">Update</button>
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