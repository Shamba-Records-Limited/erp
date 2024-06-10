@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
@php
$total_gender_distribution = $data["gender"]->female + $data["gender"]->male + $data["gender"]->other
@endphp
<div>
    <h2>Mini Dashboard</h2>
</div>
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Collections Weight (KGs) By Gender
                </div>
                <div>
                    <canvas id="CollectionsGenderBarChart" class="mb-4 mb-md-0" height="250"></canvas>
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
                                <p class="mb-0 font-weight-medium">{{ $total_gender_distribution > 0 ? number_format((($data["gender"]->female / $total_gender_distribution) * 100), 2,'.',',') : 0 }}
                                    %</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $total_gender_distribution ? (($data["gender"]->female / $total_gender_distribution) * 100) : 0}}%" aria-valuenow="{{$total_gender_distribution ? (($data["gender"]->female / $total_gender_distribution) * 100) : 0}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="wrapper mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 font-weight-medium">{{$data["gender"]->male}}</p>
                                    <small class="text-muted ml-2">Male</small>
                                </div>
                                <p class="mb-0 font-weight-medium">{{ $total_gender_distribution ? number_format((($data["gender"]->male / $total_gender_distribution) * 100), 2,'.',',') : 0 }}
                                    %</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $total_gender_distribution ? (($data["gender"]->male / $total_gender_distribution) * 100) : 0}}%" aria-valuenow="{{$total_gender_distribution ? (($data["gender"]->male / $total_gender_distribution) * 100) : 0}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div class="wrapper mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 font-weight-medium">{{$data["gender"]->other}}</p>
                                    <small class="text-muted ml-2">Other</small>
                                </div>
                                <p class="mb-0 font-weight-medium">{{ $total_gender_distribution ? number_format((($data["gender"]->other / $total_gender_distribution) * 100), 2,'.',',') : 0 }}
                                    %</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $total_gender_distribution ? (($data["gender"]->other / $total_gender_distribution) * 100) : 0}}%" aria-valuenow="{{ $total_gender_distribution ? (($data["gender"]->other / $total_gender_distribution) * 100) : 0}}" aria-valuemin="0" aria-valuemax="100"></div>
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
<script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script>
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

    // collections gender
    let maleCollectionsData = @json($data['male_collections']);
    let maleCollectionValues = maleCollectionsData.map(c => c.y);

    let collectionsGenderLabels = maleCollectionsData.map(c => c.x)
    let collectionsGenderBarChartCanvas = document.getElementById("CollectionsGenderBarChart")

    let femaleCollectionsData = @json($data['female_collections']);
    let femaleCollectionValues = femaleCollectionsData.map(c => c.y);

    let collectionsGenderBarData = {
        labels: collectionsGenderLabels,
        datasets: [{
            label: 'Male',
            data: maleCollectionValues,
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
        }, {
            label: 'Female',
            data: femaleCollectionValues,
            borderColor: 'rgba(255, 99, 132, 1)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
        }],
        // labels: ["Male", "Female", "Other"]
    };
    let collectionsGenderBarOptions = {
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
    let collectionsGenderBarChart = new Chart(collectionsGenderBarChartCanvas, {
        type: "line",
        data: collectionsGenderBarData,
        options: collectionsGenderBarOptions
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