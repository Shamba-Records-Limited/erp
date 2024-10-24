@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Logistics']['vehicles'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addVehicleAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addVehicleAccordion">
                            <span class="mdi mdi-plus"></span>Add Vehicle
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addVehicleAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Vehicle</h4>
                                </div>
                            </div>

                            <form action="{{ route('cooperative.logistics.vehicles.add') }}" method="post">
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
                                                class="form-control form-select {{ $errors->has('vehicle_type') ? ' is-invalid' : '' }}"
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
                                        <label for="driver">Driver</label>
                                        <select id="driver" name="driver"
                                                class="form-control form-select {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                placeholder="Van" value="{{ old('driver')}}" required>
                                            <option value="">select</option>
                                            @foreach ($drivers as $driver)
                                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('driver'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('driver')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row">
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
                                        <label for="status">Status</label>
                                        <select id="status" name="status"
                                                class="form-control form-select {{ $errors->has('status') ? ' is-invalid' : '' }}"
                                                value="{{ old('status')}}" required>
                                            <option value="">select</option>
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
                                    <div class="form-group col-lg-4 col-md-6 col-12">
                                        <label for="status_comment">Status comment</label>
                                        <input name="status_comment"
                                               class="form-control {{ $errors->has('status_comment') ? ' is-invalid' : '' }}"
                                               id="status_comment" value="{{ old('status_comment')}}">

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
                    <h4 class="card-title">Registered Vehicles</h4>

                    <div class="mb-5">

                        <form action="{{ route('cooperative.logistics.vehicles') }}" method="get">
                            @csrf
                            <input type="hidden" name="download" value="csv" />
                            <button type="submit"
                                class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('cooperative.logistics.vehicles') }}" method="get">
                            @csrf
                            <input type="hidden" name="download" value="xlsx" />
                            <button type="submit"
                                class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>

                        <form action="{{ route('cooperative.logistics.vehicles') }}" method="get">
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
                                <th>Registration Number</th>
                                <th>Vehicle Type</th>
                                <th>Driver</th>
                                <th>Vehicle Weight</th>
                                <th>Status</th>
                                <th>Status Comment</th>
                                <th>Status Date</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vehicles as $vehicle)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $vehicle->registration_number }}</td>
                                    <td>{{ $vehicle->type->name }}</td>
                                    <td>{{ optional($vehicle->driver)->first_name }} {{ optional($vehicle->driver)->other_names }}</td>
                                    <td>{{ $vehicle->weight }}</td>
                                    <td>{{ $vehicle->statusText() }}</td>
                                    <td>{{ $vehicle->status_comment }}</td>
                                    <td>{{ $vehicle->status_date }}</td>
                                    <td>
                                        @if(has_right_permission(config('enums.system_modules')['Logistics']['vehicle_types'], config('enums.system_permissions')['edit']))
                                            <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
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
                                                            Edit Vehicle
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.logistics.vehicles.update', $vehicle->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-6">
                                                                    <label for="registration_edit_{{$vehicle->id}}">Registration Number</label>
                                                                    <input type="text" name="registration_edit"
                                                                           class="form-control {{ $errors->has('registration_edit') ? ' is-invalid' : '' }}"
                                                                           id="registration_edit_{{$vehicle->id}}"
                                                                           placeholder="KCD908T"
                                                                           value="{{ $vehicle->registration_number }}" required>
                                                                    @if ($errors->has('registration_edit'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('registration_edit') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-6">
                                                                    <label for="vehicle_type_edit_{{$vehicle->id}}">Vehicle Type</label>
                                                                    <select id="vehicle_type_edit_{{$vehicle->id}}" name="vehicle_type_edit"
                                                                            class="form-control form-select {{ $errors->has('vehicle_type_edit') ? ' is-invalid' : '' }}"
                                                                            placeholder="Van" required>
                                                                        <option value="">select</option>
                                                                        @foreach ($vehicleTypes as $type)
                                                                            <option 
                                                                                value="{{ $type->id }}"
                                                                                @if($vehicle->type->id == $type->id) selected @endif>
                                                                                {{ $type->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('vehicle_type_edit'))
                                                                        <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('vehicle_type_edit')  }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-6">
                                                                    <label for="driver_edit_{{$vehicle->id}}">Driver</label>
                                                                    <select id="driver_edit_{{$vehicle->id}}" name="driver_edit"
                                                                        class="form-control form-select {{ $errors->has('driver_edit') ? ' is-invalid' : '' }}"
                                                                        required>
                                                                        <option value="">select</option>
                                                                        @foreach ($drivers as $driver)
                                                                            <option 
                                                                                value="{{ $driver->id }}"
                                                                                @if(optional($vehicle->driver)->id == $driver->id) selected @endif>
                                                                                {{ $driver->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('drive'))
                                                                        <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('driver')  }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-6">
                                                                    <label for="vehicle_weight_edit_{{$vehicle->id}}">Vehicle Weight (kgs)</label>
                                                                    <input type="number" name="vehicle_weight_edit"
                                                                           class="form-control {{ $errors->has('vehicle_weight_edit') ? ' is-invalid' : '' }}"
                                                                           id="vehicle_weight_edit_{{$vehicle->id}}"
                                                                           value="{{ $vehicle->weight }}" required>
                                                                    @if ($errors->has('vehicle_weight_edit'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('vehicle_weight_edit')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-6">
                                                                    <label for="status_edit_{{$vehicle->id}}">Status</label>
                                                                    <select id="status_edit_{{$vehicle->id}}" name="status_edit"
                                                                            class="form-control form-select {{ $errors->has('status_edit') ? ' is-invalid' : '' }}"
                                                                            required>
                                                                        <option value="">select</option>
                                                                        <option value="1" @if($vehicle->status == 1) selected @endif>Active</option>
                                                                        <option value="2" @if($vehicle->status == 2) selected @endif>Service</option>
                                                                        <option value="3" @if($vehicle->status == 3) selected @endif>Closed</option>
                                                                    </select>

                                                                    @if ($errors->has('status_edit'))
                                                                        <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('status_edit') }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-6">
                                                                    <label for="status_comment_edit_{{$vehicle->id}}">Status comment</label>
                                                                    <input id="status_comment_edit_{{$vehicle->id}}" name="status_comment_edit"
                                                                        class="form-control {{ $errors->has('status_comment') ? ' is-invalid' : '' }}"
                                                                        id="status_comment" value="{{$vehicle->status_comment}}">

                                                                    @if ($errors->has('status_comment'))
                                                                        <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('status_comment') }}</strong>
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
