@extends('layouts.app')

@push('plugin-styles')
@endpush
@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js">
</script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
@section('content')
<div>
    <h3>Mini Dashboard</h3>
</div>
<div class="d-flex justify-content-end  w-100">
    <div class="d-flex align-items-start">
        <form class="d-flex">
            <div class="form-group">
                <select name="date_range" placeholder="Select Date Range" class="form-control form-select"
                    onchange="this.form.submit()" id="dateRange">
                    <option value="week" @if($date_range=="week" ) selected @endif)>This Week</option>
                    <option value="month" @if($date_range=="month" ) selected @endif>This Month</option>
                    <option value="year" @if($date_range=="year" ) selected @endif>This Year</option>
                    <option value="custom" @if($date_range=="custom" ) selected @endif>Custom</option>
                </select>
            </div>
            <div class="form-group">
                <input type="date" class="form-control" name="from_date" value="{{$from_date}}"
                    onchange="this.form.submit()" id="fromDate" />
            </div>
            <div class="form-group mr-2">
                <input type="date" class="form-control" name="to_date" value="{{$to_date}}"
                    onchange="this.form.submit()" id="toDate" />
            </div>
        </form>
    </div>

</div>
<div class="col-xl-8 mb-5 mb-xl-0">
    <div class="card shadow">
        <div class="card-header bg-transparent">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
                    <h2 class=" mb-0">Collections Weight (KGs)</h2>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Chart -->
            <div class="chart">
                <!-- Chart wrapper -->
                <canvas id="CollectionsBarChart" class="chart-canvas "></canvas>
            </div>
        </div>
    </div>
</div>
<!-- Pie Chart for Collection Time Distribution -->
<div class="col-xl-4 mb-5">
    <div class="card shadow">
        <div class="card-header bg-transparent">
            <h6 class="text-uppercase text-light ls-1 mb-1">Distribution</h6>
            <h2 class="mb-0">Collection Time Distribution</h2>
        </div>
        <div class="card-body">
            <canvas id="CollectionTimePieChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
<!-- <script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script> -->
@endpush

@push('custom-scripts')
<script>
// Prepare data for collection time pie chart with readable labels
let collectionTimeData = @json($data['collectionTimeData']);
let collectionTimeLabels = Object.keys(collectionTimeData).map(label => `${label}: ${collectionTimeData[label]}`);
let collectionTimeCounts = Object.values(collectionTimeData);

let collectionTimePieChartCanvas = document.getElementById("CollectionTimePieChart").getContext("2d");
new Chart(collectionTimePieChartCanvas, {
    type: 'pie',
    data: {
        labels: collectionTimeLabels,
        datasets: [{
            data: collectionTimeCounts,
            backgroundColor: [
                '#4dc9f6', '#f67019', '#f53794', '#537bc4', '#acc236',
                '#166a8f', '#00a950', '#58595b', '#8549ba'
            ],
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: true,
            position: 'bottom',
        },
    }
});
// collections  chart
let collectionsData = @json($data['collections']);
let collectionsLabels = collectionsData.map(c => c.x);
let collectionsValues = collectionsData.map(c => c.y);
let collectionsBarChartCanvas = document.getElementById("CollectionsBarChart").getContext("2d");

// Create a gradient fill for the area below the line
let gradient = collectionsBarChartCanvas.createLinearGradient(0, 0, 0, 400);

// Define gradient stops: Darker at the top, lighter at the bottom
gradient.addColorStop(0, 'rgba(244, 216, 240, 1)'); // Darker shade at the top
gradient.addColorStop(0.5, 'rgba(244, 216, 240, 0.5)'); // Mid-point lighter shade
gradient.addColorStop(1, 'rgba(244, 216, 240, 0.1)'); // Lightest shade at the bottom

let collectionsBarData = {
    labels: collectionsLabels,
    datasets: [{
        label: 'All',
        data: collectionsValues,
        borderColor: '#2dce89', // Custom green line color
        backgroundColor: gradient, // Apply gradient as the background color
        borderWidth: 2, // Line thickness
        fill: true, // Enable the fill below the line
    }],
};

let collectionsBarOptions = {
    animationEasing: "easeOutBounce",
    animateScale: true,
    responsive: true,
    maintainAspectRatio: false,
    showScale: true,
    legend: {
        display: true
    },
    layout: {
        padding: {
            left: 0,
            right: 0,
            top: 0,
            bottom: 0
        }
    },
    scales: {
        y: {
            beginAtZero: true
        }
    }
};

// Initialize the chart with the line type and gradient
let collectionsBarChart = new Chart(collectionsBarChartCanvas, {
    type: "line",
    data: collectionsBarData,
    options: collectionsBarOptions
});
</script>
@endpush