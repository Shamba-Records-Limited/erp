@extends('layouts.app')

@push('plugin-styles')
<style>
    .text-shamba-success {
        color: #0F9D58;
    }
</style>
@endpush

@section('content')
<div class="card pt-6">
    <div class="card-body">
        <div class="card-title">Orders</div>
        <div class="d-flex justify-content-end">
            <a class="btn btn-primary btn-fw btn-sm" href="{{route('miller-admin.orders.export', 'xlsx')}}">
                <span class="mdi mdi-file-excel"></span>Export Excel
            </a>
            <a class="btn btn-primary btn-fw btn-sm ml-1" href="{{route('miller-admin.orders.export', 'pdf')}}">
                <span class="mdi mdi-file-pdf"></span>Export Pdf
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Batch No</th>
                        <th>Cooperative</th>
                        <th>Delivery</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $totalDelivered = 0;
                    $totalQuantity = 0;
                    @endphp
                    @foreach($orders as $key => $order)
                    @php
                        $deliveredQuantity = $order->deliveredQuantity ?? 0;
                        $totalQuantityOrder = $order->quantity ?? 0;

                        $totalDelivered += $deliveredQuantity;
                        $totalQuantity += $totalQuantityOrder;

                        $percentage = ($deliveredQuantity && $totalQuantityOrder) 
                                        ? number_format(($deliveredQuantity / $totalQuantityOrder) * 100, 1) 
                                        : 0;

                        $orderStatus = $deliveredQuantity == 0 ? 'Pending' : ($order->undeliveredQuantity > 0 ? 'Partial' : 'Completed');
                        $statusClass = $deliveredQuantity == 0 ? 'danger' : ($order->undeliveredQuantity > 0 ? 'warning' : 'success');
                    @endphp
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td><a href="{{ route('miller-admin.orders.detail', $order->id) }}">{{ $order->batch_number }}</a></td>
                        <td>{{ $order->cooperative->name }}</td>
                        <td>{{ $deliveredQuantity }} / {{ $totalQuantityOrder }} KGs (<span class="text-{{ $statusClass }}">{{ $percentage }}%</span>)</td>
                        <td class="text-{{ $statusClass }}">{{ $orderStatus }}</td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @php
                        $totalPercentage = $totalQuantity > 0 ? number_format(($totalDelivered / $totalQuantity) * 100, 1) : 0;
                    @endphp
                    <tr>
                        <td colspan="3" class="text-right font-weight-bold">Total:</td>
                        <td>{{ $totalDelivered }} / {{ $totalQuantity }} KGs (<span class="text-success">{{ $totalPercentage }}%</span>)</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush
