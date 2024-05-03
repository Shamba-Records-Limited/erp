@extends('layout.master')

@push('plugin-styles')

@endpush

@php
    $canDownload = has_right_permission(config('enums.system_modules')['Procurement']['purchase_orders'], config('enums.system_permissions')['download']);
    $canEdit = has_right_permission(config('enums.system_modules')['Procurement']['purchase_orders'], config('enums.system_permissions')['edit']);
@endphp
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
                            request()->date
                            or request()->product
                            or request()->supplier_type
                            or request()->supplier
                            or request()->delivery_status
                            or request()->payment_status
                            or request()->store
                            or request()->purchase_order_number)
                             show @endif "
                         id="filterAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Purchase Orders Filters</h4>
                            </div>
                        </div>


                        <form action="{{ route('manufacturing.supply.details', $raw_material_id) }}"
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
                                    <label for="product">Product</label>
                                    <select name="product" id="product"
                                            class=" form-control select2bs4">
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
                                            class=" form-control select2bs4">
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
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}" {{$supplier->id == request()->supplier ?  'selected' : ''}}> {{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="store">Store</label>
                                    <select name="store" id="store"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach($stores as $store)
                                            <option value="{{$store->id}}" {{$store->id == request()->store ?  'selected' : ''}}>
                                                {{ $store->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="payment_status">Payment Status</label>
                                    <select name="payment_status" id="payment_status"
                                            class=" form-control select2bs4">
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
                                            class=" form-control select2bs4">
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
                                    <a href="{{route('manufacturing.supply.details', $raw_material_id) }}"
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
                        <form action="{{ route('manufacturing.supply-details.download', [$raw_material_id,'csv']) }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('manufacturing.supply-details.download', [$raw_material_id,'xlsx']) }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>

                        <form action="{{ route('manufacturing.supply-details.download', [$raw_material_id,'pdf']) }}"
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
                                <th>Unit Cost</th>
                                <th>Balance</th>
                                <th>Quantity</th>
                                <th>Stock Value</th>
                                <th>Payment Status</th>
                                <th>Delivery Status</th>
                                <th>Store</th>
                                <th>Notes</th>
                                <th>Recorded By</th>
                                <th></th>
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
                                    $total_amount += $supply->amount/$supply->quantity;
                                    $total_quantity += $supply->quantity;
                                    $total_balance += $supply->balance;
                                    $total_value += $supply->amount;
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $supply->purchase_number }}</td>
                                    <td>{{ $supply->raw_material->name }}</td>
                                    <td>{{ config('enums')["supply_types"][0][$supply->supply_type] }}</td>
                                    <td>{{ $supply->supply_type  == \App\RawMaterialSupplyHistory::SUPPLY_TYPE_SUPPLIER ? ucwords(strtolower($supply->supplier->name)) : ''}}</td>
                                    <td>{{ $supply->supply_type  == \App\RawMaterialSupplyHistory::SUPPLY_TYPE_COLLECTION ? ucwords(strtolower($supply->product_collection->name)) : ''}}</td>
                                    <td>{{\Carbon\Carbon::parse($supply->supply_date)->format('D, d M Y')}} </td>
                                    <td>{{ $currency.' '.number_format($supply->amount/$supply->quantity,2)}} </td>
                                    <td>{{ $currency.' '.number_format($supply->balance,2)}} </td>
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
                                    <td>
                                        @if($supply->payment_status != \App\RawMaterialSupplyHistory::PAYMENT_STATUS_PAID && $canEdit)
                                            <button type="button"
                                                    class="btn btn-sm btn-rounded btn-info"
                                                    data-toggle="modal"
                                                    data-target="#editModal_{{$supply->id}}">
                                                <i class="mdi mdi-cash"></i>
                                                Make Payment
                                            </button>

                                            <div class="modal fade" id="editModal_{{$supply->id}}"
                                                 tabindex="-1"
                                                 role="dialog"
                                                 aria-labelledby="editModalLabel_{{$supply->id}}"
                                                 aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered"
                                                     role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editModalLabel_{{$supply->id}}">
                                                                Make Payment
                                                                of {{$supply->raw_material->name}}
                                                                to {{ ucwords(strtolower($supply->supplier->name)) }}
                                                            </h5>

                                                            <button type="button" class="close"
                                                                    data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div>
                                                            <form action="{{ route('manufacturing.supply.history.pay',$supply->id) }}"
                                                                  method="post">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="form-row">
                                                                        <div class="form-group col-12">
                                                                            <label for="amount_{{$supply->id}}">Amount</label>
                                                                            <input type="number"
                                                                                   name="amount"
                                                                                   id="amount_{{$supply->id}}"
                                                                                   class="form-control"
                                                                                   value="{{$supply->balance }}"
                                                                                   min="1"
                                                                                   max="{{$supply->balance}}">
                                                                            @if ($errors->has('amount'))
                                                                                <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('amount')  }}</strong>
                                                                                </span>
                                                                            @endif

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-dismiss="modal">
                                                                        Close
                                                                    </button>
                                                                    <button type="submit"
                                                                            class="btn btn-primary">
                                                                        Save
                                                                        changes
                                                                    </button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <a class="btn btn-warning btn-sm btn-rounded"
                                           href="{{ route('manufacturing.purchase_order.receipts.download', $supply->id) }}">
                                            <span class="mdi mdi-download"></span>
                                            Download Receipt
                                        </a>

                                        @if($supply->delivery_status == App\RawMaterialSupplyHistory::DELIVERY_STATUS_PENDING &&
                                            $supply->payment_status == \App\RawMaterialSupplyHistory::PAYMENT_STATUS_PENDING && $canEdit)
                                            <button type="button"
                                                    class="btn btn-sm btn-rounded btn-primary"
                                                    data-toggle="modal"
                                                    data-target="#editSupplyModal_{{$supply->id}}">
                                                <i class="mdi mdi-file-edit"></i>
                                            </button>

                                            <div class="modal fade"
                                                 id="editSupplyModal_{{$supply->id}}"
                                                 tabindex="-1"
                                                 role="dialog"
                                                 aria-labelledby="editSupplyModalLabel_{{$supply->id}}"
                                                 aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered"
                                                     role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editSupplyModalLabel_{{$supply->id}}">
                                                                Edit Supply
                                                                of {{$supply->raw_material->name}}
                                                            </h5>

                                                            <button type="button" class="close"
                                                                    data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div>
                                                            <form action="{{ route('manufacturing.supply.history.edit',$supply->id) }}"
                                                                  method="post">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="form-row">
                                                                        <div class="form-group col-12">
                                                                            <label for="edit_mount_{{$supply->id}}">Total
                                                                                Amount</label>
                                                                            <input type="number"
                                                                                   name="edit_mount"
                                                                                   id="edit_mount_{{$supply->id}}"
                                                                                   class="form-control"
                                                                                   value="{{$supply->amount}}"
                                                                                   min="1"
                                                                            >
                                                                            @if ($errors->has('edit_mount'))
                                                                                <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('edit_mount')}}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>

                                                                        <div class="form-group col-12">
                                                                            <label for="edit_quantity_{{$supply->id}}">Quantity</label>
                                                                            <input type="number"
                                                                                   name="edit_quantity"
                                                                                   id="edit_quantity_{{$supply->id}}"
                                                                                   class="form-control"
                                                                                   value="{{$supply->quantity}}"
                                                                                   min="1"
                                                                            >
                                                                            @if ($errors->has('edit_quantity'))
                                                                                <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('edit_quantity')  }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>

                                                                        <div class="form-group col-12">
                                                                            <label for="edit_supply_date_{{$supply->id}}">Supply
                                                                                Date</label>
                                                                            <input type="date"
                                                                                   name="edit_supply_date"
                                                                                   id="edit_supply_date_{{$supply->id}}"
                                                                                   class="form-control"
                                                                                   value="{{$supply->supply_date}}"
                                                                            >
                                                                            @if ($errors->has('edit_supply_date'))
                                                                                <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('edit_supply_date')  }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                        <div class="form-group col-12">
                                                                            <label for="edit_store_{{$supply->id}}">Store</label>
                                                                            <select name="edit_store"
                                                                                    id="edit_store_{{$supply->id}}"
                                                                                    class=" form-control select2bs4 {{ $errors->has('store') ? ' is-invalid' : '' }}"
                                                                            >
                                                                                <option value=""></option>
                                                                                @foreach($stores as $store)
                                                                                    <option value="{{$store->id}}" {{ $store->id == $supply->store_id ? 'selected' : '' }}>{{$store->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            @if ($errors->has('edit_store'))
                                                                                <span class="help-block text-danger">
                                                                                        <strong>{{ $errors->first('edit_store')  }}</strong>
                                                                                    </span>
                                                                            @endif
                                                                        </div>

                                                                        <div class="form-group col-12">
                                                                            <label for="edit_delivery_status_{{$supply->id}}">Delivery
                                                                                Status</label>
                                                                            <select name="edit_delivery_status"
                                                                                    id="edit_delivery_status_{{$supply->id}}"
                                                                                    class=" form-control select2bs4 {{ $errors->has('store') ? ' is-invalid' : '' }}"
                                                                            >
                                                                                <option value=""></option>
                                                                                @foreach(config('enums')["delivery_status"][0] as $k=>$v)
                                                                                    <option value="{{$k}}"
                                                                                            {{$k == $supply->delivery_status ? 'selected' : ''}}>
                                                                                        {{$v}}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            @if ($errors->has('edit_delivery_status'))
                                                                                <span class="help-block text-danger">
                                                                                        <strong>{{ $errors->first('edit_delivery_status')  }}</strong>
                                                                                    </span>
                                                                            @endif
                                                                        </div>

                                                                        <div class="form-group col-12">
                                                                            <label for="edit_notes_{{$supply->id}}">Notes</label>
                                                                            <input type="text"
                                                                                   name="edit_notes"
                                                                                   id="edit_notes_{{$supply->id}}"
                                                                                   class="form-control"
                                                                                   value="{{$supply->details}}"
                                                                            >
                                                                            @if ($errors->has('edit_notes'))
                                                                                <span class="help-block text-danger">
                                                                                    <strong>{{ $errors->first('edit_notes')  }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-dismiss="modal">
                                                                        Close
                                                                    </button>
                                                                    <button type="submit"
                                                                            class="btn btn-primary">
                                                                        Save
                                                                        changes
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif


                                        @if($supply->delivery_status == \App\RawMaterialSupplyHistory::DELIVERY_STATUS_PENDING && $canEdit)
                                            <a class="btn btn-dark btn-sm btn-rounded"
                                               href="{{ route('manufacturing.supply.history.mark_goods_as_recieved', $supply->id) }}">
                                                <span class="mdi mdi-check-all"></span>
                                                Receive Goods
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="7">Total</th>
                                <th colspan="1">{{$currency.' '.number_format($total_amount,2)}}</th>
                                <th colspan="1">{{$currency.' '.number_format($total_balance,2)}}</th>
                                <th colspan="1">{{ number_format($total_quantity,2)}}</th>
                                <th colspan="7">{{ $currency.' '.number_format($total_value,2)}}</th>
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
    </script>
@endpush
