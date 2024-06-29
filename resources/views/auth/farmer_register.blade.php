@extends('layout.master-mini')
@push('plugin-styles')
    @toastr_css
    <link rel="stylesheet" href=" {{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush
@section('content')

    <div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one"
         style="background-image: url({{ url('assets/images/auth/login_1.jpg') }}); background-size: cover;">
        <div class="row w-100">
            <div class="col-lg-10 mx-auto">
                <div class="auto-form-wrapper">
                    <h4 class="card-title">Register as a Farmer</h4>
                    <hr class="mb-5">
                    <form method="POST" action="{{ route('farmer.register') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="f_name">First Name</label>
                                <input type="text" name="f_name"
                                       class="form-control {{ $errors->has('f_name') ? ' is-invalid' : '' }}"
                                       id="f_name" placeholder="John" value="{{ old('f_name')}}" required>
                                @if ($errors->has('f_name'))
                                    <span class="help-block text-danger">
                                    <strong>{{ $errors->first('f_name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="o_name">Other Names</label>
                                <input type="text" name="o_names" value="{{ old('o_names')}}"
                                       class="form-control {{ $errors->has('o_names') ? ' is-invalid' : '' }}"
                                       id="o_name" placeholder="Doe" required>
                                @if ($errors->has('o_names'))
                                    <span class="help-block text-danger">
                                    <strong>{{ $errors->first('o_names')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="u_name">User Name</label>
                                <input type="text" name="u_name"
                                       class="form-control {{ $errors->has('u_name') ? ' is-invalid' : '' }}"
                                       id="u_name" placeholder="j_doe" value="{{ old('u_name')}}" required>
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
                                       id="user_email" placeholder="johndoe@abc.com" value="{{ old('user_email')}}"
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
                                        <input class="form-check-input" type="radio" name="gender" id="Male"
                                               value="M" @if( old('gender') == "M") checked @endif>
                                        Male
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="Female"
                                               value="F" @if( old('gender') == "F") checked @endif>
                                        Female
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="Other"
                                               value="X" @if( old('gender') == "X") checked @endif>
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
                                       class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}"
                                       id="dob" value="{{ old('dob')}}"
                                       required>

                                @if ($errors->has('dob'))
                                    <span class="help-block text-danger">
                                    <strong>{{ $errors->first('dob')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="country_id">Country</label>
                                <select name="country_id" id="country_id"
                                        class=" form-control select2bs4 {{ $errors->has('country_id') ? ' is-invalid' : '' }}">
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}"> {{ $country->name }}</option>
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
                                       class="form-control {{ $errors->has('county') ? ' is-invalid' : '' }}"
                                       id="county" placeholder="Nairobi" value="{{ old('county')}}"
                                       required>

                                @if ($errors->has('county'))
                                    <span class="help-block text-danger">
                                    <strong>{{ $errors->first('county')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">

                                    <x-location-picker label="Location" name="location"
                                                       :cooperativeId="$coopId"/>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="id_no">Id No./Passport</label>
                                <input type="text" name="id_no"
                                       class="form-control  {{ $errors->has('id_no') ? ' is-invalid' : '' }}"
                                       value="{{ old('id_no')}}" id="id_no" placeholder="12345678" required>
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
                                       id="phone_no" placeholder="07..." value="{{ old('phone_no')}}" required>
                                @if ($errors->has('phone_no'))
                                    <span class="help-block text-danger">
                                    <strong>{{ $errors->first('phone_no')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="route_id">Route</label>
                                <select name="route_id" id="route_id"
                                        class=" form-control select2bs4 {{ $errors->has('route_id') ? ' is-invalid' : '' }}">
                                    @foreach($routes as $route)
                                        <option value="{{$route->id}}"> {{ $route->name }}</option>
                                    @endforeach

                                    @if ($errors->has('route_id'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('route_id')  }}</strong>
                                </span>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="bank_account">Bank Account No. </label>
                                <input type="text" name="bank_account"
                                       class="form-control {{ $errors->has('bank_account') ? ' is-invalid' : '' }}"
                                       id="bank_account" placeholder="235965..."
                                       value="{{ old('bank_account')}}" required>

                                @if ($errors->has('bank_account'))
                                    <span class="help-block text-danger">
                                    <strong>{{ $errors->first('bank_account')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="bank_id">Bank</label>
                                <select name="bank_id" id="bank_id"
                                        class=" form-control select2bs4 {{ $errors->has('bank_id') ? ' is-invalid' : '' }}">
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
                                        class=" form-control select2bs4 {{ $errors->has('bank_branch_id') ? ' is-invalid' : '' }}">

                                </select>
                                @if ($errors->has('bank_branch_id'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('bank_branch_id')  }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="customer_type">Customer Type</label>
                                <select name="customer_type" id="customer_type"
                                        class=" form-control select2bs4 {{ $errors->has('customer_type') ? ' is-invalid' : '' }}">

                                    <option value=""></option>
                                    @foreach(config('enums.farmer_customer_types') as $key => $type)
                                    <option value="{{$key}}">{{$type}}</option>
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
                                        class=" form-control select2bs4 {{ $errors->has('products') ? ' is-invalid' : '' }}">
                                    @foreach($products as $product)
                                        <option value="{{$product->id}}"> {{ $product->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('products'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('products')  }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="farm_size">Farm Size (<small>Acres</small>)</label>
                                <input type="number" name="farm_size"
                                       class="form-control {{ $errors->has('farm_size') ? ' is-invalid' : '' }}"
                                       id="farm_size" placeholder="10" value="{{ old('farm_size')}}"
                                       required>

                                @if ($errors->has('farm_size'))
                                    <span class="help-block text-danger">
                                    <strong>{{ $errors->first('farm_size')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="password">Password</label>
                                <input type="password" name="password"
                                       class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       id="password"
                                       value="{{ old('password')}}" required>

                                @if ($errors->has('password'))
                                    <span class="help-block text-danger">
                                    <strong>{{ $errors->first('password')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="c_password">Confirm Password</label>
                                <input type="password" name="c_password"
                                       class="form-control {{ $errors->has('c_password') ? ' is-invalid' : '' }}"
                                       id="c_password"
                                       value="{{ old('c_password')}}" required>

                                @if ($errors->has('c_password'))
                                    <span class="help-block text-danger">
                                    <strong>{{ $errors->first('c_password')  }}</strong>
                                </span>
                                @endif
                            </div>

                        </div>

                        <div class="form-row">
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <button type="submit" class="btn btn-primary btn-fw btn-block">Register</button>
                            </div>
                        </div>

                        <div class="form-group d-flex">
                            <a href="{{ route('password.request') }}" class="text-small forgot-password text-black">Forgot
                                Password</a>
                            <span class="text-small forgot-password pl-1 pr-1">|</span>
                            <a href="{{ route('login') }}"
                               class="text-small forgot-password text-black pl-1">Login</a>
                        </div>

                    </form>
                </div>
                <p class="footer-text text-center mt-5">copyright © @php echo date('Y') @endphp Shamba Equity. All
                    rights reserved.</p>
            </div>
        </div>
    </div>

@endsection
@push('plugin-scripts')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2();

        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });
    </script>
    @toastr_js
    @toastr_render
@endpush
@push('custom-scripts')
    <script>

        $("#bank_id").change(() => {
            $("#bank_branch_id").empty()
            const bank = $("#bank_id").val();
            let url = '{{ route('bank-branches',":bank_id") }}';
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
