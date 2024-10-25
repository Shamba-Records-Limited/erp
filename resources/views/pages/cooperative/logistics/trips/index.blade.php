@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Logistics']['trip_management'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#bookTripAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="bookTripAccordion">
                            <span class="mdi mdi-plus"></span> Book a Trip
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="bookTripAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Book a Trip</h4>
                                </div>
                            </div>

                            <form action="{{ route('cooperative.logistics.trips.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="provider">Transport Provider</label>
                                        <select name="provider"
                                                class="form-control select2bs4 {{ $errors->has('provider') ? ' is-invalid' : '' }}"
                                                id="provider" value="{{ old('provider')}}" required>
                                            <option value="">--Select Provider--</option>
                                            <option value="own_vehicle">Company Vehicle</option>
                                            @foreach($transporters as $transporter)
                                                <option value="{{ $transporter->id }}">{{ $transporter->name }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('provider'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('provider')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="vehicle">Vehicle</label>
                                        <select name="vehicle"
                                                class="form-control select2bs4 {{ $errors->has('vehicle') ? ' is-invalid' : '' }}"
                                                id="vehicle" value="{{ old('vehicle')}}" required>
                                            <option value="">--Select Vehicle--</option>
                                            @foreach($vehicles as $vehicle)
                                                <option value="{{ $vehicle->id }}">{{ $vehicle->registration_number }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('vehicle'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('vehicle')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12 own_driver" style="display: none;">
                                        <label for="driver">Driver</label>
                                        <select name="driver"
                                                class="form-control select2bs4 {{ $errors->has('driver') ? ' is-invalid' : '' }}"
                                                id="driver" value="{{ old('driver')}}">
                                            <option value="">--Select Driver--</option>
                                            @foreach($drivers as $driver)
                                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('vehicle'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('vehicle')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12 3rd_party_option"
                                         style="display: none;">
                                        <label for="driver_name">Driver Name</label>
                                        <input type="text" name="driver_name"
                                               class="form-control {{ $errors->has('driver_name') ? ' is-invalid' : '' }}"
                                               id="driver_name" value="{{ old('driver_name')}}">

                                        @if ($errors->has('driver_name'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('driver_name')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12 3rd_party_option"
                                         style="display: none;">
                                        <label for="driver_phone">Driver Phone</label>
                                        <input type="text" name="driver_phone"
                                               class="form-control {{ $errors->has('driver_phone') ? ' is-invalid' : '' }}"
                                               id="driver_phone" value="{{ old('driver_phone')}}">

                                        @if ($errors->has('driver_phone'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('driver_phone')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="departure_datetime">Departure Date/Time</label>
                                        <input type="datetime-local" name="departure_datetime"
                                               class="form-control {{ $errors->has('departure_datetime') ? ' is-invalid' : '' }}"
                                               id="departure_datetime" value="{{ old('departure_datetime')}}" required>

                                        @if ($errors->has('departure_datetime'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('departure_datetime')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-12">
                                        <x-location-picker key="departure" label="Departure Location" name="departure_location"
                                                           :cooperativeId="$coopId"/>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="departure_weighbridge">Departure Weighbridge</label>
                                        <select name="departure_weighbridge"
                                                class="form-control select2bs4 {{ $errors->has('departure_weighbridge') ? ' is-invalid' : '' }}"
                                                id="departure_weighbridge" value="{{ old('departure_weighbridge')}}"
                                                required>
                                            <option value="">--Select Weighbridge--</option>
                                            @foreach($weighbridges as $weighbridge)
                                                <option value="{{ $weighbridge->id }}">{{ $weighbridge->code }}
                                                    - {{ $weighbridge->location->name ?? '' }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('departure_weighbridge'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('departure_weighbridge')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="arrival_datetime">Arrival Date/Time</label>
                                        <input type="datetime-local" name="arrival_datetime"
                                               class="form-control {{ $errors->has('arrival_datetime') ? ' is-invalid' : '' }}"
                                               id="arrival_datetime" value="{{ old('arrival_datetime')}}" required>

                                        @if ($errors->has('arrival_datetime'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('arrival_datetime')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-12">
                                        <x-location-picker key="arrival" label="Arrival Location" name="arrival_location"
                                                           :cooperativeId="$coopId"/>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="arrival_weighbridge">Arrival Weighbridge</label>
                                        <select name="arrival_weighbridge"
                                                class="form-control select2bs4 {{ $errors->has('arrival_weighbridge') ? ' is-invalid' : '' }}"
                                                id="arrival_weighbridge" value="{{ old('arrival_weighbridge')}}"
                                                required>
                                            <option value="">--Select Weighbridge--</option>
                                            @foreach($weighbridges as $weighbridge)
                                                <option value="{{ $weighbridge->id }}">{{ $weighbridge->code }}
                                                    - {{ $weighbridge->location->name ?? '' }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('arrival_weighbridge'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('arrival_weighbridge')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="load_type">Load Type</label>
                                        <input type="text" name="load_type"
                                               class="form-control {{ $errors->has('load_type') ? ' is-invalid' : '' }}"
                                               id="load_type" value="{{ old('load_type')}}">

                                        @if ($errors->has('load_type'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('load_type')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="load_unit">Load Unit</label>
                                        <select name="load_unit"
                                                class="form-control select2bs4 {{ $errors->has('load_unit') ? ' is-invalid' : '' }}"
                                                id="arrival_location" value="{{ old('load_unit')}}" required>
                                            <option value="">--Select Unit--</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('load_unit'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('load_unit')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12" style="display: none;">
                                        <label for="trip_distance">Trip Distance (Km)</label>
                                        <input type="number" name="trip_distance" min="1"
                                               class="form-control {{ $errors->has('trip_distance') ? ' is-invalid' : '' }}"
                                               id="trip_distance" value="{{ old('trip_distance')}}">

                                        @if ($errors->has('trip_distance'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('trip_distance')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="trip_cost">Trip Cost (Per Kg/Km)</label>
                                        <input type="number" name="trip_cost" min="1"
                                               class="form-control {{ $errors->has('trip_cost') ? ' is-invalid' : '' }}"
                                               id="trip_cost" value="{{ old('trip_cost')}}">

                                        @if ($errors->has('trip_cost'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('trip_cost')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-3 col-12">
                                        <label for=""></label>
                                        <button type="submit" class="btn btn-primary btn-fw btn-block">Book</button>
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
                    <h4 class="card-title">Booked Trips</h4>

                    <div class="mb-5">

                        <form action="{{ route('cooperative.logistics.trips') }}" method="get">
                            @csrf
                            <input type="hidden" name="download" value="csv" />
                            <button type="submit"
                                class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('cooperative.logistics.trips') }}" method="get">
                            @csrf
                            <input type="hidden" name="download" value="xlsx" />
                            <button type="submit"
                                class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>

                        <form action="{{ route('cooperative.logistics.trips') }}" method="get">
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
                                <th>Transport Provider</th>
                                <th>Load Type</th>
                                <th>Departure Date</th>
                                <th>Departure Location</th>
                                <th>Departure Load Weight</th>
                                <th>Arrival Date</th>
                                <th>Arrival Location</th>
                                <th>Arrival Load Weight</th>
                                <th>Discrepancy</th>
                                <th>Trip Cost (Ksh)</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($trips as $trip)
                                @php
                                    $dep = $trip->location('departure');
                                    $arr = $trip->location('arrival');
                                    $depWgt = $dep->weighBridgeEvent ? $dep->weighBridgeEvent->weight : '';
                                    $arrWgt = $dep->weighBridgeEvent ? $arr->weighBridgeEvent->weight : '';
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ route('cooperative.logistics.trips.show', $trip->id) }}">#{{ $loop->iteration }}</a>
                                    </td>
                                    <td>
                                        @if($trip->transport_type == 'OWN_VEHICLE')
                                            Company Vehicle
                                        @else
                                            {{ $trip->transportProvider->name }}
                                        @endif
                                    </td>
                                    <td>{{ $trip->load_type }}</td>
                                    <td>{{ (new DateTime($dep->datetime))->format('Y-m-d h:i A') ?? '' }}</td>
                                    <td>{{ $dep->location->name ?? '' }}</td>
                                    <td>
                                        @if($depWgt)
                                            {{ number_format($depWgt, 2) }}
                                        @else
                                            @if(has_right_permission(config('enums.system_modules')['Logistics']['trip_management'], config('enums.system_permissions')['edit']))
                                                <button type="button" class="btn btn-info btn-rounded" data-toggle="modal" data-target="#addDepartureWeight_{{$trip->id}}">
                                                    <span class="mdi mdi-weight-kilogram"></span> Add Weight
                                                </button>
                                            @else 
                                                --
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ (new DateTime($arr->datetime))->format('Y-m-d h:i A') ?? '' }}</td>
                                    <td>{{ $arr->location->name ?? '' }}</td>
                                    <td>
                                        @if($arrWgt) 
                                            {{ number_format($arrWgt, 2) }}
                                        @else
                                            @if(has_right_permission(config('enums.system_modules')['Logistics']['trip_management'], config('enums.system_permissions')['edit']))
                                                <button type="button" class="btn btn-info btn-rounded" data-toggle="modal" data-target="#addArrivalWeight_{{$trip->id}}">
                                                    <span class="mdi mdi-weight-kilogram"></span> Add Weight
                                                </button>
                                            @else 
                                                --
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ $depWgt > 0 && $arrWgt > 0 ? number_format(($depWgt - $arrWgt), 2) : '--' }}</td>
                                    <td>{{ number_format($trip->trip_cost_total, 2) }}</td>
                                    <td>
                                        @if(has_right_permission(config('enums.system_modules')['Logistics']['trip_management'], config('enums.system_permissions')['edit']))
                                            <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                    style="display: none;"
                                                    data-target="#editModal_{{$trip->id}}">
                                                <span class="mdi mdi-file-edit"></span>
                                            </button>
                                        @endif

                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$trip->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$trip->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$trip->id}}">
                                                            Edit {{$trip->name}} Vehicle Type
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.logistics.trips.update', $trip->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="name_edit_{{$trip->id}}">Name</label>
                                                                    <input type="text" name="name_edit"
                                                                           class="form-control {{ $errors->has('name_edit') ? ' is-invalid' : '' }}"
                                                                           id="name_edit_{{$trip->id}}"
                                                                           value="{{ $trip->name }}" required>
                                                                    @if ($errors->has('name_edit'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('name_edit')  }}</strong>
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

                                        {{--  modals add departure weight start --}}
                                            <div class="modal fade" id="addDepartureWeight_{{$trip->id}}" tabindex="-1"
                                                role="dialog"
                                                aria-labelledby="modalLabel_{{$dep->weighBridgeEvent->id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalLabel_{{$dep->id}}">
                                                                Record Departure Weight
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{route('cooperative.logistics.trips.record-weight', $trip->id)}}"
                                                                method="post">
                                                            <div class="modal-body">
                                                                @csrf
                                                                <div class="form-row">
                                                                    <div class="form-group col-12">
                                                                        <label for="weight_{{$dep->weighBridgeEvent->id}}">Weight (Kgs)</label>
                                                                        <input type="number" min="1"
                                                                            name="weight"
                                                                            class="form-control {{ $errors->has('weight') ? ' is-invalid' : '' }}"
                                                                            id="weight_{{$dep->weighBridgeEvent->id}}" required>
                                                                        @if ($errors->has('weight'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('weight')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="event_id" value="{{ $dep->weighBridgeEvent->id }}" />

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Record Weight</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        {{--  modal end   --}}

                                        {{--  modals add arrival weight start --}}
                                            <div class="modal fade" id="addArrivalWeight_{{$trip->id}}" tabindex="-1"
                                                role="dialog"
                                                aria-labelledby="modalLabel_{{$arr->weighBridgeEvent->id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalLabel_{{$dep->id}}">
                                                                Record Arrival Weight
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{route('cooperative.logistics.trips.record-weight', $trip->id)}}"
                                                                method="post">
                                                            <div class="modal-body">
                                                                @csrf
                                                                <div class="form-row">
                                                                    <div class="form-group col-12">
                                                                        <label for="weight_{{$arr->weighBridgeEvent->id}}">Weight (Kgs)</label>
                                                                        <input type="number" min="1"
                                                                            name="weight"
                                                                            class="form-control {{ $errors->has('weight') ? ' is-invalid' : '' }}"
                                                                            id="weight_{{$arr->weighBridgeEvent->id}}" required>
                                                                        @if ($errors->has('weight'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('weight')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="event_id" value="{{ $arr->weighBridgeEvent->id }}" />

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Record Weight</button>
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
    <script>
        let vehicles = [];
        let transportProvider = '';

        $(function () {
            $('select#provider').on('change', async function (e) {
                const value = e.target.value;
                transportProvider = value;

                $('input#driver_name').val('');
                $('input#driver_phone').val('');

                if (value == 'own_vehicle' || value == '') {
                    $('.3rd_party_option').hide();
                    $('.own_driver').show();
                } else {
                    $('.own_driver').hide();
                    $('.3rd_party_option').show();
                }
                await fetchTransportVehiclesById(value);
            });

            $('select#vehicle').on('change', function (e) {
                const vehicleId = e.target.value;
                const vehicle = vehicles.filter(({ id }) => id == vehicleId);

                if (transportProvider && vehicle.length > 0) {
                    if (transportProvider == 'own_vehicle') {
                        $('select#driver').val(vehicle[0].user_id).trigger('change');
                    } else {
                        $('input#driver_name').val(vehicle[0].driver_name);
                        $('input#driver_phone').val(vehicle[0].phone_no);
                    }
                }
            });
        });

        async function fetchTransportVehiclesById(id) {
            vehicles = [];
            let options = "<option value=''>--Select Vehicle--</option>";
            $('#vehicle').html(options);
            const vehiclesData = await axios.get(`/cooperative/logistics/transporters/${id}/vehicles`);
            vehiclesData.data.forEach(function (vehicle) {
                vehicles.push(vehicle);
                options += `<option value="${vehicle.id}">${vehicle.registration_number}</option>`;
            });
            $('#vehicle').html(options);
        }
    </script>
@endpush
