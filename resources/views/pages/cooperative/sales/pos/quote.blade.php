@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')

    @if(has_right_permission(config('enums.system_modules')['Sales']['quotation'], config('enums.system_permissions')['create']))
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-fw btn-sm float-right"
                                data-toggle="collapse"
                                data-target="#makeSale"
                                aria-expanded="@if ($errors->count() > 0) true @else false @endif"
                                aria-controls="addVetItems"><span class="mdi mdi-plus"></span>Add
                            Quotations
                        </button>
                        <div class="collapse @if ($errors->count() > 0) show @endif " id="makeSale">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h4>Create Quotation </h4>
                                </div>
                            </div>
                            <quotation-component/>

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
                                data-target="#filterCollections"
                                aria-expanded="false"
                                aria-controls="filterCollections"><span
                                    class="mdi mdi-filter-outline"></span>Filter
                        </button>
                        <!-- filter -->
                        <div class="collapse @if(request()->date or request()->batch_no or request()->farmer or request()->customer or request()->status) show @endif"
                             id="filterCollections">
                            <div class="row mt-5">
                                <div class="col-lg-12 grid-margin stretch-card col-12">
                                    <h6>Filter Collections</h6>
                                </div>
                            </div>
                            <form method="get" href="{{ route('sales.quotation') }}">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-2 col-md-3 col-12">
                                        <label for="batch_no">Batch</label>
                                        <input type="text" name="batch_no"
                                               class="form-control" placeholder="22334455"
                                               value="{{ request()->batch_no}}">
                                    </div>

                                    <div class="form-group col-lg-3 col-md-4 col-12">
                                        <label for="date">Period</label>
                                        <input type="text" name="date"
                                               class="form-control"
                                               id="date" value="{{ request()->date}}">
                                    </div>

                                    <div class="form-group col-lg-3 col-md-4 col-12">
                                        <label for="farmer-filter">Farmer</label>
                                        <select class="form-control select2bs4" name="farmer"
                                                id="farmer-filter">
                                            <option value=""></option>
                                            @foreach($farmers as $farmer)
                                                <option value="{{$farmer->farmer->id}}" {{ $farmer->farmer->id == request()->farmer ? 'selected' : '' }}>
                                                    {{ucwords(strtolower($farmer->first_name.' '.$farmer->other_names))}} - {{ $farmer->farmer->member_no}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-3 col-md-4 col-12">
                                        <label for="customer-filter">Customer</label>
                                        <select class="form-control select2bs4" name="customer"
                                                id="customer-filter">
                                            <option value=""></option>
                                            @foreach($customers as $customer)
                                                <option value="{{$customer->id}}" {{ $customer->id == request()->customer ? 'selected' : '' }}>{{ucwords(strtolower($customer->name))}}</option>
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
                                        <a href="{{ route('sales.quotation') }}" type="submit"
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
    @endif
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div>
                        @if(has_right_permission(config('enums.system_modules')['Sales']['quotation'], config('enums.system_permissions')['download']))
                            <form action="{{ route('sales.quote.export', 'csv') }}" method="post">
                                @csrf
                                <input type="hidden" name="invoice_no" value="{{ request()->invoice_no}}">
                                <input type="hidden" name="dates" value="{{ request()->date}}">
                                <input type="hidden" class="farmer" name="farmer" value="{{ request()->farmer}}">
                                <input type="hidden" class="customer" name="customer" value="{{ request()->customer}}">
                                <button type="submit" class="btn btn-sm btn-info float-right text-white">
                                    <i class="mdi mdi-download"></i> CSV
                                </button>
                            </form>

                            <form action="{{ route('sales.quote.export', 'xlsx') }}" method="post">
                                @csrf
                                <input type="hidden" name="invoice_no" value="{{ request()->invoice_no}}">
                                <input type="hidden" name="dates" value="{{ request()->date}}">
                                <input type="hidden" name="farmer" value="{{ request()->farmer}}">
                                <input type="hidden" name="customer" value="{{ request()->customer}}">
                                <button type="submit" class="btn btn-sm btn-github float-right text-white"
                                        style="margin-right: -5px!important;">
                                    <i class="mdi mdi-download"></i> Excel
                                </button>
                            </form>
                            <form action="{{ route('sales.quote.export', env('PDF_FORMAT')) }}" method="post">
                                @csrf
                                <input type="hidden" name="invoice_no" value="{{ request()->invoice_no}}">
                                <input type="hidden" name="dates" value="{{ request()->date}}">
                                <input type="hidden" name="farmer" value="{{ request()->farmer}}">
                                <input type="hidden" name="customer" value="{{ request()->customer}}">
                                <button type="submit" class="btn btn-sm btn-success float-right text-white"
                                        style="margin-right: -8px!important;">
                                    <i class="mdi mdi-download"></i> PDF
                                </button>
                            </form>

                        @endif
                    </div>
                    <h2 class="card-title">{{'Quotations' }}</h2>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Batch Number</th>
                                <th>No. Products</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Discount</th>
                                <th>Due Date</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $canDownload = has_right_permission(config('enums.system_modules')['Sales']['quotation'], config('enums.system_permissions')['download']);
                                $canEdit = has_right_permission(config('enums.system_modules')['Sales']['quotation'], config('enums.system_permissions')['edit']);
                                $user = Auth::user();
                                 $overall_total_discount = 0;
                                $overall_total_amount = 0;
                            @endphp
                            @forelse($sales as $key => $item)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>
                                        @if($canDownload)
                                            <a class="text-info" target="_blank"
                                               href="{{ route('sales.pos.quotation.pdf', $item->id)}}">
                                                {{ $item->sale_batch_number.'-'.$item->sale_count }}
                                            </a>
                                        @else
                                            {{ $item->sale_batch_number.'-'.$item->sale_count }}
                                        @endif
                                    </td>
                                    <td>{{ $item->saleItems->count() }}</td>
                                    <td>{{ $item->farmer_id ? '(Farmer) '.(ucwords(strtolower($item->farmer->user->first_name.' '.$item->farmer->user->other_names))) : $item->customer->name }}</td>
                                    <td>
                                        @php $tot_amt = 0;$tot_disc = $item->discount+$item->saleItems->sum('discount');@endphp
                                        @if($item->saleItems)
                                            @foreach($item->saleItems as $sale_item)
                                                @php
                                                    $tot_amt += $sale_item->amount*$sale_item->quantity;
                                                @endphp
                                            @endforeach
                                        @endif
                                        @php
                                            $amount = $tot_amt - $tot_disc;
                                            $overall_total_discount += $tot_disc;
                                            $overall_total_amount += $amount;
                                             $date = \Carbon\Carbon::create($item->date);
                                            $now = \Carbon\Carbon::now();
                                        @endphp
                                        {{$user->cooperative->currency}} {{ number_format($amount) }}
                                    </td>
                                    <td>{{$user->cooperative->currency}} {{ number_format($tot_disc) }}</td>
                                    <td>
                                        {{ $date->format('d M Y') }}

                                        @if($item->invoices->status != \App\Invoice::STATUS_PAID)
                                            @if($now->lt($date))
                                                <span class="badge badge-success text-white">Active</span>
                                            @else
                                                <span class="badge badge-danger text-white">Due</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($canDownload)
                                            <a class="btn btn-primary btn-sm btn-rounded"
                                               target="_blank"
                                               href="{{ route('sales.pos.quotation.pdf', $item->id) }}">
                                                Download
                                            </a>
                                        @endif
                                        @if($canEdit && $now->lt($date))
                                            <a class="btn btn-info btn-sm btn-rounded"
                                               href="{{ route('sales.quote.toinvoice', $item->id) }}">
                                                Convert to invoice
                                            </a>
                                        @endif
                                        <a class="btn btn-danger btn-sm btn-rounded"
                                           href="{{ route('sales.pos.quotation', $item->id) }}">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <p>No data</p>
                            @endforelse
                            </tbody>
                            <tr>
                                <th colspan="4">Total</th>
                                <th colspan="1">{{ $user->cooperative->currency.' '.number_format($overall_total_amount)}}</th>
                                <th colspan="3">{{ $user->cooperative->currency.' '.number_format($overall_total_discount)}}</th>
                            </tr>
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

      $("#whoIsBuying").change(() => {
        const whoIsBuying = $("#whoIsBuying").val();
        console.log(whoIsBuying);
        if (whoIsBuying === "1") {
          $("#farmerB").removeClass('d-none');
          $("#customerB").addClass('d-none');
          $('#customer').val('');
        } else if (whoIsBuying === "2") {
          $("#farmerB").addClass('d-none');
          $("#customerB").removeClass('d-none');
          $('#farmer').val('');
        } else {
          $("#farmerB").addClass('d-none');
          $("#customerB").addClass('d-none');
          $('#farmer').val('');
          $('#customer').val('');
        }
      })
      dateRangePickerFormats("date")
    </script>
@endpush
