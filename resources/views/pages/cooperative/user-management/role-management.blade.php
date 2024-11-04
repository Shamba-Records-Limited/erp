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

    @if(has_right_permission(config('enums.system_modules')['User Management']['role_management'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addRoles"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addRoles">
                            <span class="mdi mdi-plus"></span> Manage Roles
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addRoles">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Manage User Roles</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.role-management.assign-roles') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="employee">Employee</label>
                                        <select name="employee" id="employee"
                                                class=" form-control form-select {{ $errors->has('employee') ? ' is-invalid' : '' }}">
                                            @foreach($employees as $emp)
                                                <option value="{{$emp->user->id}}"> {{ ucwords(strtolower($emp->user->first_name.' '.$emp->user->other_names)) }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('employee'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('employee')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="roles">Roles</label>
                                        <select name="roles[]" multiple="multiple" id="roles"
                                                class="form-control form-select {{ $errors->has('roles') ? ' is-invalid' : '' }}">
                                            @foreach($roles as $role)
                                                <option value="{{$role->id}}"> {{ $role->role }}</option>
                                            @endforeach
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
                                <th>Roles</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $canEdit = has_right_permission(config('enums.system_modules')['User Management']['role_management'], config('enums.system_permissions')['edit']);
                            @endphp
                            @foreach($employees as $key => $emp)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{ucwords(strtolower($emp->user->first_name.' '.$emp->user->other_names))}}</td>
                                    <td>
                                        <ul class="list-wrapper">
                                            @foreach($emp->user->cooperative_roles as $role)
                                                <li class="item mb-3">
                                                    <form action="{{ route('cooperative.role-management.revoke-roles',[$emp->user->id, $role->id]) }}"
                                                          method="post">
                                                        @csrf
                                                        {{ $role->role }}
                                                        @if($canEdit)
                                                            <button type="submit"
                                                                    class="btn btn-sm btn-danger btn-rounded ml-2">
                                                                Revoke
                                                            </button>
                                                        @endif
                                                    </form>
                                                </li>
                                            @endforeach
                                        </ul>
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
