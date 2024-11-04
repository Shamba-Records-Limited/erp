@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <button type="button" class="btn btn-primary btn-sm mb-4 float-right" data-toggle="collapse" data-target="#addProductGradeAccordion" 
                aria-expanded="@if ($errors->count() > 0) true @else false @endif" aria-controls="addProductGradeAccordion">
            <span class="mdi mdi-plus"></span> Add Product Grade
        </button>

        <div class="collapse @if($errors->count() > 0) show @endif" id="addProductGradeAccordion">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="text-primary font-weight-bold mb-4">Register Product Grade</h4>

                <form action="{{ route('admin.products.store_grade') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <!-- Product Grade Details Section -->
                    <div class="section-header bg-light p-2 mb-3 rounded">
                        <h6 class="text-muted mb-0">Product Grade Details</h6>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name" class="font-weight-bold">Grade Name</label>
                            <input type="text" name="name" class="form-control rounded {{ $errors->has('name') ? ' is-invalid' : '' }}" 
                                   id="name" placeholder="Enter product grade..." value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="mdi mdi-check"></i> Add Product Grade
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
                <h4 class="card-title">Grades</h4>
                <div class="table-responsive">
                    <table class="table table-hover dt">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grades as $key => $grade)
                            <tr>
                                <td>{{++$key }}</td>
                                <td>{{$grade->name }}</td>
                                </td>
                                <td>
                                    <div class="btn-group dropdown">
                                        <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="text-info dropdown-item" href="{{ route('admin.products.view_edit_grade', $grade->id) }}">
                                                <i class="fa fa-edit"></i>Edit
                                            </a>
                                            <a onclick="return confirm('Sure to Delete?')" href="/admin/products/delete-grade/{{ $grade->id }}" class="text-danger dropdown-item">
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