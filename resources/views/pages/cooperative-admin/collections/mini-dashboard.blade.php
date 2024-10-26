@extends('layouts.app')

@push('plugin-styles')
@endpush
@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js">
</script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
@section('content')
<!-- <div>
    <h3>Mini Dashboard</h3>
</div> -->
<!-- @include('layouts.headers.cards') -->
@include('layout.export-dialog')
<div class="header bg-custom-green pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class=" text-muted mb-0" style="font-size:1rem">Total Collections</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {{ $data['totalCollections'] }}
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-warning mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
                                <span class="text-nowrap">Since yesterday</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class=" text-muted mb-0" style="font-size:1rem">Total Quantity Collected (kg)
                                    </h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {{ $data['totalQuantityCollected'] }} kg
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem"> Average Collection per Lot (kg)
                                    </h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {{ number_format($data['averageCollectionPerLot'], 2) }} kg
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-chart-pie"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 3.48%</span>
                                <span class="text-nowrap">Since last week</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
<div class="col-xl-12 mb-5">
    <div class="card shadow">
        <div class="card-header bg-transparent">
            <h6 class="text-uppercase text-light ls-1 mb-1">Grading Status</h6>
            <h2 class="mb-0">Grading Status by Lot</h2>
        </div>
        <div class="card-body">
            <div class="chart">
                <canvas id="GradingStatusStackedBarChart"></canvas>
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
// Prepare data for the stacked bar chart
let gradingStatusData = @json($data['gradingStatusData']);
let lotLabels = gradingStatusData.map(item => item.lot_number);
let gradedData = gradingStatusData.map(item => item.graded);
let ungradedData = gradingStatusData.map(item => item.ungraded);
let remainingData = gradingStatusData.map(item => item.remaining);

let gradingStatusStackedBarChartCanvas = document.getElementById("GradingStatusStackedBarChart").getContext("2d");

// Create the stacked bar chart
new Chart(gradingStatusStackedBarChartCanvas, {
    type: 'bar',
    data: {
        labels: lotLabels,
        datasets: [{
                label: 'Graded',
                data: gradedData,
                backgroundColor: '#2dce89',
            },
            {
                label: 'Ungraded',
                data: ungradedData,
                backgroundColor: '#f67019',
            },
            {
                label: 'Remaining',
                data: remainingData,
                backgroundColor: '#f53794',
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                stacked: true,
            },
            y: {
                stacked: true,
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});
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