@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
<div>Orders</div>
<div class="card">
    <div class="card-body">
        <div class="card-title">Orders</div>
        <div class="table-responsive">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Batch No</th>
                        <th>Cooperative</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $key => $order)
                    <tr>
                        <td>{{++$key }}</td>
                        <td><a href="{{route('miller-admin.orders.detail', $order->id)}}">{{$order->batch_number}}</a></td>
                        <td>{{$order->cooperative_name}}</td>
                        <td>Status</td>
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