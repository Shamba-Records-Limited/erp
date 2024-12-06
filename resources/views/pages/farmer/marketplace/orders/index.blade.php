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
        <div class="card-title">Orders Made</div>
       <!-- <div class="d-flex justify-content-end">
            <a class="btn btn-primary btn-fw btn-sm" href="{{route('miller-admin.orders.export', 'xlsx')}}">
                <span class="mdi mdi-file-excel"></span>Export Excel
            </a>
            <a class="btn btn-primary btn-fw btn-sm ml-1" href="{{route('miller-admin.orders.export', 'pdf')}}">
                <span class="mdi mdi-file-pdf"></span>Export Pdf
            </a>
        </div>-->
        <div class="table-responsive">
            <table class="table table-hover dt clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sales No</th>
                        <th>Total Value</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($orders as $key => $order)
                     <tr>
                        <td>{{ $key + 1 }}</td>
                        <td><a href="{{ route('farmer.orders.detail', $order->id) }}">{{ $order->batch_number }}</a></td>
                        <td>{{ $order->paid_amount}}</td>
                        <td>{{ $order->created_at }}</td>
                        <td class="text-success">Complete</td>
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
