@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Farm Management']['yield_config'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addFarmerYields"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addCrop"><span class="mdi mdi-plus"></span>Setup Yields
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addFarmerYields">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Setup Yields</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.configure-expected-yield') }}" method="post">
                                @csrf
                                <div class="form-row">

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="type">Type</label>
                                        <select name="type" id="type"
                                                class=" form-control select2bs4 {{ $errors->has('type') ? ' is-invalid' : '' }}"
                                                onchange="alterProduct()">
                                            <option value="" selected>--Select Type--</option>
                                            <option value="farm">Crop</option>
                                            <option value="livestock">Livestock</option>
                                        </select>
                                        @if ($errors->has('type'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('type')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 d-none" id="for-crop">
                                        <label for="crop">Crop</label>
                                        <select name="crop" id="crop"
                                                class=" form-control select2bs4 {{ $errors->has('crop') ? ' is-invalid' : '' }}">
                                            <option value="" selected>--Select Crop--</option>
                                            @foreach($crops as $crop)
                                                <option value="{{$crop->id}}">
                                                    @if($crop->product_id)
                                                        {{ ucwords( strtolower($crop->product->name.' ('.$crop->variety.')')) }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('crop'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('crop')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 d-none" id="for-livestock">
                                        <label for="breed">Livestock/Poultry Breed</label>
                                        <select name="breed" id="breed"
                                                class=" form-control select2bs4 {{ $errors->has('breed') ? ' is-invalid' : '' }}">
                                            <option value="" selected>--Select Breed--</option>
                                            @foreach($breeds as $breed)
                                                <option value="{{$breed->id}}" {{ old('breed') == $breed->id ? 'selected' : '' }}>
                                                    {{ ucwords( strtolower($breed->name)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('breed'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('breed')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="volume_indicator">Production Per </label>
                                        <select name="volume_indicator" id="volume_indicator"
                                                class=" form-control select2bs4 {{ $errors->has('volume_indicator') ? ' is-invalid' : '' }}">
                                            <option value="" selected>----</option>
                                            @foreach(volume_indicators() as $key=>$indicator)
                                                <option value="{{$indicator}}">{{ ucwords($indicator)}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('volume_indicator'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('volume_indicator')  }}</strong>
                                        </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="quantity">Quantity</label>
                                        <input type="number" name="quantity"
                                               class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}"
                                               id="expected_yield" placeholder="200" value="{{ old('quantity')}}"
                                               required>

                                        @if ($errors->has('quantity'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('quantity')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="farm_unit"> Unit Measure</label>
                                        <select name="farm_unit" id="farm_unit"
                                                class=" form-control select2bs4 {{ $errors->has('farm_unit') ? ' is-invalid' : '' }}">
                                            <option value="" selected>--Select Crop--</option>
                                            @foreach($farm_units as $unit)
                                                <option value="{{$unit->id}}">
                                                    {{ ucwords( strtolower($unit->name)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('farm_unit'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('farm_unit')  }}</strong>
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
    @endif
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Yield Setup</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Yields From</th>
                                <th>Production Per</th>
                                <th>Quantity</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($expected_yields as $key => $yield)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $yield->crop_id ? ( $yield->crop->product_id ? 'Crop: '.$yield->crop->product->name.' ('.$yield->crop->variety.')' : '-') :
                                            'Livestock: '.$yield->livestock_breed->name }}
                                    </td>
                                    <td>{{ ucwords($yield->volume_indicator) }}</td>
                                    <td>{{ $yield->quantity.' '.$yield->farm_unit->name }}</td>
                                    <td>
                                        @if(has_right_permission(config('enums.system_modules')['Farm Management']['yield_config'], config('enums.system_permissions')['edit']))
                                            <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                    data-target="#editModal_{{$yield->id}}">
                                                <span class="mdi mdi-file-edit"></span>
                                            </button>
                                        @endif

                                        {{--  modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$yield->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$yield->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$yield->id}}">
                                                            Edit Yield</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.configure-expected-yield.edit', $yield->id)}}"
                                                          method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-row">
                                                                @if($yield->crop_id)
                                                                    <div class="form-group col-12" id="for-product">
                                                                        <label for="edit_crop_{{$yield->id}}">Crop</label>
                                                                        <select name="edit_crop"
                                                                                id="edit_crop_{{$yield->id}}"
                                                                                class=" form-control select2bs4 {{ $errors->has('edit_crop') ? ' is-invalid' : '' }}">
                                                                            <option value="" selected>--Select Crop--
                                                                            </option>
                                                                            @foreach($crops as $crop)
                                                                                @if($crop->product_id)
                                                                                    <option value="{{$crop->id}}" {{ $crop->id == $yield->crop_id ? 'selected': ''}}>
                                                                                        {{ ucwords( strtolower($crop->product->name.' ('.$crop->variety.')')) }}
                                                                                    </option>
                                                                                @endif
                                                                            @endforeach
                                                                        </select>
                                                                        @if ($errors->has('edit_crop'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('edit_crop')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                @endif

                                                                @if($yield->livestock_breed_id)
                                                                    <div class="form-group col-12">
                                                                        <label for="edit_breed_{{$yield->id}}">Livestock</label>
                                                                        <select name="edit_breed"
                                                                                id="edit_breed_{{$yield->id}}"
                                                                                class=" form-control select2bs4 {{ $errors->has('edit_breed') ? ' is-invalid' : '' }}">
                                                                            <option value="" selected>--Select
                                                                                Livestock--
                                                                            </option>
                                                                            @foreach($breeds as $breed)
                                                                                <option value="{{$breed->id}}" {{$breed->id == $yield->livestock_breed_id ? 'selected' : ''}}>
                                                                                    {{ ucwords( strtolower($breed->name)) }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        @if ($errors->has('edit_livestock'))
                                                                            <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('edit_livestock')  }}</strong>
                                                                                </span>
                                                                        @endif
                                                                    </div>
                                                                @endif

                                                                <div class="form-group col-12">
                                                                    <label for="edit_volume_indicator_{{$yield->id}}">Production
                                                                        Per </label>
                                                                    <select name="edit_volume_indicator"
                                                                            id="edit_volume_indicator_{{$yield->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('volume_indicator') ? ' is-invalid' : '' }}">
                                                                        <option value="" selected>------</option>
                                                                        @foreach(volume_indicators() as $indicator)
                                                                            <option value="{{$indicator}}" {{ $yield->volume_indicator == $indicator ? 'selected' : '' }}>{{ ucwords($indicator)}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_volume_indicator'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_volume_indicator')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_quantity_{{$yield->id}}">Quantity</label>
                                                                    <input type="number" name="edit_quantity"
                                                                           class="form-control {{ $errors->has('edit_quantity') ? ' is-invalid' : '' }}"
                                                                           id="edit_quantity_{{$yield->id}}"
                                                                           placeholder="200"
                                                                           value="{{$yield->quantity}}"
                                                                           required>

                                                                    @if ($errors->has('edit_quantity'))
                                                                        <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('edit_quantity')  }}</strong>
                                                                            </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_farm_unit_{{$yield->id}}"> Unit
                                                                        Measure</label>
                                                                    <select name="edit_farm_unit"
                                                                            id="edit_farm_unit_{{$yield->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('edit_farm_unit') ? ' is-invalid' : '' }}">
                                                                        <option value="" selected>--Select Unit--
                                                                        </option>
                                                                        @foreach($farm_units as $unit)
                                                                            <option value="{{$unit->id}}" {{ $unit->id == $yield->farm_unit_id ? 'selected' :'' }}>
                                                                                {{ ucwords( strtolower($unit->name)) }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_farm_unit'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_farm_unit')  }}</strong>
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
    <script>
        const alterProduct = () => {
            const type = $("#type").val();
            if (type === 'farm') {
                $("#for-crop").removeClass('d-none')
                $("#for-livestock").addClass('d-none')
                return;
            }
            if (type === 'livestock') {
                $("#for-crop").addClass('d-none')
                $("#for-livestock").removeClass('d-none')
            } else {
                $("#for-crop").addClass('d-none')
                $("#for-livestock").addClass('d-none')
            }
        }
    </script>
@endpush

@push('custom-scripts')
@endpush
