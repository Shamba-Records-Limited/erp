@extends('layouts.app')

@push('plugin-styles')
<style>
    .dashgrid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 10px;
    }

    .span-8 {
        grid-column: span 8;
    }

    .span-4 {
        grid-column: span 4;
    }

    .span-2 {
        grid-column: span 2;
    }

    .span-6 {
        grid-column: span 6;
    }


    .row-span-2 {
        grid-row: span 2;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title">Dashboard</div>
    </div>
</div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script>
@endpush

@push('custom-scripts')
@endpush
