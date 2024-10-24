@extends('layouts.app')

@push('plugin-styles')

@endpush
@php
    $canDownload = has_right_permission(config('enums.system_modules')['Procurement']['store'], config('enums.system_permissions')['download']);
$canEdit = has_right_permission(config('enums.system_modules')['Procurement']['purchase_orders'], config('enums.system_permissions')['edit']);
@endphp
@section('content')
    <h4> Supplies in {{ $store->name }}</h4>
    <hr>
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
                            request()->date
                            or request()->product
                            or request()->supplier_type
                            or request()->supplier
                            or request()->delivery_status
                            or request()->payment_status
                            or request()->raw_material
                            or request()->purchase_order_number)
                             show @endif "
                         id="filterAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Purchase Orders Filters</h4>
                            </div>
                        </div>


                        <form action="{{ route('manufacturing.data-by-store', $store->id) }}"
                              method="get">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="purchase_order_number">Purchase Order Number</label>
                                    <input type="text" name="purchase_order_number"
                                           class="form-control"
                                           value="{{ request()->purchase_order_number}}"
                                           id="purchase_order_number"
                                    >
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="raw_material">Raw Material</label>
                                    <select name="raw_material" id="raw_material"
                                            class=" form-control form-select">
                                        <option value=""></option>
                                        @foreach($raw_materials as $material)
                                            <option value="{{$material->id}}" {{$material->id == request()->raw_material ?  'selected' : ''}}> {{ $material->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="product">Product</label>
                                    <select name="product" id="product"
                                            class=" form-control form-select">
                                        <option value=""></option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}" {{$product->id == request()->product ?  'selected' : ''}}> {{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="date">Period</label>
                                    <input type="text" name="date"
                                           class="form-control"
                                           id="date"
                                           value="{{ request()->date }}">
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="supplier_type">Supplier Type</label>
                                    <select name="supplier_type" id="supplier_type"
                                            class=" form-control form-select">
                                        <option value=""></option>
                                        @foreach(config('enums')["supply_types"][0] as $k=>$v)
                                            <option value="{{$k}}" {{$k == request()->supplier_type ? 'selected' : ''}}>
                                                {{$v}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="supplier">Supplier</label>
                                    <select name="supplier" id="supplier"
                                            class=" form-control form-select">
                                        <option value=""></option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}" {{$supplier->id == request()->supplier ?  'selected' : ''}}> {{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="payment_status">Payment Status</label>
                                    <select name="payment_status" id="payment_status"
                                            class=" form-control form-select">
                                        <option value=""></option>
                                        @foreach(config('enums')["supply_payment_status"][0] as $k=>$v)
                                            <option value="{{$k}}" {{$k == request()->payment_status ? 'selected' : ''}}>
                                                {{$v}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="delivery_status">Delivery Status</label>
                                    <select name="delivery_status" id="delivery_status"
                                            class=" form-control form-select">
                                        <option value=""></option>
                                        @foreach(config('enums')["delivery_status"][0] as $k=>$v)
                                            <option value="{{$k}}" {{$k == request()->delivery_status ? 'selected' : ''}}>
                                                {{$v}}
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
                                    <a href="{{ route('manufacturing.data-by-store', $store->id) }}"
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
                    @if($canDownload)
                        <form action="{{ route('manufacturing.store.supplies.download', [$store->id,'csv']) }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('manufacturing.store.supplies.download', [$store->id,'xlsx']) }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>

                        <form action="{{ route('manufacturing.store.supplies.download', [$store->id,'pdf']) }}"
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
                    <h4 class="card-title">Supplies</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Purchase #</th>
                                <th>Raw Material</th>
                                <th>Supply Type</th>
                                <th>Supplier</th>
                                <th>Product</th>
                                <th>Supply Date</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Quantity</th>
                                <th>Stock Value</th>
                                <th>Payment Status</th>
                                <th>Delivery Status</th>
                                <th>Store</th>
                                <th>Notes</th>
                                <th>Recorded By</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $total_amount = 0;
                                $total_quantity = 0;
                                $total_balance = 0;
                                $total_value = 0;
                                $currency = Auth::user()->cooperative->currency;
                            @endphp
                            @foreach($supplies as $key => $supply)
                                @php

                                        $total_amount += ($supply->quantity > 0 ? $supply->amount/$supply->quantity : 0);
                                        $total_quantity += $supply->quantity;
                                        $total_balance += $supply->balance;
                                        $total_value += $supply->amount
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $supply->purchase_number }}</td>
                                    <td>{{ $supply->raw_material->name }}</td>
                                    <td>{{ config('enums')["supply_types"][0][$supply->supply_type] }}</td>
                                    <td>{{ $supply->supply_type  == \App\RawMaterialSupplyHistory::SUPPLY_TYPE_SUPPLIER ? ucwords(strtolower($supply->supplier->name)) : ''}}</td>
                                    <td>{{ $supply->supply_type  == \App\RawMaterialSupplyHistory::SUPPLY_TYPE_COLLECTION ? ucwords(strtolower($supply->product_collection->name)) : ''}}</td>
                                    <td>{{\Carbon\Carbon::parse($supply->supply_date)->format('D, d M Y')}} </td>
                                    <td>{{ $currency.' '.number_format(($supply->quantity > 0 ? $supply->amount/$supply->quantity : 0),2)}} </td>
                                    <td>{{ $currency.' '.number_format($supply->balance)}} </td>
                                    <td>{{ number_format($supply->quantity,2)}} </td>
                                    <td>{{ $currency.' '.number_format($supply->amount,2)}} </td>
                                    <td>
                                        @if($supply->payment_status == \App\RawMaterialSupplyHistory::PAYMENT_STATUS_PAID)
                                            <span class="badge badge-outline badge-success text-white"> {{config('enums')["supply_payment_status"][0][$supply->payment_status]}}</span>
                                        @elseif($supply->payment_status == \App\RawMaterialSupplyHistory::PAYMENT_STATUS_PARTIAL)
                                            <span class="badge badge-outline badge-warning text-white"> {{config('enums')["supply_payment_status"][0][$supply->payment_status]}}</span>
                                        @else
                                            <span class="badge badge-outline badge-danger text-white"> {{config('enums')["supply_payment_status"][0][$supply->payment_status]}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($supply->delivery_status == \App\RawMaterialSupplyHistory::DELIVERY_STATUS_DELIVERED)
                                            <span class="badge badge-outline badge-success text-white">
                                                {{config('enums')["delivery_status"][0][$supply->delivery_status]}}
                                            </span>
                                        @else
                                            <span class="badge badge-outline badge-danger text-white">
                                                {{config('enums')["delivery_status"][0][$supply->delivery_status]}}
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $supply->manufacturing_store->name }}</td>
                                    <td>{{$supply->details}} </td>
                                    <td>{{ ucwords(strtolower($supply->user->first_name.' '.$supply->user->other_names))}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="7">Total</th>
                                <th colspan="1">{{$currency.' '.number_format($total_amount,2)}}</th>
                                <th colspan="1">{{$currency.' '.number_format($total_balance,2)}}</th>
                                <th colspan="1">{{ number_format($total_quantity,2)}}</th>
                                <th colspan="6">{{ $currency.' '.number_format($total_value,2)}}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-3">Productions in {{ $store->name }}</h4>
    <hr>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                            data-toggle="collapse"
                            data-target="#filterProductionsAccordion"
                            aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                            aria-controls="filterProductionsAccordion"><span
                                class="mdi mdi-database-search"></span>Filter
                    </button>
                    <div class="collapse @if(
                            request()->expiry_date
                            or request()->production_lot
                            or request()->expiry_status)
                             show @endif "
                         id="filterProductionsAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Supplies Filters</h4>
                            </div>
                        </div>


                        <form action="{{ route('manufacturing.data-by-store', $store->id) }}"
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
                                    <label for="expiry_status">Expiry Status</label>
                                    <select name="expiry_status" id="expiry_status"
                                            class=" form-control form-select">
                                        <option value=""></option>
                                        @foreach(config('enums')["expiry_status"][0] as $k=>$v)
                                            <option value="{{$k}}" {{$k == request()->expiry_status ? 'selected' : ''}}>
                                                {{$v}}
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
                                    <a href="{{route('manufacturing.data-by-store', $store->id) }}"
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
                    @if(has_right_permission(config('enums.system_modules')['Manufacturing']['production'], config('enums.system_permissions')['download']))
                        <form action="{{ route('manufacturing.store.production-history.download', [$store->id,'csv']) }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('manufacturing.store.production-history.download', [$store->id,'xlsx']) }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>

                        <form action="{{ route('manufacturing.store.production-history.download', [$store->id,'pdf']) }}"
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
                    <h4 class="card-title"> Production</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Production Lot</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Stock Value</th>
                                <th>Expires</th>
                                <th>Expiry Date/Status</th>
                                <th>Created By</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $canEdit = has_right_permission(config('enums.system_modules')['Manufacturing']['production'], config('enums.system_permissions')['edit']);
                                $canDelete = has_right_permission(config('enums.system_modules')['Manufacturing']['production'], config('enums.system_permissions')['delete']);
                                $canViewRawMaterials = has_right_permission(config('enums.system_modules')['Manufacturing']['raw_materials'], config('enums.system_permissions')['view']);
                                $currency = Auth::user()->cooperative->currency;
                                $total_quantity = 0;
                                $total_price = 0;
                                $total_value = 0;
                            @endphp
                            @foreach($productionHistory as $key => $prod)

                                @php
                                    $total_quantity += $prod->quantity;
                                    $total_price += $prod->unit_price;
                                    $total_value += ($prod->unit_price *  $prod->quantity);
                                @endphp

                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{$prod->production->finalProduct->name }}</td>
                                    <td>{{$prod->production_lot }}</td>
                                    <td>{{number_format($prod->quantity) }} {{$prod->production->finalProduct->unit->name }}</td>
                                    <td>{{$currency}} {{number_format($prod->unit_price,2) }}</td>
                                    <td>{{$currency}} {{ number_format($prod->unit_price*$prod->quantity,2) }}</td>
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
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="3"> Total</th>
                                <th colspan="1"> {{ number_format($total_quantity,2) }}</th>
                                <th colspan="1"> {{ $currency.' '.number_format($total_price,2) }}</th>
                                <th colspan="4"> {{ $currency.' '.number_format($total_value,2) }}</th>
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
      dateRangePickerFormats("date")
      dateRangePickerFormats("expiry_date")
    </script>
@endpush
