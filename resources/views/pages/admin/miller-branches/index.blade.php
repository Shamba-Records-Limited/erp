@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')
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
                                        {{ is_array($miller_branches) ? count($miller_branches) : $branches->count() }}
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
                                    <h5 class=" text-muted mb-0" style="font-size:1rem">Number of Millers</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {{ is_array($millers) ? count($millers) : $millers->count() }}
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
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Counties Covered</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {{ $uniqueCounties }}
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

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="container my-5">
                <div class="row">
                    <!-- Number of Miller Branches Card -->
                    <div class="col-md-4 col-12 mb-4">
                        <div class="card shadow-lg border-0 rounded">
                            <div class="card-header text-center bg-gradient-success text-white">
                                <h5 class="font-weight-bold mb-0">Number of Miller Branches</h5>
                            </div>
                            <div class="card-body text-center">
                                <h2 id="branch-count" class="font-weight-bold text-success display-4">0</h2>
                                <p class="font-weight-bold text-muted">Miller Branches</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


            
<div class="card shadow-sm">
    <div class="card-body">
        <button type="button" class="btn btn-primary btn-sm mb-4" data-toggle="collapse" data-target="#addMillerBranchAccordion" 
                aria-expanded="@if ($errors->count() > 0) true @else false @endif" aria-controls="addMillerBranchAccordion">
            <span class="mdi mdi-plus"></span> Add Miller Branch
        </button>

        <div class="collapse @if($errors->count() > 0) show @endif" id="addMillerBranchAccordion">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="text-primary font-weight-bold mb-4">Register Miller Branch</h4>

                <form action="{{ route('admin.miller-branches.add') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <!-- Miller Branch Details Section -->
                    <div class="section-header bg-light p-2 mb-3 rounded">
                        <h6 class="text-muted mb-0">Miller Branch Details</h6>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="miller_id" class="font-weight-bold">Miller</label>
                            <select name="miller_id" id="miller_id" class="form-control rounded {{ $errors->has('miller_id') ? ' is-invalid' : '' }}">
                                <option value=""> -- Select Miller --</option>
                                @foreach($millers as $miller)
                                    <option value="{{ $miller->id }}" @if(old('miller_id') == $miller->id) selected @endif>{{ $miller->name }}</option>
                                @endforeach
                            </select>
                            @error('miller_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="name" class="font-weight-bold">Name</label>
                            <input type="text" name="name" class="form-control rounded {{ $errors->has('name') ? ' is-invalid' : '' }}" 
                                   id="name" placeholder="ABC" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="code" class="font-weight-bold">Code</label>
                            <input type="text" name="code" class="form-control rounded {{ $errors->has('code') ? ' is-invalid' : '' }}" 
                                   id="code" placeholder="AB12#" value="{{ old('code') }}">
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="location" class="font-weight-bold">Location</label>
                            <input type="text" name="location" class="form-control rounded {{ $errors->has('location') ? ' is-invalid' : '' }}" 
                                   id="location" placeholder="Nairobi" value="{{ old('location') }}" required>
                            @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="address" class="font-weight-bold">Address</label>
                            <input type="text" name="address" class="form-control rounded {{ $errors->has('address') ? ' is-invalid' : '' }}" 
                                   id="address" placeholder="Nairobi" value="{{ old('address') }}" required>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="county_id" class="font-weight-bold">Select County</label>
                            <select name="county_id" id="county_id" class="form-control rounded {{ $errors->has('county_id') ? ' is-invalid' : '' }}">
                                <option value="">- Select County -</option>
                                @foreach($counties as $county)
                                    <option value="{{ $county->id }}" @if(old('county_id') == $county->id) selected @endif>{{ $county->name }}</option>
                                @endforeach
                            </select>
                            @error('county_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="sub_county_id" class="font-weight-bold">Select Sub County</label>
                            <select name="sub_county_id" id="sub_county_id" class="form-control rounded {{ $errors->has('sub_county_id') ? ' is-invalid' : '' }}" data-subcounties="{{ $sub_counties }}">
                                <option value="">- Select Sub County -</option>
                            </select>
                            @error('sub_county_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="mdi mdi-check"></i> Complete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Branch Distribution per Miller -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Branches per Miller</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Miller</th>
                                <th>Number of Branches</th>
                                <th>Counties Present</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $branchesCollection = collect($miller_branches); // Use miller branches collection
                            $branchesPerMiller = $branchesCollection->groupBy('miller_id'); // Group by miller_id
                            @endphp
                            @foreach($millers as $miller)
                            <tr>
                                <td>{{ $miller->name }}</td>
                                <td>
                                    <span class="badge badge-primary" style="font-size:14px">
                                        {{ $branchesPerMiller->get($miller->id, collect())->count() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-info" style="font-size:17px">
                                        {{ $branchesPerMiller->get($miller->id, collect())->unique('county_name')->count() }}
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
                <h4 class="card-title">Registered Miller Branches</h4>
                <div class="table-responsive">
                    <table class="table table-hover dt">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Miller</th>
                                <th>Name</th>
                                <th>County & SubCounty</th>
                                <th>Actions</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($miller_branches as $key => $branch)
                            <tr>
                                <td>{{++$key }}</td>
                                <td>{{$branch->miller_name}}</td>
                                <td>
                                    <a href="{{ route('admin.miller-branches.view', $branch->id) }}" class="text-info">
                                        {{ $branch->name }}
                                    </a>
                                </td>
                                <td>{{$branch->county_name}} - {{$branch->sub_county_name}}</td>
                                <td>
                                    <div class="btn-group dropdown">
                                        <button type="button" class="btn btn-default dropdown-toggle btn-sm"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <!-- Edit button -->
                                            <a class="dropdown-item text-info" href="{{ route('miller-branches.detail', $branch->id) }}" style="display: flex; align-items: center;">
                                                <i class="fa fa-edit" style="margin-right: 6px;"></i> Edit
                                            </a>

                                            <!-- Delete button -->
                                            <form action="{{ route('admin.miller-branches.delete', $branch->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this miller branch?');" style="display: block; margin: 0; padding-left: 15px;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" style="border: none; background: none; padding: 0; display: flex; align-items: center;">
                                                    <i class="fa fa-trash-alt" style="margin-right: 6px;"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
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

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    function deleteBranch(id) {
        let shouldDelete = confirm("Are you sure you want to delete this miller branch?");
        if (!shouldDelete) {
            return;
        }

        // Update fetch to match the correct URL format
        fetch(`/miller-branches/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                alert('Miller branch deleted successfully');
                window.location.reload(); // Reload the page or redirect as needed
            } else {
                alert('Failed to delete miller branch');
            }
        })
        .catch(error => console.error('Error:', error));
    }


    // Handle county and sub-county dropdown change
    $("#county_id").change(function(e) {
        $("#sub_county_id").val("");
        $("#sub_county_id").empty();
        $("#sub_county_id").append("<option> -- Select Sub County -- </option>");

        let subCounties = JSON.parse($("#sub_county_id").attr("data-subcounties"));
        for (let subCounty of subCounties) {
            if (subCounty.county_id == e.target.value) {
                let elem = `<option value='${subCounty.id}'>${subCounty.name}</option>`;
                $("#sub_county_id").append(elem);
            }
        }
    });

    // Update Number of Miller Branches dynamically from DataTable
    $(document).ready(function() {
        const branchesCount = $('.dt').DataTable().page.info().recordsTotal;
        $('#branch-count').text(branchesCount);
    });
</script>
@endpush
