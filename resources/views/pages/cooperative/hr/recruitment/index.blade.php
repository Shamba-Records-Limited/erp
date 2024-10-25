@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['HR Management']['recruitment'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addRecruitmentAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addRecruitmentAccordion"><span class="mdi mdi-plus"></span>Add
                            Recruitment
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addRecruitmentAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Post Recruitment</h4>
                                </div>
                            </div>

                            <form action="{{ route('hr.recruitment.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="role">Position</label>
                                        <select name="role" id="role"
                                                class=" form-control select2bs4 {{ $errors->has('role') ? ' is-invalid' : '' }}">
                                            <option value=""> - Select Role -</option>
                                            @foreach($positions as $role)
                                                <option value="{{$role->position}}"> {{ $role->position }}</option>
                                            @endforeach

                                            @if ($errors->has('role'))
                                                <span class="help-block text-danger">
                                                <strong>{{ $errors->first('role')  }}</strong>
                                            </span>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="description">Description</label>
                                        <textarea name="description"
                                                  class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                                  id="description" placeholder="Say more about the role..."
                                                  value="{{ old('description')}}" required>
                                    </textarea>

                                        @if ($errors->has('description'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('description')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="desired_skills">Desired skills</label>
                                        <textarea name="desired_skills"
                                                  class="form-control {{ $errors->has('desired_skills') ? ' is-invalid' : '' }}"
                                                  id="desired_skills" placeholder="People Management"
                                                  value="{{ old('desired_skills')}}" required>
                                    </textarea>

                                        @if ($errors->has('desired_skills'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('desired_skills')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="qualifications">Qualifications</label>
                                        <textarea name="qualifications"
                                                  class="form-control {{ $errors->has('qualifications') ? ' is-invalid' : '' }}"
                                                  id="qualifications" placeholder="Bachelors Degree"
                                                  value="{{ old('qualifications')}}" required>
                                    </textarea>

                                        @if ($errors->has('qualifications'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('qualifications')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="code">Employment Type</label>
                                        <select name="employment_type" id="employment_type"
                                                class=" form-control select2bs4 {{ $errors->has('employment_type') ? ' is-invalid' : '' }}">
                                            <option value="">- Select Type -</option>
                                            @foreach($types as $type)
                                                <option value="{{$type->type}}"> {{ $type->type }}</option>
                                            @endforeach

                                        </select>

                                        @if ($errors->has('employment_type'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('employment_type')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="salary_range">Salary Range</label>
                                        <input type="text" name="salary_range"
                                               class="form-control  {{ $errors->has('salary_range') ? ' is-invalid' : '' }}"
                                               value="{{ old('salary_range')}}" id="salary_range"
                                               placeholder="$1000-$2500"
                                               required>
                                        @if ($errors->has('salary_range'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('salary_range')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="location">Location</label>
                                        <input type="text" name="location"
                                               class="form-control  {{ $errors->has('location') ? ' is-invalid' : '' }}"
                                               value="{{ old('location')}}" id="location" placeholder="Uplands"
                                               required>
                                        @if ($errors->has('location'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('location')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="file">File</label>
                                        <input type="file" name="file"
                                               class="form-control  {{ $errors->has('file') ? ' is-invalid' : '' }}"
                                               value="{{ old('file')}}" id="file" placeholder="File"
                                               required>
                                        @if ($errors->has('file'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('file')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="end_date">End Date</label>
                                        <input type="date" name="end_date"
                                               class="form-control  {{ $errors->has('end_date') ? ' is-invalid' : '' }}"
                                               value="{{ old('end_date')}}" id="end_date" placeholder="End Date"
                                               required>
                                        @if ($errors->has('end_date'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('end_date')  }}</strong>
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
                    <h4 class="card-title">Registered Recruitments</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Role</th>
                                <th>Description</th>
                                <th>Qualification</th>
                                <th>Skills</th>
                                <th>Salary</th>
                                <th>Type</th>
                                <th>End Date</th>
                                <th>Location</th>
                                <th>File</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $canDelete = has_right_permission(config('enums.system_modules')['HR Management']['recruitment'], config('enums.system_permissions')['delete']);
                                $canEdit = has_right_permission(config('enums.system_modules')['HR Management']['recruitment'], config('enums.system_permissions')['edit']);
                            @endphp
                            @foreach($recruitments as $key => $recruitment)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$recruitment->role }} </td>
                                    <td>{{ substr($recruitment->description, 0, 50).'...' }}</td>
                                    <td>{{ substr($recruitment->qualifications, 0, 50).'...'}}</td>
                                    <td>{{ substr($recruitment->desired_skills, 0, 50).'...' }}</td>
                                    <td>{{$recruitment->salary_range }}</td>
                                    <td>{{$recruitment->employment_type }}</td>
                                    <td>{{$recruitment->end_date }}</td>
                                    <td>{{$recruitment->location }}</td>
                                    <td>
                                        @if($recruitment->file)
                                            <a href="{{$recruitment->file }}">File</a>
                                        @endif</td>
                                    <td>
                                        <div class="btn-group dropdown">
                                            <button type="button" class="btn btn-default dropdown-toggle btn-sm"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu">
                                                @if($canEdit)
                                                    <a class="text-info dropdown-item"
                                                       href="{{ route('hr.recruitments.detail', $recruitment->id)}}">
                                                        <i class="fa fa-edit"></i>Edit</a>
                                                @endif
                                                <a class="text-success dropdown-item"
                                                   href="{{ route('hr.recruitment.applications',  $recruitment->id) }}">
                                                    <i class="fa fa-eye"></i>View Applications</a>
                                                @if($canEdit)
                                                    @METHOD('DELETE')
                                                    <a onclick="return confirm('Sure to Delete?')"
                                                       href="{{ route('hr.recruitment.delete',  $recruitment->id) }}"
                                                       class="text-danger dropdown-item">
                                                        <i class="fa fa-trash-alt"></i>Delete</a>
                                                    <a onclick="return confirm('Sure to Close?')"
                                                       href="{{ route('hr.recruitment.close',  $recruitment->id) }}"
                                                       class="text-danger dropdown-item">
                                                        <i class="fa fa-trash-alt"></i>Close</a>
                                                @endif
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
