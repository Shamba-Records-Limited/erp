@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['HR Management']['job_positions'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addBranchAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addBranchAccordion"><span class="mdi mdi-plus"></span>Add Job Positions
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addBranchAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Register Job Positions</h4>
                                </div>
                            </div>

                            <form action="{{ route('hr.job-positions.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="position">Job Position</label>
                                        <input type="text" name="position"
                                               class="form-control  {{ $errors->has('position') ? ' is-invalid' : '' }}"
                                               value="{{ old('position')}}" id="position" placeholder="Manager"
                                               required>

                                        @if ($errors->has('position'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('position')  }}</strong>
                                </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="code">Code</label>
                                        <input type="text" name="code"
                                               class="form-control  {{ $errors->has('code') ? ' is-invalid' : '' }}"
                                               value="{{ old('code')}}" id="code" placeholder="P123#"
                                               required>

                                        @if ($errors->has('code'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('code')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="role">Role</label>
                                        <input type="text" name="role"
                                               class="form-control  {{ $errors->has('role') ? ' is-invalid' : '' }}"
                                               value="{{ old('role')}}" id="role" placeholder="Overseeing activities"
                                               required>

                                        @if ($errors->has('role'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('role')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="description">Description</label>
                                        <textarea name="description"
                                                  class="form-control  {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                                  value="{{ old('description')}}" id="description"
                                                  placeholder="Description of the role"
                                                  required></textarea>

                                        @if ($errors->has('description'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('description')  }}</strong>
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
                    <h4 class="card-title">Registered Job Positions</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Position</th>
                                <th>Code</th>
                                <th>Role</th>
                                <th>Employees</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $canEdit = has_right_permission(config('enums.system_modules')['HR Management']['job_positions'], config('enums.system_permissions')['edit']);
                                $canDelete = has_right_permission(config('enums.system_modules')['HR Management']['job_positions'], config('enums.system_permissions')['delete'])
                            @endphp
                            @foreach($positions as $key => $position)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$position->position }} </td>
                                    <td>{{$position->code }}</td>
                                    <td>{{$position->role }}</td>
                                    <td>{{$position->employeePosition->count() }}</td>
                                    <td>{{$position->description }}</td>
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
                                                           href="/cooperative/hr/job-positions/delete/{{ $position->id }}"
                                                           class="text-danger dropdown-item">
                                                            <i class="fa fa-trash-alt"></i>Delete
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
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
