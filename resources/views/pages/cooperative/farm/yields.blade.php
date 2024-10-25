@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Farm Management']['yields'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addFarmerYields"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addCrop"><span class="mdi mdi-plus"></span>Add Farmer Yields
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addFarmerYields">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Farmer Crop</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.farmers-yield.add') }}" method="post">
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
                                            <option value="farm_tracker">Tracked Farming</option>
                                        </select>
                                        @if ($errors->has('type'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('type')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 d-none" id="show-farmer">
                                        <label for="farmer">Farmer</label>
                                        <select name="farmer" id="farmer"
                                                class=" form-control select2bs4 {{ $errors->has('farmer') ? ' is-invalid' : '' }}">
                                            <option value="" selected>--Select Farmer--</option>
                                            @foreach($farmers as $farmer)
                                                <option value="{{$farmer->farmer->id}}" {{ old('farmer') == $farmer->farmer->id ? 'selected' : ''}}>
                                                    {{ ucwords( strtolower($farmer->first_name.' '.$farmer->other_names)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('farmer'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('farmer')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 d-none" id="show-livestock">
                                        <label for="breed">Livestock/Poultry Breed</label>
                                        <select name="breed" id="breed"
                                                class=" form-control select2bs4 {{ $errors->has('livestock') ? ' is-invalid' : '' }}">
                                            <option value="" selected>--Select Livestock/Poultry--</option>
                                            @foreach($breeds as $breed)
                                                <option value="{{$breed->id}}" {{ old('breed') == $breed->id ? 'selected' : ''}}>
                                                    {{ ucwords(strtolower($breed->name)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('livestock'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('livestock')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 d-none" id="for-crop">
                                        <label for="crop">Crop Type</label>
                                        <select name="crop" id="crop"
                                                class=" form-control select2bs4 {{ $errors->has('crop') ? ' is-invalid' : '' }}">
                                            <option value="" selected>--Select Crop--</option>
                                            @foreach($crops as $crop)
                                                @if($crop->product_id)
                                                    <option value="{{$crop->id}}" {{ old('crop') == $crop->id ? 'selected' : ''}}>
                                                        {{ ucwords( strtolower($crop->product->name.' ('.$crop->variety.')')) }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if ($errors->has('crop'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('crop')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 d-none" id="for-farm-tracker">
                                        <label for="farmer_crop">Farmer Crop</label>
                                        <select name="farmer_crop" id="farmer_crop"
                                                class=" form-control select2bs4 {{ $errors->has('farmer_crop') ? ' is-invalid' : '' }}">
                                            <option value="" selected>--Select Crop--</option>
                                            @foreach($farmer_crops as $farmer_crop)
                                                <option value="{{$farmer_crop->id}}" {{ old('farmer_crop') == $farmer_crop->id ? 'selected' : ''}}>
                                                    {{ ucwords( strtolower($farmer_crop->farmer->user->first_name.' '.$farmer_crop->farmer->user->other_names.': '.$farmer_crop->crop->name.' ('.$farmer_crop->crop->variety.')')) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('farmer_crop'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('farmer_crop')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 d-none" id="for-product">
                                        <label for="product">Product</label>
                                        <input type="text" name="product"
                                               class="form-control {{ $errors->has('product') ? ' is-invalid' : '' }}"
                                               id="product" placeholder="Milk" value="{{ old('product')}}">

                                        @if ($errors->has('product'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('product')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="date">From Date</label>
                                        <input type="date" name="date"
                                               class="form-control {{ $errors->has('date') ? ' is-invalid' : '' }}"
                                               id="date" value="{{ old('date') }}">

                                        @if ($errors->has('date'))
                                            <span class="help-block text-danger">
                                        <strong>{{ $errors->first('date')  }}</strong>
                                    </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="to_date">To Date</label>
                                        <input type="date" name="to_date"
                                               class="form-control {{ $errors->has('to_date') ? ' is-invalid' : '' }}"
                                               id="to_date" value="{{ old('to_date') }}">

                                        @if ($errors->has('to_date'))
                                            <span class="help-block text-danger">
                                        <strong>{{ $errors->first('to_date')  }}</strong>
                                    </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="volume_indicator">Production Per </label>
                                        <select name="volume_indicator" id="volume_indicator"
                                                class=" form-control select2bs4 {{ $errors->has('volume_indicator') ? ' is-invalid' : '' }}"
                                                onchange="volumeIndicatorName()">
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

                                    <div class="form-group col-lg-3 col-md-6 col-12 d-none" id="show-frequency">
                                        <label for="frequency">Yield Frequency</label>
                                        <select name="frequency" id="frequency"
                                                class="form-control select2bs4 {{ $errors->has('frequency') ? ' is-invalid' : '' }}">
                                            <option value="{{\App\FarmerYield::FREQUENCY_TYPE_TOTAL}}" selected>Total
                                                Yields
                                            </option>
                                            <option value="{{\App\FarmerYield::FREQUENCY_TYPE_DAILY}}">Daily Yields
                                            </option>
                                        </select>
                                        @if ($errors->has('frequency'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('frequency')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="volume_indicator_count_input">Number <span
                                                    id="volume_indicator_count"> </span></label>
                                        <input type="number" name="volume_indicator_count"
                                               class="form-control {{ $errors->has('number_of') ? ' is-invalid' : '' }}"
                                               id="volume_indicator_count_input" placeholder="13"
                                               value="{{ old('volume_indicator_count')}}">

                                        @if ($errors->has('volume_indicator_count'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('volume_indicator_count')  }}</strong>
                                        </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="yields">Yields</label>
                                        <input type="number" name="yields"
                                               class="form-control {{ $errors->has('yields') ? ' is-invalid' : '' }}"
                                               id="yields" placeholder="150" value="{{ old('yields')}}" required>

                                        @if ($errors->has('yields'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('yields')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="units">Units</label>
                                        <select name="units" id="units"
                                                class=" form-control select2bs4 {{ $errors->has('units') ? ' is-invalid' : '' }}">
                                            <option value="" selected>--Select Unit--</option>
                                            @foreach($units as $unit)
                                                <option value="{{$unit->id}}" {{ old('units') == $unit->id ? 'selected' : ''}}> {{ ucwords( strtolower($unit->name)) }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('units'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('units')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="comments">Comments</label>
                                        <textarea type="text" name="comments"
                                                  class="form-control {{ $errors->has('comments') ? ' is-invalid' : '' }}"
                                                  id="comments">{{ old('comments')}}</textarea>

                                        @if ($errors->has('comments'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('comments')  }}</strong>
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
                    <h4 class="card-title">Farmer Crop Yields</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Farmer</th>
                                <th>Product</th>
                                <th>Production per</th>
                                <th>Yields</th>
                                <th>Expected Yields</th>
                                <th>Deviation</th>
                                <th>Units</th>
                                <th>Period</th>
                                <th>Comments</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                function calculated_yields($quantity, $volume, $type, $start, $end){
                                    $period = 1;
                                        if($type == \App\FarmerYield::FREQUENCY_TYPE_DAILY){
                                            $period = abs(\Carbon\Carbon::parse($start)->diffInDays(\Carbon\Carbon::parse($end))) + 1;
                                        }
                                        return $quantity * $volume * $period;
                                }
                                    function calculate_deviation($expected, $actual): string
                                    {
                                        $difference = $actual - $expected ;
                                        if($difference <  0){
                                            return "$difference (less)";
                                        }

                                        if($difference >  0){
                                            return "$difference (more)";
                                        }
                                        return $difference;
                                    }
                            @endphp
                            @foreach($farmer_yields_crop as $key => $yield)
                                @php

                                    $expected_yield = calculated_yields($yield->expected_yields->quantity, $yield->volume_indicator_count, $yield->frequency_type, $yield->date, $yield->to_date);
                                    $the_yields = calculated_yields($yield->yields, $yield->volume_indicator_count, $yield->frequency_type, $yield->date, $yield->to_date);
                                @endphp
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ ucwords(strtolower($yield->farmer->user->first_name.' '.$yield->farmer->user->other_names)) }}</td>
                                    <td>{{ $yield->crop->product_id ? ucwords(strtolower( $yield->crop->product->name.'('. $yield->crop->variety.')')) : '-'}}</td>
                                    <td>{{ ucwords($yield->expected_yields->volume_indicator.' ('.$yield->volume_indicator_count.')') }}</td>
                                    <td>{{ number_format($the_yields) }}</td>
                                    <td>{{ number_format($expected_yield) }}</td>
                                    <td>{{ calculate_deviation($expected_yield, $the_yields) }}</td>
                                    <td>{{ $yield->unit->name }}</td>
                                    <td>
                                        {{
                                            $yield->date != null ?
                                                ($yield->to_date != null ?
                                                  \Carbon\Carbon::parse($yield->date)->format('d M, Y').' - '.\Carbon\Carbon::parse($yield->to_date)->format('d M, Y') :
                                                     \Carbon\Carbon::parse($yield->date)->format('M, Y')) :
                                                        '-'
                                         }}
                                    </td>
                                    <td>{{ $yield->comments }}</td>
                                    <td>
                                        <form action="{{ route('cooperative.farmers-yield.delete', $yield->id) }}"
                                              method="post">
                                            @csrf
                                            @if(has_right_permission(config('enums.system_modules')['Farm Management']['yields'], config('enums.system_permissions')['edit']))
                                                <button type="button" class="btn btn-info btn-rounded"
                                                        data-toggle="modal"
                                                        data-target="#editModal_{{$yield->id}}">
                                                    <span class="mdi mdi-file-edit"></span>
                                                </button>
                                            @endif

                                            @if(has_right_permission(config('enums.system_modules')['Farm Management']['yields'], config('enums.system_permissions')['delete']))
                                                <button type="submit" class="btn btn-danger btn-rounded" title="Delete">
                                                    <span class="mdi mdi-trash-can"></span>
                                                </button>
                                            @endif
                                        </form>


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
                                                    <form action="{{route('cooperative.farmers-yield.edit', $yield->id)}}"
                                                          method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-row">

                                                                <div class="form-group col-12">
                                                                    <label for="edit_date_{{$yield->id}}">From
                                                                        Date</label>
                                                                    <input type="date" name="edit_date"
                                                                           class="form-control {{ $errors->has('edit_date') ? ' is-invalid' : '' }}"
                                                                           id="edit_date_{{$yield->id}}"
                                                                           value="{{ $yield->date }}">

                                                                    @if ($errors->has('edit_date'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_date')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_to_date_{{$yield->id}}">To
                                                                        Date</label>
                                                                    <input type="date" name="edit_to_date"
                                                                           class="form-control {{ $errors->has('edit_to_date') ? ' is-invalid' : '' }}"
                                                                           id="edit_to_date_{{$yield->id}}"
                                                                           value="{{ $yield->to_date }}">

                                                                    @if ($errors->has('edit_date'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_date')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_volume_indicator_count_input_{{$yield->id}}">Number
                                                                        of {{ $yield->expected_yields->volume_indicator }}</label>
                                                                    <input type="number"
                                                                           name="edit_volume_indicator_count"
                                                                           class="form-control {{ $errors->has('number_of') ? ' is-invalid' : '' }}"
                                                                           id="edit_volume_indicator_count_input_{{$yield->id}}"
                                                                           value="{{ $yield->volume_indicator_count}}">

                                                                    @if ($errors->has('edit_volume_indicator_count'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_volume_indicator_count')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_yields_{{$yield->id}}">Yields</label>
                                                                    <input type="number" name="edit_yields"
                                                                           class="form-control {{ $errors->has('edit_yields') ? ' is-invalid' : '' }}"
                                                                           id="edit_yields_{{$yield->id}}"
                                                                           placeholder="150" value="{{ $yield->yields}}"
                                                                           required>

                                                                    @if ($errors->has('edit_yields'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_yields')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_comments_{{$yield->id}}">Comments</label>
                                                                    <textarea type="text" name="edit_comments"
                                                                              class="form-control {{ $errors->has('edit_comments') ? ' is-invalid' : '' }}"
                                                                              id="edit_comments_{{$yield->id}}">{{ $yield->comments }}</textarea>

                                                                    @if ($errors->has('edit_comments'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_comments')  }}</strong>
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
                    <h4 class="card-title">Farmer Livestock/Poultry Yields</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Farmer</th>
                                <th>Product</th>
                                <th>Production per</th>
                                <th>Yields</th>
                                <th>Expected Yields</th>
                                <th>Deviation</th>
                                <th>Units</th>
                                <th>Period</th>
                                <th>Comments</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($farmer_yields_livestock as $key => $yield)
                                @php


                                    $expected_yield = calculated_yields($yield->expected_yields->quantity, $yield->volume_indicator_count, $yield->frequency_type, $yield->date, $yield->to_date);
                                    $the_yields = calculated_yields($yield->yields, $yield->volume_indicator_count, $yield->frequency_type, $yield->date, $yield->to_date);
                                @endphp
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ ucwords(strtolower($yield->farmer->user->first_name.' '.$yield->farmer->user->other_names)) }}</td>
                                    <td>{{ ucwords(strtolower($yield->product))}}</td>
                                    <td>{{ ucwords($yield->expected_yields->volume_indicator.' ('.$yield->volume_indicator_count.')') }}</td>
                                    <td>{{ number_format($the_yields) }}</td>
                                    <td>{{ number_format($expected_yield) }}</td>
                                    <td>{{ calculate_deviation($expected_yield, $the_yields) }}</td>
                                    <td>{{ $yield->unit->name }}</td>
                                    <td> {{
                                            $yield->date != null ?
                                                ($yield->to_date != null ?
                                                  \Carbon\Carbon::parse($yield->date)->format('d M, Y').' - '.\Carbon\Carbon::parse($yield->to_date)->format('d M, Y') :
                                                     \Carbon\Carbon::parse($yield->date)->format('M, Y')) :
                                                        '-'
                                         }}</td>
                                    <td>{{ $yield->comments }}</td>
                                    <td>
                                        <form action="{{ route('cooperative.farmers-yield.delete', $yield->id) }}"
                                              method="post">
                                            @csrf
                                            <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                    data-target="#editModal_{{$yield->id}}"><span
                                                        class="mdi mdi-file-edit"></span></button>

                                            <button type="submit" class="btn btn-danger btn-rounded" title="Delete">
                                                <span class="mdi mdi-trash-can"></span>
                                            </button>
                                        </form>


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
                                                    <form action="{{route('cooperative.farmers-yield.edit', $yield->id)}}"
                                                          method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-row">

                                                                <div class="form-group col-12">
                                                                    <label for="edit_date_{{$yield->id}}">From
                                                                        Date</label>
                                                                    <input type="date" name="edit_date"
                                                                           class="form-control {{ $errors->has('edit_date') ? ' is-invalid' : '' }}"
                                                                           id="edit_date_{{$yield->id}}"
                                                                           value="{{ $yield->date }}">

                                                                    @if ($errors->has('edit_date'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_date')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_to_date_{{$yield->id}}">To
                                                                        Date</label>
                                                                    <input type="date" name="edit_to_date"
                                                                           class="form-control {{ $errors->has('edit_to_date') ? ' is-invalid' : '' }}"
                                                                           id="edit_to_date_{{$yield->id}}"
                                                                           value="{{ $yield->to_date }}">

                                                                    @if ($errors->has('edit_date'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_date')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_frequency_{{$yield->id}}">Yield
                                                                        Frequency</label>
                                                                    <select name="edit_frequency"
                                                                            id="edit_frequency_{{$yield->id}}"
                                                                            class="form-control select2bs4 {{ $errors->has('edit_frequency') ? ' is-invalid' : '' }}">
                                                                        <option value="{{\App\FarmerYield::FREQUENCY_TYPE_TOTAL}}" {{$yield->frequency_type == \App\FarmerYield::FREQUENCY_TYPE_TOTAL ? 'selected': ''}}>
                                                                            Total Yields
                                                                        </option>
                                                                        <option value="{{\App\FarmerYield::FREQUENCY_TYPE_DAILY}}" {{$yield->frequency_type == \App\FarmerYield::FREQUENCY_TYPE_DAILY ? 'selected': ''}}>
                                                                            Daily Yields
                                                                        </option>
                                                                    </select>
                                                                    @if ($errors->has('edit_frequency'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_frequency')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_volume_indicator_count_input_{{$yield->id}}">Number
                                                                        of {{ $yield->expected_yields->volume_indicator }}</label>
                                                                    <input type="number"
                                                                           name="edit_volume_indicator_count"
                                                                           class="form-control {{ $errors->has('number_of') ? ' is-invalid' : '' }}"
                                                                           id="edit_volume_indicator_count_input_{{$yield->id}}"
                                                                           value="{{ $yield->volume_indicator_count}}">

                                                                    @if ($errors->has('edit_volume_indicator_count'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_volume_indicator_count')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_yields_{{$yield->id}}">Yields</label>
                                                                    <input type="number" name="edit_yields"
                                                                           class="form-control {{ $errors->has('edit_yields') ? ' is-invalid' : '' }}"
                                                                           id="edit_yields_{{$yield->id}}"
                                                                           placeholder="150" value="{{ $yield->yields}}"
                                                                           required>

                                                                    @if ($errors->has('edit_yields'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_yields')  }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_comments_{{$yield->id}}">Comments</label>
                                                                    <textarea type="text" name="edit_comments"
                                                                              class="form-control {{ $errors->has('edit_comments') ? ' is-invalid' : '' }}"
                                                                              id="edit_comments_{{$yield->id}}">{{ $yield->comments }}</textarea>

                                                                    @if ($errors->has('edit_comments'))
                                                                        <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('edit_comments')  }}</strong>
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
                $("#for-product").removeClass('d-none')
                $("#show-farmer").removeClass('d-none')
                $("#show-livestock").addClass('d-none')
                $("#show-frequency").addClass('d-none')
                return;
            }
            if (type === 'livestock') {
                $("#for-crop").addClass('d-none')
                $("#for-product").removeClass('d-none')
                $("#show-farmer").removeClass('d-none')
                $("#show-livestock").removeClass('d-none')
                $("#show-frequency").removeClass('d-none')
                $("#for-farm-tracker").addClass('d-none')
                return
            }

            if (type === 'farm_tracker') {
                $("#for-crop").addClass('d-none')
                $("#for-product").addClass('d-none')
                $("#show-farmer").addClass('d-none')
                $("#show-livestock").addClass('d-none')
                $("#show-frequency").addClass('d-none')
                $("#for-farm-tracker").removeClass('d-none')
            } else {

                $("#for-crop").addClass('d-none')
                $("#for-product").addClass('d-none')
                $("#show-farmer").addClass('d-none')
                $("#show-livestock").addClass('d-none')
                $("#show-frequency").addClass('d-none')
                $("#for-farm-tracker").addClass('d-none')
            }

        }

        const volumeIndicatorName = () => {
            const volumeIndicator = $("#volume_indicator").val()
            $("#volume_indicator_count").text(' of ' + volumeIndicator)
        }

    </script>
@endpush

@push('custom-scripts')
@endpush
