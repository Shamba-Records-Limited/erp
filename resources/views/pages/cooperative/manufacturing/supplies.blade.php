@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Procurement']['purchase_orders'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                                data-toggle="collapse"
                                data-target="#addSupplyAccordion"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addSupplyAccordion">
                            <span class="mdi mdi-plus"></span>Add Supply
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif "
                             id="addSupplyAccordion">
                            <form action="{{ route('supply.add') }}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <h6 class="mb-3">Supplies</h6>
                                    </div>

                                    @if($errors)
                                        <div class="form-group col-12">
                                            <ul>
                                                @foreach($errors->all() as $error)
                                                    <li>
                                                        <span class="help-block text-danger"><strong>{{ $error }}</strong></span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="raw_material">Raw Material</label>
                                        <select name="raw_material" id="raw_material"
                                                class=" form-control form-select {{ $errors->has('raw_material') ? ' is-invalid' : '' }}"
                                                onchange="showUnits('raw_material', 'units')"
                                        >
                                            <option value=""></option>
                                            @foreach($raw_materials as $material)
                                                <option value="{{$material}}" {{ $material == old('material') ? 'selected' : '' }}>{{$material->name.' ('.$material->units.') '.'@ '.number_format($material->estimated_cost,2,'.',',')}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('raw_material'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('raw_material')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="supply_type">Supply Type</label>
                                        <select name="supply_type" id="supply_type"
                                                class=" form-control form-select {{ $errors->has('supply_type') ? ' is-invalid' : '' }}"
                                                onchange="showTheRightDiv('supply_type', 'collectionDiv', 'supplierDiv','paymentStatusDiv')"
                                        >
                                            <option value=""></option>
                                            @foreach(config('enums')["supply_types"][0] as $k=>$v)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('supply_type'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('supply_type')  }}</strong>
                                            </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-3 col-md-6 col-12 d-none"
                                         id="collectionDiv">
                                        <label for="collection">Collection</label>
                                        <select name="collection" id="collection"
                                                class=" form-control form-select {{ $errors->has('collection') ? ' is-invalid' : '' }}"
                                        >
                                            <option value=""></option>
                                            @foreach($collection_products as $product)
                                                <option value="{{$product->id}}" {{ $product->id == old('collection') ? 'selected' : '' }}>{{$product->name.' ('.number_format($product->quantity).')'}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('raw_material'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('raw_material')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 d-none"
                                         id="supplierDiv">
                                        <label for="supplier">Supplier</label>
                                        <select name="supplier" id="supplier"
                                                class=" form-control form-select {{ $errors->has('supplier') ? ' is-invalid' : '' }}"
                                        >
                                            <option value=""></option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('supplier'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('supplier')  }}</strong>
                                            </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="amount">Unit Price</label>
                                        <input type="text" name="amount"
                                               class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                               id="amount" placeholder="4000"
                                               value="{{ old('amount')}}"
                                               required>
                                        @if ($errors->has('amount'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('amount')  }}</strong>
                                            </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="quantity">Quantity <small
                                                    id="units"> </small></label>
                                        <input type="text" name="quantity"
                                               class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}"
                                               id="quantity" placeholder="40"
                                               value="{{ old('quantity')}}"
                                               required>

                                        @if ($errors->has('quantity'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('quantity')  }}</strong>
                                            </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="supply_date">Supply Date</label>
                                        <input type="date" name="supply_date"
                                               class="form-control {{ $errors->has('supply_date') ? ' is-invalid' : '' }}"
                                               id="supply_date" value="{{ old('supply_date')}}"
                                               required>
                                        @if ($errors->has('supply_date'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('supply_date')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 d-none"
                                         id="paymentStatusDiv">
                                        <label for="payment_status">Payment Status</label>
                                        <select name="payment_status" id="payment_status"
                                                class=" form-control form-select {{ $errors->has('payment_status') ? ' is-invalid' : '' }}"
                                                onchange="showPaidAmountDiv()"
                                        >
                                            <option value=""></option>
                                            @foreach(config('enums')["supply_payment_status"][0] as $k=>$v)
                                                <option value="{{$k}}" {{$k == old('payment_status') ? 'selected' : ''}}>{{$v}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('payment_status'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('payment_status')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 d-none"
                                         id="paidAmountDiv">
                                        <label for="paid_amount">Paid Amount</label>
                                        <input type="number" name="paid_amount"
                                               class="form-control {{ $errors->has('paid_amount') ? ' is-invalid' : '' }}"
                                               id="paid_amount" value="{{ old('paid_amount')}}"
                                        >
                                        @if ($errors->has('paid_amount'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('paid_amount')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="store">Store</label>
                                        <select name="store" id="store"
                                                class=" form-control form-select {{ $errors->has('store') ? ' is-invalid' : '' }}"
                                        >
                                            <option value=""></option>
                                            @foreach($stores as $store)
                                                <option value="{{$store->id}}" {{ $store->id == old('store') ? 'selected' : '' }}>{{$store->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('store'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('store')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="notes">Notes</label>
                                        <input type="text" name="notes"
                                               class="form-control {{ $errors->has('notes') ? ' is-invalid' : '' }}"
                                               id="notes" value="{{ old('notes')}}"
                                               placeholder="Timely delivered">
                                        @if ($errors->has('notes'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('notes')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 d-none"
                                         id="deliveryDiv">
                                        <label for="delivery_status">Delivery Status</label>
                                        <select name="delivery_status" id="delivery_status"
                                                class=" form-control form-select
                                                 {{ $errors->has('delivery_status') ? ' is-invalid' : '' }}"
                                        >
                                            <option value=""></option>
                                            @foreach(config('enums')["delivery_status"][0] as $k=>$v)
                                                <option value="{{$k}}"
                                                        {{$k == old('delivery_status') ? 'selected' : ''}}>
                                                    {{$v}}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('payment_status'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('payment_status')  }}</strong>
                                            </span>
                                        @endif
                                    </div>


                                </div>
                                <hr class="mt-1 mb-1">
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
                    @if(has_right_permission(config('enums.system_modules')['Procurement']['purchase_orders'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('manufacturing.supply.download', 'csv') }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('manufacturing.supply.download','xlsx') }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('manufacturing.supply.download', 'pdf') }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">Supplies</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Raw Material</th>
                                <th>Available Quantity</th>
                                <th>Supply Count</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $supply_count = 0; $total_quantity = 0;@endphp
                            @foreach($supplies as $key => $supply)
                                @php
                                    $supply_count += $supply->total_count;
                                    $total_quantity += $supply->quantity;
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $supply->name }}</td>
                                    <td>{{ number_format($supply->quantity).' '.$supply->units }}</td>
                                    <td>{{ number_format($supply->total_count) }}</td>
                                    <td>
                                        <a href="{{ route('manufacturing.supply.details', $supply->id) }}"
                                           class="btn btn-sm btn-rounded btn-info">
                                            Supply Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="2">Total</th>
                                <th colspan="1">{{ number_format($total_quantity)}}</th>
                                <th colspan="2">{{ number_format($supply_count)}}</th>
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
      const showUnits = (parentId, targetId) => {
        const value = $('#' + parentId).val();
        const targetArea = $("#" + targetId)
        targetArea.text('')
        if (value !== null || value !== "") {
          const unit = JSON.parse(value).units
          targetArea.text('(in ' + unit + ')')
        } else {
          targetArea.text('')
        }
      }

      const showTheRightDiv = (parentId, collectionDiv, supplierDiv, paymentStatusDiv) => {
        const value = $('#' + parentId).val()
        if (value !== null || value !== "") {
          if (value === '1') {
            $('#' + collectionDiv).removeClass('d-none')
            $('#' + supplierDiv).addClass('d-none')
            $('#deliveryDiv').addClass('d-none')
            $('#' + paymentStatusDiv).addClass('d-none')
          } else if (value === '2') {
            $('#' + supplierDiv).removeClass('d-none')
            $('#deliveryDiv').removeClass('d-none')
            $('#' + collectionDiv).addClass('d-none')
            $('#' + paymentStatusDiv).removeClass('d-none')
          } else {
            $('#' + supplierDiv).addClass('d-none')
            $('#deliveryDiv').addClass('d-none')
            $('#' + collectionDiv).addClass('d-none')
            $('#' + paymentStatusDiv).addClass('d-none')
          }
        }
      }

      const showPaidAmountDiv = () => {
        const status = $('#payment_status').val();

        if (status == '{{\App\RawMaterialSupplyHistory::PAYMENT_STATUS_PARTIAL}}') {
          $('#paidAmountDiv').removeClass('d-none')
        } else {
          $('#paidAmountDiv').addClass('d-none')
        }
      }
    </script>
@endpush
