@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Farmers</div>
        <div class="d-flex justify-content-end">
            <select class="select2bs4">
                <option>All</option>
                <option>Coop x</option>
            </select>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush