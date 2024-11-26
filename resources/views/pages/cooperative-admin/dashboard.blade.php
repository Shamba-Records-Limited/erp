@extends('layouts.app')
@push('plugin-styles')

@endpush
@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js">
</script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
@push('chartjs')
@endpush

@push('plugin-styles')
@endpush

@section('content')

@php
$total_gender_distribution = $data["gender"]->female + $data["gender"]->male + $data["gender"]->other;
@endphp

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
                                    <h5 class=" text-muted mb-0" style="font-size:1rem">Collection Total Weight</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {{ isset($data['total_collection_weight']) ? number_format($data['total_collection_weight']) : "0" }}
                                        KG
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                            @if($data['percent_daily'] > 0)
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> {{$data['percent_daily'] }}%</span>
                            @else
                                <span class="text-warning mr-2"><i class="fas fa-arrow-down"></i> {{$data['percent_daily'] }}%</span>
                            @endif
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
                                    <h5 class=" text-muted mb-0" style="font-size:1rem">Farmer Count</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {{ isset($data['farmer_count']) ? $data['farmer_count'] : 0 }}
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                            @if($data['percent_farmer'] > 0)
                                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{$data['percent_farmer']}}%</span>
                            @else
                            <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i>{{$data['percent_farmer']}}%</span>
                            @endif
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
                                    <h5 class="text-muted mb-0" style="font-size:1rem"> Collection Count</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {{ isset($data['collection_count']) ? $data['collection_count'] : 0 }}
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-chart-pie"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                               @if($data['percent_weekly'] > 0)
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> {{$data['percent_weekly'] }}%</span>
                                @else
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> {{$data['percent_weekly']}}%</span>
                                @endif
                                <span class="text-nowrap">Since last week</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="container-fluid mt--7">

    <div class="d-flex justify-content-between w-100">

        <div>
        </div>
        <div class=" d-flex align-items-start">
            <form class="d-flex">
                <div class="form-group">
                    <select name="date_range" placeholder="Select Date Range" class="form-control form-select"
                        onchange="this.form.submit()" id="dateRange">
                        <option value="week" @if($date_range=="week" ) selected @endif>This Week</option>
                        <option value="month" @if($date_range=="month" ) selected @endif>This Month</option>
                        <option value="year" @if($date_range=="year" ) selected @endif>This Year</option>
                        <option value="custom" @if($date_range=="custom" ) selected @endif>Custom</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="date" class="form-control" name="from_date" value="{{$from_date}}"
                        onchange="this.form.submit()" id="fromDate" />
                </div>
                <div class="form-group">
                    <input type="date" class="form-control" name="to_date" value="{{$to_date}}"
                        onchange="this.form.submit()" id="toDate" />
                </div>
            </form>
            <button class="btn btn-warning mt-1 ml-2" href="{{route('cooperative-admin.dashboard.export')}}"
                onclick="exportChart()">Export</button>
        </div>

    </div>


    <div class="row">

        <div class="col-xl-8 mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
                            <h2 class=" mb-0">Collections Quantity (KGs)</h2>
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
        
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-body">
                    <div class="card-title"></div>
                    <h2 class="mb-4">Gender Distribution</h2>

                    <div class="row gx-2 align-items-start mb-5">
                        <div class="col-md-6">
                            <div class="p-2">
                                <div class="d-flex justify-content-between">
                                    <div>Male:</div>
                                    <div>
                                        <h3 id="maleCount">{{$data["gender"]->male}}</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Female count -->
                            <div class="p-2 ">
                                <div class=" d-flex justify-content-between ">
                                    <div class="">Female:</div>
                                    <div>
                                        <h3 class="" id=" femaleCount">{{$data["gender"]->female}}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2">
                                <div class="d-flex justify-content-between">
                                    <div>Other:</div>
                                    <div>
                                        <h3 id="otherCount">{{$data["gender"]->other}}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Doughnut chart centered below -->
                    <div class="row mt-4 mb">
                        <div class="col-12 d-flex justify-content-center">
                            <canvas id="FarmersGenderDoughnutChart"
                                style="width: 100%; max-width: 300px; height: auto; margin-bottom:64px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1">Grade Distribution KGs</h6>
                            <h2 class="mb-0">Grade Distribution KGs</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart">
                        <canvas id="GradeDistributionBarChart" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>






    <div class="row mt-3">
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1"> Collections Weight (KGs) By Gender
                            </h6>
                            <h2 class=" mb-0">Collection By Gender</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart">
                        <canvas id="CollectionsGenderBarChart" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 mb-5 mb-xl-0">
            <div class="card shadow">

                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-light ls-1 mb-1">Collection By Wet Mills (KGs)</h6>
                            <h2 class=" mb-0">Wet Mills By KGs</h2>
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
    </div>
