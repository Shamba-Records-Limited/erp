@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="" id="addEmployeeAccordion">
                        <form id="allow" name="allow" action="{{ route('hr.employees.updateallowance') }}" method="post">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <h6 class="mb-3">Update Employee Benefits/Allowance</h6>
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-12">
                                    <label for="amount">Amount/Percentage</label>
                                    <input type="text" name="amount"
                                           class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                           id="amount" placeholder="e.g 1000 or 10% Add % for percentages" value="{{ $allowance->amount }}" required>
                                    @if ($errors->has('amount'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('amount')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-6 col-md-12 col-12">
                                    <label for="title">Title</label>
                                    <input title="text" name="title" value="{{ $allowance->title }}"
                                           class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}"
                                           id="title" placeholder="e.g NSSF" required>
                                    @if ($errors->has('title'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('title')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                                                                
                                <div class="form-group col-lg-6 col-md-12 col-12">
                                    <label for="type">Type</label>
                                    <select name="type" id="type"
                                            class=" form-control form-select {{ $errors->has('type') ? ' is-invalid' : '' }}" required>
                                        @foreach(config('enums.hr_deduction_types') as $key => $benefit)
                                            <option value="{{$key}}" {{ $allowance->type == $key ? 'select' : '' }}>{{config('enums.hr_deduction_types')[$key]}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('type'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('type')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-12">
                                    <label for="description">Description</label>
                                    <textarea name="description" value="{{ old('description')}}"
                                           class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                           id="description" placeholder="Description">{{ $allowance->description }}</textarea>
                                    @if ($errors->has('description'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('description')  }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <input type="hidden" name="id" value="{{ $id }}"/>
                                <input type="hidden" name="employee_id" value="{{ $allowance->employee_id }}"/>

                            </div>
                            <hr class="mt-1 mb-1">
                            <div class="form-row">
                                <div class="form-group col-lg-6 col-md-12 col-12">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Update</button>
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
