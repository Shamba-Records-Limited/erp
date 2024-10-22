@extends('layouts.app')

@push('plugin-styles')

@endpush

@section('content')
    @if(has_right_permission(config('enums.system_modules')['Sales']['void_invoices'], config('enums.system_permissions')['view']))

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
                            <form method="get" href="{{ route('sales.pos.void-invoices') }}">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-lg-2 col-md-3 col-12">
                                        <label for="batch_no">Invoice Number</label>
                                        <input type="text" name="invoice_no"
                                               class="form-control" placeholder="22334455"
                                               value="{{ request()->invoice_no}}">
                                    </div>

                                    <div class="form-group col-lg-2 col-md-3 col-12">
                                        <label for="date">Period </label>
                                        <input type="text" name="date"
                                               class="form-control date-range"
                                               id="date" value="{{ request()->date}}">
                                    </div>

                                    <div class="form-group col-lg-2 col-md-3 col-12">
                                        <label for="farmer-filter">Farmer</label>
                                        <select class="form-control select2bs4" name="farmer[]"
                                                id="farmer-filter" multiple>
                                            <option value=""></option>
                                            @foreach($farmers as $farmer)
                                                <option value="{{$farmer->farmer->id}}" {{  request()->farmer  != null ? (in_array($farmer->farmer->id,request()->farmer) ?  'selected' : '') : '' }}>{{ucwords(strtolower($farmer->first_name.' '.$farmer->other_names))}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-2 col-md-3 col-12">
                                        <label for="customer-filter">Customer</label>
                                        <select class="form-control select2bs4" name="customer[]"
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
                                        <a href="{{ route('sales.pos.void-invoices') }}" type="submit"
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
                            <form action="{{ route('sales.export', 'csv') }}" method="post">
                                @csrf
                                <input type="hidden" name="invoice_no"
                                       value="{{ request()->invoice_no}}">
                                <input type="hidden" name="date" value="{{ request()->date}}">
                                <input type="hidden" name="title" value="Invoices">
                                <input type="hidden" name="void" value="1">
                                <input type="hidden" name="farmer" value="{{ request()->farmer ?  implode(',', request()->farmer) : ''}}">
                                <input type="hidden" name="customer"
                                       value="{{ request()->customer ?  implode(',', request()->customer) : ''}}">
                                <button type="submit"
                                        class="btn btn-sm btn-info float-right text-white">
                                    <i class="mdi mdi-download"></i> CSV
                                </button>
                            </form>

                            <form action="{{ route('sales.export', 'xlsx') }}" method="post">
                                @csrf
                                <input type="hidden" name="invoice_no"
                                       value="{{ request()->invoice_no}}">
                                <input type="hidden" name="date" value="{{ request()->date}}">
                                <input type="hidden" name="title" value="Invoices">
                                <input type="hidden" name="void" value="1">
                                <input type="hidden" name="farmer" value="{{ request()->farmer ?  implode(',', request()->farmer) : ''}}">
                                <input type="hidden" name="customer"
                                       value="{{ request()->customer ?  implode(',', request()->customer) : ''}}">
                                <button type="submit"
                                        class="btn btn-sm btn-github float-right text-white"
                                        style="margin-right: -5px!important;">
                                    <i class="mdi mdi-download"></i> Excel
                                </button>
                            </form>
                            <form action="{{ route('sales.export', env('PDF_FORMAT')) }}"
                                  method="post">
                                @csrf
                                <input type="hidden" name="invoice_no"
                                       value="{{ request()->invoice_no}}">
                                <input type="hidden" name="dates" value="{{ request()->date}}">
                                <input type="hidden" name="title" value="Invoices">
                                <input type="hidden" name="void" value="1">
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
                    <h2 class="card-title">Sales</h2>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice #</th>
                                <th>Products</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Discount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $canDownload = has_right_permission(config('enums.system_modules')['Sales']['invoice'], config('enums.system_permissions')['download']);
                                $canEdit = has_right_permission(config('enums.system_modules')['Sales']['invoice'], config('enums.system_permissions')['edit']);
                                $user = Auth::user();
                                $overall_total_amount = 0;
                                $overall_total_discount = 0;
                            @endphp
                            @forelse($sales as $key => $item)
                                @php $tot_amt = 0;$tot_disc = $item->discount+$item->saleItems->sum('discount');
                                if($item->saleItems){
                                    foreach($item->saleItems as $sale_item){
                                        $tot_amt += $sale_item->amount*$sale_item->quantity;
                                    }
                                    }

                                    $amount = $tot_amt - $tot_disc;
                                    $overall_total_discount += $tot_disc;
                                    $overall_total_amount += $amount;
                                @endphp
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>
                                            @if($canEdit)
                                                <a class="text-info" target="_blank"
                                                   href="{{ route('sales.pos.invoice.pdf', $item->id)}}">
                                                    {{ $item->invoices->invoice_number.'-'.$item->invoices->invoice_count }}
                                                </a>
                                            @else
                                                {{ $item->invoices->invoice_number.'-'.$item->invoices->invoice_count }}
                                            @endif
                                        </td>
                                        <td>{{ $item->saleItems->count() }}</td>
                                        <td>{{ $item->farmer_id ? '(Farmer) '.(ucwords(strtolower($item->farmer->user->first_name.' '.$item->farmer->user->other_names))) : $item->customer->name }}</td>
                                        <td>

                                            {{$user->cooperative->currency}} {{ number_format($amount) }}
                                        </td>
                                        <td>{{$user->cooperative->currency}} {{ number_format($tot_disc) }}</td>
                                    </tr>
                            @empty
                                <p>No data</p>
                            @endforelse
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="4">Total</th>
                                <th colspan="1">{{ $user->cooperative->currency.' '.number_format($overall_total_amount)}}</th>
                                <th colspan="1">{{ $user->cooperative->currency.' '.number_format($overall_total_discount)}}</th>
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
