@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse" data-target="#addComapnyAccordion" aria-expanded="@if ($errors->count() > 0) true @else false @endif" aria-controls="addComapnyAccordion"><span class="mdi mdi-plus"></span>Add Product Category
                </button>
                <div class="collapse @if($errors->count() > 0) show @endif" id="addComapnyAccordion">
                    <div class="row mt-5">
                        <div class="col-lg-12 grid-margin stretch-card col-12">
                            <h4>Register Category</h4>
                        </div>
                    </div>

                    <form action="{{ route('admin.products.store_category') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-12">
                                <h6 class="mb-3">Product Category Details</h6>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" placeholder="Enter product category..." value="{{ old('name')}}" required>

                                @if ($errors->has('name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="unit">Unit</label>
                                <select name="unit" id="unit" class="form-control form-select {{ $errors->has('unit') ? ' is-invalid' : '' }}" required>
                                    <option value="">-- Select Unit --</option>
                                    @foreach(config('enums.units') as $key => $unit)
                                    <option value="{{$key}}" @if($key==old('unit')) selected @endif>{{$unit['name']}} ({{$key}})</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('unit'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('unit')  }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <button type="submit" class="btn btn-primary btn-fw btn-block">Add</button>
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
                <h4 class="card-title">Product Categories</h4>
                <div class="table-responsive">
                    <table class="table table-hover dt">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Unit</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $key => $category)
                            <tr>
                                <td>{{++$key }}</td>
                                <td>{{$category->name }}</td>
                                <td>{{$category->unit }}</td>
                                <td>
                                    <div class="btn-group dropdown">
                                        <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="text-info dropdown-item" href="{{ route('admin.products.view_edit_category', $category->id) }}">
                                                <i class="fa fa-edit"></i>Edit
                                            </a>
                                            <a onclick="return confirm('Sure to Delete?')" href="/admin/products/delete-category/{{ $category->id }}" class="text-danger dropdown-item">
                                                <i class="fa fa-trash-alt"></i>Delete</a>
                                        </div>
                                    </div>
                                </td>
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