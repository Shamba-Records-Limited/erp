@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @php
        $canEdit = has_right_permission(config('enums.system_modules')['Manufacturing']['production'], config('enums.system_permissions')['edit'])
    @endphp
    @if($canEdit)
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addProductAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addProductAccordion"><span class="mdi mdi-plus"></span>Add Raw Material
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addProductAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Register Produced Product</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.manufacturing.production.materials.add', $productionHistoryId) }}"
                                  method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="material">Raw Materials</label>
                                        <select name="material" id="material"
                                                class="form-control select2bs4 {{ $errors->has('material') ? ' is-invalid' : '' }}">
                                            <option value="">-Select Material-</option>
                                            @foreach($raw_materials as $material)
                                                <option value="{{$material->id}}"> {{ $material->name }} ({{$material->available_quanity}})</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('material'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('material')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="quantity">Quantity</label>
                                        <input type="text" name="quantity"
                                               class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}"
                                               id="quantity" placeholder="1"
                                               value="{{ old('quantity')}}">


                                        @if ($errors->has('quantity'))
                                            <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('quantity')  }}</strong>
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
                    @if(has_right_permission(config('enums.system_modules')['Manufacturing']['production'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('manufacturing.production-history-raw-materials.download', [$productionHistoryId,'csv']) }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('manufacturing.production-history-raw-materials.download',[$productionHistoryId,'xlsx']) }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('manufacturing.production-history-raw-materials.download', [$productionHistoryId,'pdf']) }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Production Lot # {{ $productionLot }} Raw Materials Used</h4>
                    @php $total = 0; $total_quantity = 0; $total_cost = 0; @endphp

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Raw Material</th>
                                <th>Quantity</th>
                                <th>Cost</th>
                                <th>Total Cost</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $currency = Auth::user()->cooperative->currency; @endphp
                            @foreach($production_materials as $key => $prod)
                                @php $total += $prod->cost; $total_quantity += $prod->quantity; $total_cost += ($prod->cost*$prod->quantity) @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $prod->rawMaterial->name }} </td>
                                    <td>{{$prod->quantity }} </td>
                                    <td> {{$currency}} {{ number_format($prod->cost,2,'.',',') }}</td>
                                    <td> {{$currency}} {{ number_format($prod->cost*$prod->quantity,2,'.',',') }}</td>
                                    <td>
                                        @if($canEdit)
                                            <button type="button" class="btn btn-primary btn-rounded btn-sm"
                                                    data-toggle="modal" data-target="#editModal_{{$prod->id}}">
                                                <i class="mdi mdi-file-edit"></i>
                                                Edit
                                            </button>

                                            <div class="modal fade" id="editModal_{{$prod->id}}" tabindex="-1"
                                                 role="dialog" aria-labelledby="editModalLabel_{{$prod->id}}"
                                                 aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editModalLabel_{{$prod->id}}">
                                                                Edit {{ $prod->rawMaterial->name }}
                                                            </h5>

                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div>
                                                            <form action="{{ route('cooperative.manufacturing.production.materials.edit',$prod->id ) }}"
                                                                  method="post">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="form-row">
                                                                        <div class="form-group col-12">
                                                                            <label for="material_{{$prod->id}}">Raw Materials</label>
                                                                            <select name="edit_material" id="material_{{$prod->id}}"
                                                                                    class="form-control select2bs4 {{ $errors->has('edit_material') ? ' is-invalid' : '' }}">
                                                                                <option value="">-Select Material-</option>
                                                                                @foreach($raw_materials as $material)
                                                                                    <option value="{{$material->id}}" {{$material->id == $prod->raw_material_id ? 'selected' : ''}}>
                                                                                        {{ $material->name }} ({{$material->available_quanity}})
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            @if ($errors->has('edit_material'))
                                                                                <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('edit_material')  }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>

                                                                        <div class="form-group col-12">
                                                                            <label for="quantity_{{$prod->id}}">Quantity</label>
                                                                            <input type="text" name="edit_quantity"
                                                                                   class="form-control {{ $errors->has('edit_quantity') ? ' is-invalid' : '' }}"
                                                                                   id="quantity_{{$prod->id}}" placeholder="1"
                                                                                   value="{{$prod->quantity}}">


                                                                            @if ($errors->has('edit_quantity'))
                                                                                <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('edit_quantity')  }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>

                                                                        <input type="hidden" value="{{$prod->quantity}}" name="old_quantity">
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
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th colspan="1">{{ number_format($total_quantity)}}</th>
                                    <th colspan="1">{{$currency}} {{ number_format($total)}}</th>
                                    <th colspan="2">{{$currency}} {{number_format($total_cost)}}</th>
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
