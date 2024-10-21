@extends('layouts.app')

@push('plugin-styles')
@endpush


@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Sales</div>
        <div class="d-flex justify-content-end">
            <a href="{{route('miller-admin.inventory-auction.add-sale')}}" class="btn btn-primary">Add Sale</a>
        </div>
    </div>
</div>
@endsection
@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush