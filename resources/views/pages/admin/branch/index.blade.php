@extends('layouts.app')


@push('plugin-styles')

@endpush

@section('content')
@if(auth()->user()->hasRole('admin'))
<div class="header bg-custom-green pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class=" text-muted mb-0" style="font-size:1rem">Total Branches</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {{ is_array($branches) ? count($branches) : $branches->count() }}
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="mdi mdi-store stats-icon"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-warning mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
                                <span class="text-nowrap">Since yesterday</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class=" text-muted mb-0" style="font-size:1rem">Total Cooperatives</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {{ is_array($cooperatives) ? count($cooperatives) : $cooperatives->count() }}
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="mdi mdi-office-building stats-icon"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem"> Counties Covered</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                       {{ is_array($counties) ? count($counties) : $counties->count() }}
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                       <i class="mdi mdi-map-marker stats-icon"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 3.48%</span>
                                <span class="text-nowrap">Since last week</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem"> New Branches This Month</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                       {{ $branchesThisMonth }}
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                                       <i class="mdi mdi-calendar-clock stats-icon"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 3.48%</span>
                                <span class="text-nowrap">Since last week</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- End Stats Section -->



<!-- Cooperative Branch Distribution -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Branches per Cooperative</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Cooperative</th>
                                <th>Number of Branches</th>
                                <th>Counties Present</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $branchesCollection = collect($branches);
                            $branchesPerCoop = $branchesCollection->groupBy('cooperative_id');
                            @endphp
                            @foreach($cooperatives as $coop)
                            <tr>
                                <td>{{ $coop['name'] }}</td>
                                <td>
                                    <span class="badge badge-primary" style="font-size:14px">
                                        {{ $branchesPerCoop->get($coop['id'], collect())->count() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-info" style="font-size:17px">
                                        {{ $branchesPerCoop->get($coop['id'], collect())->unique('county_name')->count() }}
                                    </span>
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
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                    data-target="#addBranchAccordion" aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                    aria-controls="addBranchAccordion"><span class="mdi mdi-plus"></span>Add Branch
                </button>
                <div class="collapse @if ($errors->count() > 0) show @endif " id="addBranchAccordion">
                    <div class="row mt-5">
                        <div class="col-lg-12 grid-margin stretch-card col-12 mb-4">
                            <h3>Register Branch</h3>
                        </div>
                    </div>

                    <form action="{{ route('branches.add') }}" method="post">
                        @csrf
                        <!-- {{ $errors }} -->
                        <div class="form-row">
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="cooperative_id">Cooperative</label>
                                <select name="cooperative_id" id="cooperative_id"
                                    class="form-control form-select {{ $errors->has('cooperative_id') ? ' is-invalid' : '' }}">
                                    @foreach($cooperatives as $coop)
                                    <option value="{{$coop->id}}">{{$coop->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('cooperative_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('cooperative_id')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="productName">Name</label>
                                <input type="text" name="name"
                                    class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                    id="productName" placeholder="XYZ Branch" value="{{ old('name')}}" required>

                                @if ($errors->has('name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('name')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="code">Code</label>
                                <input type="text" name="code"
                                    class="form-control  {{ $errors->has('code') ? ' is-invalid' : '' }}" id="code"
                                    placeholder="AB12#" value="{{ old('code')}}">

                                @if ($errors->has('code'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('code')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="location">Location</label>
                                <input type="text" name="location"
                                    class="form-control  {{ $errors->has('location') ? ' is-invalid' : '' }}"
                                    value="{{ old('location')}}" id="location" placeholder="Uplands" required>
                                @if ($errors->has('location'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('location')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="main_product_id">Main Product</label>
                                <select name="main_product_id" id="main_product_id"
                                    class="form-control form-select {{ $errors->has('main_product_id') ? ' is-invalid' : '' }}"
                                    required>
                                    <option value="">-- Select Main Product --</option>
                                    @foreach($products as $product)
                                    <option value="{{$product->id}}" @if($product->id == old('main_product_id'))
                                        selected @endif>{{$product->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('main_product_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('main_product_id')  }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="county_id">Select County</label>
                                <select name="county_id" id="county_id"
                                    class=" form-control form-select {{ $errors->has('county_id') ? ' is-invalid' : '' }}">
                                    <option value=""> -Select County-</option>
                                    @foreach($counties as $county)
                                    <option value="{{$county->id}}" @if(!empty(old('county_id')) &&
                                        old('county_id')==$county->id) selected @endif> {{ $county->name }}</option>
                                    @endforeach

                                    @if ($errors->has('county_id'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('county_id')  }}</strong>
                                        County Error
                                    </span>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="sub_county">Select Sub County</label>
                                <select data-subcounties="{{$sub_counties}}" name="sub_county_id" id="sub_county_id"
                                    class=" form-control form-select {{ $errors->has('sub_county_id') ? ' is-invalid' : '' }}">
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
                <h4 class="card-title">Registered Branches</h4>
                <div class="table-responsive">
                    <table class="table table-hover dt">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cooperative</th>
                                <th>Name</th>
                                <th>Branch Code</th>
                                <th>Sub county</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($branches as $key => $prod)
                            <tr>
                                <td>{{++$key }}</td>
                                <td>{{$prod->coop_name}}</td>
                                <td>{{$prod->name }} </td>
                                <td>{{$prod->code }}</td>
                                <td>{{$prod->sub_county_name}} - {{$prod->county_name}}</td>
                                <td>{{$prod->location }}</td>
                                <td>
                                    <div class="btn-group dropdown">
                                        <button type="button" class="btn btn-default dropdown-toggle btn-sm"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="text-info dropdown-item"
                                                href="{{ route('branches.detail', $prod->id) }}">
                                                <i class="fa fa-edit"></i>Edit
                                            </a>
                                            <a onclick="return confirm('sure to delete?')"
                                                href="/admin/branches/delete/{{ $prod->id }}"
                                                class="text-danger dropdown-item">
                                                <i class="fa fa-trash-alt"></i>Delete</a>
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
@else
<div>
    You are not authorized to access this page
</div>
@endif
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
function deleteBranch(id) {
    shouldDelete = confirm("Are you sure you want to delete this cooperative branch?")
    if (!shouldDelete) {
        return
    }


    window.location = "/branches/delete/" + id
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