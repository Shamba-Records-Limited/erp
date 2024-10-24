@extends('layouts.app')

@push('plugin-styles')
@endpush
@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js">
</script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
@section('content')
<div class="mb-5">
    <h2 class="mt-5 ml-5"> Mini Dashboard</h2>
</div>
<div class=" col-xl-8 mt-15 mb-xl-0">
    <div class="card shadow">

        <div class="card-header bg-transparent">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="text-uppercase text-light ls-1 mb-1">Collection By Wet Mills (KGs)</h6>
                    <h2 class="mb-0">Wet Mills</h2>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Chart -->
            <div class="chart">
                <!-- Chart wrapper -->
                <canvas id="WetMillCollectionsBarChart" class="chart-canvas"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@push('plugin-scripts')
<!-- <script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script> -->
@endpush

@push('custom-scripts')
<script>
// Wet mill collections chart
let wetMillCollectionsData = @json($data['collections_by_wet_mills']);
let wetMillCollectionsLabels = wetMillCollectionsData.map(c => c.name);
let wetMillCollectionsValues = wetMillCollectionsData.map(c => c.quantity);

let wetMillCollectionsBarChartCanvas = document.getElementById("WetMillCollectionsBarChart");
let ctx = wetMillCollectionsBarChartCanvas.getContext("2d"); // Get the 2D context

// Create a gradient fill for the area below the line
let gradient = ctx.createLinearGradient(0, 0, 0, 400);

// Define gradient stops: Darker at the top, lighter at the bottom
gradient.addColorStop(0, 'rgba(244, 216, 240, 1)'); // Darker shade at the top
gradient.addColorStop(0.5, 'rgba(244, 216, 240, 0.5)'); // Mid-point lighter shade
gradient.addColorStop(1, 'rgba(244, 216, 240, 0.1)'); // Lightest shade at the bottom
let wetMillCollectionsBarData = {
    labels: wetMillCollectionsLabels,
    datasets: [{
        label: 'Weight in KGs',
        data: wetMillCollectionsValues,
        borderColor: '#2dce89',
        backgroundColor: gradient,
        fill: true,
        tension: 0.4,
    }],
};

let wetMillCollectionsBarOptions = {
    scales: {
        yAxes: [{
            gridLines: {
                color: 'rgba(77, 77, 77, 0.2)',
                zeroLineColor: 'rgba(77, 77, 77, 0.5)',
            },
            ticks: {
                callback: function(value) {
                    if (value % 10 === 0) {
                        return value + ' KGs';
                    }
                },
                beginAtZero: true,
            },
        }],
        xAxes: [{
            gridLines: {
                display: false,
            },
        }],
    },
    tooltips: {
        callbacks: {
            label: function(item, data) {
                var label = data.datasets[item.datasetIndex].label || '';
                var yLabel = item.yLabel;
                var content = '';

                if (data.datasets.length > 1) {
                    content += '<span class="popover-body-label mr-auto">' + label + '</span>';
                }

                content += '<span class="popover-body-value">' + yLabel + ' KGs</span>';
                return content;
            },
        },
        mode: 'index',
        intersect: false,
    },
    maintainAspectRatio: false,
    legend: {
        display: true,
        position: 'top',
        labels: {
            boxWidth: 10,
            padding: 15,
        },
    },
    layout: {
        padding: {
            left: 0,
            right: 0,
            top: 0,
            bottom: 0,
        },
    },
    animation: {
        easing: 'easeOutBounce',
        duration: 1000,
    },
};

let wetMillCollectionsBarChart = new Chart(wetMillCollectionsBarChartCanvas, {
    type: 'line',
    data: wetMillCollectionsBarData,
    options: wetMillCollectionsBarOptions,
});
</script>
@endpush