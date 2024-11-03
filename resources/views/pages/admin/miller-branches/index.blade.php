@extends('layouts.app')

@push('plugin-styles')
@endpush

@section('content')

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
                                <p class="font-weight-bold text-muted">Unique Branches</p>
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
        $("#sub_county_id").val("");
        $("#sub_county_id").empty();
        $("#sub_county_id").append("<option> -- Select Sub County -- </option>");

        let subCounties = JSON.parse($("#sub_county_id").attr("data-subcounties"))
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
