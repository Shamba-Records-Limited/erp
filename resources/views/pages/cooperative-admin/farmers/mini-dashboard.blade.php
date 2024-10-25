@extends('layouts.app')

@push('plugin-styles')
@endpush

@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush

@section('content')
@php
$total_gender_distribution = $data["gender"]->female + $data["gender"]->male + $data["gender"]->other
@endphp
<div class="ml-3">
    <h2>Mini Dashboard</h2>
</div>

<!-- New Row for Ages & Gender and Farmers Gender Distribution Charts -->
<div class="row custom-border">
    <div class="col-sm-12 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h6 class="text-uppercase text-muted ls-1 mb-1">Ages & Gender</h6>
                    <h2 class="mb-0">Farmer Ages</h2>
                </div>
                <div>
                    <canvas id="AgesGenderBarChart" class="mb-4 mb-md-0" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-12 col-md-6">
        <div class="card" style="overflow-y: scroll; ">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center">
                        <canvas id="FarmersGenderDoughnutChart"></canvas>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Overview</h6>
                        <h2 class="mb-0">Farmers Gender Distribution</h2>
                        <div class="wrapper mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 font-weight-medium">{{$data["gender"]->female}}</p>
                                    <small class="text-muted ml-2">Female</small>
                                </div>
                                <p class="mb-0 font-weight-medium">
                                    {{ $total_gender_distribution > 0 ? number_format((($data["gender"]->female / $total_gender_distribution) * 100), 2,'.',',') : 0 }}
                                    %</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar " role="progressbar"
                                    style="background-color:#F4D8F0;width: {{ $total_gender_distribution ? (($data["gender"]->female / $total_gender_distribution) * 100) : 0}}%"
                                    aria-valuenow="{{$total_gender_distribution ? (($data["gender"]->female / $total_gender_distribution) * 100) : 0}}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <!-- Repeat for Male and Other -->
                        <div class="wrapper mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 font-weight-medium">{{$data["gender"]->male}}</p>
                                    <small class="text-muted ml-2">Male</small>
                                </div>
                                <p class="mb-0 font-weight-medium">
                                    {{ $total_gender_distribution ? number_format((($data["gender"]->male / $total_gender_distribution) * 100), 2,'.',',') : 0 }}
                                    %</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar"
                                    style="background-color:rgba(54, 162, 235, 1); width: {{ $total_gender_distribution ? (($data["gender"]->male / $total_gender_distribution) * 100) : 0}}%"
                                    aria-valuenow="{{$total_gender_distribution ? (($data["gender"]->male / $total_gender_distribution) * 100) : 0}}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div class="wrapper mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 font-weight-medium">{{$data["gender"]->other}}</p>
                                    <small class="text-muted ml-2">Other</small>
                                </div>
                                <p class="mb-0 font-weight-medium">
                                    {{ $total_gender_distribution ? number_format((($data["gender"]->other / $total_gender_distribution) * 100), 2,'.',',') : 0 }}
                                    %</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar " role="progressbar"
                                    style="background-color:#172B4D;width: {{ $total_gender_distribution ? (($data["gender"]->other / $total_gender_distribution) * 100) : 0}}%"
                                    aria-valuenow="{{ $total_gender_distribution ? (($data["gender"]->other / $total_gender_distribution) * 100) : 0}}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Collections By Gender Section -->
<div class="row custom-border">
    <div class="col">
        <div class="card shadow">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1"> Collections Weight (KGs) By Gender</h6>
                        <h2 class="mb-0">Collection By Gender</h2>
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
</div>
@endsection

@push('plugin-scripts')
<!-- <script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script> -->
@endpush

@push('custom-scripts')
<script>
//gender distribution chart
let rawGenderData = @json($data['gender']);
let genderData = Object.values(rawGenderData)
let farmersGenderdoughnutChartCanvas = document.getElementById("FarmersGenderDoughnutChart")
let genderPieData = {
    datasets: [{
        data: genderData,
        backgroundColor: [
            'rgba(54, 162, 235, 1)', //male
            '#F4D8F0 ', //female
            "172B4D", //others
        ],
        borderColor: [
            'rgba(54, 162, 235, 1)',
            '#F4D8F0 ',
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
        borderColor: '#F4D8F0',
        backgroundColor: '#F4D8F0',
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
                    if (value % 10 === 0) {
                        return value + ' KGs'; // y-axis tick label
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

// Age and Gender Chart
let maleAgesData = @json($data['male_ages']);
let maleAgesValues = maleAgesData.map(c => c.y);

let agesGenderLabels = maleAgesData.map(c => c.x);
let agesGenderBarChartCanvas = document.getElementById("AgesGenderBarChart");

let femaleAgesData = @json($data['female_ages']);
let femaleAgesValues = femaleAgesData.map(c => c.y);

let agesGenderBarData = {
    labels: agesGenderLabels,
    datasets: [{
        label: 'Male',
        data: maleAgesValues,
        borderColor: 'rgba(54, 162, 235, 1)',
        backgroundColor: 'rgba(54, 162, 235, 0.5)',
        borderWidth: 2,
        barPercentage: 0.6, // Make bars narrower
    }, {
        label: 'Female',
        data: femaleAgesValues,
        borderColor: 'rgba(255, 99, 132, 1)',
        backgroundColor: 'rgba(255, 99, 132, 0.5)',
        borderWidth: 2,
        barPercentage: 0.6, // Make bars narrower
    }],
};

let agesGenderBarOptions = {
    animation: {
        easing: "easeOutBounce",
        duration: 1000,
    },
    scales: {
        y: {
            beginAtZero: false,
            ticks: {
                // Disable showing the intersection at zero
                callback: function(value) {
                    return value === 0 ? '' : value; // Hide the label for 0
                },
                // Optionally, you can add a step size
                stepSize: 5, // Adjust step size based on your data
            },
            grid: {
                color: 'rgba(0, 0, 0, 0.1)', // Subtle grid line color
                zeroLineColor: 'rgba(0, 0, 0, 0.5)', // Color of the zero line
                lineWidth: 1,
            }
        },
        x: {
            grid: {
                display: true, // Hide grid lines for x-axis
            }
        }
    },
    plugins: {
        tooltip: {
            callbacks: {
                label: function(context) {
                    let label = context.dataset.label || '';
                    let value = context.raw; // Get the raw value
                    return `${label}: ${value} KGs`;
                },
            }
        },
        legend: {
            display: true,
            position: 'top',
            labels: {
                boxWidth: 10,
                padding: 15,
            },
        },
    },
    maintainAspectRatio: false,
    layout: {
        padding: {
            left: 0,
            right: 0,
            top: 0,
            bottom: 0
        }
    },
};

// Initialize the chart
let agesGenderBarChart = new Chart(agesGenderBarChartCanvas, {
    type: "bar",
    data: agesGenderBarData,
    options: agesGenderBarOptions
});

</script>
@endpush