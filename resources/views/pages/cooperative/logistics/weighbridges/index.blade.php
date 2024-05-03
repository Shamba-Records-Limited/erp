@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Logistics']['weighbridge'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addVehicleTypeAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addVehicleTypeAccordion"><span
                                    class="mdi mdi-plus"></span>Add Weigh Bridge
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addVehicleTypeAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Weigh Bridge</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.logistics.weighbridges.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="code">Code</label>
                                        <input type="text" name="code"
                                               class="form-control {{ $errors->has('code') ? ' is-invalid' : '' }}"
                                               id="code" value="{{ $nextId }}" readonly>

                                        @if ($errors->has('code'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('code')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="max_weight">Weight Limit (Kgs)</label>
                                        <input type="number" name="max_weight"
                                               class="form-control {{ $errors->has('max_weight') ? ' is-invalid' : '' }}"
                                               id="max_weight" value="{{ old('max_weight')}}" required>

                                        @if ($errors->has('max_weight'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('max_weight')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-12">
                                        <x-location-picker label="Location" name="location" :cooperativeId="$coopId"/>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="status">Status</label>
                                        <select name="status"
                                                class="form-control select2bs4 {{ $errors->has('status') ? ' is-invalid' : '' }}"
                                                id="status" value="{{ old('status')}}" required>
                                            <option value="1">Active</option>
                                            <option value="2">Service</option>
                                            <option value="3">Closed</option>
                                        </select>

                                        @if ($errors->has('status'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('status')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="status_comment">Status Comment</label>
                                        <input type="text" name="status_comment"
                                               class="form-control {{ $errors->has('status_comment') ? ' is-invalid' : '' }}"
                                               id="location" value="{{ old('status_comment')}}" required></textarea>

                                        @if ($errors->has('status_comment'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('status_comment')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-3 col-12">
                                        <label for=""></label>
                                        <button type="submit" class="btn btn-primary btn-fw btn-block">Add</button>
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
                    <h4 class="card-title">Weigh Bridges</h4>

                    <div class="mb-5">

                        <form action="{{ route('cooperative.logistics.weighbridges') }}" method="get">
                            @csrf
                            <input type="hidden" name="download" value="csv" />
                            <button type="submit"
                                class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('cooperative.logistics.weighbridges') }}" method="get">
                            @csrf
                            <input type="hidden" name="download" value="xlsx" />
                            <button type="submit"
                                class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>

                        <form action="{{ route('cooperative.logistics.weighbridges') }}" method="get">
                            @csrf
                            <input type="hidden" name="download" value="pdf" />
                            <button type="submit"
                                class="btn btn-sm btn-success float-right text-white">
                                <i class="mdi mdi-download"></i> PDF
                            </button>
                        </form>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Weight Limit (KGS)</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Status Comment</th>
                                <th>Registration Date</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($weighbridges as $weighbridge)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('cooperative.logistics.weighbridges.show', $weighbridge->id) }}">{{ $weighbridge->code }}</a>
                                    </td>
                                    <td>{{ number_format($weighbridge->max_weight) }}</td>
                                    <td>{{ $weighbridge->location->name ?? '---' }}</td>
                                    <td>{{ $weighbridge->statusText() }}</td>
                                    <td>{{ $weighbridge->status_comment }}</td>
                                    <td>{{ (new DateTime($weighbridge->status_date))->format('Y-m-d') }}</td>
                                    <td>
                                        @if(has_right_permission(config('enums.system_modules')['Logistics']['weighbridge'], config('enums.system_permissions')['edit']))
                                            <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                    data-target="#editModal_{{$weighbridge->id}}">
                                                <span class="mdi mdi-file-edit"></span>
                                            </button>
                                        @endif
                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$weighbridge->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$weighbridge->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$weighbridge->id}}">
                                                            Edit {{$weighbridge->code}}
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.logistics.weighbridges.update', $weighbridge->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12 col-md-6">
                                                                    <label for="code_edit_{{$weighbridge->id}}">Code</label>
                                                                    <input type="text" name="code_edit"
                                                                           class="form-control {{ $errors->has('code_edit') ? ' is-invalid' : '' }}"
                                                                           id="code_edit_{{$weighbridge->id}}"
                                                                           value="{{ $weighbridge->code }}" readonly>
                                                                    @if ($errors->has('code_edit'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('code_edit')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-12 col-md-6">
                                                                    <label for="max_weight_edit_{{$weighbridge->id}}">Weight
                                                                        Limit (Kgs)</label>
                                                                    <input type="text" name="max_weight_edit"
                                                                           class="form-control {{ $errors->has('max_weight_edit') ? ' is-invalid' : '' }}"
                                                                           id="max_weight_edit_{{$weighbridge->id}}"
                                                                           value="{{ $weighbridge->max_weight }}"
                                                                           required>
                                                                    @if ($errors->has('max_weight_edit'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('max_weight_edit')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-12">
                                                                    <x-location-picker label="Location"
                                                                                       name="location_edit"
                                                                                       :cooperativeId="$weighbridge->cooperative_id"
                                                                                       :value="$weighbridge->location->id"/>
                                                                </div>
                                                                <div class="form-group col-12 col-md-6">
                                                                    <label for="status_edit_{{$weighbridge->id}}">Status</label>
                                                                    <select name="status_edit"
                                                                            class="form-control select2bs4 {{ $errors->has('status_edit') ? ' is-invalid' : '' }}"
                                                                            id="status_edit_{{$weighbridge->id}}"
                                                                            value="{{ $weighbridge->code }}" required>
                                                                        <option value="1"
                                                                                @if($weighbridge->status == 1) selected="selected" @endif>
                                                                            Active
                                                                        </option>
                                                                        <option value="2"
                                                                                @if($weighbridge->status == 2) selected="selected" @endif>
                                                                            Service
                                                                        </option>
                                                                        <option value="3"
                                                                                @if($weighbridge->status == 3) selected="selected" @endif>
                                                                            Closed
                                                                        </option>
                                                                    </select>
                                                                    @if ($errors->has('status_edit'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('status_edit')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-12 col-md-6">
                                                                    <label for="status_comment_edit_{{$weighbridge->id}}">Status
                                                                        Comment</label>
                                                                    <input type="text" name="status_comment_edit"
                                                                           class="form-control {{ $errors->has('status_comment_edit') ? ' is-invalid' : '' }}"
                                                                           id="status_comment_edit_{{$weighbridge->id}}"
                                                                           value="{{ $weighbridge->status_comment }}"
                                                                           required>
                                                                    @if ($errors->has('status_comment_edit'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('status_comment_edit')  }}</strong>
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
