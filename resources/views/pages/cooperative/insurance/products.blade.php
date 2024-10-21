@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Insurance Product']['product_premiums'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right" data-toggle="collapse"
                                data-target="#addInsuranceProduct"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addInsuranceProduct">
                            <span class="mdi mdi-plus"></span>Add Product
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="addInsuranceProduct">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Product</h4>
                                </div>
                            </div>


                            <form action="{{ route('cooperative.insurance.product.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="name">Name</label>
                                        <input type="text" name="name"
                                               class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               id="charge" placeholder="Product A" value="{{ old('name')}}" required>

                                        @if ($errors->has('name'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('name')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="type">Product Type</label>
                                        <select name="type" id="type"
                                                class=" form-control select2bs4 {{ $errors->has('type') ? ' is-invalid' : '' }}">
                                            <option value="{{ \App\InsuranceProduct::TYPE_SERVICE }}" selected>Service
                                            </option>
                                            <option value="{{ \App\InsuranceProduct::TYPE_SAVING }}">Saving</option>
                                        </select>
                                        @if ($errors->has('type'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('type')  }}</strong>
                                            </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="premium">Premium</label>
                                        <input type="text" name="premium"
                                               class="form-control {{ $errors->has('premium') ? ' is-invalid' : '' }}"
                                               id="charge" placeholder="150000" value="{{ old('premium')}}" required>

                                        @if ($errors->has('premium'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('premium')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="benefits">Benefits</label>
                                        <select name="benefits[]" id="benefits" multiple
                                                class=" form-control select2bs4 {{ $errors->has('benefits') ? ' is-invalid' : '' }}">
                                            <option value="">---Select Benefit---</option>
                                            @foreach($benefits as $benefit)
                                                <option value="{{$benefit->id}}">{{$benefit->name}}</option>
                                            @endforeach

                                        </select>
                                        @if ($errors->has('benefits'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('benefits')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="interest">Interest (Optional)</label>
                                        <input type="text" name="interest"
                                               class="form-control {{ $errors->has('interest') ? ' is-invalid' : '' }}"
                                               id="interest" placeholder="2.5" value="{{ old('interest')}}">

                                        @if ($errors->has('interest'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('interest')  }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-3 col-12">
                                        <label for="RouteName"></label>
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
                    <h4 class="card-title">Registered Product Premiums</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Premium</th>
                                <th>Interest</th>
                                <th>Benefits</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $canViewBenefits = has_right_permission(config('enums.system_modules')['Insurance Product']['product_benefits'], config('enums.system_permissions')['view']);
                            @endphp
                            @foreach($products as $key => $product)
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$product->name}}</td>
                                    <td>{{$product->type == \App\InsuranceProduct::TYPE_SERVICE  ? 'Service' : 'Saving' }}</td>
                                    <td>{{ $currency.' '.number_format($product->premium, 2, '.', ',') }}</td>
                                    <td> {{ number_format($product->interest, 2) }} </td>
                                    <td>
                                        @if($canViewBenefits)
                                            <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                    data-target="#viewModal_{{$product->id}}">
                                                    <span class="mdi mdi-file-edit">
                                                        view benefits
                                                    </span>
                                            </button>
                                        @endif
                                        <div class="modal fade" id="viewModal_{{$product->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="modalLabel_{{$product->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel_{{$product->id}}">
                                                            View {{$product->name}} Insurance Product Benefits</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <ul class="list-wrapper">
                                                            @foreach($product->benefits as $b)
                                                                <li class="pt-2"> {{$b->name}}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
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
@endpush
