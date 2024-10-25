@extends('layouts.app')

@push('plugin-styles')
@endpush

@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush

@section('content')
<div class="mb-5">
    <h2 class="mt-5 ml-5"> Mini Dashboard</h2>
</div>

<div class="col-xl-12 mt-15 mb-xl-0">
    <div class="card shadow">
        <div class="card-header bg-transparent">
            <div class="row align-items-center justify-content-between">
                <div class="col">
                    <h6 class="text-uppercase text-light ls-1 mb-1">Collection By Wet Mills (KGs)</h6>
                    <h2 class="mb-0">Wet Mills</h2>
                </div>
                <div class="col-auto">
                    <button id="toggleView" class="btn btn-success">Show Average Collections Over Time</button>
                </div>
            </div>
        </div>
        <div class=" card-body">
            <!-- Chart -->
            <div class="chart">
                <!-- Chart wrapper -->
                <canvas id="WetMillCollectionsBarChart" class="chart-canvas"></canvas>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Registered Branches</h4>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Branch Code</th>
                                    <th>Performance Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($branches) && $branches->isNotEmpty())
                                @foreach($branches as $key => $branch)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $branch->name }}</td>
                                    <td>{{ $branch->code }}</td>
                                    <td>
                                        <span
                                            class="badge badge-{{ $branch->performance_status === 'High Performing' ? 'success' : ($branch->performance_status === 'Active' ? 'warning' : 'danger') }}">
                                            {{ $branch->performance_status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4" class="text-center">No branches found.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
// Wet mill collections chart
let wetMillCollectionsData = @json($data['collections_by_wet_mills']);
let wetMillCollectionsLabels = wetMillCollectionsData.map(c => c.name);
let wetMillCollectionsValues = wetMillCollectionsData.map(c => c.quantity);

let averageCollectionData = @json($data['average_collection_over_time']);
let averageCollectionLabels = averageCollectionData.map(c => c.date);
let averageCollectionValues = averageCollectionData.map(c => c.average_quantity);

// Chart variables
let wetMillCollectionsBarChartCanvas = document.getElementById("WetMillCollectionsBarChart");
let ctx = wetMillCollectionsBarChartCanvas.getContext("2d");

// Create a gradient fill for the area below the line
let gradient = ctx.createLinearGradient(0, 0, 0, 400);
gradient.addColorStop(0, 'rgba(244, 216, 240, 1)');
gradient.addColorStop(0.5, 'rgba(244, 216, 240, 0.5)');
gradient.addColorStop(1, 'rgba(244, 216, 240, 0.1)');

// Data for the primary dataset (branch collections)
let wetMillCollectionsBarData = {
    labels: wetMillCollectionsLabels,
    datasets: [{
        label: 'Weight in KGs',
        data: wetMillCollectionsValues,
        borderColor: '#2dce89',
        backgroundColor: gradient,
        fill: true,
        tension: 0.4,
    }]
};

// Data for the average collection dataset
let averageCollectionBarData = {
    labels: averageCollectionLabels,
    datasets: [{
        label: 'Average Collection Volume',
        data: averageCollectionValues,
        borderColor: '#f86c6b',
        backgroundColor: gradient,
        fill: true,
        tension: 0.4,
    }]
};

let currentData = wetMillCollectionsBarData;
let currentOptions = wetMillCollectionsBarOptions(currentData.labels);

// Function to update chart options based on selected data
function wetMillCollectionsBarOptions(labels) {
    return {
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

                    content += '<span class="popover-body-label mr-auto">' + label + '</span>';
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
}

// Initialize the chart
let wetMillCollectionsBarChart = new Chart(wetMillCollectionsBarChartCanvas, {
    type: 'line',
    data: currentData,
    options: wetMillCollectionsBarOptions(currentData.labels),
});

// Toggle functionality for showing different datasets
document.getElementById("toggleView").addEventListener("click", function() {
    if (currentData === wetMillCollectionsBarData) {
        currentData = averageCollectionBarData;
        wetMillCollectionsBarChart.data = currentData;
        wetMillCollectionsBarChart.options = wetMillCollectionsBarOptions(currentData.labels);
        this.innerText = "Show Branch Collections (KGs)";
    } else {
        currentData = wetMillCollectionsBarData;
        wetMillCollectionsBarChart.data = currentData;
        wetMillCollectionsBarChart.options = wetMillCollectionsBarOptions(currentData.labels);
        this.innerText = "Show Average Collections Over Time";
    }
    wetMillCollectionsBarChart.update();
});
</script>
@endpush