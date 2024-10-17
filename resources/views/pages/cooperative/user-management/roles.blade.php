@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['User Management']['roles'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addRoles"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addRoles">
                            <span class="mdi mdi-plus"></span>Roles
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addRoles">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Roles</h4>
                                </div>
                            </div>

                            <form action="{{ route('cooperative.role.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="roleId">Role</label>
                                        <input type="text" name="role"
                                               class="form-control {{ $errors->has('role') ? ' is-invalid' : '' }}"
                                               id="roleId" placeholder="Dangerous" value="{{ old('role')}}" required>

                                        @if ($errors->has('role'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('role')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-3 col-12">
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
                    <h4 class="card-title">Registered Categories</h4>
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
                            @php
                                $canEdit = has_right_permission(config('enums.system_modules')['User Management']['roles'], config('enums.system_permissions')['edit']);
                                $canDelete = has_right_permission(config('enums.system_modules')['User Management']['roles'], config('enums.system_permissions')['delete']);
                            @endphp
                            @foreach($roles as $key => $role)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$role->role }}</td>
                                    <td>
                                        <form action="{{ route('cooperative.role.delete', $role->id) }}" method="post">
                                            @csrf
                                            @if($canEdit)
                                                <button type="button" class="btn btn-info btn-rounded"
                                                        data-toggle="modal"
                                                        data-target="#editModal_{{$role->id}}">
                                                    <span class="mdi mdi-file-edit"></span>
                                                </button>
                                            @endif
                                            @if($canDelete)
                                                <button type="submit" class="btn btn-danger btn-rounded">
                                                    <span class="mdi mdi-trash-can mr-1"></span>
                                                </button>
                                            @endif
                                        </form>
                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$role->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$role->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$role->id}}">
                                                            Edit {{$role->role}} Role</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.role.edit', $role->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="editRole{{$role->id}}">Name</label>
                                                                    <input type="text" name="edit_role"
                                                                           class="form-control {{ $errors->has('edit_role') ? ' is-invalid' : '' }}"
                                                                           id="editRole{{$role->id}}"
                                                                           placeholder="Manager"
                                                                           value="{{ $role->role }}" required>
                                                                    @if ($errors->has('edit_role'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_role')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close
                                                            </button>
                                                            <button type="submit" class="btn btn-primary">Save changes
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        {{--  modal end   --}}
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
