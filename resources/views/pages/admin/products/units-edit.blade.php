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
                            <h4>Edit Unit</h4>
                        </div>
                    </div>

                    <form action="{{ route('admin.products.edit_unit', $unit->id) }}" method="post">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" placeholder="Enter product category" value="{{ $unit->name }}" required>

                                @if ($errors->has('name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="abbreviation">Abbreviation</label>
                                <input type="text" name="abbreviation" class="form-control {{ $errors->has('abbreviation') ? ' is-invalid' : '' }}" id="abbreviation" placeholder="Enter product abbreviation..." value="{{ $unit->abbreviation }}" required>

                                @if ($errors->has('abbreviation'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('abbreviation')  }}</strong>
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