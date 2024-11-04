@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <button type="button" class="btn btn-primary btn-sm mb-4 float-right" data-toggle="collapse" data-target="#addProductCategoryAccordion" 
                aria-expanded="@if ($errors->count() > 0) true @else false @endif" aria-controls="addProductCategoryAccordion">
            <span class="mdi mdi-plus"></span> Add Product Category
        </button>

        <div class="collapse @if($errors->count() > 0) show @endif" id="addProductCategoryAccordion">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="text-primary font-weight-bold mb-4">Register Product Category</h4>

                <form action="{{ route('admin.products.store_category') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <!-- Product Category Details Section -->
                    <div class="section-header bg-light p-2 mb-3 rounded">
                        <h6 class="text-muted mb-0">Product Category Details</h6>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name" class="font-weight-bold">Category Name</label>
                            <input type="text" name="name" class="form-control rounded {{ $errors->has('name') ? ' is-invalid' : '' }}" 
                                   id="name" placeholder="Enter product category..." value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="unit" class="font-weight-bold">Unit</label>
                            <select name="unit" id="unit" class="form-control rounded {{ $errors->has('unit') ? ' is-invalid' : '' }}" required>
                                <option value="">-- Select Unit --</option>
                                @foreach(config('enums.units') as $key => $unit)
                                    <option value="{{ $key }}" @if($key == old('unit')) selected @endif>
                                        {{ $unit['name'] }} ({{ $key }})
                                    </option>
                                @endforeach
                            </select>
                            @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="mdi mdi-check"></i> Add Product Category
                        </button>
                    </div>
                </form>
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