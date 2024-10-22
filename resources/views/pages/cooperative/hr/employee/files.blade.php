@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['HR Management']['files'], config('enums.system_permissions')['download']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addEmployeeAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addEmployeeAccordion">
                            <span class="mdi mdi-plus">Add Files</span>
                        </button>

                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addEmployeeAccordion">
                            <form id="allow" name="allow" action="{{ route('hr.employees.files.upload')}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-6 col-md-6 col-12">
                                        <label>Employee</label>
                                        <select name="employee" id="employee"
                                                class=" form-control select2bs4 {{ $errors->has('employee') ? ' is-invalid' : '' }}">
                                            <option value=""></option>
                                            @foreach($employees as $emp)
                                                <option value="{{$emp->employee->id}}"> {{ ucwords(strtolower($emp->first_name.' '.$emp->other_names)) }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('employee'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('employee')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-12">
                                        <label>File Type</label>
                                        <input type="text" class="form-control"
                                               placeholder="e.g Resume(For multiple files, rename file to correct name)"
                                               name="file_name"/>
                                        @if ($errors->has('file_name'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('file_name')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-12">
                                        <label>File(s) </label>
                                        <input type="file" multiple class="form-control" name="file[]"/>
                                        @if ($errors->has('file'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('file')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <hr class="mt-1 mb-1">
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <button type="submit" class="btn btn-primary btn-fw btn-block">Upload</button>
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
                    <h4 class="card-title">Registered Employees</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Employee No.</th>
                                <th>Id/Passport No.</th>
                                <th>Files</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($employees as $key => $employee)
                                <tr data-href="{{ route('hr.employees.details', $employee->employee->id) }}">
                                    <td>{{++$key }}</td>
                                    <td>
                                        <a href="{{ route('hr.employees.details', $employee->employee->id) }}">{{ ucwords(strtolower($employee->first_name).' '.strtolower($employee->other_names) )}}</a>
                                    </td>
                                    <td>{{$employee->employee->employee_no}}</td>
                                    <td>{{$employee->employee->id_no}}</td>
                                    <td>
                                        @if($employee->employee->files)
                                            <ul>
                                                @foreach($employee->employee->files as $file)
                                                    <li><a target="_blank"
                                                           href="{{$file->file_link}}">{{$file->file_name}}</a></li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        @if($employee->employee->employeeLeave)
                                            <ul>
                                                @foreach($employee->employee->employeeLeave as $file)
                                                    <li><a target="_blank" href="{{$file->file}}">Leave File</a></li>
                                                @endforeach
                                            </ul>
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
    <script>

    </script>
@endpush
