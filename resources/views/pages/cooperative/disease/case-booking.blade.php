@extends('layouts.app')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/override.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/full-calendar/main.min.css') }}" type="text/css">
@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Disease Management']['disease_cases'], config('enums.system_permissions')['create']))
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


                            <form action="{{ route('cooperative.disease.case.book', $id) }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="vet">Vet</label>
                                        <select name="vet" id="vet"
                                                class=" form-control form-select {{ $errors->has('vet') ? ' is-invalid' : '' }}">
                                            <option value="">---Select Vet---</option>
                                            @foreach($vets as $vet)
                                                <option value="{{$vet->id}}"> {{ ucwords(strtolower($vet->first_name).' '.strtolower($vet->other_names) )}}</option>
                                            @endforeach

                                            @if ($errors->has('vet'))
                                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('vet')  }}</strong>
                                </span>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="start">Booking Date</label>
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
        axios.get(url).then(({data}) =>{
            return data
        }).then(data =>{
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
    </script>
@endpush

@push('custom-scripts')
@endpush
