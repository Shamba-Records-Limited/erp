@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if(has_right_permission(config('enums.system_modules')['Customer Management']['crm'], config('enums.system_permissions')['download']))
                        <a class="btn btn-sm btn-info float-right text-white"
                           href="{{ route('manufacturing.supplier.supplies.download', [$supplier->id,'csv']) }}">
                            <i class="mdi mdi-download"></i> CSV
                        </a>

                        <a class="btn btn-sm btn-github float-right text-white"
                           href="{{ route('manufacturing.supplier.supplies.download',[$supplier->id,'xlsx']) }}"
                           style="margin-right: -5px!important;">
                            <i class="mdi mdi-download"></i> Excel
                        </a>
                        <a class="btn btn-sm btn-success float-right text-white"
                           href="{{ route('manufacturing.supplier.supplies.download', [$supplier->id, 'pdf']) }}"
                           style="margin-right: -8px!important;">
                            <i class="mdi mdi-download"></i> PDF
                        </a>
                    @endif
                    <h4 class="card-title">{{$supplier->name}} Supplies</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt clickable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Purchase #</th>
                                <th>Raw Material</th>
                                <th>Supply Date</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Quantity</th>
                                <th>Payment Status</th>
                                <th>Delivery Status</th>
                                <th>Store</th>
                                <th>Notes</th>
                                <th>Recorded By</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = Auth::user()->cooperative->currency;
                                $total_amount = 0;
                                $total_quantity = 0;
                                $total_balance = 0;
                            @endphp
                            @foreach($supplies as $key => $supply)
                                @php
                                    $total_amount += $supply->amount;
                                    $total_quantity += $supply->quantity;
                                    $total_balance += $supply->balance;
                                @endphp
                                <tr>
                                    <td>{{++$key }}</td>
                                    <td>{{ $supply->purchase_number }}</td>
                                    <td>{{ $supply->raw_material->name }}</td>
                                    <td>{{\Carbon\Carbon::parse($supply->supply_date)->format('D, d M Y')}} </td>
                                    <td>{{ $currency.' '.number_format($supply->amount)}} </td>
                                    <td>{{ $currency.' '.number_format($supply->balance)}} </td>
                                    <td>{{ number_format($supply->quantity)}} </td>
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
                                <th colspan="4">Total</th>
                                <th colspan="1">{{$currency.' '.number_format($total_amount)}}</th>
                                <th colspan="1">{{$currency.' '.number_format($total_balance)}}</th>
                                <th colspan="6">{{number_format($total_quantity)}}</th>

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
@endpush
