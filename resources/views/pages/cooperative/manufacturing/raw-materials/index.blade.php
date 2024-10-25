@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Manufacturing']['raw_materials'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                                data-toggle="collapse"
                                data-target="#addProductAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addProductAccordion"><span
                                    class="mdi mdi-plus"></span>Add Raw Material
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif "
                             id="addProductAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Register Raw Material</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.manufacturing.store.raw-materials') }}"
                                  method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="productName">Name</label>
                                        <input type="text" name="name"
                                               class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               id="productName" placeholder="ABC"
                                               value="{{ old('name')}}">

                                        @if ($errors->has('name'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('name')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="estimated_cost">Estimate Cost</label>
                                        <input type="text" name="estimated_cost"
                                               class="form-control  {{ $errors->has('estimated_cost') ? ' is-invalid' : '' }}"
                                               value="{{ old('estimated_cost')}}"
                                               id="estimated_cost"
                                               placeholder="100.60">
                                        @if ($errors->has('estimated_cost'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('estimated_cost')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="units">Units</label>
                                        <select name="units" id="units"
                                                class=" form-control select2bs4 {{ $errors->has('units') ? ' is-invalid' : '' }}"
                                        >
                                            <option value=""></option>
                                            @foreach($units as $unit)
                                                <option value="{{$unit->id}}" {{$unit->id == old('units') ? 'selected' : ''}}>{{$unit->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('units'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('units')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <button type="submit"
                                                class="btn btn-primary btn-fw btn-block">Add
                                        </button>
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
                    @if(has_right_permission(config('enums.system_modules')['Manufacturing']['raw_materials'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('cooperative.manufacturings.rawmaterials.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('cooperative.manufacturings.rawmaterials.download','xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('cooperative.manufacturings.rawmaterials.download', 'pdf') }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Registered Raw Materials</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Estimate Cost</th>
                                <th>Unit</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $canEdit = has_right_permission(config('enums.system_modules')['Manufacturing']['raw_materials'], config('enums.system_permissions')['edit']);
                                $user = Auth::user();
                                $total_estimate_cost = 0;
                            @endphp
                            @foreach($materials as $key => $material)
                                @php $total_estimate_cost += $material->estimated_cost; @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$material->name}} </td>
                                    <td> {{$user->cooperative->currency}} {{ number_format($material->estimated_cost,2,'.',',') }}</td>
                                    <td>{{ $material->unit->name}}</td>
                                    <td>

                                        @if($canEdit)
                                            {{--  modals edit start--}}
                                            <button type="button" class="btn btn-info btn-rounded"
                                                    data-toggle="modal"
                                                    data-target="#editModal_{{$material->id}}">
                                                <span class="mdi mdi-file-edit"></span>
                                            </button>

                                            <div class="modal fade" id="editModal_{{$material->id}}"
                                                 tabindex="-1" role="dialog"
                                                 aria-labelledby="modalLabel_{{$material->id}}"
                                                 aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered"
                                                     role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="modalLabel_{{$material->id}}">
                                                                Edit {{$material->name}}</h5>
                                                            <button type="button" class="close"
                                                                    data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('cooperative.manufacturing.raw-material.edit', $material->id) }}"
                                                              method="post">
                                                            <div class="modal-body">
                                                                @csrf
                                                                <div class="form-row">
                                                                    <div class="form-group col-12">
                                                                        <label for="productName_{{$material->id}}">Name</label>
                                                                        <input type="text"
                                                                               name="edit_name"
                                                                               class="form-control {{ $errors->has('edit_name') ? ' is-invalid' : '' }}"
                                                                               id="productName_{{$material->id}}"
                                                                               value="{{ $material->name}}">

                                                                        @if ($errors->has('edit_name'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('edit_name')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="estimated_cost_{{$material->id}}">Estimate
                                                                            Cost</label>
                                                                        <input type="text"
                                                                               name="edit_estimated_cost"
                                                                               class="form-control  {{ $errors->has('edit_estimated_cost') ? ' is-invalid' : '' }}"
                                                                               value="{{ $material->estimated_cost }}"
                                                                               id="estimated_cost_{{$material->id}}"
                                                                        >
                                                                        @if ($errors->has('edit_estimated_cost'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('edit_estimated_cost')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="units_{{$material->id}}">Units</label>
                                                                        <select name="edit_units"
                                                                                id="units_{{$material->id}}"
                                                                                class=" form-control select2bs4 {{ $errors->has('edit_units') ? ' is-invalid' : '' }}"
                                                                        >
                                                                            @foreach($units as $unit)
                                                                                <option value="{{$unit->id}} {{$unit->id == $material->unit_id ? 'selected' : ''}}">{{$unit->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @if ($errors->has('edit_units'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('edit_units')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button"
                                                                        class="btn btn-secondary"
                                                                        data-dismiss="modal">Close
                                                                </button>
                                                                <button type="submit"
                                                                        class="btn btn-primary">Save
                                                                    changes
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--  modal end   --}}
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="2">Total</th>
                                <th colspan="3">{{$user->cooperative->currency.' '.$total_estimate_cost}}</th>
                            </tr>
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
@endpush
