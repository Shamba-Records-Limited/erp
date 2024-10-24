@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
@php
$gender_options = config('enums.employee_configs')['gender'];
$marital_status_options = config('enums.employee_configs')['marital_status'];
@endphp
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <form action="{{ route('admin.users.make-employee', $user->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <h6 class="mb-3">Make User Employee</h6>
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="cooperative">Cooperative</label>
                            <input type="text" name="cooperative" class="form-control {{ $errors->has('cooperative') ? ' is-invalid' : '' }}" id="cooperative" placeholder="John" value="{{ $user->coop_name }}" required readonly>
                            @if ($errors->has('cooperative'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('cooperative')  }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" id="first_name" placeholder="John" value="{{ $user->first_name }}" required readonly>
                            @if ($errors->has('first_name'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('first_name')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="other_name">Other Names</label>
                            <input type="text" name="other_names" value="{{ $user->other_names }}" class="form-control {{ $errors->has('other_names') ? ' is-invalid' : '' }}" id="other_name" placeholder="Doe" required readonly>
                            @if ($errors->has('other_names'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('other_names')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="user_name">User Name</label>
                            <input type="text" name="user_name" class="form-control {{ $errors->has('user_name') ? ' is-invalid' : '' }}" id="user_name" placeholder="j_doe" value="{{ $user->username }}" required readonly>
                            @if ($errors->has('user_name'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('user_name')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" placeholder="johndoe@abc.com" value="{{ $user->email }}" required readonly>
                            @if ($errors->has('email'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('email')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="country">Country</label>
                            <select name="country" id="country" class=" form-control form-select {{ $errors->has('country') ? ' is-invalid' : '' }}">
                                <option value=""> -Select Country-</option>
                                @foreach($countries as $country)
                                <option value="{{$country->id}}"> {{ $country->name }}</option>
                                @endforeach

                                @if ($errors->has('country'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('country')  }}</strong>
                                </span>
                                @endif
                            </select>
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="county">County</label>
                            <input type="text" name="county" class="form-control {{ $errors->has('county') ? ' is-invalid' : '' }}" id="county" placeholder="Nairobi" value="{{ old('county')}}" required>

                            @if ($errors->has('county'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('county')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="area_of_residence">Area of Residence</label>
                            <input type="text" name="area_of_residence" class="form-control  {{ $errors->has('area_of_residence') ? ' is-invalid' : '' }}" id="area_of_residence" placeholder="Karen" value="{{ old('area_of_residence')}}" required>

                            @if ($errors->has('area_of_residence'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('area_of_residence')  }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="id_no">Id No./Passport</label>
                            <input type="text" name="id_no" class="form-control  {{ $errors->has('id_no') ? ' is-invalid' : '' }}" value="{{ old('id_no')}}" id="id_no" placeholder="12345678">
                            @if ($errors->has('id_no'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('id_no')  }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="dob">D.o.B</label>
                            <input type="date" name="dob" class="form-control  {{ $errors->has('dob') ? ' is-invalid' : '' }}" value="{{ old('dob')}}" id="dob">
                            @if ($errors->has('dob'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('dob')  }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" class=" form-control form-select {{ $errors->has('gender') ? ' is-invalid' : '' }}">
                                <option value=""> -Select Gender-</option>
                                @foreach($gender_options as $key => $option)
                                <option value="{{$option}}"> {{ $option}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('gender'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('gender')  }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="marital_status">Marital Status</label>
                            <select name="marital_status" id="marital_status" class=" form-control form-select {{ $errors->has('marital_status') ? ' is-invalid' : '' }}">
                                <option value=""> -Select Marital Status-</option>
                                @foreach($marital_status_options as $key => $option)
                                <option value="{{$option}}"> {{ $option}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('marital_status'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('marital_status')  }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="phone_no">Phone No.</label>
                            <input type="text" name="phone_no" class="form-control  {{ $errors->has('phone_no') ? ' is-invalid' : '' }}" id="phone_no" placeholder="2547..." value="{{ old('phone_no')}}">
                            @if ($errors->has('phone_no'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('phone_no')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="bank_account">Bank Account No. </label>
                            <input type="text" name="bank_account" class="form-control {{ $errors->has('bank_account') ? ' is-invalid' : '' }}" id="bank_account" placeholder="235965..." value="{{ old('bank_account')}}">

                            @if ($errors->has('bank_account'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('bank_account')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="bank_account_name">Bank Account Name. </label>
                            <input type="text" name="bank_account_name" class="form-control {{ $errors->has('bank_account_name') ? ' is-invalid' : '' }}" id="bank_account_name" placeholder="John Doe..." value="{{ old('bank_account_name')}}">

                            @if ($errors->has('bank_account_name'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('bank_account_name')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="bank_id">Bank</label>
                            <select name="bank_id" id="bank_id" class=" form-control form-select {{ $errors->has('bank_id') ? ' is-invalid' : '' }}">
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
                            <select name="bank_branch_id" id="bank_branch_id" class=" form-control form-select {{ $errors->has('bank_branch_id') ? ' is-invalid' : '' }}">

                            </select>
                            @if ($errors->has('bank_branch_id'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('bank_branch_id')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="kra">KRA PIN</label>
                            <input type="text" name="kra" class="form-control {{ $errors->has('kra') ? ' is-invalid' : '' }}" id="kra" placeholder="A236...Z" value="{{ old('kra')}}">

                            @if ($errors->has('kra'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('kra')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="nssf">NSSF</label>
                            <input type="text" name="nssf" class="form-control {{ $errors->has('nssf') ? ' is-invalid' : '' }}" id="nssf" placeholder="1234567" value="{{ old('nssf')}}">

                            @if ($errors->has('nssf'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('nssf')  }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="nhif">NHIF</label>
                            <input type="text" name="nhif" class="form-control {{ $errors->has('nhif') ? ' is-invalid' : '' }}" id="nhif" placeholder="1234567" value="{{ old('nhif')}}">

                            @if ($errors->has('nhif'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('nhif')  }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="department">Department</label>
                            <select name="department" id="department" class=" form-control form-select {{ $errors->has('department') ? ' is-invalid' : '' }}">
                                <option value="">-Select Department-</option>
                                @foreach($departments as $department)
                                <option value="{{$department->id}}"> {{ $department->name }}</option>
                                @endforeach


                            </select>
                            @if ($errors->has('department'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('department')  }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="employee_number">Employee No.</label>
                            <input type="text" name="employee_number" class="form-control {{ $errors->has('employee_number') ? ' is-invalid' : '' }}" id="employee_number" placeholder="EM1234" value="{{ old('employee_number')}}">

                            @if ($errors->has('employee_number'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('employee_number')  }}</strong>
                            </span>
                            @endif
                        </div>


                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="employment_type">Employment Type</label>
                            <select name="employment_type" id="employment_type" class=" form-control form-select {{ $errors->has('employment_type') ? ' is-invalid' : '' }}">

                                @foreach($types as $type)
                                <option value="{{$type->id}}"> {{ ucwords(strtolower($type->type)) }}</option>
                                @endforeach

                            </select>
                            @if ($errors->has('employment_type'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('employment_type')  }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="position">Positions</label>
                            <select name="position" id="position" class=" form-control form-select {{ $errors->has('position') ? ' is-invalid' : '' }}">
                                @foreach($positions ?? '' as $position)
                                <option value="{{$position->id}}"> {{ $position->position }}</option>
                                @endforeach


                            </select>
                            @if ($errors->has('position'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('position')  }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="job_group">Job Group</label>
                            <input type="text" name="job_group" class="form-control {{ $errors->has('job_group') ? ' is-invalid' : '' }}" id="job_group" placeholder="C1" value="{{ old('job_group')}}" required>
                            @if ($errors->has('job_group'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('job_group') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="basic_salary">Basic Salary
                                ({{ Auth::user()->cooperative->currency}})</label>
                            <input type="text" name="basic_salary" class="form-control {{ $errors->has('basic_salary') ? ' is-invalid' : '' }}" id="basic_salary" placeholder="10000" value="{{ old('basic_salary')}}" required>
                            @if ($errors->has('basic_salary'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('basic_salary') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label for="mainImage">Profile Picture</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('profile_picture') is-invalid @enderror" id="profile_picture" name="profile_picture" value="{{ old('profile_picture') }}">
                                    <label class="custom-file-label" for="profile_picture">Image</label>
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
                                <img id="picturePreview" src="#" alt="pic" height="150px" width="150px" />
                            </div>
                        </div>

                    </div>
                    <hr class="mt-1 mb-1">
                    <div class="form-row">
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <button type="submit" class="btn btn-primary btn-fw btn-block">Apply
                            </button>
                        </div>
                    </div>
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
    $("#bank_id").change(() => {
        $("#bank_branch_id").empty()
        const bank = $("#bank_id").val();
        let url = '{{ route("bank_branches-by-bank",":bank_id") }}';
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
</script>
@endpush