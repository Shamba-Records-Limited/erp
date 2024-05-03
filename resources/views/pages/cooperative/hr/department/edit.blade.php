@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="" id="addDepartmentAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Update Department</h4>
                            </div>
                        </div>

                        <form action="{{ route('hr.departments.edit') }}" method="post">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="productName">Name</label>
                                    <input type="hidden" name="id" value="{{ $id }}"/>
                                    <input type="text" name="name"
                                           class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           id="productName" placeholder="XYZ Branch" value="{{ $department->name }}" required>

                                    @if ($errors->has('name'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('name')  }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="code">Code</label>
                                    <input type="text" name="code"
                                           class="form-control  {{ $errors->has('code') ? ' is-invalid' : '' }}"
                                           id="code" placeholder="AB12#" value="{{ $department->code }}">

                                    @if ($errors->has('code'))
                                        <span class="help-block text-danger">
                                    <strong>{{ $errors->first('code')  }}</strong>
                                </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="office_number">Office Number</label>
                                    <input type="text" name="office_number"
                                           class="form-control  {{ $errors->has('office_number') ? ' is-invalid' : '' }}"
                                           value="{{ $department->office_number }}" id="office_number" placeholder="Uplands"
                                           required>
                                    @if ($errors->has('office_number'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('office_number')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="branch">Branch</label>
                                <select name="branch" id="branch"
                                    class=" form-control select2bs4 {{ $errors->has('branch') ? ' is-invalid' : '' }}">
                                    <option value="{{$department->branch_id}}"> {{ $department->coopBranch->name }}</option>
                                    @foreach($branches as $branch)
                                        <option value="{{$branch->id}}"> {{ $branch->name }}</option>
                                    @endforeach

                                    @if ($errors->has('branch'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('branch')  }}</strong>
                                    </span>
                                    @endif
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Submit</button>
                                </div>
                            </div>
                        </form>
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
        document.addEventListener("DOMContentLoaded", () => {
            const rows = document.querySelectorAll("tr[data-href]");
            rows.forEach(row => {
                row.addEventListener("click", () => {
                    window.location.href = row.dataset.href
                })
            })
        })
    </script>
@endpush