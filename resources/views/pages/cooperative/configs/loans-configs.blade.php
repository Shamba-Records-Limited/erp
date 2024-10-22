@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Financial Products']['loan_products'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#loanTypesAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="loanTypesAccordion"><span class="mdi mdi-plus"></span>Add Loan
                            Configurations
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="loanTypesAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Loan Configurations</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.loan_config.add') }}" method="post">
                                @csrf
                                <div class="form-row">

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="type">Loan Type</label>
                                        <input type="text" name="type"
                                               class="form-control {{ $errors->has('type') ? ' is-invalid' : '' }}"
                                               id="type" placeholder="School Loan" value="{{ old('type')}}" required>

                                        @if ($errors->has('type'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('type')  }}</strong>
                                </span>
                                        @endif

                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="interest">Interest Rate (%)</label>
                                        <input type="text" name="interest"
                                               class="form-control {{ $errors->has('interest') ? ' is-invalid' : '' }}"
                                               id="interest" placeholder="4" value="{{ old('interest')}}" required>
                                        @if ($errors->has('interest'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('interest')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="penalty">Penalty</label>
                                        <input type="text" name="penalty"
                                               class="form-control  {{ $errors->has('penalty') ? ' is-invalid' : '' }}"
                                               id="penalty" placeholder="1.5" value="{{ old('penalty')}}">

                                        @if ($errors->has('penalty'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('penalty')  }}</strong>
                                </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="period">Loan Period (months)</label>
                                        <input type="text" name="period"
                                               class="form-control  {{ $errors->has('period') ? ' is-invalid' : '' }}"
                                               value="{{ old('period')}}" id="period" placeholder="12"
                                               required>
                                        @if ($errors->has('period'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('period')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="installments">Installments (months)</label>
                                        <input type="text" name="installments"
                                               class="form-control  {{ $errors->has('installments') ? ' is-invalid' : '' }}"
                                               value="{{ old('installments')}}" id="installments" placeholder="12"
                                               required>
                                        @if ($errors->has('installments'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('installments')  }}</strong>
                                </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <button type="submit" class="btn btn-primary btn-fw btn-block">Save</button>
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
                    <h4 class="card-title">Loan Products Configurations</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Loan Type</th>
                                <th>Interest Rate</th>
                                <th>Period</th>
                                <th>Penalty</th>
                                <th>Installments</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $canEdit = has_right_permission(config('enums.system_modules')['Financial Products']['loan_products'], config('enums.system_permissions')['edit'])
                            @endphp
                            @foreach($loan_configurations as $key => $conf)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$conf->type }}</td>
                                    <td>{{$conf->interest }}%</td>
                                    <td>{{$conf->period }}</td>
                                    <td>{{$conf->penalty }}%</td>
                                    <td>{{$conf->installments }}</td>
                                    <td>
                                        @if($canEdit)
                                            <button type="button" class="btn btn-info btn-rounded"
                                                    data-toggle="modal" data-target="#editModal_{{$conf->id}}">
                                                <span class="mdi mdi-file-edit"></span>
                                            </button>
                                        @endif

                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$conf->id}}" tabindex="-1" role="dialog"
                                             aria-labelledby="modalLabel_{{$conf->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$conf->id}}">
                                                            Edit {{$conf->type}} Saving Type</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
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
                                                                    <label for="type">Loan Type</label>
                                                                    <input type="text" name="type"
                                                                           class="form-control {{ $errors->has('type') ? ' is-invalid' : '' }}"
                                                                           id="type" placeholder="School Loan"
                                                                           value="{{ $conf->type}}" required>

                                                                    @if ($errors->has('type'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('type')  }}</strong>
                                                                        </span>
                                                                    @endif

                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="interest">Interest Rate (%)</label>
                                                                    <input type="text" name="interest"
                                                                           class="form-control {{ $errors->has('interest') ? ' is-invalid' : '' }}"
                                                                           id="interest" placeholder="4"
                                                                           value="{{ $conf->interest}}" required>
                                                                    @if ($errors->has('interest'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('interest')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="penalty">Penalty</label>
                                                                    <input type="text" name="penalty"
                                                                           class="form-control  {{ $errors->has('penalty') ? ' is-invalid' : '' }}"
                                                                           id="penalty" placeholder="1.5"
                                                                           value="{{ $conf->penalty }}">

                                                                    @if ($errors->has('penalty'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('penalty')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-12">
                                                                    <label for="period">Loan Period (months)</label>
                                                                    <input type="text" name="period"
                                                                           class="form-control  {{ $errors->has('period') ? ' is-invalid' : '' }}"
                                                                           value="{{ $conf->period }}" id="period"
                                                                           placeholder="12"
                                                                           required>
                                                                    @if ($errors->has('period'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('period')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="installments">Installments
                                                                        (months)</label>
                                                                    <input type="text" name="installments"
                                                                           class="form-control  {{ $errors->has('installments') ? ' is-invalid' : '' }}"
                                                                           value="{{ $conf->installments }}"
                                                                           id="installments" placeholder="12"
                                                                           required>
                                                                    @if ($errors->has('installments'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('installments')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close
                                                            </button>
                                                            <button type="submit" class="btn btn-primary">Save changes
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
