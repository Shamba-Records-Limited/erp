@extends('layouts.app')

@push('plugin-styles')
    <style>
        .list-wrapper .item {
            list-style-type: "";
            font-size: 15px;
        }
    </style>
@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['User Management']['permissions'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addRoles"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addRoles">
                            <span class="mdi mdi-plus"></span> Manage Permissions
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addRoles">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Manage Permissions</h4>
                                </div>
                            </div>

                            <form action="{{ route('cooperative.permissions.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="employee">Employee</label>
                                        <select name="employee" id="employee"
                                                class=" form-control select2bs4 {{ $errors->has('employee') ? ' is-invalid' : '' }}">
                                            <option value=""></option>
                                            @foreach($employees as $employee)
                                                <option value="{{$employee->id}}"> {{ ucwords(strtolower($employee->first_name.' '.$employee->other_names)) }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('module'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('module')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="module">Module</label>
                                        <select name="module" id="module" onchange="getSubmodulesByModule()"
                                                class=" form-control select2bs4 {{ $errors->has('module') ? ' is-invalid' : '' }}">
                                            <option value=""></option>
                                            @foreach($modules as $module)
                                                <option value="{{$module->id}}"> {{ $module->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('module'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('module')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="module">Sub Module</label>
                                        <select name="submodule" id="submodule"
                                                class=" form-control select2bs4 {{ $errors->has('module') ? ' is-invalid' : '' }}">

                                        </select>
                                        @if ($errors->has('submodule'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('submodule')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="roles">Permissions</label>
                                        <select name="permissions[]" multiple="multiple" id="roles"
                                                class="form-control select2bs4 {{ $errors->has('roles') ? ' is-invalid' : '' }}">
                                            <option value="{{\App\InternalUserPermission::CAN_VIEW}}">Can View</option>
                                            <option value="{{\App\InternalUserPermission::CAN_CREATE}}">Can Create
                                            </option>
                                            <option value="{{\App\InternalUserPermission::CAN_EDIT}}">Can Edit</option>
                                            <option value="{{\App\InternalUserPermission::CAN_DELETE}}">Can Delete
                                            </option>
                                            <option value="{{\App\InternalUserPermission::CAN_DOWNLOAD_REPORT}}">Can
                                                Download Reports
                                            </option>
                                        </select>
                                        @if ($errors->has('roles'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('roles')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-3 col-12">
                                        <button type="submit" class="btn btn-primary btn-fw btn-block">Assign</button>
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
                                <th>Employee</th>
                                <th>Roles</th>
                                <th>Module</th>
                                <th>Sub Module</th>
                                <th>Permissions</th>
                                <th>Assigned By</th>
                                <th>Updated By</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $canEdit = has_right_permission(config('enums.system_modules')['User Management']['permissions'], config('enums.system_permissions')['edit']);
                            @endphp
                            @foreach($permissions as $key => $permission)
                                @php
                                    $roles = [];
                                    foreach ($permission->employee->cooperative_roles as $r){
                                        $roles[]=$r->role;
                                    }
                                @endphp
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{ucwords(strtolower($permission->employee->first_name.' '.$permission->employee->other_names))}}</td>
                                    <td>{{ implode(", ", $roles) }}</td>
                                    <td>{{ $permission->subModule->module->name }}</td>
                                    <td>{{ $permission->subModule->name }}</td>

                                    <td>
                                        <form action="{{ route('cooperative.permissions.edit', $permission->id) }}"
                                              method="post" id="form_{{$permission->id}}">
                                            @csrf
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox"
                                                       id="canView_{{$permission->id}}"
                                                       name="view"
                                                       {{ $permission->can_view == 1 ? 'checked' : '' }}
                                                       value="1">
                                                <label class="pr-3" for="canView_{{$permission->id}}">View</label>

                                                <input class="form-check-input" type="checkbox"
                                                       id="canCreate_{{$permission->id}}"
                                                       {{ $permission->can_create == 1 ? 'checked' : '' }}
                                                       name="create"
                                                       value="2">
                                                <label class="pr-3" for="canCreate_{{$permission->id}}">Create</label>

                                                <input class="form-check-input" type="checkbox"
                                                       id="canEdit_{{$permission->id}}"
                                                       {{ $permission->can_edit == 1 ? 'checked' : '' }}
                                                       name="edit"
                                                       value="3">
                                                <label class="pr-3" for="canEdit_{{$permission->id}}">Edit</label>

                                                <input class="form-check-input" type="checkbox"
                                                       id="canDelete_{{$permission->id}}"
                                                       {{ $permission->can_delete == 1 ? 'checked' : '' }}
                                                       name="delete"
                                                       value="5">
                                                <label class="pr-3" for="canDelete_{{$permission->id}}">Delete</label>

                                                <input class="form-check-input" type="checkbox"
                                                       id="canDownloadReport_{{$permission->id}}"
                                                       {{ $permission->can_download_report == 1 ? 'checked' : '' }}
                                                       name="download"
                                                       value="5">
                                                <label class="pr-3" for="canDownloadReport_{{$permission->id}}">Download
                                                    Report</label>

                                                @if($canEdit)
                                                    <button type="submit" class="btn btn-primary btn-rounded btn-sm">
                                                        <span class="mdi mdi-content-save">Update</span>
                                                    </button>
                                                @endif
                                            </div>
                                        </form>
                                    </td>
                                    <td>{{ ucwords(strtolower($permission->createdBy->first_name.' '.$permission->createdBy->other_names)) }}</td>
                                    <td>{{ $permission->updated_by_user_id ? ucwords(strtolower($permission->updatedBy->first_name.' '.$permission->updatedBy->other_names)) : '-' }}</td>
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
    <script>
        const getSubmodulesByModule = () => {
            const moduleId = $('#module').val();
            if (moduleId !== null || moduleId !== "") {
                let url = '{{ route('cooperative.sub-modules.by-module-id',":moduleId") }}';
                url = url.replace(':moduleId', moduleId);
                let htmlCode = ``;
                axios.get(url).then(res => {
                    const data = res.data
                    htmlCode += `<option value="">---Select SubModule---</option>`;
                    data.forEach(d => {
                        htmlCode += `<option value="${d.id}">${d.name}</option>`;
                    });
                    $("#submodule").html(htmlCode)
                }).catch(() => {
                    htmlCode += `<option value=""></option>`;
                    $("#submodule").html(htmlCode);
                })
            } else {
                htmlCode += `<option value=""></option>`;
                $("#submodule").html(htmlCode);
            }
        }
    </script>
@endpush
