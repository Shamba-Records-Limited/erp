@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

  @endphp
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                        <form action="{{ route('hr.employees.edit') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <h6 class="mb-3">Edit Employee Details</h6>
                                </div>

                                @if($employee->user->profile_picture)
                                    <div class="form-group col-12 mb-2">
                                        <div class="imageHolder pl-2">
                                            <img src="{{url('storage/'.$employee->user->profile_picture)}}"/>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="first_name">First Name:</label>
                                    <input type="text" class="form-control" value="{{ $employee->user->first_name }}" name="first_name"/>
                                    <input type="hidden" class="form-control" value="{{ $employee->user->id }}" name="user_id"/>
                                    @if ($errors->has('first_name'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('first_name')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="other_name">Other Names:</label>
                                    <input type="text" class="form-control" value="{{ $employee->user->other_names }}" name="other_names"/>
                                    <input type="hidden" class="form-control" value="{{ $employee->id }}" name="id"/>
                                    @if ($errors->has('other_names'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('other_names')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="user_name">User Name</label>
                                    <input type="text" class="form-control" value="{{ $employee->user->username }}" name="user_name"/>
                                    @if ($errors->has('user_name'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('user_name')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" value="{{ $employee->user->email }}" name="email"/>
                                    @if ($errors->has('email'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('email')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="country">Country</label>
                                    <select name="country" id="country"
                                            class=" form-control form-select {{ $errors->has('country') ? ' is-invalid' : '' }}">
                                        <option value="{{ $employee->country->id}}"> {{ $employee->country->name }}</option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}"> {{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('country'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('country')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="county">County</label>
                                    <input type="text" class="form-control" value="{{ $employee->county_of_residence }}" name="county"/>
                                    @if ($errors->has('county'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('county')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="area_of_residence">Area of Residence</label>
                                    <input type="text" class="form-control" value="{{ $employee->area_of_residence }}" name="area_of_residence"/>
                                    @if ($errors->has('area_of_residence'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('area_of_residence')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="id_no">Id No./Passport</label>
                                    <input type="text" class="form-control" value="{{ $employee->id_no }}" name="id_no"/>
                                    @if ($errors->has('id_no'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('id_no')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="dob">D.o.B</label>
                                    <input type="text" class="form-control" value="{{ $employee->dob }}" name="dob"/>
                                    @if ($errors->has('dob'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('dob')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="gender">Gender</label>
                                    <select name="gender" id="gender"
                                            class=" form-control form-select {{ $errors->has('gender') ? ' is-invalid' : '' }}">
                                            <option value="{{ $employee->gender }}"> {{ $employee->gender }}</option>
                                            <option value="Female"> Female</option>
                                            <option value="Male"> Male</option>
                                            <option value="Other"> Other</option>
                                    </select>
                                    @if ($errors->has('gender'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('gender')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="marital_status">Marital Status</label>
                                    <select name="marital_status" id="marital_status"
                                        class=" form-control form-select {{ $errors->has('marital_status') ? ' is-invalid' : '' }}">
                                        <option value="{{ $employee->marital_status }}"> {{ $employee->marital_status }}</option>
                                        <option value="Married"> Married</option>
                                        <option value="Divorced"> Divorced</option>
                                        <option value="Engaged"> Engaged</option>
                                        <option value="Single"> Single</option>
                                    </select>
                                    @if ($errors->has('marital_status'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('marital_status')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="phone_no">Phone No.</label>
                                    <input type="text" class="form-control" value="{{ $employee->phone_no }}" name="phone_no"/>
                                    @if ($errors->has('phone_no'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('phone_no')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="bank_id">Bank</label>
                                    <select name="bank_id" id="bank_id"
                                            class=" form-control form-select {{ $errors->has('bank_id') ? ' is-invalid' : '' }}">
                                        <option value="">---Select Bank---</option>
                                        @foreach($banks as $bank)
                                            <option value="{{$bank->id}}"> {{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('bank_id'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('bank_id')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="bank_branch_id">Bank Branch</label>
                                    <select name="bank_branch_id" id="bank_branch_id"
                                            class=" form-control form-select {{ $errors->has('bank_branch_id') ? ' is-invalid' : '' }}">

                                    </select>
                                    @if ($errors->has('bank_branch_id'))
                                        <span class="help-block text-danger">
                                        <strong>{{ $errors->first('bank_branch_id')  }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="bank_account">Bank Account No. </label>
                                    <input type="text" class="form-control" value="{{ $employee->bankDetails->account_number}}" name="bank_account"/>
                                    @if ($errors->has('bank_account'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('bank_account')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="bank_account_name">Bank Account Name. </label>
                                    <input type="text" class="form-control" value="{{ $employee->bankDetails->account_name }}" name="bank_account_name"/>
                                    @if ($errors->has('bank_account_name'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('bank_account_name')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="kra">KRA PIN</label>
                                    <input type="text" class="form-control" value="{{ $employee->kra }}" name="kra"/>
                                    @if ($errors->has('kra'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('kra')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="nssf">NSSF</label>
                                    <input type="text" class="form-control" value="{{ $employee->nssf_no }}" name="nssf"/>
                                    @if ($errors->has('nssf'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('nssf')  }}</strong>
                                        </span>
                                    @endif

                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="nhif">NHIF</label>
                                    <input type="text" class="form-control" value="{{ $employee->nhif_no }}" name="nhif"/>
                                    @if ($errors->has('nhif'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('nhif')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="employee_number">Employee No.</label>
                                    <input type="text" class="form-control" value="{{ $employee->employee_no }}" name="employee_number"/>
                                    @if ($errors->has('employee_number'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('employee_number')  }}</strong>
                                        </span>
                                    @endif
                                </div>
{{--                                <div class="form-group col-lg-3 col-md-6 col-12">--}}
{{--                                    <label for="employee_number">Employee Type</label>--}}
{{--                                    <input type="hidden" class="form-control" value="{{ $employee->employmentType->id}}" name="type_id"/>--}}
{{--                                    <select name="employment_type" id="employment_type"--}}
{{--                                            class=" form-control form-select {{ $errors->has('employment_type') ? ' is-invalid' : '' }}">--}}
{{--                                        <option value="{{ $employee->employmentType->employeeType->id }}">{{ $employee->employmentType->employeeType->type }}</option>--}}
{{--                                        @foreach($types as $type)--}}
{{--                                            <option value="{{$type->id}}"> {{ $type->type }}</option>--}}
{{--                                        @endforeach--}}
{{--                                        --}}
{{--                                    </select>--}}
{{--                                    @if ($errors->has('employment_type'))--}}
{{--                                        <span class="help-block text-danger">--}}
{{--                                            <strong>{{ $errors->first('employment_type')  }}</strong>--}}
{{--                                        </span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                                <div class="form-group col-lg-3 col-md-6 col-12">--}}
{{--                                    <label for="employee_number">Position</label>--}}
{{--                                    <input type="hidden" class="form-control" value="{{ $employee->position->id}}" name="position_id"/>--}}
{{--                                    <select name="position" id="position"--}}
{{--                                            class=" form-control form-select {{ $errors->has('position') ? ' is-invalid' : '' }}">--}}
{{--                                            <option value="{{$employee->position->position->id}}"> {{ $employee->position->position->position }}</option>--}}
{{--                                        @foreach($positions as $position)--}}
{{--                                            <option value="{{$position->id}}"> {{ $position->position }}</option>--}}
{{--                                        @endforeach--}}

{{--                                        --}}
{{--                                    </select>--}}
{{--                                    @if ($errors->has('position'))--}}
{{--                                        <span class="help-block text-danger">--}}
{{--                                            <strong>{{ $errors->first('position')  }}</strong>--}}
{{--                                        </span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                                <div class="form-group col-lg-3 col-md-6 col-12">--}}
{{--                                    <label for="employee_number">Department</label>--}}
{{--                                    --}}
{{--                                    <select name="department" id="department"--}}
{{--                                            class=" form-control form-select {{ $errors->has('department') ? ' is-invalid' : '' }}">--}}
{{--                                        <option value="{{ $employee->department->id }}">{{ $employee->department->name }}</option>--}}
{{--                                        @foreach($departments as $department)--}}
{{--                                            <option value="{{$department->id}}"> {{ $department->name }}</option>--}}
{{--                                        @endforeach--}}

{{--                                        --}}
{{--                                    </select>--}}
{{--                                    @if ($errors->has('department'))--}}
{{--                                        <span class="help-block text-danger">--}}
{{--                                            <strong>{{ $errors->first('department')  }}</strong>--}}
{{--                                        </span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}

{{--                                <div class="form-group col-lg-3 col-md-6 col-12">--}}
{{--                                    <label for="job_group">Job Group</label>--}}
{{--                                    <input type="text" name="job_group"--}}
{{--                                           class="form-control {{ $errors->has('job_group') ? ' is-invalid' : '' }}"--}}
{{--                                           id="job_group" value="{{$employee->employeeSalary ? $employee->employeeSalary->job_group : ''}}"--}}
{{--                                           required>--}}
{{--                                    @if ($errors->has('job_group'))--}}
{{--                                        <span class="help-block text-danger">--}}
{{--                                                <strong>{{ $errors->first('job_group') }}</strong>--}}
{{--                                            </span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="mainImage">Profile Picture</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file"
                                                   class="custom-file-input @error('profile_picture') is-invalid @enderror"
                                                   id="profile_picture" name="profile_picture"
                                                   value="{{ old('profile_picture') }}">
                                            <label class="custom-file-label"
                                                   for="profile_picture">Image</label>
                                        </div>

                                    </div>
                                </div>
                                @if ($errors->has('profile_picture'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('profile_picture')  }}</strong>
                                    </span>
                                @endif

                                <div class="d-none" id="imagePreviewContainer">
                                    <div class="imageHolder pl-2">
                                        <img id="picturePreview" src="#" alt="pic" height="150px" width="150px"/>
                                    </div>
                                </div>

                                <input type="hidden" name="bank_detail_id" value="{{ $employee->bankDetails->id}}">

                            </div>
                            <hr class="mt-1 mb-1">
                            <button type="submit" class="btn btn-primary btn-fw">Save</button>
                            <a href="{{ route('hr.employees.show') }}" class="btn btn-danger btn-fw pull-right">Cancel</a>
                        </form>
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

        $("#bank_id").change(() => {
          $("#bank_branch_id").empty()
          const bank = $("#bank_id").val();
          let url = '{{ route('bank_branches-by-bank',":bank_id") }}';
          url = url.replace(':bank_id', bank);
          let htmlCode = '';
          axios.post(url).then(res => {
            const data = res.data
            htmlCode += `<option value="">---Select Bank Branch---</option>`;
            data.forEach(d => {
              htmlCode += `<option value="${d.id}">${d.name}</option>`;
            })

            $("#bank_branch_id").append(htmlCode)
          }).catch(() => {
            htmlCode += `<option value="">---Select Bank Branch---</option>`;
            $("#bank_branch_id").append(htmlCode);
          })
        })

        $('#profile_picture').change(function () {
          previewImage(this, 'picturePreview', 'imagePreviewContainer');
        });

    </script>
@endpush
