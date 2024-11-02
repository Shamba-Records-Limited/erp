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
                                    <h5 class="text-uppercase text-muted mb-0">Collection Total Quantity</h5>
                                    <span class="h2 font-weight-bold mb-0" id="collectionTotalWeight">{{$data["total_collection_weight"] ?? "0"}} KG</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-weight-hanging"></i>
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
                                    <h5 class="text-uppercase text-muted mb-0">Farmer Count</h5>
                                    <span class="h2 font-weight-bold mb-0" id="farmerCount">{{$data["farmer_count"]}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-user-friends"></i>
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
                                    <span class="h2 font-weight-bold mb-0" id="collectionCount">{{$data["collection_count"]}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-box"></i>
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
                                    <span class="h2 font-weight-bold mb-0" id="cooperativesCount">{{$data["cooperatives_count"]}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-people-carry"></i>
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
                                    <span class="h2 font-weight-bold mb-0" id="millersCount">{{$data["collection_count"]}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                        <i class="fas fa-industry"></i>
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
                                    <h5 class="text-uppercase text-muted mb-0">Number of Final Products</h5>
                                    <span class="h2 font-weight-bold mb-0" id="finalProductsCount">1,234</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-box"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 15%</span>
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
                                    <h5 class="text-uppercase text-muted mb-0">Milled Coffee Quantity</h5>
                                    <span class="h2 font-weight-bold mb-0" id="milledCoffeeQuantity">4,560 KG</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-coffee"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 10%</span>
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
                                    <h5 class="text-uppercase text-muted mb-0">Pre-Milled Coffee Quantity</h5>
                                    <span class="h2 font-weight-bold mb-0" id="preMilledCoffeeQuantity">3,200 KG</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-seedling"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 8%</span>
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
                                    <h5 class="text-uppercase text-muted mb-0">Number of Products Available</h5>
                                    <span class="h2 font-weight-bold mb-0" id="productsAvailableCount">890</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-cube"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 5%</span>
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
                                    <h5 class="text-uppercase text-muted mb-0">Total Sales</h5>
                                    <span class="h2 font-weight-bold mb-0" id="totalSales">$12,345</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 20%</span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-5 pl-5 pr-5 mt--7">
        <div class="card shadow">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
                        <h2 class=" mb-0">Collection Quantity (KGs)</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="CollectionsBarChart" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row pl-4 pr-4">
    <div class="col-lg-6">
        <div class="card" style="height: 100%;">
            <div class="card-body">
                <div class="col pb-2">
                    <h2 class="mb-0">Collection Quantity Per Cooperative (KGs)</h2>
                </div>
                <div>
                    <canvas id="CooperativeCollectionsLineChart" class="mb-4 mb-md-0" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 pt-2">
        <div class="card" style="overflow-y: scroll; height: 500px;">
            <div class="card-body">
                <div class="col">
                    <h2 class="mb-0">Grade Distribution KGs</h2>
                </div>
                <div class="row">
                    <div class="col-12 d-flex align-items-center">
                        <canvas id="GradeDistributionBarChart" class="mb-4 mb-md-0" style="height: 300px;"></canvas>
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
    };
    let collectionsBarOptions = {
        animationEasing: "easeOutBounce",
        responsive: true,
        maintainAspectRatio: false,
        showScale: true,
        legend: { display: true },
    };
    let collectionsBarChart = new Chart(collectionsBarChartCanvas, {
        type: "line",
        data: collectionsBarData,
        options: collectionsBarOptions
    });

    // cooperative collections chart
    let coopCollectionsArrData = @json($data['collections_by_cooperative']);
    let coopCollectionsLabels = [];
    let firstKey = Object.keys(coopCollectionsArrData)[0];
    if (coopCollectionsArrData.length != 0) {
        coopCollectionsLabels = coopCollectionsArrData[firstKey].map(c => c.x)
    }
    let coopCollectionsBarChartCanvas = document.getElementById("CooperativeCollectionsLineChart");

    // Generate a unique color for each cooperative line
    const colors = [
        'rgba(255, 99, 132, 0.8)',
        'rgba(54, 162, 235, 0.8)',
        'rgba(75, 192, 192, 0.8)',
        'rgba(153, 102, 255, 0.8)',
        'rgba(255, 159, 64, 0.8)'
    ];

    let coopCollectionsBarData = {
        labels: coopCollectionsLabels,
        datasets: [],
    };

    let colorIndex = 0;
    for (let key in coopCollectionsArrData) {
        let coopCollections = coopCollectionsArrData[key];
        let values = coopCollections.map(c => c.y);
        let color = colors[colorIndex % colors.length];
        coopCollectionsBarData.datasets.push({
            label: key,
            data: values,
            borderColor: color,
            backgroundColor: color.replace('0.8', '0.3'), // Transparent background
            fill: false
        });
        colorIndex++;
    }

    let coopCollectionsBarOptions = {
        animationEasing: "easeOutBounce",
        responsive: true,
        maintainAspectRatio: false,
        showScale: true,
        legend: { display: true },
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
        legend: { display: false },
    };
    let gradeDistributionChart = new Chart(gradeDistributionBarChartCanvas, {
        type: "horizontalBar",
        data: gradeDistributionBarData,
        options: gradeDistributionBarOptions
    });
</script>
@endpush
