@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse" data-target="#addCollectionForm" aria-expanded="@if ($errors->count() > 0) true @else false @endif" aria-controls="addCollectionForm"><span class="mdi mdi-plus"></span>Collect
                </button>
                <div class="collapse @if ($errors->count() > 0) show @endif " id="addCollectionForm">
                    <div class="row mt-5">
                        <div class="col-lg-12 grid-margin stretch-card col-12">
                            <h4>Add Collection</h4>
                        </div>
                    </div>

                    <form action="{{ route('branches.add') }}" method="post">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="product_grade_id">Coop Branch</label>
                                <select name="product_grade_id" id="product_grade_id" class="form-control select2bs4 {{ $errors->has('product_grade_id') ? ' is-invalid' : '' }}" required>
                                    <option value="">-- Select Branch --</option>
                                    @foreach($coopBranches as $branch)
                                    <option value="{{$branch->id}}" @if($branch->id == old('branch_id')) selected @endif>{{$branch->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('product_grade_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('product_grade_id')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="farmer_id">Farmer</label>
                                <select name="farmer_id" id="farmer_id" class="form-control select2bs4 {{ $errors->has('farmer_id') ? ' is-invalid' : '' }}" required>
                                    <option value="">-- Select Farmer --</option>
                                    @foreach($farmers as $farmer)
                                    <option value="{{$farmer->id}}" @if($product->id == old('farmer_id')) selected @endif>{{$farmer->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('farmer_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('farmer_id')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="product_id">Product {{empty(old('product_id'))}}</label>
                                <select name="product_id" id="product_id" class="form-control select2bs4 {{ $errors->has('product_id') ? ' is-invalid' : '' }}" required>
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
                                <label for="product_grade_id">Grade</label>
                                <select name="product_grade_id" id="product_grade_id" class="form-control select2bs4 {{ $errors->has('product_grade_id') ? ' is-invalid' : '' }}" required>
                                    <option value="">-- Select Grade --</option>
                                    @foreach($grading as $grade)
                                    <option value="{{$grade->id}}" @if($grade->id == old('product_grade_id')) selected @endif>{{$grade->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('product_grade_id'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('product_grade_id')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12 d-none">
                                <label for="quantity">Quantity</label>
                                <input type="text" name="quantity" class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}" id="quantity" placeholder="10.5" value="{{ old('quantity')}}" required>

                                @if ($errors->has('quantity'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('quantity')  }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label for="collection_time">Collection Time</label>
                                <select name="collection_time" id="collection_time" class="form-control select2bs4 {{ $errors->has('collection_time') ? ' is-invalid' : '' }}" required>
                                    <option value="">-- Select Collection Time --</option>
                                    <option>Morning</option>
                                    <option>Afternoon</option>
                                    <option>Evening</option>
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
                                <label>Comments</label>
                                <textarea class="form-control" rows="3"></textarea>
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
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush