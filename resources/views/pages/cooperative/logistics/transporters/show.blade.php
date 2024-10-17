@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Logistics']['transport_providers'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addVehicleTypeAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addVehicleTypeAccordion"><span
                                    class="mdi mdi-plus"></span>Add Vehicle
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addVehicleTypeAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Vehicle</h4>
                                </div>
                            </div>

                            <form action="{{ route('cooperative.logistics.transporters.add-vehicle', $transporter->id) }}"
                                  method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-4 col-md-6 col-12">
                                        <label for="unitName">Registration Number</label>
                                        <input type="text" name="registration"
                                               class="form-control {{ $errors->has('registration') ? ' is-invalid' : '' }}"
                                               id="unitName" placeholder="KDD898T" value="{{ old('registration')}}"
                                               required>

                                        @if ($errors->has('registration'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('registration')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-4 col-md-6 col-12">
                                        <label for="vehicle_type">Vehicle Type</label>
                                        <select id="vehicle_type" name="vehicle_type"
                                                class="form-control select2bs4 {{ $errors->has('vehicle_type') ? ' is-invalid' : '' }}"
                                                placeholder="Van" value="{{ old('vehicle_type')}}" required>
                                            <option value="">select</option>
                                            @foreach ($vehicleTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('vehicle_type'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('vehicle_type')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-4 col-md-6 col-12">
                                        <label for="vehicle_weight">Vehicle Weight (Kgs)</label>
                                        <input type="number" id="vehicle_weight" name="vehicle_weight"
                                               class="form-control {{ $errors->has('vehicle_weight') ? ' is-invalid' : '' }}"
                                               value="{{ old('vehicle_weight')}}" required>

                                        @if ($errors->has('vehicle_weight'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('vehicle_weight') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-4 col-md-6 col-12">
                                        <label for="vehicle_weight">Driver Name</label>
                                        <input id="driver_name" name="driver_name"
                                               class="form-control {{ $errors->has('driver_name') ? ' is-invalid' : '' }}"
                                               value="{{ old('driver_name')}}" required>

                                        @if ($errors->has('driver_name'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('driver_name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-4 col-md-6 col-12">
                                        <label for="phone_number">Phone Number</label>
                                        <input id="phone_number" name="phone_number"
                                               class="form-control {{ $errors->has('phone_number') ? ' is-invalid' : '' }}"
                                               value="{{ old('phone_number')}}">

                                        @if ($errors->has('phone_number'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('phone_number') }}</strong>
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
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>Location</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{ $transporter->name }}</td>
                                <td>{{ $transporter->phone_number }}</td>
                                <td>{{ $transporter->location }}</td>
                                <td>
                                    @if(has_right_permission(config('enums.system_modules')['Logistics']['transport_providers'], config('enums.system_permissions')['edit']))
                                        <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                data-target="#editModal_{{$transporter->id}}">
                                            <span class="mdi mdi-file-edit"></span>
                                        </button>
                                    @endif

                                    {{--  modals edit start--}}
                                    <div class="modal fade" id="editModal_{{$transporter->id}}" tabindex="-1"
                                         role="dialog"
                                         aria-labelledby="modalLabel_{{$transporter->id}}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLabel_{{$transporter->id}}">
                                                        Edit {{$transporter->name}}
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{route('cooperative.logistics.transporters.update', $transporter->id)}}"
                                                      method="post">
                                                    <div class="modal-body">
                                                        @csrf
                                                        <div class="form-row">
                                                            <div class="form-group col-12">
                                                                <label for="name_edit_{{$transporter->id}}">Name</label>
                                                                <input type="text" name="name_edit"
                                                                       class="form-control {{ $errors->has('name_edit') ? ' is-invalid' : '' }}"
                                                                       id="name_edit_{{$transporter->id}}"
                                                                       placeholder="Van"
                                                                       value="{{ $transporter->name }}" required>
                                                                @if ($errors->has('name_edit'))
                                                                    <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('name_edit')  }}</strong>
                                                                        </span>
                                                                @endif
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label for="phone_number_edit_{{$transporter->id}}">Phone
                                                                    Number</label>
                                                                <input type="text" name="phone_number_edit"
                                                                       class="form-control {{ $errors->has('phone_number_edit') ? ' is-invalid' : '' }}"
                                                                       id="phone_number_edit_{{$transporter->id}}"
                                                                       value="{{ $transporter->phone_number }}"
                                                                       required>
                                                                @if ($errors->has('phone_number_edit'))
                                                                    <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('phone_number_edit')  }}</strong>
                                                                        </span>
                                                                @endif
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label for="location_{{$transporter->id}}">Location</label>
                                                                <textarea name="location_edit"
                                                                          class="form-control {{ $errors->has('name_edit') ? ' is-invalid' : '' }}"
                                                                          id="location_{{$transporter->id}}"
                                                                          placeholder="Van"
                                                                          value="{{ $transporter->name }}"
                                                                          required>{{ $transporter->location }}</textarea>
                                                                @if ($errors->has('location_edit'))
                                                                    <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('location_edit')  }}</strong>
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
                    <h4 class="card-title">Vehicles</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Registration Number</th>
                                <th>Vehicle Type</th>
                                <th>Vehicle Weight</th>
                                <th>Driver Name</th>
                                <th>Phone Number</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($transporter->vehicles)
                                @foreach($transporter->vehicles as $vehicle)
                                    <tr>
                                        <td>{{ $vehicle->registration_number }}</td>
                                        <td>{{ $vehicle->type->name }}</td>
                                        <td>{{ $vehicle->weight }}</td>
                                        <td>{{ $vehicle->driver_name }}</td>
                                        <td>{{ $vehicle->phone_no }}</td>
                                        <td>
                                            @if(has_right_permission(config('enums.system_modules')['Logistics']['transport_providers'], config('enums.system_permissions')['edit']))
                                                <button type="button" class="btn btn-info btn-rounded"
                                                        data-toggle="modal"
                                                        data-target="#editModal_{{$vehicle->id}}">
                                                    <span class="mdi mdi-file-edit"></span>
                                                </button>
                                            @endif

                                            {{--  modals edit start--}}
                                            <div class="modal fade" id="editModal_{{$vehicle->id}}" tabindex="-1"
                                                 role="dialog"
                                                 aria-labelledby="modalLabel_{{$vehicle->id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalLabel_{{$vehicle->id}}">
                                                                Edit {{ $vehicle->registration_number }}
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('cooperative.logistics.transporters.update-vehicle', [ 'id' => $transporter->id, 'vid' => $vehicle->id]) }}"
                                                              method="post">
                                                            <div class="modal-body">
                                                                @csrf
                                                                <div class="form-row">
                                                                    <div class="form-group col-6">
                                                                        <label for="registration_{{$vehicle->id}}">Registration
                                                                            Number</label>
                                                                        <input type="text" name="registration_edit"
                                                                               class="form-control {{ $errors->has('registration_edit') ? ' is-invalid' : '' }}"
                                                                               id="registration_{{$vehicle->id}}"
                                                                               value="{{ $vehicle->registration_number }}"
                                                                               required>
                                                                        @if ($errors->has('registration_number'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('registration_number')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="form-group col-6">
                                                                        <label for="vehicle_type_edit_{{$vehicle->id}}">Vehicle Type</label>
                                                                        <select name="vehicle_type_edit"
                                                                                class="form-control select2bs4 {{ $errors->has('vehicle_type_edit') ? ' is-invalid' : '' }}"
                                                                                id="vehicle_type_edit_{{$vehicle->id}}"
                                                                                required>
                                                                            @foreach ($vehicleTypes as $type)
                                                                                <option value="{{ $type->id }}"
                                                                                        @if($type->id == $vehicle->vehicle_type_id) selected="selected" @endif>{{ $type->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @if ($errors->has('vehicle_type_edit'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('vehicle_type_edit')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="form-group col-6">
                                                                        <label for="weight_edit_{{$vehicle->id}}">Weight</label>
                                                                        <input type="number" name="weight_edit"
                                                                               class="form-control {{ $errors->has('weight_edit') ? ' is-invalid' : '' }}"
                                                                               id="weight_edit_{{$vehicle->id}}"
                                                                               value="{{ $vehicle->weight }}" required>
                                                                        @if ($errors->has('weight_edit'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('weight_edit')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="form-group col-6">
                                                                        <label for="driver_name_edit_{{$vehicle->id}}">Driver Name</label>
                                                                        <input name="driver_name_edit"
                                                                               class="form-control {{ $errors->has('driver_name_edit') ? ' is-invalid' : '' }}"
                                                                               id="driver_name_edit_{{$vehicle->id}}"
                                                                               value="{{ $vehicle->driver_name }}" required>
                                                                        @if ($errors->has('driver_name_edit'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('driver_name_edit')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="form-group col-6">
                                                                        <label for="phone_number_edit_{{$vehicle->id}}">Phone Number</label>
                                                                        <input name="phone_number_edit"
                                                                               class="form-control {{ $errors->has('phone_number_edit') ? ' is-invalid' : '' }}"
                                                                               id="phone_number_edit_{{$vehicle->id}}"
                                                                               value="{{ $vehicle->phone_no }}" required>
                                                                        @if ($errors->has('phone_number_edit'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('phone_number_edit')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Save
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
                            @else
                                <tr align="center">
                                    <td colspan="4"><em>No vehicles</em></td>
                                </tr>
                            @endif
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
