@extends('layouts.app')

@push('plugin-styles')
@endpush


@section('content')
@php
$gender_options = config('enums.employee_configs')['gender'];
@endphp
<div class="card">
    <div class="card-body">
        <div class="card-title">Customer Details</div>
        <form action="{{route('miller-admin.inventory-auction.update-customer-details')}}" method="post">
            @csrf
            @method('PUT')
            <input type="hidden" name="customer_id" value="{{$customer->id}}">
            <div class="row">
                <div class="form-group col-lg-4 col-md-6 col-12">
                    <label for="title">Customer Type</label>
                    <select name="customer_type" id="customer_type" class=" form-control select2bs4 {{ $errors->has('customer_type') ? ' is-invalid' : '' }}">
                        <option value=""> -Select Customer Type-</option>
                        <option value="Individual" @if($customer->customer_type == 'Individual') selected @endif>Individual</option>
                        <option value="Company" @if($customer->customer_type == 'Company') selected @endif>Company</option>
                    </select>

                    @if ($errors->has('customer_type'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('customer_type')  }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group col-lg-4 col-md-6 col-12">
                    <label for="title">Title</label>
                    <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" placeholder="e.g. Mr, Mrs, Miss..." name="title" value="{{old('title',$customer->title)}}">

                    @if ($errors->has('title'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group col-lg-4 col-md-6 col-12">
                    <label for="name">Full Name</label>
                    <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" placeholder="Customer full name" name="name" value="{{old('name',$customer->name)}}">

                    @if ($errors->has('name'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group col-lg-4 col-md-6 col-12">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" class=" form-control select2bs4 {{ $errors->has('gender') ? ' is-invalid' : '' }}">
                        <option value=""> -Select Gender-</option>
                        @foreach($gender_options as $key => $option)
                        <option value="{{$option}}" @if($option==old('gender', $customer->gender )) selected @endif> {{ $option}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('gender'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('gender')  }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group col-lg-4 col-md-6 col-12">
                    <label for="email">Email</label>
                    <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" placeholder="Enter Email" name="email" value="{{old('email',$customer->email)}}">

                    @if ($errors->has('email'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group col-lg-4 col-md-6 col-12">
                    <label for="phone_number">Phone Number</label>
                    <input type="phone_number" class="form-control {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" id="phone_number" placeholder="2547...." name="phone_number" value="{{old('phone_number',$customer->phone_number)}}">

                    @if ($errors->has('phone_number'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('phone_number') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group col-lg-4 col-md-6 col-12">
                    <label for="address">Address</label>
                    <input type="address" class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" id="address" placeholder="Enter Address" name="address" value="{{old('address',$customer->address)}}">

                    @if ($errors->has('address'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('address') }}</strong>
                    </span>
                    @endif
                </div>

                <div>
                    <button class="btn btn-outline-primary" name="save">Save</button>
                    @if(is_null($customer->published_at))
                    <button class="btn btn-outline-success" name="save_and_publish">Save and Publish</button>
                    <a class="btn btn-outline-warning" onclick="return confirm('Are you sure you want to discard this draft?')">Discard Draft</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush