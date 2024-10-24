@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Insurance Product']['premium_adjustments'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addPaymentModeConfiguration"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addPaymentModeConfiguration">
                            <span class="mdi mdi-plus"></span>Add Payment Mode Configuration
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addPaymentModeConfiguration">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Payment Mode Configuration</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.insurance.config.premium-adjustment.add') }}"
                                  method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="payment_mode">Mode</label>
                                        <select name="payment_mode" id="payment_mode"
                                                class=" form-control form-select {{ $errors->has('payment_mode') ? ' is-invalid' : '' }}">
                                            <option value="">---Select Mode---</option>
                                            <option value="4">Weekly</option>
                                            <option value="1">Monthly</option>
                                            <option value="2">Quarterly</option>
                                            <option value="3">Annually</option>

                                        </select>
                                        @if ($errors->has('payment_mode'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('payment_mode')  }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="adjusted_interest">Adjusted Rate</label>
                                        <input type="text" name="adjusted_interest"
                                               class="form-control {{ $errors->has('adjusted_interest') ? ' is-invalid' : '' }}"
                                               id="adjusted_interest" placeholder="1.5"
                                               value="{{ old('adjusted_interest')}}">

                                        @if ($errors->has('adjusted_interest'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('adjusted_interest')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-3 col-12">
                                        <button type="submit" class="btn btn-primary btn-fw btn-block" id="submit-btn">
                                            Add
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
                    <h4 class="card-title">Payment Mode Rate Adjustments</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Mode</th>
                                <th>Adjusted Rate</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $canEdit = has_right_permission(config('enums.system_modules')['Insurance Product']['premium_adjustments'], config('enums.system_permissions')['edit']);
                            @endphp
                            @foreach($configs as $key => $config)
                                @php $mode =
                                            $config->payment_mode == \App\InsuranceSubscriber::MODE_MONTHLY ? 'Monthly' :
                                            ($config->payment_mode == \App\InsuranceSubscriber::MODE_QUARTERLY ? 'Quarterly' :
                                             ($config->payment_mode == \App\InsuranceSubscriber::MODE_WEEKLY ? 'Weekly' : 'Annually'));
                                    ;@endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $mode }}</td>
                                    <td>{{ number_format($config->adjusted_rate,2).'%'}}</td>


                                    <td>
                                        @if($canEdit)
                                            <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                    data-target="#editModal_{{$config->id}}">
                                                <span class="mdi mdi-file-edit"></span>
                                            </button>
                                        @endif

                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$config->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$config->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$config->id}}">
                                                            Edit {{$mode}} Payment Mode</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.insurance.config.premium-adjustment.update', $config->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="payment_mode_{{$config->id}}">Mode</label>
                                                                    <select name="payment_mode"
                                                                            id="payment_mode_{{$config->id}}"
                                                                            class=" form-control form-select {{ $errors->has('payment_mode') ? ' is-invalid' : '' }}">
                                                                        <option value="">---Select Mode---</option>
                                                                        <option value="1" {{ $config->payment_mode == 1 ? 'selected' : '' }}>
                                                                            Monthly
                                                                        </option>
                                                                        <option value="2" {{ $config->payment_mode == 2 ? 'selected' : '' }}>
                                                                            Quarterly
                                                                        </option>
                                                                        <option value="3" {{ $config->payment_mode == 3 ? 'selected' : '' }}>
                                                                            Annually
                                                                        </option>
                                                                        <option value="3" {{ $config->payment_mode == 4 ? 'selected' : '' }}>
                                                                            Weekly
                                                                        </option>

                                                                    </select>
                                                                    @if ($errors->has('payment_mode'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('payment_mode')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-12">
                                                                    <label for="adjusted_interest_{{$config->id}}">Adjusted
                                                                        Rate</label>
                                                                    <input type="text" name="adjusted_interest"
                                                                           class="form-control {{ $errors->has('adjusted_interest') ? ' is-invalid' : '' }}"
                                                                           id="adjusted_interest_{{$config->id}}"
                                                                           placeholder="1.5"
                                                                           value="{{ $config->adjusted_rate}}">

                                                                    @if($errors->has('adjusted_interest'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('adjusted_interest')  }}</strong>
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
