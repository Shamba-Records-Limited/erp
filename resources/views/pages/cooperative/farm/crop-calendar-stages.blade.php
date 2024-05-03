@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Farm Management']['calendar_stages'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addCropCalendarStage"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addCropCalendarStage"><span class="mdi mdi-plus"></span>Add
                            Livestock/Poultry
                            Calendar
                            Stage
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addCropCalendarStage">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Crop/Livestock/Poultry Calendar Stage</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.farm.add.crop-calendar-stage') }}" method="post">
                                @csrf
                                <div class="form-row">

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="type">Type</label>
                                        <select name="type" id="type"
                                                class=" form-control select2bs4 {{ $errors->has('type') ? ' is-invalid' : '' }}"
                                                onchange="changeStageType()">
                                            <option value="">--Select Type--</option>
                                            <option value="1">Crop</option>
                                            <option value="2">Livestock/Poultry</option>
                                        </select>
                                        @if ($errors->has('type'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('type')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 d-none" id="showLivestock">
                                        <label for="livestock">Livestock/Poultry</label>
                                        <select name="livestock" id="livestock"
                                                class=" form-control select2bs4 {{ $errors->has('livestock') ? ' is-invalid' : '' }}">
                                            <option value="">--Select Livestock/Poultry--</option>
                                            @foreach($livestock as $animal)
                                                <option value="{{$animal->id}}">{{ucwords(strtolower($animal->name.'( '.$animal->breed.' '.$animal->animal_type.')'))}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('livestock'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('livestock')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 d-none" id="showCrop">
                                        <label for="crop">Crop</label>
                                        <select name="crop" id="crop"
                                                class=" form-control select2bs4 {{ $errors->has('crop') ? ' is-invalid' : '' }}">
                                            <option value="">--Select Crop--</option>
                                            @foreach($crops as $crop)
                                                @if($crop->product_id)
                                                    <option value="{{$crop->id}}">{{ucwords(strtolower($crop->product->name.'('.$crop->variety.')'))}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if ($errors->has('crop'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('crop')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group mt-3 col-lg-3 col-md-6 col-12">

                                        <button type="button" class="mt-4 ml-2 btn btn-info btn-rounded"
                                                data-toggle="tooltip" data-placement="top"
                                                title="Click to break down your costs">
                                            <span data-toggle="modal" data-target="#stagesModal">Period Details</span>
                                        </button>

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
                                                                <input type="text" name="name"
                                                                       class="form-control"
                                                                       id="name" placeholder="Planting">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label for="period">Period</label>
                                                                <input type="number" name="period" class="form-control"
                                                                       id="period" placeholder="2">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label for="period_measure">Period Measure</label>
                                                                <select name="period_measure" id="period_measure"
                                                                        class="form-control select2bs4">
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

                                                </div>
                                            </div>
                                        </div>
                                        {{--  modal end   --}}
                                        <input type="hidden" name="stages" class="form-control" id="stages"
                                               value="{{ old('stages')}}">
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
    @endif
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Registered Crop Calendar Stages</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Crop</th>
                                <th>Total Period (days)</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cropCalendarStages as $key => $stage)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $stage->crop}}</td>
                                    <td>{{$stage->period.' Days'}}</td>
                                    <td>
                                        <a href="{{ route('cooperative.farm.crop-calendar-stages.stages', [\App\CropCalendarStage::TYPE_CROP, $stage->id])}}"
                                           type="button" class="btn btn-info btn-rounded">
                                            <span class="mdi mdi-eye"></span>
                                        </a>
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
                    <h4 class="card-title">Registered Livestock/Poultry Calendar Stages</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Livestock</th>
                                <th>Total Period (days)</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($livestockCalendarStages as $key => $stage)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $stage->animal }}</td>
                                    <td>{{ $stage->period.' Days' }}</td>
                                    <td>
                                        <a href="{{ route('cooperative.farm.crop-calendar-stages.stages', [ \App\CropCalendarStage::TYPE_LIVESTOCK, $stage->id]) }}"
                                           type="button" class="btn btn-info btn-rounded">
                                            <span class="mdi mdi-eye"></span>
                                        </a>
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
