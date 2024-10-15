@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
@php
$collection_time_options = config('enums.collection_time');
@endphp
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div>
                    <button type="button" class="btn btn-primary btn-fw btn-sm" data-toggle="collapse" data-target="#bulkUploadCollectionsAccordion" aria-expanded="@if ($errors->count() > 0) true @else false @endif" aria-controls="bulkUploadCollectionsAccordion">
                        <span class="mdi mdi-plus">Bulk Import</span>
                    </button>
                    <button type="button" class="btn btn-primary btn-fw btn-sm" data-toggle="collapse" data-target="#addCollectionForm" aria-expanded="@if ($errors->count() > 0) true @else false @endif" aria-controls="addCollectionForm"><span class="mdi mdi-plus"></span>Collect
                    </button>
                    <a class="btn btn-primary btn-fw btn-sm" href="{{route('cooperative-admin.collections.export', 'xlsx')}}"><span class="mdi mdi-file-excel"></span>Export Excel
                    </a>
                    <a class="btn btn-primary btn-fw btn-sm" href="{{route('cooperative-admin.collections.export', 'pdf')}}"><span class="mdi mdi-file-pdf"></span>Export Pdf
                    </a>
                </div>

                @php
                $uploadErrors = Session::get('uploadErrors');
                @endphp
                <div class="collapse @if (isset($uploadErrors)) show @endif " id="bulkUploadCollectionsAccordion">
                    <form action="{{ route('cooperative-admin.collections.import-bulk') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-12">
                                <h6 class="mb-3">Bulk Import Collections</h6>
                            </div>
                            <div class="form-row col-12">
                                @if(isset($uploadErrors))
                                <div>
                                    @foreach($uploadErrors as $error)
                                    <li class="list text-danger">{{ $error[0] }}</li>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <a download="collections_bulk_import" href="{{ route('cooperative-admin.download-upload-collections-template') }}">
                                    Download Template</a>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('collections') is-invalid @enderror" id="collections" name="collections" value="{{ old('collections') }}">
                                        <label class="custom-file-label" for="exampleInputFile">Collections File</label>

                                        @if ($errors->has('collections'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('collections')  }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <button type="submit" class="btn btn-primary btn-fw btn-block">Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="collapse @if ($errors->count() > 0) show @endif " id="addCollectionForm">
                    <div class="row mt-5">
                        <div class="col-lg-12 grid-margin stretch-card col-12">
                            <h4>Add Collection</h4>
                        </div>
                    </div>

                    <form action="{{ route('cooperative-admin.collections.store') }}" method="post">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="coop_branch_id">Coop Branch<span class="text-danger">*</span> </label>
                                <select name="coop_branch_id" id="coop_branch_id" class="form-control select2bs4 {{ $errors->has('coop_branch_id') ? ' is-invalid' : '' }}" required>
                                    <option value="">-- Select Branch --</option>
                                    @foreach($coopBranches as $branch)
                                    <option value="{{$branch->id}}" @if($branch->id == old('branch_id')) selected @endif>{{$branch->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('coop_branch_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('coop_branch_id')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="farmer_id">Farmer<span class="text-danger">*</span></label>
                                <select name="farmer_id" id="farmer_id" class="form-control select2bs4 {{ $errors->has('farmer_id') ? ' is-invalid' : '' }}" required>
                                    <option value="">-- Select Farmer --</option>
                                    @foreach($farmers as $farmer)
                                    <option value="{{$farmer->id}}" @if($farmer->id == old('farmer_id')) selected @endif>{{$farmer->first_name}} {{$farmer->other_names}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('farmer_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('farmer_id')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="product_id">Product<span class="text-danger">*</span></label>
                                <select name="product_id" id="product_id" class="form-control select2bs4 {{ $errors->has('product_id') ? ' is-invalid' : '' }}" required onchange="set_unit_id()">
                                    <option value="">-- Select Product --</option>
                                    @foreach($products as $product)
                                    <option value="{{$product->id}}" @if ($product->id == old('product_id')) selected @elseif (empty(old('product_id')) && $product->id == $default_product_id) selected @endif>{{$product->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('product_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('product_id')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="quantity">Quantity<span class="text-danger">*</span></label>
                                <input type="text" name="quantity" class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}" id="quantity" placeholder="10.5" value="{{ old('quantity')}}" required>

                                @if ($errors->has('quantity'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('quantity')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="unit">Unit<span class="text-danger">*</span></label>
                                <!-- <select name="unit" id="unit" class="form-control select2bs4 {{ $errors->has('unit') ? ' is-invalid' : '' }}" required disabled>
                                    <option value="">-- Select Unit --</option>
                                    @foreach(config('enums.units') as $key => $unit)
                                    <option value="{{$key}}" @if($key==old('unit')) selected @endif>{{$unit['name']}} ({{$key}})</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('unit'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('unit')  }}</strong>
                                </span>
                                @endif -->
                                <input type="text" name="unit" class="form-control {{ $errors->has('unit') ? ' is-invalid' : '' }}" id="unit" placeholder="KG" value="{{ old('unit')}}" required readonly>
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="collection_time">Collection Time<span class="text-danger">*</span></label>
                                <select name="collection_time" id="collection_time" class="form-control select2bs4 {{ $errors->has('collection_time') ? ' is-invalid' : '' }}" required>
                                    <option value="">-- Select Collection Time --</option>
                                    @foreach($collection_time_options as $key => $option)
                                    <option value="{{$key}}">{{$option}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('quality_standard_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('quality_standard_id')  }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-6 col-12">
                                <label>Comments<span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="3" name="comments">{{old('comment')}}</textarea>
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
                <h4 class="card-title">Collections</h4>
                <div class="table-responsive">
                    <table class="table table-hover dt clickable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Collection No</th>
                                <th>Lot No</th>
                                <th>Farmer</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Collection Time</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($collections as $key => $collection)
                            <tr>
                                <td>{{++$key }}</td>
                                <td>{{$collection->collection_number}}</td>
                                <td>{{$collection->lot_number}}</td>
                                <td>
                                    <a href="{{route('cooperative-admin.farmers.detail', $collection->farmer_id)}}">{{$collection->first_name}} {{$collection->other_names}} - {{$collection->member_no}}</a>
                                </td>
                                <td>{{$collection->product_name}}</td>
                                <td>{{$collection->quantity}}</td>
                                <td>{{$collection->unit}}</td>
                                <td>{{ $collection_time_options[$collection->collection_time]}}</td>
                                <td>
                                    <div class="btn-group dropdown">
                                        <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="text-primary dropdown-item" href="#">
                                                <i class="fa fa-pdf"></i> Generate Receipt
                                            </a>
                                        </div>
                                    </div>
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
    function set_unit_id() {
        let product_id = $("#product_id").val();
        if (product_id == "") {
            return;
        }

        let url = `/common/product/${product_id}/unit`
        console.log(url);
        axios.get(url).then(function(response) {
            unit = response.data;
            $("#unit").val(unit);
            $("#unit").trigger("change");
        }).catch(function(error) {
            console.log(error);
        });
    }

    $(document).ready(function() {
        set_unit_id();
    });
</script>
@endpush