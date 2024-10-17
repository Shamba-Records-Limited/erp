@extends('layouts.app')

@push('plugin-styles')
<style>
    .text-shamba-success {
        color: #0F9D58;
    }
</style>

@endpush

@section('content')
<div>Orders</div>
<div class="card">
    <div class="card-body">
        <div class="card-title">Orders</div>
        <div class="d-flex justify-content-end">
            <a class="btn btn-primary btn-fw btn-sm" href="{{route('miller-admin.orders.export', 'xlsx')}}"><span class="mdi mdi-file-excel"></span>Export Excel
            </a>
            <a class="btn btn-primary btn-fw btn-sm ml-1" href="{{route('miller-admin.orders.export', 'pdf')}}"><span class="mdi mdi-file-pdf"></span>Export Pdf
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
                    @foreach($orders as $key => $order)
                    <tr>
                        <td>{{++$key }}</td>
                        <td><a href="{{route('miller-admin.orders.detail', $order->id)}}">{{$order->batch_number}}</a></td>
                        <td>{{$order->cooperative->name}}</td>
                        @php
                        if($order->deliveredQuantity == 0 || $order->quantity == 0) {
                            $percentage = 0;
                        } else {
                            $percentage = ($order->deliveredQuantity/$order->quantity) * 100;
                        }
                        
                        $orderStatus = $order->deliveredQuantity == 0 ? 'Pending' : ($order->undeliveredQuantity > 0 ? 'Partial' : 'Completed');
                        $statusClass = $order->deliveredQuantity == 0 ? 'danger' : ($order->undeliveredQuantity > 0 ? 'warning' : 'success');
                        @endphp
                        <td>(<span class="text-{{$statusClass}}">{{$percentage}} %</span>) {{$order->deliveredQuantity}} / {{$order->quantity}} KGs </td>
                        <td class="text-{{$statusClass}}">{{$orderStatus}}</td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush