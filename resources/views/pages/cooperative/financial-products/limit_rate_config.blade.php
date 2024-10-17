@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
    @php
        $canEdit = has_right_permission(config('enums.system_modules')['Financial Products']['limit_rate_setting'], config('enums.system_permissions')['edit']);
        $canCreate = has_right_permission(config('enums.system_modules')['Financial Products']['limit_rate_setting'], config('enums.system_permissions')['create']);
    @endphp
    @if($canEdit && $canCreate)
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="collapse show "
                             id="limitRate">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Credit/Loan Score</h4>
                                </div>
                            </div>

                            <form action="{{ route('cooperative.limit-rate.config.create') }}"
                                  method="post">
                                @csrf
                                <div class="form-row">

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="rate">Rate <small>(%)</small></label>
                                        <input type="text" name="rate"
                                               class="form-control {{ $errors->has('rate') ? ' is-invalid' : '' }}"
                                               id="rate"
                                               placeholder="60"
                                               value="{{$config ? $config->rate : old('rate') }}"
                                               required>

                                        @if ($errors->has('rate'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('rate')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="needs_approval">Needs Approval</label>

                                        <select name="needs_approval" id="needs_approval"
                                                class=" form-control select2bs4 {{ $errors->has('needs_approval') ? ' is-invalid' : '' }}">
                                            <option value="">--- Select Option ---</option>
                                            <option value="0" {{ $config ? ($config->needs_approval == 0 || old('needs_approval') == 0 ? 'selected' : '' ) : ''  }}>
                                                No
                                            </option>
                                            <option value="1" {{ $config ? ($config->needs_approval == 1 || old('needs_approval') == 1 ? 'selected' : '' ) : ''  }}>
                                                Yes
                                            </option>
                                        </select>

                                        @if ($errors->has('needs_approval'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('needs_approval')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="limit_for_approval">Set Approval Requirement <small>(optional)</small></label>
                                        <input type="text" name="limit_for_approval"
                                               class="form-control {{ $errors->has('limit_for_approval') ? ' is-invalid' : '' }}"
                                               id="limit_for_approval"
                                               placeholder="50000"
                                               value="{{ $config ? $config->limit_for_approval : old('limit_for_approval') }}"
                                        >

                                        @if ($errors->has('limit_for_approval'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('limit_for_approval')  }}</strong>
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
    @include('pages.cooperative.financial-products.limit_guidelines')
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush
