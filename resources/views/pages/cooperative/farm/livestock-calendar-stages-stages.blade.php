@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    @php
        $theStage = $livestockCalendarStages[0]->animal;
    @endphp
    @if(has_right_permission(config('enums.system_modules')['Farm Management']['calendar_stages'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addCropCalendarStage"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addCropCalendarStage"><span class="mdi mdi-plus"></span>
                            Add stages for {{$theStage}}
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addCropCalendarStage">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4> Add stages for {{$theStage}}</h4>
                                </div>
                            </div>

                            <form action="{{ route('cooperative.farm.crop-calendar-stages.stages.add', [\App\CropCalendarStage::TYPE_LIVESTOCK,$livestockCalendarStages[0]->livestock_id ]) }}"
                                  method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group mt-0 col-12">

                                        <button type="button" class="ml-2 btn btn-info btn-rounded"
                                                data-toggle="tooltip" data-placement="top"
                                                title="Click to break down your costs">
                                            <span data-toggle="modal" data-target="#stagesModal">Stages</span>
                                        </button>

                                        @if ($errors->has('stages'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('stages')  }}</strong>
                                        </span>
                                        @endif

                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="stagesModal" tabindex="-1" role="dialog"
                                             aria-labelledby="stagesModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="stagesModalLabel">
                                                            Stages
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
                                                                       id="stageTable">
                                                                    <thead class="thead-dark">
                                                                    <th>Stage</th>
                                                                    <th>Period</th>
                                                                    <th>Period Measure</th>
                                                                    </thead>
                                                                    <tbody>

                                                                    </tbody>
                                                                    <tfoot>
                                                                    <th>Total</th>
                                                                    <th id="totalPeriod">0</th>
                                                                    <th>Days</th>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="form-row mt-2">
                                                            <div class="form-group col-12">
                                                                <label for="name">Stage Name</label>
                                                                <input type="text"
                                                                       class="form-control"
                                                                       id="name" placeholder="Planting">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label for="period">Period</label>
                                                                <input type="number" class="form-control"
                                                                       id="period" placeholder="2">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label for="period_measure">Period Measure</label>
                                                                <select name="period_measure" id="period_measure"
                                                                        class="form-control form-select">
                                                                    @foreach(config('enums')["crop_calendar_period_measure"][0] as $period_measure)
                                                                        <option value="{{$period_measure}}">{{ucwords($period_measure)}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary"
                                                                onclick="addStage()">
                                                            Add
                                                        </button>
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">
                                                            Close
                                                        </button>
                                                    </div>
                                                    <div>
                                                    </div>
                                                </div>
                                                {{--  modal end   --}}
                                                <input type="hidden" name="stages" class="form-control" id="stages"
                                                       value="{{ old('stages')}}">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-12">
                                            <button type="submit" class="btn btn-primary btn-fw btn-block">Update
                                                Stages
                                            </button>
                                        </div>
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
                    <h4 class="card-title">Stages for {{ $theStage }}</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Stage</th>
                                <th>Total Period (days)</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $totalDays = 0;@endphp
                            @foreach($livestockCalendarStages as $key => $stage)
                                @php $totalDays += $stage->period;@endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $stage->name }}</td>
                                    <td>{{ $stage->period.' Days' }}</td>
                                    <td>
                                        <form action="{{ route('cooperative.farm.crop-calendar-stages.stages.delete',$stage->id) }}"
                                              method="post">
                                            @csrf
                                            @if(has_right_permission(config('enums.system_modules')['Farm Management']['calendar_stages'], config('enums.system_permissions')['edit']))
                                                <button type="button" class="btn btn-info btn-rounded"
                                                        data-toggle="modal"
                                                        data-target="#editModal_{{$stage->id}}">
                                                    <span class="mdi mdi-file-edit"></span>
                                                </button>
                                            @endif

                                            @if(has_right_permission(config('enums.system_modules')['Farm Management']['calendar_stages'], config('enums.system_permissions')['delete']))
                                                <button type="submit" class="btn btn-danger btn-rounded"
                                                        data-toggle="modal">
                                                    <span class="mdi mdi-trash-can"></span>
                                                </button>
                                            @endif
                                        </form>

                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$stage->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$stage->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$stage->id}}">
                                                            Edit {{$stage->name}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.farm.crop-calendar-stages.stages.edit', $stage->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="edit_name_{{$stage->id}}">Stage
                                                                        Name</label>
                                                                    <input type="text" name="stage"
                                                                           class="form-control {{ $errors->has('edit_name') ? ' is-invalid' : '' }}"
                                                                           id="edit_name_{{$stage->id}}"
                                                                           value="{{ $stage->name }}" required>
                                                                    @if ($errors->has('name_edit'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('name_edit')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_period_{{$stage->id}}">Period
                                                                        (Days)</label>
                                                                    <input type="number" name="period"
                                                                           class="form-control  {{ $errors->has('edit_period') ? ' is-invalid' : '' }}"
                                                                           id="edit_period_{{$stage->id}}"
                                                                           value="{{ $stage->period }}">

                                                                    @if ($errors->has('edit_period'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_period')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close
                                                            </button>
                                                            <button type="submit" class="btn btn-primary">
                                                                Save changes
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
                            <tfoot>
                            <th colspan="2"> Total</th>
                            <th colspan="2">{{$totalDays}} Days</th>
                            </tfoot>
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
        const changeStageType = () => {
            const type = $('#type').val()
            if (type === '1') {
                $('#showCrop').removeClass('d-none')
                $('#showLivestock').addClass('d-none')
            } else if (type === '2') {
                $('#showCrop').addClass('d-none')
                $('#showLivestock').removeClass('d-none')
            } else {
                $('#showCrop').addClass('d-none')
                $('#showLivestock').addClass('d-none')
            }
        }

        var stagesObj = []
        const addStage = () => {
            const name = $("#name").val()
            const period = $("#period").val()
            const period_measure = $("#period_measure").find(":selected").val();
            if (name && period && period_measure) {
                const numberOfDays = Number($("#totalPeriod").text());
                const tableBody = document.getElementById('stageTable').getElementsByTagName('tbody')[0]

                const newRow = tableBody.insertRow()
                const nameCell = newRow.insertCell(0)
                const periodCell = newRow.insertCell(1)
                const periodMeasureCell = newRow.insertCell(2)

                nameCell.innerHTML = name
                periodCell.innerHTML = period
                periodMeasureCell.innerHTML = period_measure

                const periodInDays = calculateDays(period, period_measure.toLowerCase())
                $("#totalPeriod").text(numberOfDays + Number(periodInDays))
                const obj = {"name": name, "period": periodInDays}
                stagesObj.push(obj)

                $("#stages").val(JSON.stringify(stagesObj));
                $("#name").val("");
                $("#period").val("");
                $("#period_measure").val("");
            }
        }

        const calculateDays = (period, periodMeasure) => {

            let periodInDays = 0;
            switch (periodMeasure) {
                case 'weeks':
                    periodInDays = 7 * period
                    break
                case 'months':
                    periodInDays = 30 * period
                    break
                case 'years':
                    periodInDays = period * 366
                    break
                default:
                    periodInDays = period
                    break
            }

            return periodInDays
        }
    </script>
@endpush
