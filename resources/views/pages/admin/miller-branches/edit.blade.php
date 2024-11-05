@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <div class="" id="editMillerBranchAccordion">
                    <div class="row mt-5">
                        <div class="col-lg-12 grid-margin stretch-card col-12">
                            <h4 class="card-title">Update Miller Branch</h4>
                        </div>
                    </div>

                    <form action="{{ route('admin.miller-branches.update', $branch->id) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="form-row">
                            <!-- Miller Name (Read-only) -->
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="miller">Miller</label>
                                <input type="text" name="miller"
                                    class="form-control {{ $errors->has('miller') ? ' is-invalid' : '' }}"
                                    id="miller" placeholder="Miller Name" value="{{ $branch->miller->name }}" required
                                    readonly>

                                @if ($errors->has('miller'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('miller') }}</strong>
                                </span>
                                @endif
                            </div>

                            <!-- Branch Name -->
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="branchName">Branch Name</label>
                                <input type="text" name="name"
                                    class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                    id="branchName" placeholder="Branch Name" value="{{ $branch->name }}" required>

                                @if ($errors->has('name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <!-- Branch Code -->
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="code">Code</label>
                                <input type="text" name="code"
                                    class="form-control {{ $errors->has('code') ? ' is-invalid' : '' }}" id="code"
                                    placeholder="Branch Code" value="{{ $branch->code }}">

                                @if ($errors->has('code'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('code') }}</strong>
                                </span>
                                @endif
                            </div>

                            <!-- Branch Location -->
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="location">Location</label>
                                <input type="hidden" name="id" value="{{ $branch->id }}" />
                                <input type="text" name="location"
                                    class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }}"
                                    value="{{ $branch->location }}" id="location" placeholder="Branch Location" required>

                                @if ($errors->has('location'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('location') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- County and Sub-County -->
                        <div class="form-row">
                            <!-- County Selection -->
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="county">County</label>
                                <select name="county_id" id="county"
                                    class="form-control {{ $errors->has('county_id') ? ' is-invalid' : '' }}" required>
                                    <option value="">Select County</option>
                                    @foreach($counties as $county)
                                        <option value="{{ $county->id }}" 
                                            {{ $branch->county_id == $county->id ? 'selected' : '' }}>
                                            {{ $county->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('county_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('county_id') }}</strong>
                                </span>
                                @endif
                            </div>

                            <!-- Sub-County Selection -->
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="sub_county">Sub-County</label>
                                <select name="sub_county_id" id="sub_county"
                                    class="form-control {{ $errors->has('sub_county_id') ? ' is-invalid' : '' }}" required>
                                    <option value="">Select Sub-County</option>
                                    @foreach($sub_counties as $sub_county)
                                        <option value="{{ $sub_county->id }}"
                                            {{ $branch->sub_county_id == $sub_county->id ? 'selected' : '' }}>
                                            {{ $sub_county->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('sub_county_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('sub_county_id') }}</strong>
                                </span>
                                @endif
                            </div>

                            <!-- Branch Address -->
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="address">Address</label>
                                <input type="text" name="address"
                                    class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}"
                                    id="address" placeholder="Branch Address" value="{{ $branch->address }}" required>

                                @if ($errors->has('address'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <form action="{{ route('admin.miller-branches.update', $branch->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Update Branch</button>
                                </div>
                            </div>
                        </form>

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
