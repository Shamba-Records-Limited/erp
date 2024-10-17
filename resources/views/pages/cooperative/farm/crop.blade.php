@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Farm Management']['crop_details'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addCrop"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addCrop"><span class="mdi mdi-plus"></span>Add Crop Details
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addCrop">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Crop Details</h4>
                                </div>
                            </div>

                            <form action="{{ route('cooperative.farm.add.crop') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="product">Product</label>
                                        <select name="product" id="product"
                                                class=" form-control select2bs4 {{ $errors->has('product') ? ' is-invalid' : '' }}">
                                            <option value="">---Select Product---</option>
                                            @foreach( $products as $product)
                                                <option value="{{$product->id}}">{{ $product->name.'( '.$product->category->name.')'}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('product'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('product')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="variety">Crop variety</label>
                                        <input type="text" name="variety"
                                               class="form-control {{ $errors->has('variety') ? ' is-invalid' : '' }}"
                                               id="variety" placeholder="Orange Beans" value="{{ old('variety')}}"
                                               required>

                                        @if ($errors->has('variety'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('variety')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="farm_unit">Farm Unit</label>
                                        <select name="farm_unit" id="farm_unit"
                                                class=" form-control select2bs4 {{ $errors->has('farm_unit') ? ' is-invalid' : '' }}">
                                            <option value="">---Select Farm Unit---</option>
                                            @foreach( $farm_units as $fu)
                                                <option value="{{$fu->id}}">{{ $fu->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('edit_farm_unit'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('edit_farm_unit')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="recommended_areas">Recommended Areas</label>
                                        <input type="text" name="recommended_areas"
                                               class="form-control  {{ $errors->has('recommended_areas') ? ' is-invalid' : '' }}"
                                               id="recommended_areas" placeholder=""
                                               value="{{ old('recommended_areas')}}">

                                        @if ($errors->has('recommended_areas'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('recommended_areas')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="description">Description</label>
                                        <textarea type="text" name="description"
                                                  class="form-control  {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                                  id="description" placeholder="">
                                {{ old('description')}}
                                </textarea>

                                        @if ($errors->has('description'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('description')  }}</strong>
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
                    @if(has_right_permission(config('enums.system_modules')['Farm Management']['crop_details'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('cooperative.crops-details.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('cooperative.crops-details.download','xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('cooperative.crops-details.download', 'pdf') }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Registered Crops</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Variety</th>
                                <th>Farm Unit</th>
                                <th>Recommended Areas</th>
                                <th>Description</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($crops as $key => $crop)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $crop->product_id ? $crop->product->name : '-' }}</td>
                                    <td>{{ $crop->variety }}</td>
                                    <td>{{ $crop->expected_yields.' '.($crop->farm_unit_id ? $crop->farm_unit->name : '')}}</td>
                                    <td>{{ $crop->recommended_areas }}</td>
                                    <td>{{ $crop->description }}</td>
                                    <td>
                                        @if(has_right_permission(config('enums.system_modules')['Farm Management']['crop_details'], config('enums.system_permissions')['edit']))
                                            <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                    data-target="#editModal_{{$crop->id}}">
                                                <span class="mdi mdi-file-edit"></span>
                                            </button>
                                        @endif

                                        {{-- modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$crop->id}}" tabindex="-1" role="dialog"
                                             aria-labelledby="modalLabel_{{$crop->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$crop->id}}">
                                                            Edit {{$crop->name}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('cooperative.farm.edit.crop', $crop->id)}}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="edit_product_{{$crop->id}}">Product</label>
                                                                    <select name="edit_product"
                                                                            id="edit_product_{{$crop->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('edit_product') ? ' is-invalid' : '' }}">
                                                                        @foreach( $products as $product)
                                                                            <option value="{{$product->id}}" {{ $crop->product_id == $product->id ? "selected" : '' }}>
                                                                                {{ $product->name.'( '.$product->category->name.')'}}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_product'))
                                                                        <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('edit_product')  }}</strong>
                                                                </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_variety_{{$crop->id}}">Crop
                                                                        Variety</label>
                                                                    <input type="text" name="edit_variety"
                                                                           class="form-control {{ $errors->has('edit_variety') ? ' is-invalid' : '' }}"
                                                                           id="edit_variety_{{$crop->id}}"
                                                                           value="{{ $crop->variety }}" required>
                                                                    @if ($errors->has('edit_variety'))
                                                                        <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('edit_variety')  }}</strong>
                                                                </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_farm_unit_{{$crop->id}}">Farm
                                                                        Unit</label>
                                                                    <select name="edit_farm_unit"
                                                                            id="edit_farm_unit_{{$crop->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('edit_farm_unit') ? ' is-invalid' : '' }}">
                                                                        @foreach( $farm_units as $fu)
                                                                            <option value="{{$fu->id}}" {{ $crop->farm_unit_id == $fu->id ? 'selected': null }}>{{ $fu->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('edit_farm_unit'))
                                                                        <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('edit_farm_unit')  }}</strong>
                                                                </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_recommended_areas_{{$crop->id}}">Recommended
                                                                        Areas</label>
                                                                    <input type="text" name="edit_recommended_areas"
                                                                           class="form-control  {{ $errors->has('edit_recommended_areas') ? ' is-invalid' : '' }}"
                                                                           id="edit_recommended_areas_{{$crop->id}}"
                                                                           value="{{ $crop->recommended_areas }}">

                                                                    @if ($errors->has('edit_recommended_areas'))
                                                                        <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('edit_recommended_areas')  }}</strong>
                                                                </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="edit_description_{{$crop->id}}">Description</label>
                                                                    <textarea type="text" name="edit_description"
                                                                              class="form-control  {{ $errors->has('edit_description') ? ' is-invalid' : '' }}"
                                                                              id="edit_description_{{$crop->id}}">{{$crop->description }}</textarea>

                                                                    @if ($errors->has('edit_description'))
                                                                        <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('edit_description')  }}</strong>
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
                                        {{-- modal end   --}}
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
@endpush
