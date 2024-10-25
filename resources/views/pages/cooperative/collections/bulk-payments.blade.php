@extends('layouts.app')

@push('plugin-styles')

@endpush

@php
    $currency = Auth::user()->cooperative->currency;
    $total_worth = 0;
    $total_pending_payments = 0;
    $canDownload = has_right_permission(config('enums.system_modules')['Collections']['bulk_payment'],config('enums.system_permissions')['edit']);
    $canEdit = has_right_permission(config('enums.system_modules')['Collections']['bulk_payment'],config('enums.system_permissions')['download']);
@endphp

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-info btn-fw btn-sm float-right"
                            data-toggle="collapse"
                            data-target="#filterAccordion"
                            aria-expanded="@if (request()->date
                                                or request()->batch_no
                                                 or request()->product) true @else false @endif"
                            aria-controls="filterAccordion"><span
                                class="mdi mdi-database-search"></span>Filter Records
                    </button>
                    <div class="collapse
                         @if(request()->date
                            or request()->batch_no
                            or request()->product) show @endif" id="filterAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Filter Records</h4>
                            </div>
                        </div>
                        <form action="{{ route('cooperative.collection.bulk-payment') }}"
                              method="get">
                            @csrf
                            <div class="form-row">

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="batch_no">Batch No.</label>
                                    <input type="text" name="batch_no"
                                           class="form-control"
                                           id="batch_no" placeholder="C20240117"
                                           value="{{ request()->batch_no}}">
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="product">Product</label>
                                    <select name="product" id="product"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}" {{ request()->product == $product->id ?  'selected' : ''}}> {{ $product->name }}</option>
                                            {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="date">Date</label>
                                    <input type="text" name="date"
                                           class="form-control"
                                           id="date"
                                           value="{{ request()->date }}">
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit"
                                            class="btn btn-primary btn-fw btn-block">Search
                                    </button>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <a href="{{route('cooperative.collection.bulk-payment') }}"
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
                        <form action="{{ route('cooperative.collection.bulk-payment.download','csv') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('cooperative.collection.bulk-payment.download','xlsx') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>
                        <form action="{{ route('cooperative.collection.bulk-payment.download','pdf') }}"
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


                    <h2 class="card-title"> Pending Payments</h2>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Farmer</th>
                                <th>Member No</th>
                                <th>Phone</th>
                                <th>Bank</th>
                                <th>Account No</th>
                                <th>Collection Worth</th>
                                <th>Pending Payments</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($pending_payments as $key => $item)
                                @php
                                    $total_worth += $item->collection_worth;
                                    $total_pending_payments += $item->pending_payments;
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>
                                        <a href="{{ route('cooperative.farmer.profile', $item->id) }}">
                                            {{ ucwords(strtolower($item->name)) }}
                                        </a>
                                    </td>
                                    <td>{{$item->member_no }}</td>
                                    <td>{{$item->phone_no }}</td>
                                    <td>{{$item->bank.', '.$item->branch }}</td>
                                    <td>{{$item->bank_account }}</td>
                                    <td>{{$currency.' '.number_format($item->collection_worth,2)}}</td>
                                    <td>{{$currency.' '.number_format($item->pending_payments,2)}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>

                            <tr>
                                <th colspan="6">Total</th>
                                <th colspan="1">{{ $currency.' '.number_format($total_worth,2) }}</th>
                                <th colspan="1">
                                    {{ $currency.' '.number_format($total_pending_payments,2) }}
                                    @if($total_pending_payments > 0)
                                        <button type="button"
                                                class="btn btn-danger btn-sm btn-rounded"
                                                data-toggle="modal"
                                                data-target="#modalPay">
                                            <span class="mdi mdi-cash-multiple pr-1"></span>
                                            Pay Farmers
                                        </button>
                                    @endif

                                    {{--  modals edit start--}}
                                    <div class="modal fade" id="modalPay" tabindex="-1"
                                         role="dialog"
                                         aria-labelledby="modalPay" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered"
                                             role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalPay">
                                                        Bulk Payments</h5>
                                                    <button type="button" class="close"
                                                            data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('cooperative.collection.bulk-payment.pay') }}"
                                                      method="post" id="submitBulkPaymentToFarmers">
                                                    <div class="modal-body">
                                                        @csrf
                                                        <input type="hidden"
                                                               value=" {{ $currency.' '.number_format($total_pending_payments,2) }}"
                                                               name="total_value_to_pay"
                                                               id="totalValueToPay"
                                                        >
                                                        <input type="hidden"
                                                               value=" {{ $currency.' '.number_format($total_worth,2) }}"
                                                               name="collection_worth"
                                                               id="totalCollectionWorth"
                                                        >
                                                        <input type="hidden" name="request_data"
                                                               id="requestDataId"
                                                               value="{{json_encode(request()->all())}}">

                                                        @if(request()->product && $filtered_product)
                                                            <div class="form-group col-12">
                                                                <label for="product_name">Product</label>
                                                                <input type="text"
                                                                       name="product_name"
                                                                       class="form-control"
                                                                       id="product_name"
                                                                       value="{{ $filtered_product->name }}">
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label for="product_unit_price">Unit
                                                                    Price</label>
                                                                <input type="text"
                                                                       name="product_unit_price"
                                                                       class="form-control"
                                                                       id="product_unit_price"
                                                                       value="{{$filtered_product->buying_price }}">
                                                            </div>
                                                            <input type="hidden" name="product_id"
                                                                   value="{{$filtered_product->id}}">
                                                        @endif

                                                        <div class="form-group col-12">
                                                            <label for="employee">Mode of
                                                                Payment</label>
                                                            <select name="mode" id="pmode"
                                                                    class="form-control form-select">
                                                                @foreach(config('enums.bulk_payment_modes') as $k => $v)
                                                                    <option value="{{$k}}">
                                                                        {{$v}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button"
                                                                class="btn btn-secondary"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                        @if($total_pending_payments > 0)
                                                            <button type="button"
                                                                    id="totalValueToPayBtn"
                                                                    class="btn btn-success">
                                                                Pay
                                                            </button>
                                                        @endif
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{--  modal end   --}}
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <h4 class="mt-5">Completed Payments</h4>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-info btn-fw btn-sm float-right"
                            data-toggle="collapse"
                            data-target="#filterBulkPaymentAccordion"
                            aria-expanded="@if (request()->dates
                                                or request()->batch
                                                or request()->mode
                                                 or request()->employees) true @else false @endif"
                            aria-controls="filterBulkPaymentAccordion"><span
                                class="mdi mdi-database-search"></span>Filter Payments
                    </button>
                    <div class="collapse
                         @if (request()->dates
                                or request()->batch
                                or request()->mode
                                 or request()->employees) show @endif"
                         id="filterBulkPaymentAccordion">
                        <div class="row mt-5">
                            <div class="col-lg-12 grid-margin stretch-card col-12">
                                <h4>Filter Payments</h4>
                            </div>
                        </div>
                        <form action="{{ route('cooperative.collection.bulk-payment') }}"
                              method="get">
                            @csrf
                            <div class="form-row">

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="batch">Payment Batch</label>
                                    <input type="text" name="batch"
                                           class="form-control"
                                           id="batch" placeholder="C20240117"
                                           value="{{ request()->batch}}">
                                </div>

                                {{--                                <div class="form-group col-lg-3 col-md-6 col-12">--}}
                                {{--                                    <label for="employee">Employee</label>--}}
                                {{--                                    <select name="employee" id="employee"--}}
                                {{--                                            class=" form-control select2bs4">--}}
                                {{--                                        <option value=""></option>--}}
                                {{--                                        @foreach($products as $product)--}}
                                {{--                                            <option value="{{$product->id}}" {{ request()->products ? (in_array($product->id,  request()->products) ?  'selected' : '') : ''}}> {{ $product->name }}</option>--}}
                                {{--                                            {{ $product->name }}--}}
                                {{--                                            </option>--}}
                                {{--                                        @endforeach--}}
                                {{--                                    </select>--}}
                                {{--                                </div>--}}

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="mode">Payment Mode</label>
                                    <select name="mode" id="mode"
                                            class=" form-control select2bs4">
                                        <option value=""></option>
                                        @foreach(config('enums.bulk_payment_modes') as $k => $v)
                                            <option value="{{$k}}" {{ (request()->mode == $k ?  'selected' : '') }}>
                                                {{ config('enums.bulk_payment_modes')[$k] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label for="dates">Date</label>
                                    <input type="text" name="dates"
                                           class="form-control"
                                           id="dates"
                                           value="{{ request()->dates }}">
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <button type="submit"
                                            class="btn btn-primary btn-fw btn-block">Search
                                    </button>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-3">
                                    <a href="{{route('cooperative.collection.bulk-payment') }}"
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
                        <form action="{{ route('cooperative.collection.processed-payment.download','csv') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-info float-right text-white">
                                <i class="mdi mdi-download"></i> CSV
                            </button>
                        </form>

                        <form action="{{ route('cooperative.collection.processed-payment.download','xlsx') }}"
                              method="get">
                            @csrf
                            <input type="hidden" name="request_data"
                                   value="{{ json_encode(request()->all())}}"/>
                            <button type="submit"
                                    class="btn btn-sm btn-github float-right text-white">
                                <i class="mdi mdi-download"></i> Excel
                            </button>
                        </form>
                        <form action="{{ route('cooperative.collection.processed-payment.download','pdf') }}"
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


                    <h2 class="card-title"> Completed Payments</h2>

                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Batch</th>
                                <th>Initiated By</th>
                                <th>Payment Mode</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Date Completed</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $total_payment = 0;
                            @endphp
                            @foreach($bulk_payments as $key => $item)
                                @php
                                    $total_payment += $item->total_amount;
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>
                                        <a href="{{route('cooperative.collection.bulk-payment-farmers', $item->id)}}">
                                            {{$item->batch }}
                                        </a>
                                    </td>
                                    <td>{{ ucwords(strtolower($item->names)) }}</td>
                                    <td>{{ config('enums.bulk_payment_modes')[$item->mode] }}</td>
                                    <td>{{$currency.' '.number_format($item->total_amount,2)}}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d F, Y') }}</td>
                                    <td>{{ $item->date_updated ?
                                            \Carbon\Carbon::parse($item->date_updated)->format('d F, Y') : '' }}</td>
                                    <td>
                                        @if($item->status == \App\BulkPayment::PAYMENT_MODE_STATUS_COMPLETED)
                                            <badge class="badge badge-success text-white">
                                                {{ config('enums.bulk_payment_status')[$item->status] }}
                                            </badge>
                                        @else
                                            <badge class="badge badge-danger text-white">
                                                {{ config('enums.bulk_payment_status')[$item->status] }}
                                            </badge>
                                        @endif
                                    </td>
                                    <td>
                                        @if($canEdit && $item->status ==
                                                \App\BulkPayment::PAYMENT_MODE_STATUS_PENDING)
                                            <form method="post"
                                                  action="{{ route('cooperative.collection.complete-payment', $item->id) }}">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-rounded btn-info">
                                                    Complete Payments
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="4"> Total</th>
                                <th colspan="5">{{ number_format($total_payment, 2) }}</th>
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
      dateRangePickerFormats("date");
      dateRangePickerFormats("dates");

      const updateProductDetails = () => {

      }

      $(document).ready(function () {
        $('#totalValueToPayBtn').on('click', function () {
          const pendingPayments = $('#totalValueToPay').val();
          const collectionWorth = $('#totalCollectionWorth').val();
          const amount = pendingPayments < collectionWorth ? pendingPayments : collectionWorth;
          const formData = JSON.parse($('#requestDataId').val())

          const batchNo = formData.batch_no;
          const datePeriod = formData.date;

          let products = 0
          if (formData.hasOwnProperty('products')) {
            products = formData.products.length
          }

          var message = "Are you sure you want to pay a total of " + amount;

          if (batchNo) {
            message += " for batch number " + batchNo
          }

          if (products > 0) {
            if (batchNo) {
              message += " and " + products + " products"
            } else {
              message += " for " + products + " products"
            }
          }

          if (datePeriod) {
            message += " between " + datePeriod
          }

          centerConfirmDialog(message += '?', 'submitBulkPaymentToFarmers', true)
          $('#modalPay').modal('hide');
        });
      });
    </script>
@endpush
