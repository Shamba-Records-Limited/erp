@extends('layouts.app')

@push('plugin-styles')
<style>
.imageHolder {
    height: 250px;
    width: 250px;
}

.imageHolder img {
    max-width: 250px;
    max-height: 250px;
    min-width: 250px;
    min-height: 250px;
}
</style>

@endpush

@php
$coopId = Auth::user()->cooperative_id;
$location = $farmer->location_id;
@endphp

@section('content')

@if(has_right_permission(config('enums.system_modules')['Farmer CRM']['farmers'],
config('enums.system_permissions')['edit']))
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div>
                    <form action="{{ route('cooperative.farmer.profile.update', $farmer->user->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-12">
                                <h4 class="mb-3">Farmer Details</h6>
                            </div>

                            @if($farmer->user->profile_picture)
                            <div class="form-group col-12">
                                <div class="imageHolder pl-2">
                                    <img src="{{url('storage/'.$farmer->user->profile_picture)}}" />
                                </div>
                            </div>
                            @endif
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="f_name">First Name</label>
                                <input type="text" name="f_name"
                                    class="form-control {{ $errors->has('f_name') ? ' is-invalid' : '' }}" id="f_name"
                                    placeholder="John" value="{{ $farmer->user->first_name}}" required>
                                @if ($errors->has('f_name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('f_name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="o_name">Other Names</label>
                                <input type="text" name="o_names" value="{{ $farmer->user->other_names }}"
                                    class="form-control {{ $errors->has('o_names') ? ' is-invalid' : '' }}" id="o_name"
                                    placeholder="Doe" required>
                                @if ($errors->has('o_names'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('o_names')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="u_name">User Name</label>
                                <input type="text" name="u_name"
                                    class="form-control {{ $errors->has('u_name') ? ' is-invalid' : '' }}" id="u_name"
                                    placeholder="j_doe" value="{{ $farmer->user->username }}" required>
                                @if ($errors->has('u_name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('u_name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="user_email">Email</label>
                                <input type="email" name="user_email"
                                    class="form-control {{ $errors->has('user_email') ? ' is-invalid' : '' }}"
                                    id="user_email" placeholder="johndoe@abc.com" value="{{ $farmer->user->email}}"
                                    required>
                                @if ($errors->has('user_email'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('user_email')  }}</strong>
                                </span>
                                @endif
                            </div>


                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="">Gender</label>
                                <div class="form-row">
                                    <div class="form-check form-check-inline ml-2">
                                        <input class="form-check-input" type="radio" name="gender" id="Male" value="M"
                                            @if( $farmer->gender == "M") checked @endif>
                                        Male
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="Female" value="F"
                                            @if( $farmer->gender == "F") checked @endif>
                                        Female
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="Other" value="X"
                                            @if( $farmer->gender == "X") checked @endif>
                                        Other
                                    </div>
                                </div>

                                @if ($errors->has('gender'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('gender')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="dob">Date of Birth</label>
                                <input type="date" name="dob"
                                    class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" id="dob"
                                    value="{{ $farmer->dob}}" required>

                                @if ($errors->has('dob'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('dob')  }}</strong>
                                </span>
                                @endif
                            </div>


                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="country_id">Country</label>
                                <select name="country_id" id="country_id"
                                    class=" form-control form-select {{ $errors->has('country_id') ? ' is-invalid' : '' }}">
                                    @foreach($countries as $country)
                                    <option value="{{$country->id}}"
                                        {{ $country->id == $farmer->country_id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('country_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('country_id')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="county">County/State/Province</label>
                                <input type="text" name="county"
                                    class="form-control {{ $errors->has('county') ? ' is-invalid' : '' }}" id="county"
                                    placeholder="Nairobi" value="{{ $farmer->county }}" required>

                                @if ($errors->has('county'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('county')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <x-location-picker label="Location" name="location" :cooperativeId="$coopId"
                                    :selectedValue="$location" />
                            </div>


                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="id_no">Id No./Passport</label>
                                <input type="text" name="id_no"
                                    class="form-control  {{ $errors->has('id_no') ? ' is-invalid' : '' }}"
                                    value="{{ $farmer->id_no}}" id="id_no" placeholder="12345678" required>
                                @if ($errors->has('id_no'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('id_no')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="phone_no">Phone No.</label>
                                <input type="text" name="phone_no"
                                    class="form-control  {{ $errors->has('phone_no') ? ' is-invalid' : '' }}"
                                    id="phone_no" placeholder="07..." value="{{$farmer->phone_no}}" required>
                                @if ($errors->has('phone_no'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('phone_no')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="route_id">Route</label>
                                <select name="route_id" id="route_id"
                                    class=" form-control form-select {{ $errors->has('route_id') ? ' is-invalid' : '' }}">
                                    @foreach($routes as $route)
                                    <option value="{{$route->id}}"
                                        {{ $route->id == $farmer->route_id ? 'selected' : '' }}>
                                        {{ $route->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('route_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('route_id')  }}</strong>
                                </span>
                                @endif
                            </div>


                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="bank_account">Bank Account No. </label>
                                <input type="text" name="bank_account"
                                    class="form-control {{ $errors->has('bank_account') ? ' is-invalid' : '' }}"
                                    id="bank_account" placeholder="235965..." value="{{ $farmer->bank_account }}"
                                    required>

                                @if ($errors->has('bank_account'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('bank_account')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="bank_id">Bank</label>
                                <select name="bank_id" id="bank_id" class=" form-control form-select
                                                 {{ $errors->has('bank_id') ? ' is-invalid' : '' }}"
                                    onchange="loadBankBranches('bank_id','bank_branch_id', '{{$farmer->bank_branch_id }}')">
                                    <option value="">---Select Bank---</option>
                                    @foreach($banks as $bank)
                                    <option value="{{$bank->id}}"
                                        {{$bank->id == $farmer->bank_branch->bank_id ? 'selected' : ''}}>
                                        {{ $bank->name }}</option>
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
                                <label for="member_no">Member No.</label>
                                <input type="text" name="member_no"
                                    class="form-control {{ $errors->has('member_no') ? ' is-invalid' : '' }}"
                                    id="member_no" placeholder="123" value="{{ $farmer->member_no}}" required>

                                @if ($errors->has('member_no'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('member_no')  }}</strong>
                                </span>
                                @endif
                            </div>


                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="customer_type">Customer Type</label>
                                <select name="customer_type" id="customer_type"
                                    class=" form-control form-select {{ $errors->has('customer_type') ? ' is-invalid' : '' }}">

                                    <option value=""></option>
                                    @foreach(config('enums.farmer_customer_types') as $key => $type)
                                    <option value="{{$key}}" {{ $key == $farmer->customer_type ? 'selected' : '' }}>
                                        {{$type}}
                                    </option>
                                    @endforeach

                                </select>
                                @if ($errors->has('customer_type'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('customer_type')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="products">Products</label>
                                <select name="products[]" multiple="multiple" id="products"
                                    class=" form-control form-select {{ $errors->has('products') ? ' is-invalid' : '' }}">
                                    @foreach($products as $product)
                                    <option value="{{$product->id}}"
                                        {{ in_array($product->id, $farmer->user->products()->pluck('product_id')->toArray()) ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('products'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('products')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="farm_size">Farm Size
                                    (<small>Acres</small>)</label>
                                <input type="number" name="farm_size"
                                    class="form-control {{ $errors->has('farm_size') ? ' is-invalid' : '' }}"
                                    id="farm_size" placeholder="10" value="{{$farmer->farm_size}}" required>

                                @if ($errors->has('farm_size'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('farm_size')  }}</strong>
                                </span>
                                @endif
                            </div>


                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="mainImage">Profile Picture</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('profile_picture') is-invalid @enderror"
                                            id="profile_picture" name="profile_picture"
                                            value="{{ old('profile_picture') }}">
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
                                <button type="submit" class="btn btn-primary btn-fw btn-block">
                                    Update
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

@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
const loadBankBranches = (parentId, targetId, selectedValue) => {
    $("#" + targetId).empty()

    const bank = $("#" + parentId).val();
    let url = '{{ route('
    cooperative.farmer.bank_branch.get ',":bank_id") }}';
    url = url.replace(':bank_id', bank);
    let htmlCode = '';
    axios.post(url).then(res => {
        const data = res.data
        htmlCode += `<option value="">---Select Bank Branch---</option>`;
        data.forEach(d => {
            htmlCode += `<option value="${d.id}"  ${selectedValue == d.id ? 'selected'
                : ''}>${d.name}</option>`;
        })

        $("#" + targetId).append(htmlCode)
    }).catch(() => {
        htmlCode += `<option value="">---Select Bank Branch---</option>`;
        $("#" + targetId).append(htmlCode);
    })
}

window.onload = function() {
    loadBankBranches('bank_id', 'bank_branch_id', '{{ $farmer->bank_branch_id }}')
}

$('#profile_picture').change(function() {
    previewImage(this, 'picturePreview', 'imagePreviewContainer');
});
</script>
@endpush