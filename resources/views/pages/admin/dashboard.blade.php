@extends('layouts.app')
@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js">
</script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush

@section('content')
@include('layouts.headers.cards')
<div class="header bg-custom-green pb-8 pt-5 pt-md-5">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0">Collection Total Weight</h5>
                                    <span class="h2 font-weight-bold mb-0" id="collectionTotalWeight">{{$data["total_collection_weight"] ?? "0"}} KG</h3>
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
                                    <h5 class="text-uppercase text-muted mb-0">Farmer Count
                                        <br>
                                        <span class="h2 font-weight-bold mb-0" id="collectionTotalWeight">{{$data["farmer_count"]}}</h3>
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
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0">Collection Count</h5>
                                    <span class="h2 font-weight-bold mb-0" id="collectionTotalWeight">{{$data["collection_count"]}}</h3>
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
                                    <h5 class="text-uppercase text-muted mb-0">Cooperatives Count</h5>
                                    <span class="h2 font-weight-bold mb-0" id="collectionTotalWeight">{{$data["cooperatives_count"]}}</h3>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-percent"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 pt-5">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0">Millers Count</h5>
                                    <span class="h2 font-weight-bold mb-0" id="collectionTotalWeight">{{$data["collection_count"]}}</h3>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-percent"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>



<div class="dashgrid">
    <div class="card span-8 row-span-2">
        <div class="card-body">
            <div class="card-title">
                Collections Weight (KGs)
            </div>
            <div>
                <canvas id="CollectionsBarChart" class="mb-4 mb-md-0" height="250"></canvas>
            </div>
        </div>
    </div>

   
              


    <div class="card span-8 row-span-2">
        <div class="card-body">
            <div class="card-title">
                Collections Weight By Cooperatives (KGs)
            </div>
            <div>
                <canvas id="CooperativeCollectionsLineChart" class="mb-4 mb-md-0" height="250"></canvas>
            </div>
        </div>
    </div>

    

    <div class="card span-6" style="overflow-y: scroll; height:350px;">
        <div class="card-body">
            <div class="card-title">Grade Distribution KGs</div>
            <div class="row">
                <div class="col-12 d-flex align-items-center">
                    <canvas id="GradeDistributionBarChart" class="mb-4 mb-md-0" style="height: 200px;"></canvas>
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
    // collections chart
    let collectionsData = @json($data['collections']);
    let collectionsLabels = collectionsData.map(c => c.x)
    let collectionsValues = collectionsData.map(c => c.y)
    let collectionsBarChartCanvas = document.getElementById("CollectionsBarChart")

    let collectionsBarData = {
        labels: collectionsLabels,
        datasets: [{
            label: 'All',
            data: collectionsValues,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
        }],
        // labels: ["Male", "Female", "Other"]
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
        }
        // y axis is KGs
    };
    let collectionsBarChart = new Chart(collectionsBarChartCanvas, {
        type: "line",
        data: collectionsBarData,
        options: collectionsBarOptions
    });

    // coop collections chart
    let coopCollectionsArrData = @json($data['collections_by_cooperative']);
    let coopCollectionsLabels = [];
    let firstKey = Object.keys(coopCollectionsArrData)[0];
    if (coopCollectionsArrData.length != 0) {
        coopCollectionsLabels = coopCollectionsArrData[firstKey].map(c => c.x)
    }
    let coopCollectionsBarChartCanvas = document.getElementById("CooperativeCollectionsLineChart")

    let coopCollectionsBarData = {
        labels: collectionsLabels,
        datasets: [],
        // labels: ["Male", "Female", "Other"]
    };
    for (let key in coopCollectionsArrData) {
        let coopCollections = coopCollectionsArrData[key];
        let values = coopCollections.map(c => c.y)
        coopCollectionsBarData.datasets.push({
            label: key,
            data: values,
        });
    }


    let coopCollectionsBarOptions = {
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
    let coopCollectionsBarChart = new Chart(coopCollectionsBarChartCanvas, {
        type: "line",
        data: coopCollectionsBarData,
        options: coopCollectionsBarOptions
    });

    // grade distribution chart
    let gradeDistributionData = @json($data['grade_distribution']);
    let gradeDistributionLabels = gradeDistributionData.map(c => c.name)
    let gradeDistributionValues = gradeDistributionData.map(c => c.quantity)
    let gradeDistributionBarChartCanvas = document.getElementById("GradeDistributionBarChart")
    let gradeDistributionBarData = {
        datasets: [{
            data: gradeDistributionValues,
            backgroundColor: [
                'rgba(65, 47, 38, 1)',
                'rgba(165, 113, 80, 1)',
                'rgba(184, 134, 11, 1)',
                'rgba(245, 245, 220, 1)',
            ]
        }],
        labels: gradeDistributionLabels
    };
    let gradeDistributionBarOptions = {
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
    };
    let gradeDistributionChart = new Chart(gradeDistributionBarChartCanvas, {
        type: "horizontalBar",
        data: gradeDistributionBarData,
        options: gradeDistributionBarOptions
    });
</script>
@endpush