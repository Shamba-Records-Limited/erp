@extends('layouts.app')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/override.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/full-calendar/main.min.css') }}" type="text/css">
@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Vet & Extension Services']['bookings'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addCategoryAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addCategoryAccordion"><span class="mdi mdi-plus"></span>Add Booking
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addCategoryAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Event</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.vet.bookings.add') }}" method="post">
                                @csrf
                                <div class="form-row">

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="type">Booking Type</label>
                                        <select name="type" id="type"
                                                class=" form-control form-select {{ $errors->has('type') ? ' is-invalid' : '' }}"
                                                onchange="getVets('{{ 'vet' }}', '{{'type'}}')">
                                            <option value="">---Select Booking Type---</option>
                                            @foreach(config('enums.vet_service_types')[0] as $type)
                                                <option value="{{$type}}"> {{$type}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('type'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('type')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="vet">Vet/Extension Officer</label>
                                        <select name="vet" id="vet"
                                                class=" form-control form-select {{ $errors->has('vet') ? ' is-invalid' : '' }}">

                                        </select>
                                        @if ($errors->has('vet'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('vet')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="farmer">Farmer</label>
                                        <select name="farmer" id="farmer"
                                                class=" form-control form-select {{ $errors->has('farmer') ? ' is-invalid' : '' }}">
                                            <option value="">---Select Farmer---</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}"> {{ ucwords(strtolower($user->first_name.' '.$user->other_names))}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('farmer'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('farmer')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="start">Start</label>
                                        <input type="datetime-local" name="start"
                                               class="form-control {{ $errors->has('start') ? ' is-invalid' : '' }}?>"
                                               id="start" value=" {{ old('start') }}" required>

                                        @if($errors->has('start'))
                                            : ?>
                                            <span class="help-block text-danger">
                                            <strong> {{  $errors->first('start') }} </strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="duration">Duration (HRs)</label>
                                        <input type="number" name="duration"
                                               class="form-control {{ $errors->has('duration') ? ' is-invalid' : '' }}?>"
                                               id="start_date" value=" {{ old('duration') }}" required placeholder="2">

                                        @if($errors->has('duration'))
                                            : ?>
                                            <span class="help-block text-danger">
                                            <strong> {{  $errors->first('duration') }} </strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="service">Service</label>
                                        <select name="service" id="service"
                                                class=" form-control form-select {{ $errors->has('service') ? ' is-invalid' : '' }}">
                                            <option value="">---Select Service---</option>
                                            @foreach($services as $service)
                                                <option value="{{$service->id}}"> {{$service->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('service'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('service')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="booking_details">Booking Details</label>
                                        <textarea name="booking_details"
                                                  class="form-control {{ $errors->has('booking_details') ? ' is-invalid' : '' }}"
                                                  id="booking_details" rows="4">{{ old('booking_details')}}</textarea>

                                        @if ($errors->has('booking_details'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('booking_details')  }}</strong>
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
                    @if(has_right_permission(config('enums.system_modules')['Vet & Extension Services']['bookings'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('cooperative.vet.bookings.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('cooperative.vet.bookings.download','xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('cooperative.vet.bookings.download', 'pdf') }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Bookings</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Farmer</th>
                                <th>Purpose</th>
                                <th>Vet</th>
                                <th>Booking Type</th>
                                <th>Service</th>
                                <th>Vet Charges</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $pending = config('enums.booking_status')[0][0];
                                $checked = config('enums.booking_status')[0][1];
                                $resolved = config('enums.booking_status')[0][2];
                                $currency = Auth::user()->cooperative->currency
                            @endphp
                            @foreach($bookings as $key => $booking)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ucwords(strtolower($booking->farmer->first_name) . ' ' . strtolower($booking->farmer->other_names)) }}</td>
                                    <td>{{$booking->event_name}}</td>
                                    <td>{{ucwords(strtolower($booking->vet->first_name) . ' ' . strtolower($booking->vet->other_names))}}</td>
                                    <td>{{$booking->booking_type }}</td>
                                    <td>{{ $booking->service ? $booking->service->name : '-' }}</td>
                                    <td>{{ $currency.' '.number_format($booking->charges)  }}</td>
                                    <td>
                                        <div class="badge text-white {{ $booking->status == $pending  ?  'badge-warning' :   ($booking->status == $checked ? 'badge-success': 'badge-info')}} mr-2">
                                            {{ $booking->status }}
                                        </div>
                                    </td>
                                    <td>
                                        @if(has_right_permission(config('enums.system_modules')['Vet & Extension Services']['bookings'], config('enums.system_permissions')['edit']))

                                            <button type="button" class="btn btn-sm btn-info btn-rounded"
                                                    data-toggle="modal"
                                                    {{$booking->status == $resolved ? 'disabled' : ''}}
                                                    data-target="#editModal_{{$booking->id}}"><span
                                                        class="mdi mdi-file-edit"></span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger btn-rounded"
                                                    data-toggle="modal"
                                                    {{$booking->status == $resolved ? 'disabled' : ''}}
                                                    data-target="#status_{{$booking->id}}">
                                                <span class="mdi">Update Status</span>
                                            </button>

                                            <button type="button" class="btn btn-sm btn-success btn-rounded"
                                                    data-toggle="modal"
                                                    {{$booking->status == $resolved ? 'disabled' : ''}}
                                                    data-target="#vetItems_{{$booking->id}}">
                                                <span class="mdi">Add Vet Items</span>
                                            </button>
                                        @endif
                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$booking->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$booking->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$booking->id}}">
                                                            Edit {{$booking->event_name}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.edit.vet-booking', $booking->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="type">Booking Type</label>
                                                                    <select name="edit_type" id="type_{{$booking->id}}"
                                                                            class=" form-control form-select {{ $errors->has('edit_type') ? ' is-invalid' : '' }}"
                                                                            onchange="getVets('{{ 'vet_'.$booking->id }}', '{{'type_'.$booking->id}}')">
                                                                        <option value="">---Select Booking Type---
                                                                        </option>
                                                                        @foreach(config('enums.vet_service_types')[0] as $type)
                                                                            <option value="{{$type}}"> {{$type}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_type'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_type')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="vet_{{$booking->id}}">Vet/Extension
                                                                        Officer</label>
                                                                    <select name="edit_vet" id="vet_{{$booking->id}}"
                                                                            class=" form-control form-select {{ $errors->has('edit_vet') ? ' is-invalid' : '' }}">
                                                                    </select>
                                                                    @if ($errors->has('edit_vet'))
                                                                        <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('edit_vet')  }}</strong>
                                                                            </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="farmer_{{$booking->id}}">Farmer</label>
                                                                    <select name="edit_farmer"
                                                                            id="farmer_{{$booking->id}}"
                                                                            class=" form-control form-select {{ $errors->has('edit_farmer') ? ' is-invalid' : '' }}">
                                                                        <option value="">---Select Farmer---</option>
                                                                        @foreach($users as $user)
                                                                            <option value="{{$user->id}}" {{ $user->id == $booking->farmer_id ? 'selected' : '' }}> {{ ucwords(strtolower($user->first_name.' '.$user->other_names))}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_farmer'))
                                                                        <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('edit_farmer')  }}</strong>
                                                                            </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="start_{{$booking->id}}">Start
                                                                        ({{ $booking->event_start }})</label>
                                                                    <input type="datetime-local" name="edit_start"
                                                                           class="form-control {{ $errors->has('edit_start') ? ' is-invalid' : '' }}?>"
                                                                           id="start_{{$booking->id}}">

                                                                    @if($errors->has('edit_start'))
                                                                        <span class="help-block text-danger">
                                                                            <strong> {{  $errors->first('edit_start') }} </strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                @php
                                                                    $start = \Carbon\Carbon::parse($booking->event_start);
                                                                    $end = \Carbon\Carbon::parse($booking->event_end);
                                                                    $duration = $end->diffInHours($start);
                                                                @endphp

                                                                <div class="form-group col-12">
                                                                    <label for="duration_{{$booking->id}}">Duration
                                                                        (HRs)</label>
                                                                    <input type="text" name="edit_duration"
                                                                           class="form-control {{ $errors->has('duration') ? ' is-invalid' : '' }}?>"
                                                                           id="duration_{{$booking->id}}"
                                                                           value=" {{$duration }}" required>

                                                                    @if($errors->has('edit_duration'))
                                                                        <span class="help-block text-danger">
                                                                            <strong> {{  $errors->first('edit_duration') }} </strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="service_{{$booking->id}}">Service</label>
                                                                    <select name="edit_service"
                                                                            id="service_{{$booking->id}}"
                                                                            class=" form-control form-select {{ $errors->has('edit_service') ? ' is-invalid' : '' }}">
                                                                        <option value="">---Select Service---</option>
                                                                        @foreach($services as $service)
                                                                            <option value="{{$service->id}}" {{ $booking->service_id == $service->id ? 'selected' : '' }}> {{$service->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_service'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_service')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="booking_details_{{$booking->id}}">Booking
                                                                        Details</label>
                                                                    <textarea name="edit_booking_details"
                                                                              class="form-control {{ $errors->has('edit_booking_details') ? ' is-invalid' : '' }}"
                                                                              id="booking_details_{{$booking->id}}"
                                                                              rows="4">{{ $booking->event_name}}</textarea>

                                                                    @if ($errors->has('edit_booking_details'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_booking_details')  }}</strong>
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

                                        {{--  modals status start--}}
                                        <div class="modal fade" id="status_{{$booking->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$booking->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$booking->id}}">
                                                            Update Status of {{$booking->event_name}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.edit.vet-booking.status', $booking->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">

                                                                <div class="form-group col-12">
                                                                    <label for="status_{{$booking->id}}">Status</label>
                                                                    <select name="status" id="status_{{$booking->id}}"
                                                                            class=" form-control form-select {{ $errors->has('status') ? ' is-invalid' : '' }}">
                                                                        @foreach(config('enums.booking_status')[0] as $status)
                                                                            <option value="{{$status}}" {{$booking->status == $status ? 'selected' : ''}}> {{$status}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('status'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('status')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="charges_{{$booking->id}}">Charges</label>
                                                                    <input type="text" name="charges"
                                                                           class="form-control {{ $errors->has('charges') ? ' is-invalid' : '' }}?>"
                                                                           id="charges_{{$booking->id}}"
                                                                           placeholder="2000"
                                                                           required>

                                                                    @if($errors->has('charges'))
                                                                        <span class="help-block text-danger">
                                                                            <strong> {{  $errors->first('charges') }} </strong>
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
                                        {{--  modal status end   --}}

                                        {{--  modals vet items start--}}
                                        <div class="modal fade" id="vetItems_{{$booking->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$booking->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$booking->id}}">
                                                            Add vet items of {{$booking->event_name}} Service</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.add.vet-booking.items', $booking->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="vet_items_{{$booking->id}}">Vet
                                                                        Items</label>
                                                                    <select name="item"
                                                                            id="vet_items_{{$booking->id}}"
                                                                            class=" form-control form-select {{ $errors->has('item') ? ' is-invalid' : '' }}">
                                                                        <option value="">---Select Item---</option>
                                                                        @foreach($vet_items as $item)
                                                                            <option value="{{$item->id}}"> {{$item->name.' ('.$item->quantity.')'}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('item'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('item')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="quantity_{{$booking->id}}">Quantity</label>
                                                                    <input type="text" name="quantity"
                                                                           class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}?>"
                                                                           id="quantity_{{$booking->id}}"
                                                                           placeholder="13"
                                                                           required>

                                                                    @if($errors->has('quantity'))
                                                                        <span class="help-block text-danger">
                                                                            <strong> {{  $errors->first('quantity') }} </strong>
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
                                        {{--  modal status end   --}}

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
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">All Vet Bookings</h4>
                    <div class="table-responsive">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/full-calendar/main.min.js') }}"></script>
    <script>
        const url = "{{ route('cooperative.vet.bookings.fetch') }}"
        axios.get(url).then(({data}) => {
            return data
        }).then(data => {
            let calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialDate: new Date(),
                initialView: 'listWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                editable: true,
                selectable: true,
                businessHours: true,
                dayMaxEvents: true,
                weekNumbers: true,
                weekNumberCalculation: 'ISO',
                events: data
            });

            calendar.render();
        })


        const getVets = (fieldId, primaryIdField) => {
            const id = "#" + primaryIdField;
            console.log(id);
            const bookingType = $(id).val();
            console.log(bookingType)
            let htmlCode = ``
            if (bookingType !== '') {
                getVetsByCategory(bookingType, fieldId)
            } else {
                htmlCode += `<option value="">---Select Vet---</option>`;
                $("#" + fieldId).html(htmlCode);
            }
        }
        const getVetsByCategory = (bookingType, fieldId) => {
            let url = '{{ route('cooperative.vets_by_category',":category") }}';
            url = url.replace(':category', bookingType);
            let htmlCode = ``;
            axios.get(url).then(res => {
                const data = res.data
                htmlCode += `<option value="">---Select Vet---</option>`;
                data.forEach(d => {
                    htmlCode += `<option value="${d.id}">${d.first_name + ' ' + d.other_names}</option>`;
                });
                $("#" + fieldId).html(htmlCode)
            }).catch(() => {
                htmlCode += `<option value="">---Select Vet---</option>`;
                $("#" + fieldId).html(htmlCode);
            })
        }
    </script>
@endpush

@push('custom-scripts')
@endpush
