@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $weighbridge->code }}</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>Weight Limit</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Status Comment</th>
                                <th>Status Date</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{ $weighbridge->code }}</td>
                                <td>{{ $weighbridge->max_weight }}</td>
                                <td>{{ $weighbridge->location->name ?? '---' }}</td>
                                <td>{{ $weighbridge->statusText() }}</td>
                                <td>{{ $weighbridge->status_comment }}</td>
                                <td>{{ (new DateTime($weighbridge->status_date))->format('d M Y h:m A') }}</td>
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
                                                                       value="{{ $weighbridge->max_weight }}" required>
                                                                @if ($errors->has('max_weight_edit'))
                                                                    <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('max_weight_edit')  }}</strong>
                                                                        </span>
                                                                @endif
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <x-location-picker label="Location" name="location_edit"
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
                    <h4 class="card-title">Weighbridge Bookings</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Vehicle</th>
                                <th>Driver</th>
                                <th>Load Type/Unit</th>
                                <th>Booking Date</th>
                                <th>Weight</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($weighbridge->weighbridgeEvents as $event)
                                @php
                                    $vehicle = $event->trip->transport_type == 'OWN_VEHICLE' ? $event->trip->vehicle : $event->trip->transporterVehicle;
                                    $trip = $event->trip;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $vehicle->registration_number }}</td>
                                    <td>
                                        @if($vehicle->driver)    
                                            {{ $vehicle->driver->first_name }} {{ $vehicle->driver->other_names }}
                                        @else
                                            {{ $vehicle->driver_name }}
                                        @endif
                                    </td>
                                    <td>{{ $trip->load_type }}/{{ $trip->unit->name }}</td>
                                    <td>{{ (new DateTime($trip->datetime))->format('d M Y h:m A') }}</td>
                                    <td>{{ $event->weight ?? '---' }}</td>
                                    <td>
                                        @if($event->weight == '')

                                            <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                    data-target="#weightModal_{{$event->id}}">
                                                <span class="mdi mdi-weight-kilogram"></span>
                                            </button>

                                            {{--  modals add weight start--}}
                                            <div class="modal fade" id="weightModal_{{$event->id}}" tabindex="-1"
                                                 role="dialog"
                                                 aria-labelledby="modalLabel_{{$event->id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalLabel_{{$event->id}}">
                                                                Record Weight
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{route('cooperative.logistics.trips.record-weight', $event->trip_id)}}"
                                                              method="post">
                                                            <div class="modal-body">
                                                                @csrf
                                                                <div class="form-row">
                                                                    <div class="form-group col-12">
                                                                        <label for="weight_{{$event->id}}">Weight
                                                                            (Kgs)</label>
                                                                        <input type="number" min="1"
                                                                               name="weight"
                                                                               class="form-control {{ $errors->has('weight') ? ' is-invalid' : '' }}"
                                                                               id="weight_{{$event->id}}" required>
                                                                        @if ($errors->has('weight'))
                                                                            <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('weight')  }}</strong>
                                                                                </span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="event_id"
                                                                       value="{{ $event->id }}"/>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Record
                                                                    Weight
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--  modal end   --}}

                                        @endif
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
