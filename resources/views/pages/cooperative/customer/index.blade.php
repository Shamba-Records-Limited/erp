@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Customer Management']['crm'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                                data-toggle="collapse"
                                data-target="#addFarmerAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addFarmerAccordion">
                            <span class="mdi mdi-plus"></span>Add Customer
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif "
                             id="addFarmerAccordion">
                            <form action="{{ route('customer.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <h6 class="mb-3">Customer Type</h6>
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="customer_type">Customer Type</label>
                                        <select name="customer_type" id="customer_type"
                                                class=" form-control select2bs4 {{ $errors->has('customer_type') ? ' is-invalid' : '' }}"
                                                onchange="showTitle('customer_type', 'title-select')"
                                        >
                                            <option value=""></option>
                                            @foreach(config('enums')["customer_types"][0] as $k=>$v)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('customer_type'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('customer_type')  }}</strong>
                                            </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-1 col-md-6 col-12 d-none"
                                         id="title-select">
                                        <label for="title">Title</label>
                                        <select name="title" id="title"
                                                class=" form-control select2bs4 {{ $errors->has('title') ? ' is-invalid' : '' }}">
                                            <option value=""></option>
                                            @foreach(config('enums')["titles"][0] as $title)
                                                <option value="{{$title}}">{{$title}}</option>
                                            @endforeach

                                        </select>
                                        @if ($errors->has('title'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('title')  }}</strong>
                                            </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="name">Name</label>
                                        <input type="text" name="name"
                                               class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               id="name" placeholder="John" value="{{ old('name')}}"
                                               required>
                                        @if ($errors->has('name'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('name')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="email">Email</label>
                                        <input type="email" name="email"
                                               class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                               id="email" placeholder="johndoe@abc.com"
                                               value="{{ old('email')}}"
                                               required>
                                        @if ($errors->has('email'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('email')  }}</strong>
                                            </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-2 col-md-6 col-12 {{ $errors->has('gender') ? '' : 'd-none' }}" id="genderSelector">
                                        <label for="">Gender</label>
                                        <div class="form-row">
                                            <div class="form-check form-check-inline ml-2">
                                                <input class="form-check-input" type="radio"
                                                       name="gender" id="Male"
                                                       value="M"
                                                       @if( old('gender') == "M") checked @endif>
                                                M
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                       name="gender" id="Female"
                                                       value="F"
                                                       @if( old('gender') == "F") checked @endif>
                                                F
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                       name="gender" id="Other"
                                                       value="X"
                                                       @if( old('gender') == "X") checked @endif>
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
                                        <label for="phone_no">Phone No.</label>
                                        <input type="text" name="phone_no"
                                               class="form-control  {{ $errors->has('phone_no') ? ' is-invalid' : '' }}"
                                               id="phone_no" placeholder="07..."
                                               value="{{ old('phone_no')}}" required>
                                        @if ($errors->has('phone_no'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('phone_no')  }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="location">Location</label>
                                        <input type="text" name="location"
                                               class="form-control  {{ $errors->has('location') ? ' is-invalid' : '' }}"
                                               id="location" placeholder="Koleni"
                                               value="{{ old('location')}}" required>
                                        @if ($errors->has('location'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('location')  }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="address">Address</label>
                                        <input type="text" name="address"
                                               class="form-control  {{ $errors->has('address') ? ' is-invalid' : '' }}"
                                               id="address" placeholder="Naivas, Thika road"
                                               value="{{ old('phone_no')}}" required>
                                        @if ($errors->has('address'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('address')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                                <hr class="mt-1 mb-1">
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
                    @if(has_right_permission(config('enums.system_modules')['Customer Management']['crm'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('cooperative.customers.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('cooperative.customers.download','xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('cooperative.customers.download', 'pdf') }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Our Customers</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name/Company</th>
                                <th>Type</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Location</th>
                                <th>Address</th>
                                {{--                                <th>Last Visit</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @php $count = 0 @endphp
                            @foreach($customers as $customer)
                                @php $isCompany = ($customer->customer_type == \App\Customer::CUSTOMER_TYPE_COMPANY)  @endphp
                                <tr>
                                    <td>{{++$count }}</td>
                                    <td>{{$customer->title." ".ucwords(strtolower($customer->name))}}</td>
                                    <td>{{ config('enums')["customer_types"][0][$customer->customer_type] }}</td>
                                    <td>{{ !$isCompany ? $customer->gender == "M" ? "Male": ($customer->gender == "F" ? "Female" : "Other") : ""}}</td>
                                    <td>{{$customer->email}} </td>
                                    <td>{{$customer->phone_number}} </td>
                                    <td>{{$customer->location}} </td>
                                    <td>{{$customer->address}} </td>
                                    {{--                                    <td>{{\Illuminate\Support\Carbon::parse($customer->last_visit)->diffForHumans()}} </td>--}}
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
      const showTitle = (parentId, targetId) => {
        const value = $('#' + parentId).val();
        if (value !== null || value !== "") {
          if (value == '{{ \App\Customer::CUSTOMER_TYPE_INDIVIDUAL }}') {
            $("#" + targetId).removeClass('d-none')
            $("#genderSelector").removeClass('d-none')
          } else {
            $("#" + targetId).addClass('d-none')
            $("#genderSelector").addClass('d-none')
          }
        }
      }
    </script>
@endpush
