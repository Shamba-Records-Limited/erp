@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['HR Management']['departments'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                                data-toggle="collapse"
                                data-target="#addDepartmentAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addDepartmentAccordion"><span
                                    class="mdi mdi-plus"></span>Add Department
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif "
                             id="addDepartmentAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Register Department</h4>
                                </div>
                            </div>

                            <form action="{{ route('hr.departments.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="productName">Name</label>
                                        <input type="text" name="name"
                                               class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               id="productName" placeholder="XYZ Branch"
                                               value="{{ old('name')}}"
                                               required>

                                        @if ($errors->has('name'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('name')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="code">Code</label>
                                        <input type="text" name="code"
                                               class="form-control  {{ $errors->has('code') ? ' is-invalid' : '' }}"
                                               id="code" placeholder="AB12#"
                                               value="{{ old('code')}}">

                                        @if ($errors->has('code'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('code')  }}</strong>
                                </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="office_number">Office Number</label>
                                        <input type="text" name="office_number"
                                               class="form-control  {{ $errors->has('office_number') ? ' is-invalid' : '' }}"
                                               value="{{ old('office_number')}}" id="office_number"
                                               placeholder="Uplands"
                                               required>
                                        @if ($errors->has('office_number'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('office_number')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="branch">Branch</label>
                                    <select name="branch" id="branch"
                                            class=" form-control select2bs4 {{ $errors->has('branch') ? ' is-invalid' : '' }}">
                                        <option value=""> -Select Branch-</option>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->id}}"> {{ $branch->name }}</option>
                                        @endforeach

                                        @if ($errors->has('branch'))
                                            <span class="help-block text-danger">
                                        <strong>{{ $errors->first('branch')  }}</strong>
                                    </span>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <button type="submit"
                                                class="btn btn-primary btn-fw btn-block">Add
                                        </button>
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
                    <h4 class="card-title">Registered Departments</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Branch Code</th>
                                <th>Office Number</th>
                                <th>Branch</th>
                                <th>Employees</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $canEdit = has_right_permission(config('enums.system_modules')['HR Management']['departments'], config('enums.system_permissions')['edit']);
                                $canDelete = has_right_permission(config('enums.system_modules')['HR Management']['departments'], config('enums.system_permissions')['delete']);
                                $canViewEmployees = has_right_permission(config('enums.system_modules')['HR Management']['employees'], config('enums.system_permissions')['view']);
                                $canViewPayrolls = has_right_permission(config('enums.system_modules')['HR Management']['payroll'], config('enums.system_permissions')['view']);
                            @endphp
                            @foreach($departments as $key => $department)
                                <tr data-href="{{ route('hr.departments.employees', $department->id) }}">
                                    <td>{{++$key }}</td>
                                    <td>{{$department->name }} </td>
                                    <td>{{$department->code }}</td>
                                    <td>{{$department->office_number }}</td>
                                    <td>{{$department->coopBranch->name }}</td>
                                    <td>{{$department->departmentEmployee->count() }}</td>
                                    <td>
                                        <!-- <div class="btn-group dropdown">
                                            <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions </button> -->
                                        <div class="">
                                            @if($canViewEmployees)
                                                <a class="btn btn-info btn-sm btn-rounded" href="#">Employees</a>
                                            @endif
                                            @if($canEdit)
                                                <a class="btn btn-warning btn-sm btn-rounded"
                                                   href="{{ route('hr.departments.detail', $department->id) }}">
                                                    <i class="fa fa-edit"></i>Edit</a>
                                            @endif
                                            @if($canDelete)
                                                @METHOD('DELETE')
                                                <a onclick="return confirm('Sure to Delete?')"
                                                   href="/cooperative/hr/departments/delete/{{ $department->id }}"
                                                   class="btn btn-danger btn-sm btn-rounded">
                                                    <i class="fa fa-trash-alt"></i>Delete</a>
                                            @endif

                                            @if($canViewPayrolls)
                                                <a class="btn btn-dark btn-sm btn-rounded"
                                                   href="{{ route('hr.employees.payroll.department',["department" => $department->id]) }}">
                                                    Payrolls
                                                </a>
                                            @endif
                                        </div>
                                        <!-- </div> -->
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
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        const rows = document.querySelectorAll("tr[data-href]");
        rows.forEach(row => {
          row.addEventListener("click", () => {
            window.location.href = row.dataset.href
          })
        })
      })
    </script>
@endpush
