@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['HR Management']['job_type'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addBranchAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addBranchAccordion"><span class="mdi mdi-plus"></span>Add Employment Type
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addBranchAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Register Employment Type</h4>
                                </div>
                            </div>

                            <form action="{{ route('hr.employment-types.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="type">Employment Type</label>
                                        <input type="text" name="type"
                                               class="form-control {{ $errors->has('date') ? ' is-invalid' : '' }}"
                                               id="date" placeholder="e.g Casual, Contract" value="{{ old('type')}}"
                                               required>

                                        @if ($errors->has('type'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('type')  }}</strong>
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
                    <h4 class="card-title">Registered Employment Types</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Employees</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $canEdit = has_right_permission(config('enums.system_modules')['HR Management']['job_type'], config('enums.system_permissions')['edit']);
                                $canDelete = has_right_permission(config('enums.system_modules')['HR Management']['job_type'], config('enums.system_permissions')['delete']);
                            @endphp
                            @foreach($types as $key => $type)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$type->type }} </td>
                                    <td>{{$type->typeEmployees->count() }}</td>
                                    <td>
                                        @if($canEdit || $canDelete)
                                            <div class="btn-group dropdown">
                                                <button type="button" class="btn btn-default dropdown-toggle btn-sm"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if($canEdit)
                                                        <a class="text-info dropdown-item" href="#">
                                                            <i class="fa fa-edit"></i>Edit</a>
                                                    @endif
                                                    @if($canDelete)
                                                        @METHOD('DELETE')
                                                        <a onclick="return confirm('Sure to Delete?')"
                                                           href="/cooperative/hr/employment-types/delete/{{ $type->id }}"
                                                           class="text-danger dropdown-item">
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
