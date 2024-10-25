@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Product Management']['products'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                                data-toggle="collapse"
                                data-target="#addProductAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addProductAccordion"><span
                                    class="mdi mdi-plus"></span>Add Product
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif "
                             id="addProductAccordion">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Register Product</h4>
                                </div>
                            </div>
                            <form action="{{ route('cooperative.product.add') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="productName">Name</label>
                                        <input type="text" name="name"
                                               class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               id="productName" placeholder="ABC"
                                               value="{{ old('name')}}" required>

                                        @if ($errors->has('name'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('name')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="mode">Mode</label>
                                        <input type="text" name="mode"
                                               class="form-control  {{ $errors->has('mode') ? ' is-invalid' : '' }}"
                                               id="mode" placeholder="A.B.C"
                                               value="{{ old('mode')}}">

                                        @if ($errors->has('mode'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('mode')  }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="buying_price">Buying Price</label>
                                        <input type="text" name="buying_price"
                                               class="form-control  {{ $errors->has('buying_price') ? ' is-invalid' : '' }}"
                                               value="{{ old('buying_price')}}" id="buying_price"
                                               placeholder="90.60"
                                               required>
                                        @if ($errors->has('buying_price'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('buying_price')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="sale_price">Selling Price</label>
                                        <input type="text" name="sale_price"
                                               class="form-control  {{ $errors->has('sale_price') ? ' is-invalid' : '' }}"
                                               value="{{ old('sale_price')}}" id="sale_price"
                                               placeholder="100.60"
                                               required>
                                        @if ($errors->has('sale_price'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('sale_price')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="vat">V.A.T</label>
                                        <input type="text" name="vat"
                                               class="form-control  {{ $errors->has('vat') ? ' is-invalid' : '' }}"
                                               id="vat" placeholder="10" value="{{ old('vat')}}"
                                               required>
                                        @if ($errors->has('vat'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('vat')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="category">Category</label>
                                        <select name="category" id="category"
                                                class=" form-control select2bs4 {{ $errors->has('category') ? ' is-invalid' : '' }}">
                                            <option value=""></option>
                                            @foreach($categories as $cat)
                                                <option value="{{$cat->id}}" {{ $cat->id == old('category') ? 'selected' : ''}}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('category'))
                                            <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('category')  }}</strong>
                                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="unit">Unit Measure</label>
                                        <select name="unit" id="unit"
                                                class=" form-control select2bs4 {{ $errors->has('unit') ? ' is-invalid' : '' }}">
                                            <option value=""></option>
                                            @foreach($units as $unit)
                                                <option value="{{$unit->id}}" {{ $unit->id == old('unit') ? 'selected' : ''}}>
                                                    {{ $unit->name }}
                                                </option>
                                            @endforeach

                                        </select>

                                        @if ($errors->has('unit'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('unit')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="serial_number">Serial Number</label>
                                        <input type="text" name="serial_number"
                                               class="form-control {{ $errors->has('serial_number') ? ' is-invalid' : '' }}"
                                               id="serial_number" placeholder="SN9864"
                                               value="{{ old('serial_number')}}">


                                        @if ($errors->has('serial_number'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('serial_number')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">

                                        <label for="mainImage">Product Image</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file"
                                                       class="custom-file-input @error('image') is-invalid @enderror"
                                                       id="image" name="image"
                                                       value="{{ old('image') }}">
                                                <label class="custom-file-label"
                                                       for="exampleInputFile">Image</label>

                                                @if ($errors->has('image'))
                                                    <span class="help-block text-danger">
                                                        <strong>{{ $errors->first('image')  }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="threshold">Minimum Quantity Alert</label>
                                        <input type="text" name="threshold"
                                               class="form-control {{ $errors->has('threshold') ? ' is-invalid' : '' }}"
                                               id="threshold" placeholder="15"
                                               value="{{ old('threshold')}}">


                                        @if ($errors->has('threshold'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('threshold')  }}</strong>
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
                    <button type="button" class="btn btn-info btn-fw btn-sm float-right"
                            data-toggle="collapse"
                            data-target="#filterProductAccordion"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="filterProductAccordion"><span
                                class="mdi mdi-database-search"></span>Filter Product
                    </button>
                    <div class="collapse
                         @if(
                            request()->filter_category
                            or request()->filter_serial_no
                            or request()->filter_mode
                            or request()->filter_unit
                        )
                             show @endif"
                         id="filterProductAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Filter Product</h4>
                            </div>
                        </div>
                        <form action="{{ route('cooperative.products.show') }}" method="get">
                            @csrf
                            <div class="form-row">

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="filter_category">Category</label>
                                    <select name="filter_category" id="filter_category"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach($categories as $cat)
                                            <option value="{{$cat->id}}"
                                                    {{ $cat->id == request()->filter_category ? 'selected' : ''}}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="filter_serial_no">Serial Number</label>
                                    <input type="text" name="filter_serial_no"
                                           class="form-control"
                                           id="filter_serial_no" placeholder="SN9864"
                                           value="{{ request()->filter_serial_no}}">
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="filter_mode">Mode</label>
                                    <input type="text" name="filter_mode"
                                           class="form-control"
                                           id="filter_mode" placeholder="A.B.C"
                                           value="{{ request()->filter_mode }}">
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="filter_unit">Unit Measure</label>
                                    <select name="filter_unit" id="filter_unit"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach($units as $unit)
                                            <option value="{{$unit->id}}"
                                                    {{ $unit->id == request()->filter_unit ? 'selected' : ''}}>
                                                {{ $unit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit"
                                            class="btn btn-primary btn-fw btn-block">Search
                                    </button>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <a href="{{route('cooperative.products.show') }}"
                                       type="submit"
                                       class="btn btn-info btn-fw btn-block">
                                        Reset
                                    </a>
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
                    @if(has_right_permission(config('enums.system_modules')['Product Management']['products'], config('enums.system_permissions')['edit']))

                        <form action="{{ route('cooperative.products.download','csv') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('cooperative.products.download','xlsx') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>

                        <form action="{{ route('cooperative.products.download','pdf') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-success float-right text-white">
                                <i class="mdi mdi-download"></i> PDF
                            </button>
                        </form>
                    @endif
                    <h4 class="card-title">Registered Products</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Buying Price</th>
                                <th>V.A.T</th>
                                <th>Selling Price</th>
                                <th>Selling Price + VAT</th>
                                <th>Profit Margin</th>
                                <th>Unit Measure</th>
                                <th>Mode</th>
                                <th>S.N</th>
                                <th>Minimum Quantity Alert</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $key => $prod)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$prod->name }} </td>
                                    <td>{{$prod->category->name }}</td>
                                    <td>{{$prod->cooperative->currency}} {{ number_format($prod->buying_price,2,'.',',') }}</td>
                                    <td>{{$prod->vat }}{{ '%' }}</td>
                                    <td>{{$prod->cooperative->currency}} {{ number_format($prod->sale_price ,2,'.',',') }}</td>
                                    <td>{{$prod->cooperative->currency}} {{ number_format((($prod->sale_price * $prod->vat)/100)+$prod->sale_price,2,'.',',') }}</td>
                                    <td>{{$prod->cooperative->currency}} {{ number_format(($prod->sale_price - $prod->buying_price),2,'.',',') }}</td>
                                    <td>{{$prod->unit->name }}</td>
                                    <td>{{$prod->mode }}</td>
                                    <td>{{$prod->serial_number }}</td>
                                    <td>{{$prod->threshold.' '.$prod->unit->name }}</td>
                                    <td>
                                        @if(has_right_permission(config('enums.system_modules')['Product Management']['products'], config('enums.system_permissions')['edit']))
                                            <button type="button" class="btn btn-info btn-rounded"
                                                    data-toggle="modal"
                                                    data-target="#editModal_{{$prod->id}}">
                                                <span class="mdi mdi-file-edit"></span>
                                            </button>
                                        @endif

                                        <a class="btn btn-sm btn-rounded btn-primary" href="{{ route('cooperative.collections.product.show', $prod->id)  }}">
                                           Collections
                                        </a>

                                        {{-- modals edit start--}}
                                        <div class="modal fade" id="editModal_{{$prod->id}}"
                                             tabindex="-1" role="dialog"
                                             aria-labelledby="modalLabel_{{$prod->id}}"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered"
                                                 role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="modalLabel_{{$prod->id}}">
                                                            Edit Product: {{$prod->name}}</h5>

                                                        <button type="button" class="close"
                                                                data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <br>
                                                    </div>
                                                    <form action="{{ route('cooperative.product.edit', $prod->id) }}"
                                                          method="post"
                                                          enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            @if($prod->image)
                                                                <div class="text-left mb-2">
                                                                    <img class="rounded image-override"
                                                                         src="{{asset('storage/'.$prod->image)}}"
                                                                         width="200" height="200"/>
                                                                </div>
                                                            @endif
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label for="productName_{{$prod->id}}">Name</label>
                                                                    <input type="text" name="name"
                                                                           class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                                           id="productName_{{$prod->id}}"
                                                                           placeholder="ABC"
                                                                           value="{{ $prod->name }}"
                                                                           required>

                                                                    @if ($errors->has('name'))
                                                                        <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('name')  }}</strong>
                                                                </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="mode_{{$prod->id}}">Mode</label>
                                                                    <input type="text" name="mode"
                                                                           class="form-control  {{ $errors->has('mode') ? ' is-invalid' : '' }}"
                                                                           id="mode_{{$prod->id}}"
                                                                           placeholder="A.B.C"
                                                                           value="{{ $prod->mode}}">

                                                                    @if ($errors->has('mode'))
                                                                        <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('mode')  }}</strong>
                                                                </span>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group col-12">
                                                                    <label for="buying_price_{{$prod->id}}">Buying
                                                                        Price</label>
                                                                    <input type="text"
                                                                           name="buying_price"
                                                                           class="form-control  {{ $errors->has('buying_price') ? ' is-invalid' : '' }}"
                                                                           value="{{ $prod->buying_price }}"
                                                                           id="buying_price_{{$prod->id}}"
                                                                           placeholder="90.60"
                                                                           required>
                                                                    @if ($errors->has('buying_price'))
                                                                        <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('buying_price')  }}</strong>
                                                                </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="sale_price_{{$prod->id}}">Selling
                                                                        Price</label>
                                                                    <input type="text"
                                                                           name="sale_price"
                                                                           class="form-control  {{ $errors->has('sale_price') ? ' is-invalid' : '' }}"
                                                                           value="{{$prod->sale_price}}"
                                                                           id="sale_price_{{$prod->id}}"
                                                                           placeholder="100.60"
                                                                           required>
                                                                    @if ($errors->has('sale_price'))
                                                                        <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('sale_price')  }}</strong>
                                                                </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="vat_{{$prod->id}}">V.A.T</label>
                                                                    <input type="text" name="vat"
                                                                           class="form-control  {{ $errors->has('vat') ? ' is-invalid' : '' }}"
                                                                           id="vat_{{$prod->id}}"
                                                                           placeholder="10"
                                                                           value="{{$prod->vat}}"
                                                                           required>
                                                                    @if ($errors->has('vat'))
                                                                        <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('vat')  }}</strong>
                                                                </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="category_{{$prod->id}}">Category</label>
                                                                    <select name="category"
                                                                            id="category_{{$prod->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('category') ? ' is-invalid' : '' }}">
                                                                        @foreach($categories as $cat)
                                                                            <option value="{{$cat->id}}" {{ $cat->id == $prod->category_id ? 'selected' : '' }}> {{ $cat->name }}</option>
                                                                        @endforeach

                                                                        @if ($errors->has('category'))
                                                                            <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('category')  }}</strong>
                                                                    </span>
                                                                        @endif
                                                                    </select>
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="unit_{{$prod->id}}">Unit
                                                                        Measure</label>
                                                                    <select name="unit"
                                                                            id="unit_{{$prod->id}}"
                                                                            class=" form-control select2bs4 {{ $errors->has('unit') ? ' is-invalid' : '' }}">
                                                                        @foreach($units as $unit)
                                                                            <option value="{{$unit->id}}" {{ $unit->id == $prod->unit_id ? 'selected' : '' }}> {{ $unit->name }}</option>
                                                                        @endforeach

                                                                        @if ($errors->has('unit'))
                                                                            <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('unit')  }}</strong>
                                                                    </span>
                                                                        @endif
                                                                    </select>
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="serial_number_{{$prod->id}}">Serial
                                                                        Number</label>
                                                                    <input type="text"
                                                                           name="serial_number"
                                                                           class="form-control {{ $errors->has('serial_number') ? ' is-invalid' : '' }}"
                                                                           id="serial_number_{{$prod->id}}"
                                                                           placeholder="SN9864"
                                                                           value="{{$prod->serial_number}}">


                                                                    @if ($errors->has('serial_number'))
                                                                        <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('serial_number')  }}</strong>
                                                                </span>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label for="mainImage_{{$prod->id}}">Product
                                                                        Image</label>
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input type="file"
                                                                                   class="custom-file-input @error('image') is-invalid @enderror"
                                                                                   id="mainImage_{{$prod->id}}"
                                                                                   name="image"
                                                                                   value="{{ old('image') }}">
                                                                            <label class="custom-file-label"
                                                                                   for="exampleInputFile">Image</label>

                                                                            @if ($errors->has('image'))
                                                                                <span class="help-block text-danger">
                                                                            <strong>{{ $errors->first('image')  }}</strong>
                                                                        </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="form-group col-12">
                                                                    <label for="threshold_{{$prod->id}}">Minimum Quantity Alert</label>
                                                                    <input type="text"
                                                                           name="threshold"
                                                                           class="form-control {{ $errors->has('threshold') ? ' is-invalid' : '' }}"
                                                                           id="threshold_{{$prod->id}}"
                                                                           placeholder="15"
                                                                           value="{{$prod->threshold}}">


                                                                    @if ($errors->has('threshold'))
                                                                        <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('threshold')  }}</strong>
                                                                </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <button type="submit"
                                                                            class="btn btn-primary btn-fw btn-block">
                                                                        Save
                                                                    </button>
                                                                </div>
                                                            </div>
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
