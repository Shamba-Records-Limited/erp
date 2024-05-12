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
                            <h4>Update Branch</h4>
                        </div>
                    </div>

                    <form action="{{ route('branches.edit') }}" method="post">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="cooperative">Cooperative</label>
                                <input type="text" name="cooperative" class="form-control {{ $errors->has('cooperative') ? ' is-invalid' : '' }}" id="cooperative" placeholder="XYZ Branch" value="{{ $branch->coop_name }}" required readonly>

                                @if ($errors->has('cooperative'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('cooperative')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="productName">Name</label>
                                <input type="text" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="productName" placeholder="XYZ Branch" value="{{ $branch->name }}" required>

                                @if ($errors->has('name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="code">Code</label>
                                <input type="text" name="code" class="form-control  {{ $errors->has('code') ? ' is-invalid' : '' }}" id="code" placeholder="AB12#" value="{{ $branch->code }}">

                                @if ($errors->has('code'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('code')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="location">Location</label>
                                <input type="hidden" name="id" value="{{ $branch->id }}" />
                                <input type="text" name="location" class="form-control  {{ $errors->has('location') ? ' is-invalid' : '' }}" value="{{ $branch->location }}" id="location" placeholder="Uplands" required>
                                @if ($errors->has('location'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('location')  }}</strong>
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