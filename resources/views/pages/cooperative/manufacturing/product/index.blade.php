@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Manufacturing']['final_products'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addProductAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addProductAccordion"><span class="mdi mdi-plus"></span>Add Final Product
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addProductAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Register Final Product</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.manufacturing.add') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="productName">Name</label>
                                        <input type="text" name="name"
                                               class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               id="productName" placeholder="ABC" value="{{ old('name')}}" required>

                                        @if ($errors->has('name'))
                                            <span class="help-block text-danger">
                                    <strong>{{ $errors->first('name')  }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="selling_price">Selling Price/Unit</label>
                                        <input type="text" name="selling_price"
                                               class="form-control  {{ $errors->has('selling_price') ? ' is-invalid' : '' }}"
                                               value="{{ old('selling_price')}}" id="selling_price" placeholder="100.60"
                                               required>
                                        @if ($errors->has('selling_price'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('selling_price')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="category">Category</label>
                                        <select name="category" id="category"
                                                class=" form-control form-select {{ $errors->has('category') ? ' is-invalid' : '' }}">
                                            <option value=""></option>
                                            @foreach($categories as $cat)
                                                <option value="{{$cat->id}}" {{$cat->id == old('category') ?  'selected' : ''}}> {{ $cat->name }}</option>
                                            @endforeach

                                            @if ($errors->has('category'))
                                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('category')  }}</strong>
                                </span>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="unit">Unit Measure</label>
                                        <select name="unit" id="unit"
                                                class=" form-control form-select {{ $errors->has('unit') ? ' is-invalid' : '' }}">
                                            @foreach($units as $unit)
                                                <option value="{{$unit->id}}" {{$unit->id == old('unit') ? 'selected' : ''}}> {{ $unit->name }}</option>
                                            @endforeach

                                            @if ($errors->has('unit'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('unit')  }}</strong>
                                                </span>
                                            @endif
                                        </select>
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
                    @if(has_right_permission(config('enums.system_modules')['Manufacturing']['final_products'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('cooperative.manufacturings.products.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('cooperative.manufacturings.products.download','xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('cooperative.manufacturings.products.download', 'pdf') }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Registered Final Products</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Selling Price/Unit</th>
                                <th>Unit</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php

                                $canEdit = has_right_permission(config('enums.system_modules')['Manufacturing']['final_products'], config('enums.system_permissions')['edit']);
                            @endphp
                            @foreach($products as $key => $prod)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$prod->name }} </td>
                                    <td>{{$prod->category->name }}</td>
                                    <td> {{$prod->cooperative->currency}} {{ number_format($prod->selling_price,2,'.',',') }}</td>
                                    <td>{{$prod->unit->name }}</td>
                                    <td>
                                        @if($canEdit)
                                            <button type="button" class="btn btn-info btn-rounded"
                                                    data-toggle="modal" data-target="#editModal_{{$prod->id}}">
                                                <span class="mdi mdi-file-edit"></span>
                                            </button>


                                            {{--  modals edit start--}}
                                            <div class="modal fade" id="editModal_{{$prod->id}}" tabindex="-1" role="dialog"
                                                 aria-labelledby="modalLabel_{{$prod->id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalLabel_{{$prod->id}}">
                                                                Edit {{$prod->name}}</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('cooperative.manufacturing.final_product.edit', $prod->id) }}"
                                                              method="post">
                                                            <div class="modal-body">
                                                                @csrf
                                                                <div class="form-row">

                                                                    <div class="form-group col-12">
                                                                        <label for="productName_{{$prod->name}}">Name</label>
                                                                        <input type="text" name="edit_name"
                                                                               class="form-control {{ $errors->has('edit_name') ? ' is-invalid' : '' }}"
                                                                               id="productName_{{$prod->name}}" value="{{ $prod->name}}" required>

                                                                        @if ($errors->has('edit_name'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('edit_name')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="selling_price_{{$prod->id}}">Selling Price/Unit</label>
                                                                        <input type="text" name="edit_selling_price"
                                                                               class="form-control  {{ $errors->has('edit_selling_price') ? ' is-invalid' : '' }}"
                                                                               value="{{ $prod->selling_price}}" id="selling_price_{{$prod->id}}" placeholder="100.60"
                                                                               required>
                                                                        @if ($errors->has('edit_selling_price'))
                                                                            <span class="help-block text-danger">
                                                                                <strong>{{ $errors->first('edit_selling_price')  }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="category_{{$prod->id}}">Category</label>
                                                                        <select name="edit_category" id="category_{{$prod->id}}"
                                                                                class=" form-control form-select {{ $errors->has('edit_category') ? ' is-invalid' : '' }}">
                                                                            <option value=""></option>
                                                                            @foreach($categories as $cat)
                                                                                <option value="{{$cat->id}}" {{$cat->id == $prod->category_id ?  'selected' : ''}}> {{ $cat->name }}</option>
                                                                            @endforeach

                                                                            @if ($errors->has('edit_category'))
                                                                                <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('edit_category')  }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </select>
                                                                    </div>

                                                                    <div class="form-group col-12">
                                                                        <label for="unit_{{$prod->id}}">Unit Measure</label>
                                                                        <select name="edit_unit" id="unit_{{$prod->id}}"
                                                                                class=" form-control form-select {{ $errors->has('edit_unit') ? ' is-invalid' : '' }}">
                                                                            @foreach($units as $unit)
                                                                                <option value="{{$unit->id}}" {{$unit->id == $prod->unit_id ? 'selected' : ''}}> {{ $unit->name }}</option>
                                                                            @endforeach

                                                                            @if ($errors->has('edit_unit'))
                                                                                <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('edit_unit')  }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </select>
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
                                        @endif
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
