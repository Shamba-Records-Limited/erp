@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse" data-target="#addComapnyAccordion" aria-expanded="@if ($errors->count() > 0) true @else false @endif" aria-controls="addComapnyAccordion"><span class="mdi mdi-plus"></span>Add Miller Branch
                </button>
                <div class="collapse @if($errors->count() > 0) show @endif" id="addComapnyAccordion">
                    <div class="row mt-5">
                        <div class="col-lg-12 grid-margin stretch-card col-12">
                            <h4>Register Miller Branch</h4>
                        </div>
                    </div>


                    <form action="{{ route('admin.miller-branches.add') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        {{ $errors }}
                        <div class="form-row">
                            <div class="form-group col-12">
                                <h6 class="mb-3">Miller Branch Details</h6>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="miller_id">Miller</label>
                                <select name="miller_id" id="miller_id" class="form-control select2bs4 {{ $errors->has('miller_id') ? ' is-invalid' : '' }}">
                                    <option value=""> -- Select Miller --</option>
                                    @foreach($millers as $miller)
                                    <option value="{{$miller->id}}">{{$miller->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('miller_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('miller_id')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="miller_name">Name</label>
                                <input type="text" name="miller_name" class="form-control {{ $errors->has('miller_name') ? ' is-invalid' : '' }}" id="miller_name" placeholder="ABC" value="{{ old('miller_name')}}" required>

                                @if ($errors->has('miller_name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('miller_name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="code">Code</label>
                                <input type="text" name="code" class="form-control  {{ $errors->has('code') ? ' is-invalid' : '' }}" id="code" placeholder="AB12#" value="{{ old('code')}}">

                                @if ($errors->has('code'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('code')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="location">Location</label>
                                <input type="text" name="location" class="form-control  {{ $errors->has('location') ? ' is-invalid' : '' }}" value="{{ old('location')}}" id="location" placeholder="Nairobi" required>
                                @if ($errors->has('location'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('location')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="address">Address</label>
                                <input type="text" name="address" class="form-control  {{ $errors->has('address') ? ' is-invalid' : '' }}" id="address" placeholder="Nairobi" value="{{ old('address')}}" required>
                                @if ($errors->has('address'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('address')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="county_id">Select County</label>
                                <select name="county_id" id="county_id" class=" form-control select2bs4 {{ $errors->has('county_id') ? ' is-invalid' : '' }}">
                                    <option value=""> -Select County-</option>
                                    @foreach($counties as $county)
                                    <option value="{{$county->id}}"> {{ $county->name }}</option>
                                    @endforeach

                                    @if ($errors->has('county_id'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('county_id')  }}</strong>
                                    </span>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="sub_county">Select Sub County</label>
                                <select data-subcounties="{{$sub_counties}}" name="sub_county_id" id="sub_county_id" class=" form-control select2bs4 {{ $errors->has('sub_county_id') ? ' is-invalid' : '' }}">
                                    <option value=""> -Select Sub County-</option>

                                    @if ($errors->has('sub_county_id'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('sub_county_id')  }}</strong>
                                    </span>
                                    @endif
                                </select>
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
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Registered Miller Branches</h4>
                <div class="table-responsive">
                    <table class="table table-hover dt">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Miller</th>
                                <th>Name</th>
                                <th>Sub County</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($miller_branches as $key => $branch)
                            <tr>
                                <td>{{++$key }}</td>
                                <td>{{$branch->miller_name}}</td>
                                <td>{{$branch->name }}</td>
                                <td>{{$branch->county_name}} - {{$branch->sub_county_name}}</td>
                                <td></td>
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
    function deleteCoop(id) {
        shouldDelete = confirm("Are you sure you want to delete this cooperative?")
        if (!shouldDelete) {
            return
        }

        window.location = "/admin/cooperative/setup/delete/" + id
    }
    $("#county_id").change(function(e) {
        $("#sub_county_id").value = "";
        $("#sub_county_id").empty();

        $("#sub_county_id").append("<option> -- Select Sub County -- </option>");

        let subCounties = JSON.parse($("#sub_county_id").attr("data-subcounties"))
        let filteredSubCounties = []
        for (let subCounty of subCounties) {
            console.log(subCounty)
            if (subCounty.county_id == e.target.value) {
                elem = `<option value='${subCounty.id}'>${subCounty.name}</option>`
                $("#sub_county_id").append(elem)
            }
        }
    });
</script>
@endpush