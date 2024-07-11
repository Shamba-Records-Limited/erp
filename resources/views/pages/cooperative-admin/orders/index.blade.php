@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Orders</div>
        <div class="table-responsive">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Batch No</th>
                        <th>Miller</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $key => $order)
                    <tr>
                        <td>{{++$key }}</td>
                        <td><a href="{{route('cooperative-admin.orders.detail', $order->id)}}">{{$order->batch_number}}</a></td>
                        <td>{{$order->miller->name}}</td>
                        @php
                        $orderStatus = $order->deliveredQuantity == 0 ? 'Pending' : ($order->undeliveredQuantity > 0 ? 'Partial' : 'Completed');
                        $statusClass = $order->deliveredQuantity == 0 ? 'danger' : ($order->undeliveredQuantity > 0 ? 'warning' : 'success');
                        @endphp
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