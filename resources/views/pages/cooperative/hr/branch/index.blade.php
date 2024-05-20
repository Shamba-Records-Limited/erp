@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
@if(has_right_permission(config('enums.system_modules')['HR Management']['branches'], config('enums.system_permissions')['create']))
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse" data-target="#addBranchAccordion" aria-expanded="@if ($errors->count() > 0) true @else false @endif" aria-controls="addBranchAccordion"><span class="mdi mdi-plus"></span>Add Branch
                </button>
                <div class="collapse @if ($errors->count() > 0) show @endif " id="addBranchAccordion">
                    <div class="row mt-5">
                        <div class="col-lg-12 grid-margin stretch-card col-12">
                            <h4>Register Branch</h4>
                        </div>
                    </div>

                    <form action="{{ route('hr.branches.add') }}" method="post">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="productName">Name</label>
                                <input type="text" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="productName" placeholder="XYZ Branch" value="{{ old('name')}}" required>

                                @if ($errors->has('name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="code">Code</label>
                                <input type="text" name="code" class="form-control  {{ $errors->has('code') ? ' is-invalid' : '' }}" id="code" placeholder="AB12#" value="{{ old('code')}}">

                                @if ($errors->has('code'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('code')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="location">Location</label>
                                <input type="text" name="location" class="form-control  {{ $errors->has('location') ? ' is-invalid' : '' }}" value="{{ old('location')}}" id="location" placeholder="Uplands" required>
                                @if ($errors->has('location'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('location')  }}</strong>
                                </span>
                                @endif
                            </div>

                        </div>
                        <div class="form-row">
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
@endif
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Registered Branches</h4>
                <div class="table-responsive">
                    <table class="table table-hover dt">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Branch Code</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $canEdit = has_right_permission(config('enums.system_modules')['HR Management']['branches'], config('enums.system_permissions')['edit']);
                            $canDelete = has_right_permission(config('enums.system_modules')['HR Management']['branches'], config('enums.system_permissions')['delete'])
                            @endphp
                            @foreach($branches as $key => $prod)
                            <tr>
                                <td>{{++$key }}</td>
                                <td>{{$prod->name }} </td>
                                <td>{{$prod->code }}</td>
                                <td>{{$prod->location }}</td>
                                <td>
                                    @if($canEdit || $canDelete)
                                    <div class="btn-group dropdown">
                                        <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="text-info dropdown-item" href="{{ route('cooperative-admin.branches.detail', $prod->id) }}">
                                                <i class="fa fa-edit"></i>View Details
                                            </a>
                                            @if($canEdit)
                                            <a class="text-info dropdown-item" href="{{ route('hr.branches.detail', $prod->id) }}">
                                                <i class="fa fa-edit"></i>Edit
                                            </a>
                                            @endif
                                            @if($canDelete)
                                            @METHOD('DELETE')
                                            <a onclick="return confirm('Sure to Delete?')" href="/cooperative/hr/branches/delete/{{ $prod->id }}" class="text-danger dropdown-item">
                                                <i class="fa fa-trash-alt"></i>Delete</a>
                                            @endif
                                        </div>
                                        @endif
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