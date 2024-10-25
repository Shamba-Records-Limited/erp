@extends('layouts.app')

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
                            <h4>Edit Category</h4>
                        </div>
                    </div>

                    <form action="{{ route('admin.products.edit_category', $category->id) }}" method="post">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" placeholder="Enter product category" value="{{ $category->name }}" required>

                                @if ($errors->has('name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('name')  }}</strong>
                                </span>
                                @endif
                            </div>
                            
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="unit">Unit</label>
                                <select name="unit" id="unit" class="form-control select2bs4 {{ $errors->has('unit') ? ' is-invalid' : '' }}" required>
                                    <option value="">-- Select Unit --</option>
                                    @foreach(config('enums.units') as $key => $unit)
                                    <option value="{{$key}}" @if($key == old('unit')) selected @endif>{{$unit['name']}} ({{$key}})</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('unit'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('unit')  }}</strong>
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