@extends('layouts.app')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/override.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/full-calendar/main.min.css') }}" type="text/css">
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#addFarmerCropStageTracker"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="addCrop"><span class="mdi mdi-plus"></span>Add Stage Tracker
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="addFarmerCropStageTracker">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Add Farmer Crop/Livestock/Poultry Stage Tracker</h4>
                            </div>
                        </div>

                        <form action="{{ route('farm.crop-stages.tracker.add',$farmer_crop_id) }}"
                              method="post">
                            <input type="hidden" id="farmer_crop_id" value="{{$farmer_crop_id}}">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="stage">Stage</label>
                                    <select name="stage" id="stage"
                                            class=" form-control form-select {{ $errors->has('stage') ? ' is-invalid' : '' }}">
                                        <option value="">--None--</option>
                                        @foreach($stages as $stage)
                                            <option value="{{$stage->id}}"> {{ ucwords( strtolower($stage->name)) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('stage'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('stage')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" name="start_date"
                                           class="form-control {{ $errors->has('start_date') ? ' is-invalid' : '' }}"
                                           id="start_date">

                                    @if ($errors->has('start_date'))
                                        <span class="help-block text-danger">
                                        <strong>{{ $errors->first('start_date')  }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="next_stage">Next Stage</label>
                                    <select name="next_stage" id="next_stage"
                                            class=" form-control form-select {{ $errors->has('next_stage') ? ' is-invalid' : '' }}">
                                        <option value="">--None--</option>
                                        @foreach($stages as $stage)
                                            <option value="{{$stage->id}}"> {{ ucwords( strtolower($stage->name)) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('next_stage'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('next_stage')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="status">Status</label>
                                    <select name="status" id="status"
                                            class=" form-control form-select {{ $errors->has('status') ? ' is-invalid' : '' }}">
                                        @foreach(config('enums')["farmer_crop_status"][0] as $status)
                                            <option value="{{$status}}">{{ucwords($status)}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('status'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('status')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">

                                    <button type="button" class="mt-4 ml-2 btn btn-info btn-rounded"
                                            data-toggle="tooltip" data-placement="top"
                                            title="Click to break down your costs">
                                        <span data-toggle="modal" data-target="#costModal">Cost Breakdown</span>
                                    </button>

                                    {{--  modals edit start--}}
                                    <div class="modal fade" id="costModal" tabindex="-1" role="dialog"
                                         aria-labelledby="costModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="costModalLabel">
                                                        Cost Breakdown
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <table class="table table-hover table-striped"
                                                                   id="costTable">
                                                                <thead class="thead-dark">
                                                                <th>Item</th>
                                                                <th>Cost</th>
                                                                </thead>
                                                                <tbody>

                                                                </tbody>
                                                                <tfoot>
                                                                <th>Total</th>
                                                                <th id="totalCost">0</th>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="form-row mt-2">
                                                        <div class="form-group col-12">
                                                            <label for="item">Item</label>
                                                            <input type="text" name="item" class="form-control"
                                                                   id="item" placeholder="Seeds">
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <label for="amount">Cost</label>
                                                            <input type="number" name="amount" class="form-control"
                                                                   id="amount" placeholder="1000">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary"
                                                            onclick="costBreakDown()">
                                                        Add
                                                    </button>
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">
                                                        Close
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    {{--  modal end   --}}
                                    <input type="hidden" name="cost" class="form-control" id="cost"
                                           value="{{ old('cost')}}">

                                    @if ($errors->has('cost'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('cost')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit" class="btn btn-primary btn-fw btn-block">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">My Crop/Livestock/Poultry Calendars</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Crop</th>
                                <th>Crop Variety</th>
                                <th>Current Stage</th>
                                <th>Start Date</th>
                                <th>Last Date</th>
                                <th>Next Stage</th>
                                <th>Status</th>
                                <th>Cost</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $currency = Auth::user()->cooperative->currency @endphp
                            @foreach($trackers as $key => $tracker)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{  $tracker->farmer_crop->type == 1 ?  ($tracker->farmer_crop->crop->product_id ? ucwords(strtolower($tracker->farmer_crop->crop->product->name)) : '-') :  ucwords(strtolower($tracker->farmer_crop->livestock->name.', '.$tracker->farmer_crop->livestock->animal_type))}}</td>
                                    <td>{{ $tracker->farmer_crop->type == 1  ? ucwords(strtolower($tracker->farmer_crop->crop->variety)) : ucwords(strtolower($tracker->farmer_crop->livestock->breed->name)) }}</td>
                                    <td>{{ ucwords(strtolower($tracker->stage->name)) }}</td>
                                    <td>{{ $tracker->start_date ?? '-' }}</td>
                                    <td>{{ $tracker->last_date }}</td>
                                    <td>{{ $tracker->next_stage ? ucwords(strtolower($tracker->next_stage->name)) : '-'}}</td>
                                    <td>{{ $tracker->status }}</td>
                                    <td><a href="{{ route('farm.crop-stages.tracker.cost_breakdown', $tracker->id) }}">{{ $currency.' '.$tracker->cost }}</a></td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                data-target="#editModal_{{$tracker->id}}"><span
                                                    class="mdi mdi-file-edit"></span></button>

                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$tracker->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$tracker->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$tracker->id}}">
                                                            Edit Stage</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.farm.edit.tracker-progress', $tracker->id)}}"
                                                          method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="stage_{{$tracker->id}}">Stage</label>
                                                                    <select name="edit_stage"
                                                                            id="stage_{{$tracker->id}}"
                                                                            class=" form-control form-select {{ $errors->has('edit_stage') ? ' is-invalid' : '' }}">
                                                                        @foreach($stages as $stage)
                                                                            <option value="{{$stage->id}}" {{ $tracker->stage_id == $stage->id ? 'selected' : ''}}> {{ ucwords( strtolower($stage->name)) }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_stage'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_stage')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_start_date_{{$tracker->id}}">Start
                                                                        Date</label>
                                                                    <input type="date" name="edit_start_date"
                                                                           class="form-control {{ $errors->has('edit_start_date') ? ' is-invalid' : '' }}"
                                                                           id="edit_start_date_{{$tracker->id}}"
                                                                           value="{{$tracker->start_date}}">

                                                                    @if ($errors->has('edit_start_date'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_start_date')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="next_stage_{{$tracker->id}}">Next
                                                                        Stage</label>
                                                                    <select name="edit_next_stage"
                                                                            id="next_stage_{{$tracker->id}}"
                                                                            class=" form-control form-select {{ $errors->has('edit_next_stage') ? ' is-invalid' : '' }}">
                                                                        <option value="">--None--</option>
                                                                        @foreach($stages as $stage)
                                                                            <option value="{{$stage->id}}" {{ $tracker->next_stage_id == $stage->id ? 'selected' : ''}}> {{ ucwords( strtolower($stage->name)) }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_next_stage'))
                                                                        <span class="help-block text-danger">
                                                                                        <strong>{{ $errors->first('edit_next_stage')  }}</strong>
                                                                                    </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="status_{{$tracker->id}}">Status</label>
                                                                    <select name="edit_status"
                                                                            id="status_{{$tracker->id}}"
                                                                            class=" form-control form-select {{ $errors->has('edit_status') ? ' is-invalid' : '' }}">
                                                                        @foreach(config('enums')["farmer_crop_status"][0] as $status)
                                                                            <option value="{{$status}}" {{ $tracker->status == $status ? 'selected' : ''}}>{{ucwords($status)}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_status'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_status')  }}</strong>
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

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Calendar View</h4>
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
        let url = "{{ route('farm.crop-stages-calendar-data', ':farmer_crop_id') }}"
        const farmer_crop_id = $("#farmer_crop_id").val();
        url = url.replace(':farmer_crop_id', farmer_crop_id);
        axios.post(url).then(({data}) => {
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

        var costBreakDownObj = []
        const costBreakDown = () => {
            const item = $("#item").val()
            const amount = $("#amount").val()
            if (amount && item) {
                const initialCost = Number($("#totalCost").text());
                const tableBody = document.getElementById('costTable').getElementsByTagName('tbody')[0]

                const newRow = tableBody.insertRow()
                const itemCell = newRow.insertCell(0)
                const amountCell = newRow.insertCell(1)

                $("#totalCost").text(initialCost + Number(amount))

                itemCell.innerHTML = item
                amountCell.innerHTML = amount

                const obj = {"item": item, "amount": amount}
                costBreakDownObj.push(obj)

                $("#cost").val(JSON.stringify(costBreakDownObj));
                $("#item").val("");
                $("#amount").val("");
            }
        }
    </script>
@endpush

@push('custom-scripts')
@endpush
