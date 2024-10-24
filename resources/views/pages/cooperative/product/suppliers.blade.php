@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

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
                            request()->filter_member_no
                            or request()->filter_route
                            or request()->filter_customer_type
                            or request()->filter_id_no
                        )
                             show @endif"
                         id="filterProductAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Filter Product</h4>
                            </div>
                        </div>
                        <form action="{{ route('cooperative.products.suppliers.show') }}" method="get">
                            @csrf
                            <div class="form-row">

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="filter_route">Route</label>
                                    <select name="filter_route" id="filter_route"
                                            class=" form-control form-select">
                                        <option value=""></option>
                                        @foreach($routes as $route)
                                            <option value="{{$route->id}}"
                                                    {{ $route->id == request()->filter_route ? 'selected' : ''}}>
                                                {{ $route->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="filter_member_no">Member Number</label>
                                    <input type="text" name="filter_member_no"
                                           class="form-control"
                                           id="filter_member_no" placeholder="M9864"
                                           value="{{ request()->filter_member_no}}">
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="filter_customer_type">Customer Type</label>
                                    <select name="filter_customer_type" id="filter_customer_type"
                                            class=" form-control form-select">
                                        <option value=""></option>
                                        @foreach(config('enums.farmer_customer_types') as $key=>$type)
                                            <option value="{{$key}}"
                                                    {{ $key == request()->filter_customer_type ? 'selected' : ''}}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="filter_id_no">ID No/Passport</label>
                                    <input type="text" name="filter_id_no"
                                           class="form-control"
                                           id="filter_id_no" placeholder="12345678"
                                           value="{{ request()->filter_id_no}}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit"
                                            class="btn btn-primary btn-fw btn-block">Search
                                    </button>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <a href="{{route('cooperative.products.suppliers.show') }}"
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
                @if(has_right_permission(config('enums.system_modules')['Product Management']['suppliers'], config('enums.system_permissions')['download']))
                        <form action="{{ route('cooperative.products.suppliers.download','csv') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('cooperative.products.suppliers.download','xlsx') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>

                        <form action="{{ route('cooperative.products.suppliers.download','pdf') }}"
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

                    <h4 class="card-title">Suppliers</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Route</th>
                                <th>Member No.</th>
                                <th>Id/Passport No.</th>
                                <th>Phone No.</th>
                                <th>Customer Type</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($farmers as $key => $farmer)
                                    <tr>
                                        <td>{{ ucwords(strtolower($farmer->name) )}}</td>
                                        <td>{{$farmer->route}}</td>
                                        <td>{{$farmer->member_no}}</td>
                                        <td>{{$farmer->id_no}}</td>
                                        <td>{{$farmer->phone_no}}</td>
                                        <td>{{ config('enums.farmer_customer_types')[strtolower($farmer->customer_type)]}}</td>
                                        <td>
                                            @if(has_right_permission(config('enums.system_modules')['Product Management']['products'], config('enums.system_permissions')['view']))
                                                <a href="{{ route("cooperative.farmer.products.suppliers.show", $farmer->user_id) }}" class="btn btn-primary btn-sm btn-rounded">
                                                    <span class="mdi mdi-eye"></span> View Products
                                                </a>
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
