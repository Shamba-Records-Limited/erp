@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
<div>
    <h3>Mini Dashboard</h3>
</div>
<div class="row">
    <div class="col">
        <div class="card" style="overflow-y: scroll; height:350px;">
            <div class="card-body">
                <div class="card-title">Collection By Wet Mills (KGs)</div>
                <div class="row">
                    <div class="col-12 d-flex align-items-center">
                        <canvas id="WetMillCollectionsBarChart" class="mb-4 mb-md-0" style="height: 200px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('plugin-scripts')
<script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
    // wet mill collections chart
    let wetMillCollectionsData = @json($data['collections_by_wet_mills']);
    let wetMillCollectionsLabels = wetMillCollectionsData.map(c => c.name)
    let wetMillCollectionsValues = wetMillCollectionsData.map(c => c.quantity)
    let wetMillCollectionsBarChartCanvas = document.getElementById("WetMillCollectionsBarChart")
    let wetMillCollectionsBarData = {
        labels: wetMillCollectionsLabels,
        datasets: [{
            data: wetMillCollectionsValues,
            backgroundColor: []
        }]
    };
    let wetMillCollectionsBarOptions = {
        animationEasing: "easeOutBounce",
        responsive: true,
        maintainAspectRatio: true,
        showScale: true,
        legend: {
            display: false
        },
        layout: {
            padding: {
                left: 0,
                right: 0,
                top: 0,
                bottom: 0
            }
        }
    }

    let wetMillCollectionsBarChart = new Chart(wetMillCollectionsBarChartCanvas, {
        type: "bar",
        data: wetMillCollectionsBarData,
        options: wetMillCollectionsBarOptions
    });
</script>
@endpush