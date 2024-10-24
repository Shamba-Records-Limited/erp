@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if(has_right_permission(config('enums.system_modules')['HR Management']['leave'], config('enums.system_permissions')['create']))
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addDepartmentAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addDepartmentAccordion"><span class="mdi mdi-plus"></span>Add Application
                        </button>
                    @endif
                    <button type="button" class="btn btn-info btn-fw btn-sm float-right mr-3" data-toggle="collapse"
                            data-target="#filterLeaves"
                            aria-expanded="false"
                            aria-controls="filterLeaves"><span class="mdi mdi-filter-outline"></span>Filter
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="addDepartmentAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Register Leave Application</h4>
                            </div>
                        </div>

                        <form action="{{ route('hr.leaves.add') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="employee">Employee No</label>
                                    <input type="text" name="employee_no"
                                           class="form-control {{ $errors->has('employee_no') ? ' is-invalid' : '' }}"
                                           id="employee_no" placeholder="EM123" value="{{ old('employee_no')}}"
                                           required>
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" name="start_date"
                                           class="form-control {{ $errors->has('start_date') ? ' is-invalid' : '' }}"
                                           id="start_date" value="{{ old('start_date')}}" required>

                                    @if ($errors->has('start_date'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('start_date')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="end_date">End Date</label>
                                    <input type="date" name="end_date"
                                           class="form-control {{ $errors->has('end_date') ? ' is-invalid' : '' }}"
                                           id="end_date" value="{{ old('end_date')}}" required>

                                    @if ($errors->has('end_date'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('end_date')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="file">File</label>
                                    <input type="file" name="file"
                                           class="form-control {{ $errors->has('file') ? ' is-invalid' : '' }}"
                                           id="file" value="{{ old('file')}}">

                                    @if ($errors->has('file'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('file')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="status">Status</label>
                                    <select name="status"
                                            class="form-control form-select {{ $errors->has('status') ? ' is-invalid' : '' }}"
                                            id="status" value="{{ old('status')}}">
                                        <option value="0"> Pending</option>
                                        <option value="1"> Grant</option>
                                        <option value="2"> Reject</option>
                                        <option value="3"> Complete</option>
                                    </select>
                                    @if ($errors->has('status'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('status')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="reason">Reason </label>
                                    <select name="reason"
                                            class="form-control form-select {{ $errors->has('reason') ? ' is-invalid' : '' }}"
                                            id="reason" value="{{ old('reason')}}">
                                        <option value="">-Select Reason-</option>
                                        <option value="Sick Leave">Sick Leave</option>
                                        <option value="Annual Work leave">Annual Work leave</option>
                                        <option value="Maternity/partenity leave">Maternity/partenity leave</option>
                                        <option value="Family Issues">Family Issues</option>
                                        <option value="Emergencies">Emergencies</option>
                                        <option value="Forced Leave">Forced Leave</option>
                                        <option value="Religious reasons">Religious reasons</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    @if ($errors->has('reason'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('reason')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="remarks">Remarks </label>
                                    <textarea name="remarks"
                                              class="form-control  {{ $errors->has('remarks') ? ' is-invalid' : '' }}"
                                              value="{{ old('remarks')}}" id="remarks"
                                              placeholder="Remarks from the person approving"
                                              required></textarea>
                                    @if ($errors->has('remarks'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('remarks')  }}</strong>
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
                    <!-- filter -->
                    <div class="collapse" id="filterLeaves">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h6>Filter Collections</h6>
                            </div>
                        </div>
                        <form method="get">
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <label for="employee"> ID No</label>
                                    <input type="text" name="id_no"
                                           class="form-control" placeholder="22334455">
                                </div>

                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <label for="employee"> Name</label>
                                    <input type="text" name="name"
                                           class="form-control" placeholder="John">
                                </div>

                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <label for="employee">Employee No</label>
                                    <input type="text" name="member_no"
                                           class="form-control" placeholder="1223">
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <label for=""></label>
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- ./filter -->
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if(has_right_permission(config('enums.system_modules')['HR Management']['leave'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('hr.leaves.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('hr.leaves.download','xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('hr.leaves.download', 'pdf') }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif

                    <h4 class="card-title">Registered Employee Leaves</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Reason</th>
                                <th>Remarks</th>
                                <th>File</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $canEdit = has_right_permission(config('enums.system_modules')['HR Management']['leave'], config('enums.system_permissions')['edit']);
                                $canDelete = has_right_permission(config('enums.system_modules')['HR Management']['leave'], config('enums.system_permissions')['delete']);
                            @endphp
                            @foreach($leaves as $key => $leave)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>
                                        <b>Name</b>: {{$leave->employee->user->first_name }}</br>
                                        <b>Number</b>: {{$leave->employee->employee_no }}
                                    </td>
                                    <td>
                                        <b>From</b>: {{$leave->start_date }}</br>
                                        <b>To</b>: {{$leave->end_date }}
                                    </td>
                                    <td>
                                        @if($leave->status == 0)
                                            <span class="text-warning">Pending</span>
                                        @elseif($leave->status == 1)
                                            <span class="text-success">Accepted</span>
                                        @elseif($leave->status == 2)
                                            <span class="text-danger">Rejected</span>
                                        @else
                                            <span class="text-info">Complete</span>
                                        @endif
                                    </td>
                                    <td>{{$leave->reason }}</td>
                                    <td>{{$leave->remarks }}</td>
                                    <td>@if($leave->file)
                                            <a href="{{ $leave->file }}">File</a>
                                        @endif</td>
                                    <td>

                                        @if($canDelete || $canEdit)
                                            <div class="btn-group dropdown">
                                                <button type="button" class="btn btn-default dropdown-toggle btn-sm"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if($canEdit)
                                                        <a class="text-success dropdown-item"
                                                           href="{{ route('hr.leaves.change',$leave->id) }}?status=1">
                                                            <i class="fa fa-check"></i>Grant
                                                        </a>
                                                        <a class="text-warning dropdown-item"
                                                           href="{{ route('hr.leaves.change',$leave->id) }}?status=2">
                                                            <i class="fa fa-warning"></i>Reject
                                                        </a>
                                                        <a class="text-info dropdown-item"
                                                           href="{{ route('hr.leaves.change',$leave->id) }}?status=3">
                                                            <i class="fa fa-times"></i>Complete
                                                        </a>
                                                    @endif
                                                    @if($canDelete)
                                                        @METHOD('DELETE')
                                                        <a onclick="return confirm('Sure to Delete?')"
                                                           href="{{ route('hr.leaves.delete',$leave->id) }}"
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
