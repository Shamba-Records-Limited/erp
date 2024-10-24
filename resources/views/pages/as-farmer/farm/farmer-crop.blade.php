@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                            data-target="#addFarmerCrop"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="addCrop"><span class="mdi mdi-plus"></span>Add Crop/Livestock/Poultry
                    </button>
                    <div class="collapse @if ($errors->count() > 0) show @endif " id="addFarmerCrop">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Add Farmer Crop/Livestock/Poultry</h4>
                            </div>
                        </div>

                        <form action="{{ route('farm.crop-stages.add') }}" method="post">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="type">Type</label>
                                    <select name="type" id="type"
                                            class=" form-control form-select {{ $errors->has('type') ? ' is-invalid' : '' }}"
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

                                <div class="form-group col-lg-3 col-md-6 col-12 d-none" id="showCrop">
                                    <label for="crop">Crop</label>
                                    <select name="crop" id="crop"
                                            class=" form-control form-select {{ $errors->has('crop') ? ' is-invalid' : '' }}"
                                            onchange="getStages('crop', 'stage','next_stage')">
                                        <option value="" selected>--Select Crop--</option>
                                        @foreach($crops as $crop)
                                            @if($crop->product_id)
                                                <option value="{{$crop->id}}"> {{ ucwords( strtolower($crop->product->name.' ('.$crop->variety.')')) }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if ($errors->has('crop'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('crop')  }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12 d-none" id="showLivestock">
                                    <label for="livestock">Livestock/Poultry</label>
                                    <select name="livestock" id="livestock"
                                            class=" form-control form-select {{ $errors->has('livestock') ? ' is-invalid' : '' }}"
                                            onchange="getStages('livestock', 'stage','next_stage')">
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

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="stage">Stage</label>
                                    <select name="stage" id="stage"
                                            class=" form-control form-select {{ $errors->has('stage') ? ' is-invalid' : '' }}">
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
                    <h4 class="card-title">My Crop Calendars</h4>
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
                                <th>Total Cost</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $currency = Auth::user()->cooperative->currency; $index = 0@endphp
                            @foreach($farmer_crops as $farmer_crop)
                                @if($farmer_crop->type == 1)
                                    <tr>
                                        <td>{{++$index }}</td>
                                        <td>{{ $farmer_crop->crop->product_id ?  ucwords( strtolower($farmer_crop->crop->product->name)) : '-' }}</td>
                                        <td>{{ ucwords( strtolower($farmer_crop->crop->variety)) }}</td>
                                        <td>{{ ucwords( strtolower($farmer_crop->stage->name)) }}</td>
                                        <td>{{ $farmer_crop->start_date }}</td>
                                        <td>{{ $farmer_crop->last_date }}</td>
                                        <td>{{ $farmer_crop->next_stage ? ucwords( strtolower($farmer_crop->next_stage->name)) : '-' }}</td>
                                        <td>{{ $currency.' '.$farmer_crop->total_cost }}</td>
                                        <td>
                                            <a class="btn btn-info btn-rounded"
                                               href="{{ route('farm.farmer-crop.trackers',[$farmer_crop->id, $farmer_crop->type]) }}">
                                                <span class="mdi mdi-eye"></span>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
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
                    <h4 class="card-title">My Livestock/Poultry Calendars</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Livestock</th>
                                <th>Breed</th>
                                <th>Current Stage</th>
                                <th>Start Date</th>
                                <th>Last Date</th>
                                <th>Next Stage</th>
                                <th>Total Cost</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $currency = Auth::user()->cooperative->currency; $index = 0@endphp
                            @foreach($farmer_crops as  $farmer_crop)
                                @if($farmer_crop->type == 2)
                                    <tr>
                                        <td>{{++$index }}</td>
                                        <td>{{ ucwords( strtolower($farmer_crop->livestock->name.', '.$farmer_crop->livestock->animal_type)) }}</td>
                                        <td>{{ ucwords( strtolower($farmer_crop->livestock->breed->name)) }}</td>
                                        <td>{{ ucwords( strtolower($farmer_crop->stage->name)) }}</td>
                                        <td>{{ $farmer_crop->start_date }}</td>
                                        <td>{{ $farmer_crop->last_date }}</td>
                                        <td>{{ $farmer_crop->next_stage ? ucwords( strtolower($farmer_crop->next_stage->name)) : '-' }}</td>
                                        <td>{{ $currency.' '.$farmer_crop->total_cost }}</td>
                                        <td>
                                            <a class="btn btn-info btn-rounded"
                                               href="{{ route('farm.farmer-crop.trackers',[$farmer_crop->id, $farmer_crop->type]) }}">
                                                <span class="mdi mdi-eye"></span>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
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
        const getStages = (selectorField, fieldToUpdate1, fieldToUpdate2) => {
            $("#" + fieldToUpdate1).empty();
            $("#" + fieldToUpdate2).empty();
            const crop_id = $('#' + selectorField).val();
            const type = $('#type').val()
            let url = '{{ route('cooperative.stages-by-crop',[":crop_id",":type"]) }}';
            url = url.replace(':crop_id', crop_id);
            url = url.replace(':type', type);
            let htmlCode = '';
            axios.post(url).then(res => {
                const data = res.data
                htmlCode += `<option value="">---Select Stage---</option>`;
                data.forEach(d => {
                    htmlCode += `<option value="${d.id}">${d.name}</option>`;
                });
                $("#" + fieldToUpdate1).append(htmlCode)
                $("#" + fieldToUpdate2).append(htmlCode)
            }).catch(() => {
                htmlCode += `<option value="">---Select Stage---</option>`;
                $("#" + fieldToUpdate1).append(htmlCode);
                $("#" + fieldToUpdate2).append(htmlCode);
            })
        }

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
