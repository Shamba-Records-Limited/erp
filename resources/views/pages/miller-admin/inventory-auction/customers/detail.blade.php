@extends('layout.master')

@push('plugin-styles')
@endpush


@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Customer Details</div>
        <div class="card-subtitle">{{$customer->title}} {{$customer->name}} &nbsp; -> &nbsp; {{$customer->address}}</div>
        <div class="row">
            <div class="col d-flex">
                <div class="">Email:</div>
                <div class="font-weight-bold"> {{$customer->email}}</div>
            </div>
            <div class="col d-flex">
                <div class="">Phone:</div>
                <div class="font-weight-bold"> {{$customer->phone_number}}</div>
            </div>
            <div class="col d-flex">
                <div class="">Gender:</div>
                <div class="font-weight-bold"> {{$customer->gender}}</div>
            </div>
        </div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'quotations'?'active':'' }}" href="?tab=quotations">Quotations</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'sales'?'active':'' }}" href="?tab=sales">Sales</a>
            </li>
        </ul>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush