@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    @php
            $date = \Carbon\Carbon::create($sale->date);
            $now = \Carbon\Carbon::now();
           $canEdit = has_right_permission(config('enums.system_modules')['Sales']['invoice'], config('enums.system_permissions')['edit']) && $now->lt($date) && $sale->invoices->delivery_status === \App\Invoice::DELIVERY_STATUS_PENDING;
    @endphp
    @if($canEdit &&  ($sale->type == \App\Sale::SALE_TYPE_QUOTATION || $sale->invoices->status == \App\Invoice::STATUS_UNPAID))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                                data-toggle="collapse"
                                data-target="#makeSale"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addVetItems"><span class="mdi mdi-plus"></span>Add
                            Item
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="makeSale">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Add Item Selling</h4>
                                </div>
                            </div>
                            <form action="{{ route('sales.pos.add.item', $sale->id) }}"
                                  method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="product">What to sell</label>

                                        <select id="whatToSell" name="what_to_sell"
                                                class="form-control form-select ">
                                            <option value=""> {{ '- Select what to sell -'}}</option>
                                            <option value="1"> {{ 'Collections'}}</option>
                                            <option value="2"> {{ 'Manufactured Products'}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12 d-none"
                                         id="collectionP">
                                        <label for="product">Collected Product</label>

                                        <select name="product" id="product"
                                                class="form-control form-select {{ $errors->has('product') ? ' is-invalid' : '' }}">
                                            <option value=""> {{ '- Select Product -'}}</option>
                                            @foreach($collections as $product)
                                                <option value="{{$product->collections[0]->id}}"> {{ $product->name }}
                                                    (in {{ $product->unit->name }}
                                                    ) {{'@'}}{{Auth::user()->cooperative->currency}}{{$product->sale_price}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('product'))
                                            <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('product')  }}</strong>
                                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12 d-none"
                                         id="manufacturedP">
                                        <label for="manufactured">Manufactured Product</label>
                                        <select name="manufactured" id="manufactured"
                                                class="form-control form-select {{ $errors->has('manufactured') ? ' is-invalid' : '' }}">
                                            <option value=""> {{ '- Select Manufactured Product -'}}</option>
                                            @foreach($productions as $product)
                                                <option value="{{$product->id}}"> {{ $product->finalProduct->name }}{{ '@'.$product->final_selling_price }} </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('manufactured'))
                                            <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('manufactured')  }}</strong>
                                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="amount">Amount</label>
                                        <input type="text" name="amount"
                                               class="form-control  {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                               value="{{ old('amount')}}" id="amount"
                                               placeholder="100.60"
                                               required>
                                        @if ($errors->has('amount'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('amount')  }}</strong>
                                        </span>
                                        @endif
                                    </div>


                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label for="quantity"> Quantity</label>
                                        <input type="text" name="quantity"
                                               class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}"
                                               id="quantity" placeholder="1"
                                               value="{{ old('quantity')}}">

                                        @if ($errors->has('quantity'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('quantity')  }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <button type="submit"
                                                class="btn btn-primary btn-fw btn-block">
                                            Add
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <!-- filter -->
                        <div class="collapse" id="filterCollections">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h6>Filter Collections</h6>
                                </div>
                            </div>
                            <form method="get">
                                <div class="form-row">
                                    <div class="form-group col-lg-3 col-md-3 col-3">
                                        <label for="batch_no">Batch</label>
                                        <input type="text" name="batch_no"
                                               class="form-control" placeholder="22334455">
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
                                </div>
                            </form>
                        </div>
                        <!-- ./filter -->
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    @if($sale->type == \App\Sale::SALE_TYPE_SALE )
                        <a href="{{ route('sales.pos.invoice.pdf', $sale_id)}}"
                           class="btn btn-success btn-sm float-left" target="_blank">
                            <span class="mdi mdi-printer"></span> Print Invoice
                        </a>&nbsp;
                    @else
                        <a href="{{ route('sales.pos.quotation.pdf', $sale_id)}}"
                           class="btn btn-success btn-sm float-left" target="_blank">
                            <span class="mdi mdi-printer"></span> Print Invoice
                        </a>&nbsp;
                    @endif

                    @if($sale)
                        <div class="mt-3 text-left">
                            <b>Customer:</b> {{ $sale->farmer_id ? $sale->farmer->user->first_name." ".$sale->farmer->user->other_names : $sale->customer->name }}
                            <br/>
                            <b>Email:</b> {{ $sale->farmer_id ? $sale->farmer->user->email : $sale->customer->email }}
                            <br/>
                            <b>Sale Batch #:</b> {{ $sale->sale_batch_number.'-'.$sale->sale_count}}

                        </div>
                    @endif
                    <br/>
                    <h2 class="card-title">Sale Items</h2>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <!-- <thead> -->
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                            <tbody>
                            @php $total_amt = 0;@endphp
                            @php $total_discount = 0;@endphp
                            @php $note = null;@endphp
                            @php $currency = Auth::user()->cooperative->currency; @endphp

                            @foreach($items as $key => $item)
                                @php
                                    $total_discount = $item->sale->discount ?? 0;
                                    $note = $item->sale->note ?? 'This is digitally generated.';
                                    $units = $item->manufactured_product_id ? $item->manufactured_product->finalProduct->unit->name : $item->collection->product->unit->name;
                                    $itemName = $item->manufactured_product_id ? $item->manufactured_product->finalProduct->name : $item->collection->product->name
                                @endphp

                                <tr>
                                    @php $total_amt += $item->amount*$item->quantity;@endphp
                                    <td>{{++$key }}</td>
                                    <td>{{ ucwords(strtolower($itemName)) }}</td>
                                    <td>{{ $item->quantity }} {{ $units }}</td>
                                    <td>{{$currency}} {{ number_format($item->amount) }}</td>
                                    <td>{{$currency}} {{ number_format($item->amount*$item->quantity) }}</td>
                                    <td>
                                        @if($canEdit &&  ($sale->type == \App\Sale::SALE_TYPE_QUOTATION || $sale->invoices->status == \App\Invoice::STATUS_UNPAID))
                                            <form action="{{ route('sales.pos.delete.item', $item->id) }}"
                                                  method="post">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-primary"
                                                        data-toggle="modal"
                                                        data-target="#editSaleItem_{{$item->id}}">
                                                    <span class="mdi mdi-file-edit"></span>
                                                </button>
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <span class="mdi mdi-trash-can"></span>
                                                </button>
                                            </form>
                                        @endif


                                        {{--  modals discount start--}}
                                        <div class="modal fade" id="editSaleItem_{{$item->id}}"
                                             tabindex="-1" role="dialog"
                                             aria-labelledby="editSaleItemLabel_{{$item->id}}"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered"
                                                 role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="editSaleItemLabel_{{$item->id}}">
                                                            {{ ucwords(strtolower($itemName))}}</h5>
                                                        <button type="button" class="close"
                                                                data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('sales.pos.update.price-quantity', $item->id) }}"
                                                          method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-12">
                                                                    <label> Quantity ({{ $units }}
                                                                        )</label>
                                                                    <input type="text"
                                                                           name="quantity"
                                                                           class="form-control"
                                                                           placeholder="10"
                                                                           value="{{ $item->quantity}}"
                                                                           required>
                                                                </div>

                                                                <div class="form-group col-12">
                                                                    <label> Unit Prices
                                                                        ({{ $currency }})</label>
                                                                    <input type="number"
                                                                           name="unit_price"
                                                                           class="form-control"
                                                                           placeholder="10"
                                                                           value="{{ $item->amount}}"
                                                                           required>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button"
                                                                    class="btn btn-secondary"
                                                                    data-dismiss="modal">Close
                                                            </button>
                                                            <button type="submit"
                                                                    class="btn btn-primary">Save
                                                                changes
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        {{--  modal end   --}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="4">SUB TOTAL</th>
                                <th colspan="2">{{$currency}} {{ number_format($total_amt)}}</th>
                            </tr>
                            <tr>
                                <th colspan="4">DISCOUNT</th>
                                <th colspan="1">{{$currency}} {{ number_format($total_discount)}}</th>
                                <th>
                                    @if($canEdit &&  ($sale->type == \App\Sale::SALE_TYPE_QUOTATION || $sale->invoices->status == \App\Invoice::STATUS_UNPAID))
                                        <button type="button" class="btn btn-sm btn-primary"
                                                data-toggle="modal"
                                                data-target="#editDiscountModal_{{$item->id}}">
                                            <span class="mdi mdi-file-edit"></span>
                                        </button>
                                    @endif

                                    {{--  modals discount start--}}
                                    <div class="modal fade" id="editDiscountModal_{{$item->id}}"
                                         tabindex="-1" role="dialog"
                                         aria-labelledby="editDiscountModal_{{$item->id}}"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered"
                                             role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="editDiscountModal_{{$item->id}}">
                                                        Edit Discount</h5>
                                                    <button type="button" class="close"
                                                            data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('sales.pos.update.discount', $item->sale->id) }}"
                                                      method="post">
                                                    <div class="modal-body">
                                                        @csrf
                                                        <div class="form-row">

                                                            <div class="form-group col-12">
                                                                <label>Discount
                                                                    Amount</label>
                                                                <input type="number" name="discount"
                                                                       class="form-control"
                                                                       placeholder="100"
                                                                       value="{{ $item->sale->discount}}"
                                                                       required>
                                                            </div>

                                                        </div>
                                                        <input type="hidden" name="sale_amount"
                                                               value="{{($total_amt - $total_discount)}}">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button"
                                                                class="btn btn-secondary"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                        <button type="submit"
                                                                class="btn btn-primary">Save changes
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{--  modal end   --}}
                                </th>
                            </tr>
                            <tr>
                                <th colspan="4">SUB TOTAL</th>
                                <th colspan="2">{{$currency}} {{ number_format($total_amt - $total_discount)}}</th>
                            </tr>
                            <tr>
                                <th colspan="6"></th>
                            </tr>

                            </tfoot>
                        </table>
                        <p>{{ $note }}</p>
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
      $("#whatToSell").change(() => {
        const whatToSell = $('#whatToSell').val();
        if (whatToSell === "1") {
          $("#manufacturedP").addClass('d-none')
          $("#collectionP").removeClass('d-none')
          $("#manufactured").val('');
        } else if (whatToSell === "2") {
          $("#manufacturedP").removeClass('d-none')
          $("#collectionP").addClass('d-none')
          $("#product").val('');
        } else {
          $("#manufacturedP").addClass('d-none')
          $("#collectionP").addClass('d-none')
          $("#manufactured").val('');
          $("#product").val('');
        }
      })

    </script>
@endpush
