@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    @php
        $dep = $trip->location('departure');
        $arr = $trip->location('arrival');
        $depWgt = $dep->weighBridgeEvent ? $dep->weighBridgeEvent->weight : '';
        $arrWgt = $dep->weighBridgeEvent ? $arr->weighBridgeEvent->weight : '';
    @endphp

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $dep->location->name }} / {{ $arr->location->name }} - {{ (new DateTime($trip->created_at))->format('Y-m-d h:i A') }}</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>Load Type/Unit</th>
                                    <th>Departure Location</th>
                                    <th>Departure Date</th>
                                    <th>Arrival Location</th>
                                    <th>Arrival Date</th>
                                </tr>
                                <tr>
                                    <td>{{ $trip->load_type }}/{{ $trip->unit->name }}</td>
                                    <td>{{ $dep->location->name }}</td>
                                    <td>{{ (new DateTime($dep->datetime))->format('Y-m-d h:i A') }}</td>
                                    <td>{{ $arr->location->name }}</td>
                                    <td>{{ (new DateTime($arr->datetime))->format('Y-m-d h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Distance/Cost</th>
                                    <th>Trip Total Cost</th>
                                    <th>Weight Discrepancy</th>
                                </tr>
                                <tr>
                                    <td>
                                        @if($trip->transport_type == 'OWN_VEHICLE') {{ $trip->vehicle->registration_number }} @endif
                                        @if($trip->transport_type == '3RD_PARTY') {{ $trip->transporterVehicle->registration_number }} @endif
                                    </td>
                                    <td>{{ $trip->driver_name }} ({{ str_replace('254', '0', $trip->driver_phone_number) }})</td>
                                    <td>{{ $trip->trip_distance }}/{{ $trip->trip_cost_per_km }}</td>
                                    <td>{{ number_format($trip->trip_cost_total, 2) }}</td>
                                    <td>{{ $depWgt > 0 && $arrWgt > 0 ? number_format(($depWgt - $arrWgt), 2) : '--' }}</td>
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
                    <h4 class="card-title">Trip Locations</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Location</th>
                                    <th>Type</th>
                                    <th>Booking Date</th>
                                    <th>Weight</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $dep = $trip->location('departure');
                                    $arr = $trip->location('arrival');
                                @endphp
                                <tr>
                                    <td>{{ $dep->location->name }}</td>
                                    <td>Departure</td>
                                    <td>{{ $dep->datetime }}</td>
                                    <td>{{ $dep->weighBridgeEvent->weight ? number_format($dep->weighBridgeEvent->weight, 2) : '--' }}</td>
                                    <td>
                                        @if($dep->weighBridgeEvent->weight == '')

                                            <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                data-target="#weightModal_{{$dep->id}}">
                                                <span class="mdi mdi-weight-kilogram"></span>
                                            </button>

                                            {{--  modals add weight start--}}
                                            <div class="modal fade" id="weightModal_{{$dep->id}}" tabindex="-1"
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

                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ $arr->location->name }}</td>
                                    <td>Arrival</td>
                                    <td>{{ $arr->datetime }}</td>
                                    <td>{{ $arr->weighBridgeEvent->weight ? number_format($arr->weighBridgeEvent->weight, 2) : '--' }}</td>
                                    <td>
                                        @if($arr->weighBridgeEvent->weight == '')

                                            <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                data-target="#weightModal_{{$arr->id}}">
                                                <span class="mdi mdi-weight-kilogram"></span>
                                            </button>

                                            {{--  modals add weight start--}}
                                            <div class="modal fade" id="weightModal_{{$arr->id}}" tabindex="-1"
                                                role="dialog"
                                                aria-labelledby="modalLabel_{{$arr->weighBridgeEvent->id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalLabel_{{$arr->id}}">
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

                                        @endif
                                    </td>
                                </tr>                                
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