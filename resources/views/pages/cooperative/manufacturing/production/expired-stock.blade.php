@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                            data-toggle="collapse"
                            data-target="#filterAccordion"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="filterAccordion"><span
                                class="mdi mdi-database-search"></span>Filter
                    </button>
                    <div class="collapse @if(
                            request()->expiry_date
                            or request()->production_lot
                            or request()->final_product)
                             show @endif "
                         id="filterAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Expired Stock Filters</h4>
                            </div>
                        </div>


                        <form action="{{ route('manufacturing.production.expired-stock') }}"
                              method="get">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="production_lot">Production Lot</label>
                                    <input type="text" name="production_lot"
                                           class="form-control"
                                           value="{{ request()->production_lot}}"
                                           id="production_lot"
                                    >
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="expiry_date">Expiry Date Period</label>
                                    <input type="text" name="expiry_date"
                                           class="form-control"
                                           id="expiry_date"
                                           value="{{ request()->expiry_date }}">
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="final_product">Final Product</label>
                                    <select name="final_product" id="final_product"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach($finalProducts as $product)
                                            <option value="{{$product->id}}" {{$product->id == request()->final_product ? 'selected' : ''}}>
                                                {{$product->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <label for=""></label>
                                    <button type="submit"
                                            class="btn btn-primary btn-fw btn-block">
                                        Filter
                                    </button>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <label for=""></label>
                                    <a href="{{route('manufacturing.production.expired-stock') }}"
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
                    @if(has_right_permission(config('enums.system_modules')['Manufacturing']['expired_stock'], config('enums.system_permissions')['download']))
                        <form action="{{ route('manufacturing.production.expired-stock.download','csv') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('manufacturing.production.expired-stock.download','xlsx') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>

                        <form action="{{ route('manufacturing.production.expired-stock.download', 'pdf') }}"
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
                    <h4 class="card-title"> Expired Stock</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Production Lot</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Value</th>
                                <th>Expires</th>
                                <th>Expiry Date/Status</th>
                                <th>Created By</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $canViewRawMaterials = has_right_permission(config('enums.system_modules')['Manufacturing']['raw_materials'], config('enums.system_permissions')['view']);
                                $currency = Auth::user()->cooperative->currency;
                                $total_quantity = 0;
                                $total_price = 0;
                                $total_value = 0;
                            @endphp
                            @foreach($expired_products as $key => $prod)

                                @php
                                    $total_quantity += $prod->quantity;
                                    $total_price += $prod->unit_price;
                                    $total_value += ($prod->unit_price *  $prod->quantity);
                                @endphp

                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$prod->production_lot }}</td>
                                    <td>{{$prod->production->finalProduct->name }}</td>
                                    <td>{{number_format($prod->quantity) }} {{$prod->production->finalProduct->unit->name }}</td>
                                    <td>{{$currency}} {{number_format($prod->unit_price) }}</td>
                                    <td>{{$currency}} {{ number_format($prod->unit_price*$prod->quantity) }}</td>
                                    <td>{{ config('enums')["will_expire"][0][$prod->expires]  }}</td>
                                    <td>
                                        {{ $prod->expires == 1 ? \Carbon\Carbon::parse($prod->expiry_date)->format('D, d M Y') : '' }}
                                        @if($prod->expiry_status == \App\ProductionHistory::EXPIRY_STATUS_EXPIRED)
                                            <badge class="badge badge-danger text-white">
                                                Expired
                                            </badge>
                                        @else
                                            <badge class="badge badge-success text-white">
                                                Valid Status
                                            </badge>
                                        @endif
                                    </td>
                                    <td>{{$prod->registered_by->first_name.' '.$prod->registered_by->other_names }}</td>
                                    <td>
                                        <a class="btn btn-info btn-rounded btn-sm"
                                           href="{{ route('cooperative.manufacturing.production.raw-materials', [$prod->production->id, $prod->id]) }}">
                                            Raw Materials
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="3"> Total</th>
                                <th colspan="1"> {{ number_format($total_quantity,2) }}</th>
                                <th colspan="1"> {{ $currency.' '.number_format($total_price,2) }}</th>
                                <th colspan="5"> {{ $currency.' '.number_format($total_value,2) }}</th>
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
    <script>
      dateRangePickerFormats("expiry_date")
    </script>
@endpush
