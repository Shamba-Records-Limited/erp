@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Accounting']['reports'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addCategoryAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addCategoryAccordion"><span class="mdi mdi-plus"></span>Add Property
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addCategoryAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Property</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.accounting.property.store') }}"
                                  enctype="multipart/form-data" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="categoryName">Name</label>
                                        <input type="text" name="property"
                                               class="form-control {{ $errors->has('property') ? ' is-invalid' : '' }}"
                                               id="categoryproperty" value="{{ old('property')}}" required>

                                        @if ($errors->has('property'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('property')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="type">Type</label>
                                        <input type="text" name="property_type"
                                               class="form-control {{ $errors->has('property_type') ? ' is-invalid' : '' }}"
                                               id="property_type" value="{{ old('property_type')}}" required>

                                        @if ($errors->has('property_type'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('property_type')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="type">Buying Price</label>
                                        <input type="text" name="buying_price"
                                               class="form-control {{ $errors->has('buying_price') ? ' is-invalid' : '' }}"
                                               id="buying_price" value="{{ old('buying_price')}}" required>

                                        @if ($errors->has('buying_price'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('buying_price')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="type">Status</label>
                                        <select name="status" id="status"
                                                class=" form-control select2bs4 {{ $errors->has('type') ? ' is-invalid' : '' }}">
                                            <option value="available">Available</option>
                                            <option value="sold"> Sold</option>
                                        </select>

                                        @if ($errors->has('status'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('status')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="type">Depreciation Rate</label>
                                        <input type="text" name="deprecation_rate_pa"
                                               class="form-control {{ $errors->has('deprecation_rate_pa') ? ' is-invalid' : '' }}"
                                               id="deprecation_rate_pa" value="{{ old('deprecation_rate_pa')}}"
                                               required>

                                        @if ($errors->has('deprecation_rate_pa'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('deprecation_rate_pa')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="type">Selling Price</label>
                                        <input type="text" name="selling_price"
                                               class="form-control {{ $errors->has('selling_price') ? ' is-invalid' : '' }}"
                                               id="selling_price" value="{{ old('selling_price')}}">

                                        @if ($errors->has('selling_price'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('selling_price')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="type">Documents</label>
                                        <input type="file" name="file"
                                               class="form-control {{ $errors->has('file') ? ' is-invalid' : '' }}"
                                               id="file" value="{{ old('file')}}">

                                        @if ($errors->has('file'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('file')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-3 col-12">
                                        <label for=""></label>
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
                    <h4 class="card-title">Registered Assets</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Property</th>
                                <th>Type</th>
                                <th>Bought At(Kes.)</th>
                                <th>Depreciation(%)</th>
                                <th>Documents</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($property as $key => $asset)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$asset->property }}</td>
                                    <td>{{$asset->type }}</td>
                                    <td>{{$asset->buying_price }}</td>
                                    <td>{{$asset->deprecation_rate_pa }}</td>
                                    <td>@if($asset->documents)
                                            <a href="{{$asset->documents }}">Doc</a>
                                        @endif</td>
                                    <td>{{ $asset->status }}
                                        @if($asset->status == 'sold')
                                            {{ '@ Kes.'.$asset->selling_price}}
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('cooperative.accounting.property.delete', $asset->id) }}"  method="POST">
                                            @csrf
                                            @if(has_right_permission(config('enums.system_modules')['Accounting']['reports'], config('enums.system_permissions')['edit']))
                                                <button data-toggle="modal" data-target="#edit-{{$asset->id}}"
                                                        type="button" class="btn btn-info btn-rounded btn-sm">
                                                    <span class="mdi mdi-file-edit"></span>
                                                </button>
                                            @endif
                                            @if(has_right_permission(config('enums.system_modules')['Accounting']['reports'], config('enums.system_permissions')['edit']))
                                                <button type="submit" class="btn btn-danger btn-sm btn-rounded">
                                                    <span class="mdi mdi-trash-can"></span>
                                                </button>
                                            @endif
                                        </form>
                                        <!-- modal edit -->
                                        <div class="modal fade" id="edit-{{$asset->id}}" tabindex="-1" role="dialog"
                                             aria-labelledby="editCollectioln" aria-modal="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"
                                                 role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editCollectioln">Edit Property</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true" class="text-danger">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('cooperative.accounting.property.update') }}"
                                                              enctype="multipart/form-data"
                                                              method="post">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-lg-4 col-md-6 col-12">
                                                                    <label for="categoryName">Name</label>
                                                                    <input type="hidden" name="id"
                                                                           value="{{$asset->id}}"/>
                                                                    <input type="text" name="property"
                                                                           value="{{$asset->property}}"
                                                                           class="form-control {{ $errors->has('property') ? ' is-invalid' : '' }}"
                                                                           id="categoryproperty_{{$asset->id}}"
                                                                           required>

                                                                    @if ($errors->has('property'))
                                                                        <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('property')  }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-lg-4 col-md-6 col-12">
                                                                    <label for="type">Type</label>
                                                                    <input type="text" name="property_type"
                                                                           value="{{$asset->type}}"
                                                                           class="form-control {{ $errors->has('property_type') ? ' is-invalid' : '' }}"
                                                                           id="property_type_{{$asset->id}}" required>

                                                                    @if ($errors->has('property_type'))
                                                                        <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('property_type')  }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-lg-4 col-md-6 col-12">
                                                                    <label for="type">Buying Price</label>
                                                                    <input type="text" name="buying_price"
                                                                           value="{{$asset->buying_price}}"
                                                                           class="form-control {{ $errors->has('buying_price') ? ' is-invalid' : '' }}"
                                                                           id="buying_price_{{$asset->id}}" required>

                                                                    @if ($errors->has('buying_price'))
                                                                        <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('buying_price')  }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-lg-4 col-md-6 col-12">
                                                                    <label for="type">Status</label>
                                                                    <select name="status" id="status_{{$asset->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('type') ? ' is-invalid' : '' }}">

                                                                        <option value="available" {{ strtolower($asset->status == 'available') ? 'selected' : ''}}>
                                                                            Available
                                                                        </option>
                                                                        <option value="sold" {{ strtolower($asset->status == 'sold') ? 'selected' : ''}}>
                                                                            Sold
                                                                        </option>
                                                                    </select>

                                                                    @if ($errors->has('status'))
                                                                        <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('status')  }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-lg-4 col-md-6 col-12">
                                                                    <label for="type">Depreciation Rate</label>
                                                                    <input type="text" name="deprecation_rate_pa"
                                                                           value="{{$asset->deprecation_rate_pa}}"
                                                                           class="form-control {{ $errors->has('deprecation_rate_pa') ? ' is-invalid' : '' }}"
                                                                           id="deprecation_rate_pa"
                                                                           required>

                                                                    @if ($errors->has('deprecation_rate_pa'))
                                                                        <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('deprecation_rate_pa')  }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-lg-4 col-md-6 col-12">
                                                                    <label for="type">Selling Price</label>
                                                                    <input type="text" name="selling_price"
                                                                           value="{{$asset->selling_price}}"
                                                                           class="form-control {{ $errors->has('selling_price') ? ' is-invalid' : '' }}"
                                                                           id="selling_price">

                                                                    @if ($errors->has('selling_price'))
                                                                        <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('selling_price')  }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-lg-12 col-md-6 col-12">
                                                                    <label for="type">Documents</label>
                                                                    <input type="file" name="file"
                                                                           class="form-control {{ $errors->has('file') ? ' is-invalid' : '' }}"
                                                                           id="file" value="{{ old('file')}}" >

                                                                    @if ($errors->has('file'))
                                                                        <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('file')  }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>

                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-lg-3 col-md-3 col-12">
                                                                    <label for=""></label>
                                                                    <button type="submit"
                                                                            class="btn btn-primary btn-fw btn-block">Save
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ./ modal edit -->
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
