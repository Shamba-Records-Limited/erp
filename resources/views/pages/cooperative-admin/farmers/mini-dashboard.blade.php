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

@section('content')
@php
$total_gender_distribution = $data["gender"]->female + $data["gender"]->male + $data["gender"]->other
@endphp
<div>
    <h2>Mini Dashboard</h2>
</div>
<div class="row">
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
</div>
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Farmer Ages
                </div>
                <div>
                    <canvas id="AgesGenderBarChart" class="mb-4 mb-md-0" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6 grid-margin stretch-card">
        <div class="card" style="overflow-y: scroll; height:280px;">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5 d-flex align-items-center">
                        <canvas id="FarmersGenderDoughnutChart" class="400x160 mb-4 mb-md-0" height="200"></canvas>
                    </div>
                    <div class="col-md-7">
                        <h4 class="card-title font-weight-medium mb-0 d-none d-md-block">Farmers Gender
                            Distribution</h4>
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
                                <div class="progress-bar bg-primary" role="progressbar"
                                    style="width: {{ $total_gender_distribution ? (($data["gender"]->female / $total_gender_distribution) * 100) : 0}}%"
                                    aria-valuenow="{{$total_gender_distribution ? (($data["gender"]->female / $total_gender_distribution) * 100) : 0}}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
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
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $total_gender_distribution ? (($data["gender"]->male / $total_gender_distribution) * 100) : 0}}%"
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
                                <div class="progress-bar bg-danger" role="progressbar"
                                    style="width: {{ $total_gender_distribution ? (($data["gender"]->other / $total_gender_distribution) * 100) : 0}}%"
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
@endsection

@push('plugin-scripts')
<!-- <script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script> -->
@endpush

@push('custom-scripts')
<script>
let rawGenderData = @json($data['gender']);
let genderData = Object.values(rawGenderData)
let farmersGenderdoughnutChartCanvas = document.getElementById("FarmersGenderDoughnutChart")
let genderPieData = {
    datasets: [{
        data: genderData,
        backgroundColor: [
            successColor,
            primaryColor,
            dangerColor
        ],
        borderColor: [
            successColor,
            primaryColor,
            dangerColor
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
                color: 'rgb(251,99,64)',
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

// age gender
let maleAgesData = @json($data['male_ages']);
let maleAgesValues = maleAgesData.map(c => c.y);

let agesGenderLabels = maleAgesData.map(c => c.x)
let agesGenderBarChartCanvas = document.getElementById("AgesGenderBarChart")

let femaleAgesData = @json($data['female_ages']);
let femaleAgesValues = femaleAgesData.map(c => c.y);

let agesGenderBarData = {
    labels: agesGenderLabels,
    datasets: [{
        label: 'Male',
        data: maleAgesValues,
        borderColor: 'rgba(54, 162, 235, 1)',
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
    }, {
        label: 'Female',
        data: femaleAgesValues,
        borderColor: 'rgba(255, 99, 132, 1)',
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
    }],
    // labels: ["Male", "Female", "Other"]
};
let agesGenderBarOptions = {
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
    }
    // y axis is KGs
};
let agesGenderBarChart = new Chart(agesGenderBarChartCanvas, {
    type: "bar",
    data: agesGenderBarData,
    options: agesGenderBarOptions
});
</script>
@endpush