</div>
@endsection
@push('plugin-scripts')
@endpush

@push('custom-scripts')

<script>
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
        yAxes: [{
            gridLines: {
                // color: 'rgb(251,99,64)',
                zeroLineColor: 'rgba(77, 77, 77, 0.5)',
            },
            ticks: {
                callback: function(value) {
                    return value.toLocaleString()
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
};

// Initialize the chart with the line type and gradient
let collectionsBarChart = new Chart(collectionsBarChartCanvas, {
    type: "line",
    data: collectionsBarData,
    options: collectionsBarOptions
});


// Collections gender chart data
let maleCollectionsData = @json($data['male_collections']);
let maleCollectionValues = maleCollectionsData.map(c => c.y);

let collectionsGenderLabels = maleCollectionsData.map(c => c.x);
let collectionsGenderBarChartCanvas = document.getElementById("CollectionsGenderBarChart");

let femaleCollectionsData = @json($data['female_collections']);
let femaleCollectionValues = femaleCollectionsData.map(c => c.y);

let collectionsGenderBarData = {
    labels: collectionsGenderLabels,
    datasets: [{
        label: 'Male',
        data: maleCollectionValues,
        borderColor: 'rgba(54, 162, 235, 1)', //male
        backgroundColor: 'rgba(54, 162, 235, 1)', //male
        tension: 0.4,
        fill: true,
    }, {
        label: 'Female',
        data: femaleCollectionValues,
        borderColor: '#f53794',
        backgroundColor: '#f53794',
        tension: 0.4,
        fill: true,
    }],
};

let collectionsGenderBarOptions = {
    scales: {
        yAxes: [{
            gridLines: {
                // color: 'rgb(251,99,64)',
                zeroLineColor: 'rgba(77, 77, 77, 0.5)',
            },
            ticks: {
                callback: function(value) {
                    return value.toLocaleString()
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
        intersect: true,
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

let collectionsGenderBarChart = new Chart(collectionsGenderBarChartCanvas, {
    type: 'bar',
    data: collectionsGenderBarData,
    options: collectionsGenderBarOptions,
});


// Wet mill collections chart
let wetMillCollectionsData = @json($data['collections_by_wet_mills']);
let wetMillCollectionsLabels = wetMillCollectionsData.map(c => c.name);
let wetMillCollectionsValues = wetMillCollectionsData.map(c => c.quantity);

let wetMillCollectionsBarChartCanvas = document.getElementById("WetMillCollectionsBarChart");


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
    animationEasing: "easeOutBounce",
    animateScale: true,
    responsive: true,
    maintainAspectRatio: false,
    showScale: true,
    scales: {
        yAxes: [{
            gridLines: {
                color: 'rgba(77, 77, 77, 0.2)',
                zeroLineColor: 'rgba(77, 77, 77, 0.5)',
            },
            ticks: {
                // Format y-axis labels with commas and add "KGs"
                callback: function(value) {
                    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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



// Grade distribution chart
let gradeDistributionData = @json($data['grade_distribution']);
let gradeDistributionLabels = gradeDistributionData.map(c => c.name);
let gradeDistributionValues = gradeDistributionData.map(c => c.quantity);
let gradeDistributionBarChartCanvas = document.getElementById("GradeDistributionBarChart");

// Chart data
let gradeDistributionBarData = {
    datasets: [{
        data: gradeDistributionValues,
        // backgroundColor: '#FB6340',
        backgroundColor: '#2dce89', // Single color for simplicity, you can use multiple colors if desired
    }],
    labels: gradeDistributionLabels, // These will now be used for the y-axis in a horizontal bar chart
};

// Chart options
let gradeDistributionBarOptions = {
    scales: {
        // Y-axis (horizontal) - contains the grade names
        yAxes: [{
            ticks: {
                beginAtZero: true, // Start from zero
                fontSize: 10, // Adjust the font size if needed
                padding: 10, // Space between the chart and the y-axis labels
            },
            gridLines: {
                display: false, // Hide grid lines for the y-axis
            },
        }],
        // X-axis (vertical) - contains the percentage values
        xAxes: [{
            ticks: {
                beginAtZero: true, // Start from zero for the x-axis as well
                callback: function(value) {
                    return value + '';
                },
            },
            gridLines: {
                color: 'rgba(77, 77, 77, 0.2)', // Lighter grid lines for the x-axis
                zeroLineColor: 'rgba(77, 77, 77, 0.5)', // Zero line color
            },
        }],
    },
    tooltips: {
        callbacks: {
            label: function(item, data) {
                var label = data.datasets[item.datasetIndex].label || '';
                var yLabel = item
                    .xLabel; // Using xLabel since we are using a horizontal bar chart
                var content = '';

                if (data.datasets.length > 1) {
                    content += '<span class="popover-body-label mr-auto">' + label +
                        '</span>';
                }

                content += '<span class="popover-body-value">' + yLabel +
                    'KG</span>'; // Format tooltip to show percentages
                return content;
            },
        },
        mode: 'index',
        intersect: true,
    },
    maintainAspectRatio: false,
    legend: {
        display: false, // Optional: Disable the legend if not needed
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

// Create the chart
let gradeDistributionChart = new Chart(gradeDistributionBarChartCanvas, {
    type: "horizontalBar", // Horizontal bar chart type
    data: gradeDistributionBarData,
    options: gradeDistributionBarOptions
});


//gender distribution chart
let rawGenderData = @json($data['gender']);
let genderData = Object.values(rawGenderData)
let farmersGenderdoughnutChartCanvas = document.getElementById("FarmersGenderDoughnutChart")
let genderPieData = {
    datasets: [{
        data: genderData,
        backgroundColor: [
            'rgba(54, 162, 235, 1)', //male
            '#f53794', //female
            "172B4D", //others
        ],
        borderColor: [
            'rgba(54, 162, 235, 1)',
            '#f53794 ',
            "172B4D",
        ]

    }],
    labels: ["Male", "Female", "Other"]
};
let genderPieOptions = {
    cutoutPercentage: 70,
    animationEasing: "easeOutBounce",
    animateRotate: true,
    animateScale: false,
    responsive: true,
    maintainAspectRatio: true,
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
    }
};
let genderChart = new Chart(farmersGenderdoughnutChartCanvas, {
    type: "doughnut",
    data: genderPieData,
    options: genderPieOptions
});


function exportChart() {
    let dateRange = document.getElementById("dateRange").value;
    let fromDate = document.getElementById("fromDate").value;
    let toDate = document.getElementById("toDate").value;

    let farmerCount = document.getElementById("farmerCount").innerHTML;
    let collectionCount = document.getElementById("collectionCount").innerHTML;
    let collectionTotalWeight = document.getElementById("collectionTotalWeight").innerHTML;


    var genderChartImg = genderChart.toBase64Image();
    var collectionsBarChartImg = collectionsBarChart.toBase64Image();
    var gradeDistributionChartImg = gradeDistributionChart.toBase64Image();

    let data = {
        dateRange,
        fromDate,
        toDate,
        farmerCount,
        collectionCount,
        collectionTotalWeight,
        genderChartImg,
        collectionsBarChartImg,
        gradeDistributionChartImg
    }

    fetch('/cooperative-admin/dashboard/export', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.blob())
        .then(blob => {
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "chart.pdf";
            link.click();
        });
}
</script>
@endpush