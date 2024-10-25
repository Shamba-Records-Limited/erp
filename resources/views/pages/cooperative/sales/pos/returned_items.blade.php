@extends('layouts.app')

@push('plugin-styles')

@endpush
@section('content')
    @if(has_right_permission(config('enums.system_modules')['Sales']['returned_items'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                                data-toggle="collapse"
                                data-target="#returnedItems"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="returnedItems"><span class="mdi mdi-plus"></span>
                            Return Goods
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif "
                             id="returnedItems">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Record Returned Item </h4>
                                </div>
                            </div>
                            <form method="post" action="{{ route('record.returned.items') }}">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-2 col-md-6 col-12">
                                        <label for="invoice">Invoice Number</label>
                                        <select class="form-control form-select" name="invoice"
                                                id="invoice"
                                                onchange="loadProductsGivenSaleId('invoice', 'manufactured_product', 'collection')">
                                            <option value=""></option>
                                            {{-- sale id --}}
                                            @foreach($sales as $sale)
                                                <option value="{{$sale->id}}" {{ $sale->id == old('invoice') ? 'selected' : '' }}>{{ $sale->invoice_number }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('invoice'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('invoice')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-2 col-md-6 col-12 d-none"
                                         id="type_div">
                                        <label for="type">Product Type</label>
                                        <select class="form-control form-select" name="type"
                                                id="type"
                                                onchange="showInputField('type','manufactured_div', 'collection_div')">
                                            <option value=""></option>
                                            <option value="1" {{ "1" == old('type') ? 'selected' : '' }}>Manufactured Product</option>
                                            <option value="2" {{ "2" == old('type') ? 'selected' : '' }}>Collection</option>
                                        </select>
                                        @if ($errors->has('type'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('type')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-2 col-md-6 col-12 d-none"
                                         id="manufactured_div">
                                        <label for="manufactured_product">Manufactured
                                            Products</label>
                                        <select class="form-control form-select"
                                                name="manufactured_product_prefix"
                                                id="manufactured_product"
                                                onchange="setHiddenValues('manufactured_product','manufactured_product_hidden')"
                                        >
                                        </select>
                                        <input type="hidden" id="manufactured_product_hidden" name="manufactured_product" value="{{ old('manufactured_product') }}">
                                        @if ($errors->has('manufactured_product'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('manufactured_product')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-2 col-md-6 col-12 d-none"
                                         id="collection_div">
                                        <label for="collection">Collection</label>
                                        <select class="form-control form-select" name="collection_prefix"
                                                id="collection"
                                        onchange="setHiddenValues('collection','collection_hidden')"
                                        >
                                        </select>
                                        <input type="hidden" id="collection_hidden" name="collection" value="{{ old('collection') }}">
                                        @if ($errors->has('collection'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('collection')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-2 col-md-3 col-12">
                                        <label for="quantity">Quantity</label>
                                        <input type="text" name="quantity"
                                               class="form-control" placeholder="10"
                                               value="{{ old('quantity')}}">
                                        @if ($errors->has('quantity'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('quantity')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-2 col-md-3 col-12">
                                        <label for="amount">Amount <small>(Total
                                                amount)</small></label>
                                        <input type="text" name="amount"
                                               class="form-control" placeholder="1000"
                                               value="{{ old('amount')}}">
                                        @if ($errors->has('amount'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('amount')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-3 col-12">
                                        <label for="notes">Notes</label>
                                        <input type="text" name="notes"
                                               class="form-control" placeholder="Some notes"
                                               value="{{ old('notes')}}">
                                        @if ($errors->has('notes'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('notes')  }}</strong>
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

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-info btn-fw btn-sm float-right mr-2"
                                data-toggle="collapse"
                                data-target="#filterReturnedItems"
                                aria-expanded="false"
                                aria-controls="filterReturnedItems"><span
                                    class="mdi mdi-filter-outline"></span>Filter
                        </button>
                        <!-- filter -->
                        <div class="collapse @if(request()->date or request()->invoice_no or request()->farmer or request()->customer or ((int)request()->status >=0 && request()->status)) show @endif"
                             id="filterReturnedItems">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h6>Filter Returned Items</h6>
                                </div>
                            </div>
                            <form method="get" href="{{ route('sales.returned-items') }}">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-2 col-md-3 col-12">
                                        <label for="batch_no">Invoice Number</label>
                                        <input type="text" name="invoice_no"
                                               class="form-control" placeholder="22334455"
                                               value="{{ request()->invoice_no}}">
                                    </div>

                                    <div class="form-group col-lg-2 col-md-3 col-12">
                                        <label for="date">Period</label>
                                        <input type="text" name="date"
                                               class="form-control date-range"
                                               id="date" value="{{ request()->date}}">
                                    </div>

                                    <div class="form-group col-lg-2 col-md-3 col-12">
                                        <label for="farmer-filter">Farmer</label>
                                        <select class="form-control form-select" name="farmer[]"
                                                id="farmer-filter" multiple>
                                            <option value=""></option>
                                            @foreach($farmers as $farmer)
                                                <option value="{{$farmer->farmer->id}}" {{  request()->farmer  != null ? (in_array($farmer->farmer->id,request()->farmer) ?  'selected' : '') : '' }}>{{ucwords(strtolower($farmer->first_name.' '.$farmer->other_names))}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-2 col-md-3 col-12">
                                        <label for="customer-filter">Customer</label>
                                        <select class="form-control form-select" name="customer[]"
                                                id="customer-filter" multiple>
                                            <option value=""></option>
                                            @foreach($customers as $customer)
                                                <option value="{{$customer->id}}" {{ request()->customer  != null ? (in_array($customer->id, request()->customer) ? 'selected' : '') : '' }}>{{ucwords(strtolower($customer->name))}}</option>
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
                                        <a href="{{ route('sales.returned-items') }}" type="submit"
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
            <!-- ./filter -->
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div>
                        @if(has_right_permission(config('enums.system_modules')['Sales']['void_invoices'], config('enums.system_permissions')['download']))
                            <form action="{{ route('export.returned.items', 'csv') }}" method="post">
                                @csrf
                                <input type="hidden" name="invoice_no"
                                       value="{{ request()->invoice_no}}">
                                <input type="hidden" name="date" value="{{ request()->date}}">
                                <input type="hidden" name="title" value="Invoices">
                                <input type="hidden" name="farmer" value="{{ request()->farmer ?  implode(',', request()->farmer) : ''}}">
                                <input type="hidden" name="customer"
                                       value="{{ request()->customer ?  implode(',', request()->customer) : ''}}">
                                <button type="submit"
                                        class="btn btn-sm btn-info float-right text-white">
                                    <i class="mdi mdi-download"></i> CSV
                                </button>
                            </form>

                            <form action="{{ route('export.returned.items', 'xlsx') }}" method="post">
                                @csrf
                                <input type="hidden" name="invoice_no"
                                       value="{{ request()->invoice_no}}">
                                <input type="hidden" name="date" value="{{ request()->date}}">
                                <input type="hidden" name="title" value="Invoices">
                                <input type="hidden" name="farmer" value="{{ request()->farmer ?  implode(',', request()->farmer) : ''}}">
                                <input type="hidden" name="customer"
                                       value="{{ request()->customer ?  implode(',', request()->customer) : ''}}">
                                <button type="submit"
                                        class="btn btn-sm btn-github float-right text-white"
                                        style="margin-right: -5px!important;">
                                    <i class="mdi mdi-download"></i> Excel
                                </button>
                            </form>
                            <form action="{{ route('export.returned.items', env('PDF_FORMAT')) }}"
                                  method="post">
                                @csrf
                                <input type="hidden" name="invoice_no"
                                       value="{{ request()->invoice_no}}">
                                <input type="hidden" name="dates" value="{{ request()->date}}">
                                <input type="hidden" name="title" value="Invoices">
                                <input type="hidden" name="farmer" value="{{ request()->farmer ?  implode(',', request()->farmer) : ''}}">
                                <input type="hidden" name="customer"
                                       value="{{ request()->customer ?  implode(',', request()->customer) : ''}}">
                                <button type="submit"
                                        class="btn btn-sm btn-success float-right text-white"
                                        style="margin-right: -8px!important;">
                                    <i class="mdi mdi-download"></i> PDF
                                </button>
                            </form>

                        @endif
                    </div>
                    <h2 class="card-title">Sales {{request()->date}}</h2>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice No.</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                                <th>Notes</th>
                                <th>Date</th>
                                <th>Served By</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $canDownload = has_right_permission(config('enums.system_modules')['Sales']['returned_items'], config('enums.system_permissions')['download']);
                                $user = Auth::user();
                                $total_amount = 0;
                                $total_quantity = 0;
                            @endphp
                            @forelse($items as $key => $item)
                                @php
                                    $itemName = $item->manufactured_product_id ? $item->manufactured_product->finalProduct->name : $item->collection->product->name;
                                    $total_amount += $item->amount;
                                    $total_quantity += $item->quantity;
                                    $date = \Carbon\Carbon::parse($item->date);
                                @endphp
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>
                                        {{ $item->sale->invoices->invoice_number.'-'.$item->sale->invoices->invoice_count }}
                                    </td>
                                    <td>{{ $item->sale->farmer_id ? '(Farmer) '.(ucwords(strtolower($item->sale->farmer->user->first_name.' '.$item->sale->farmer->user->other_names))) : $item->sale->customer->name }}</td>
                                    <td>{{ $itemName }}</td>
                                    <td>{{ number_format($item->quantity) }}</td>
                                    <td>{{$user->cooperative->currency}} {{ number_format($item->amount) }}</td>
                                    <td> {{ $item->notes }} </td>
                                    <td>{{ $date->format('d M Y') }}</td>
                                    <td>{{ ucwords(strtolower($item->served_by->first_name.' '.$item->served_by->other_names))}}</td>
                                </tr>
                            @empty
                                <p>No data</p>
                            @endforelse
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="4">Total</th>
                                <th colspan="1">{{ number_format($total_quantity)}}</th>
                                <th colspan="4">{{ $user->cooperative->currency.' '.number_format($total_amount)}}</th>
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
      const loadProductsGivenSaleId = (parent, target1, target2) => {
        const saleId = $("#" + parent).val()
        $("#" + target1).text('');
        $("#" + target2).text('')
        let url = "{{ route('sales.load-sale-items', ':saleId') }}";
        url = url.replace(':saleId', saleId);
        if (saleId) {
          $("#type_div").removeClass('d-none')
          axios.post(url).then(res => {
            $("#" + target1).append(displayManufacturedProducts(res.data));
            $("#" + target2).append(displayCollection(res.data));
          }).catch(() => {
            const htmlCode = `<option value="">---No Data---</option>`;
            $("#" + target1).append(htmlCode);
            $("#" + target2).append(htmlCode);
          })
        } else {
          $("#type_div").addClass('d-none')
        }
      }

      const displayManufacturedProducts = (data) => {
        let htmlCode = '';
        const manufacturedProducts = data.filter(d => d.production_id !== null);
        if (manufacturedProducts.length > 0) {
          htmlCode += `<option value="">---Select Product---</option>`;
          manufacturedProducts.forEach(d => {
            htmlCode += `<option value="${d.production_id}">${d.manufactured_product_name}</option>`;
          })
        } else {
          htmlCode += `<option value="">---No Data---</option>`;
        }

        return htmlCode;
      }

      const displayCollection = (data) => {
        let htmlCode = '';
        const collections = data.filter(d => d.collection_id !== null);
        if (collections.length > 0) {
          htmlCode += `<option value="">---Select Collections---</option>`;
          collections.forEach(d => {
            htmlCode += `<option value="${d.collection_id}">${d.product_name}</option>`;
          })
        } else {
          htmlCode += `<option value="">---No Data---</option>`;
        }

        return htmlCode;
      }

      const showInputField = (parent, target1, target2) => {
        const value = $("#" + parent).val()
        console.log(value)

        if (value === "1") {
          $("#" + target1).removeClass('d-none')
          $("#" + target2).addClass('d-none')
        } else if (value === "2") {
          $("#" + target2).removeClass('d-none')
          $("#" + target1).addClass('d-none')
        } else {
          $("#" + target1).addClass('d-none')
          $("#" + target2).addClass('d-none')
        }
      }

      const setHiddenValues = (parent, target) => {
        const value = $('#'+parent).val()
        $('#'+target).val(value);
      }
      dateRangePickerFormats("date")
    </script>
@endpush
