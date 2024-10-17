@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Financial Products']['group_loan_type'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                                data-toggle="collapse"
                                data-target="#groupLoanType"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="groupLoanType"><span class="mdi mdi-plus"></span>
                            Group Loan Types
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif "
                             id="groupLoanType">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Group Loan Types</h4>
                                </div>
                            </div>

                            <form action="{{ route('cooperative.group_loan_type.create') }}" method="post">
                                @csrf
                                <div class="form-row">

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="name">Name</label>
                                        <input type="text" name="name"
                                               class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               id="name" placeholder="Fertilizer"
                                               value="{{ old('name')}}" required>

                                        @if ($errors->has('name'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('name')  }}</strong>
                                            </span>
                                        @endif

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <button type="submit"
                                                class="btn btn-primary btn-fw btn-block">Save
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
                    <h4 class="card-title">Group Loan Types</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Created By </th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $canEdit = has_right_permission(config('enums.system_modules')['Financial Products']['group_loan_type'], config('enums.system_permissions')['edit'])
                            @endphp
                            @foreach($groupLoanTypes as $key => $conf)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$conf->name }}</td>
                                    <td>{{ ucwords(strtolower($conf->createdBy->first_name.' '.$conf->createdBy->other_names)) }}</td>
                                    <td>
{{--                                        @if($canEdit)--}}
{{--                                            <button type="button" class="btn btn-info btn-rounded"--}}
{{--                                                    data-toggle="modal"--}}
{{--                                                    data-target="#editModal_{{$conf->id}}">--}}
{{--                                                <span class="mdi mdi-file-edit"></span>--}}
{{--                                            </button>--}}
{{--                                        @endif--}}

                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$conf->id}}"
                                             tabindex="-1" role="dialog"
                                             aria-labelledby="modalLabel_{{$conf->id}}"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered"
                                                 role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="modalLabel_{{$conf->id}}">
                                                            Edit {{$conf->name}} </h5>
                                                        <button type="button" class="close"
                                                                data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('cooperative.loan_type.edit', $conf->id) }}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">

                                                                <div class="form-group col-12">
                                                                    <label for="type">Loan
                                                                        Type</label>
                                                                    <input type="text" name="name"
                                                                           class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                                           id="name" placeholder="School Loan"
                                                                           value="{{ $conf->name}}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button"
                                                                    class="btn btn-secondary"
                                                                    data-dismiss="modal">Close
                                                            </button>
                                                            <button type="submit"
                                                                    class="btn btn-primary">Save
                                                                changes
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        {{--  modal end   --}}
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
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush
