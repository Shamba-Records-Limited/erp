@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="" id="addRecruitmentAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Update Recruitment</h4>
                            </div>
                        </div>

                        <form action="{{ route('hr.recruitment.edit') }}" method="post">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="role">Position</label>
                                    <input type="hidden" name="id" value="{{ $id }}" />
                                    <select name="role" id="role"
                                            class=" form-control select2bs4 {{ $errors->has('role') ? ' is-invalid' : '' }}">
                                            <option value="{{ $recruitment->role }}"> {{ $recruitment->role }}</option>
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
                                           id="description" placeholder="Say more about the role..." required>
                                           {{ $recruitment->description}}
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
                                           id="desired_skills" placeholder="People Management" required>
                                           {{ $recruitment->desired_skills}} 
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
                                           id="qualifications" placeholder="Bachelors Degree" required>
                                           {{ $recruitment->qualifications}}
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
                                            <option value="{{ $recruitment->employment_type }}">{{ $recruitment->employment_type }}</option>
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
                                           value="{{ $recruitment->salary_range }}" id="salary_range" placeholder="$1000-$2500"
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
                                           value="{{ $recruitment->location}}" id="location" placeholder="Uplands"
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
                                           value="{{ old('file')}}" id="file" placeholder="File">
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
                                           value="{{ $recruitment->end_date }}" id="end_date" placeholder="End Date"
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
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Submit</button>
                                </div>
                            </div>
                        </form>
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