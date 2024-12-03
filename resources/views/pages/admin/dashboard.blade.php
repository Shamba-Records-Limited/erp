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
                                    <h5 class="text-uppercase text-muted mb-0">Raw Material Quantity</h5>
                                    <span class="h2 font-weight-bold mb-0" id="collectionTotalWeight">
                                        {{ number_format($data["total_collection_weight"] ?? 0) }} KG
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-weight-hanging"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                @if($data["raw_percent"] > 0)
                                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i>{{$data["raw_percent"]}} %</span>
                                @else
                                <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i>{{$data["raw_percent"]}} %</span>
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
                                @if($data["farmer_percent"]>0)
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> {{$data["farmer_percent"]}}%</span>
                                 @else
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> {{$data["farmer_percent"]}}%</span>
                                @endif
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
                                 @if($data["collection_percent"]>0)
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> {{$data["collection_percent"]}}%</span>
                                 @else
                                <span class="text-warning mr-2"><i class="fas fa-arrow-down"></i> {{$data["collection_percent"]}}%</span>
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
                                    <h5 class="text-uppercase text-muted mb-0">Aggregator Count</h5>
                                    <span class="h2 font-weight-bold mb-0" id="cooperativesCount">{{$data["cooperatives_count"]}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-people-carry"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                @if($data["coop_percent"]>0)
                                 <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> {{$data["coop_percent"]}}%</span>
                                @else
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> {{$data["coop_percent"]}}%</span>
                                @endif
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
                                    <h5 class="text-uppercase text-muted mb-0">Millers/Processors Count</h5>
                                    <span class="h2 font-weight-bold mb-0" id="millersCount">{{$data["millers_count"]}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                        <i class="fas fa-industry"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                @if($data["millers_percent"]>0)
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> {{$data["millers_percent"]}}%</span>
                                @else
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> {{$data["millers_percent"]}}%</span>
                                @endif
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
                                    <span class="h2 font-weight-bold mb-0" id="finalProductsCount">{{$data["final_products_count"]}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-box"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                @if($data["finalproductcount_percent"]>0)
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> {{$data["finalproductcount_percent"]}}%</span>
                                @else
                                <span class="text-danger mr-2"><i class="fas fa-arrow-dwon"></i> {{$data["finalproductcount_percent"]}}%</span>
                                @endif
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
                                    <h5 class="text-uppercase text-muted mb-0">Processed Product Quantity</h5>
                                    <span class="h2 font-weight-bold mb-0" id="milledCoffeeQuantity">{{$data["final_products_quantity"]}} KG</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-coffee"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                @if($data["finalproductqnty_percent"]>0)
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> {{$data["finalproductqnty_percent"]}} %</span>
                                @else
                                <span class="text-danger mr-2"><i class="fas fa-arrow-up"></i> {{$data["finalproductqnty_percent"]}}%</span>
                                @endif
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
                                    <h5 class="text-uppercase text-muted mb-0">Pre-Processed Product Quantity</h5>
                                    <span class="h2 font-weight-bold mb-0" id="preMilledCoffeeQuantity">{{$data["total_Premilled_since_last_month"]}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-seedling"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                @if($data["premilled_percent"]>0)
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> {{$data["premilled_percent"]}}%</span>
                                @else
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> {{$data["premilled_percent"]}}%</span>
                                 @endif
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
                                    <span class="h2 font-weight-bold mb-0" id="productsAvailableCount">{{$data["final_products_count"]}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-cube"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                @if($data["finalproductcount_percent"]>0)
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> {{$data["finalproductcount_percent"]}}%</span>
                                @else
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> {{$data["finalproductcount_percent"]}}%</span>
                                @endif
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
                                    <span class="h2 font-weight-bold mb-0" id="totalSales">Ksh {{$data["sales_since_last_month"]}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                @if($data["sales_percent"]>0)
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> {{$data["sales_percent"]}}%</span>
                                @ese
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> {{$data["sales_percent"]}}%</span>
                                @endif
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>

<div class="container-fluid mt--7">
    <!-- Global Filter for Date Range and Export -->
    <div class="d-flex justify-content-between w-100 mb-4">
        <div></div>
        <div class="d-flex align-items-start">
            <form class="d-flex">
                <div class="form-group mr-2">
                    <select name="date_range" placeholder="Select Date Range" class="form-control form-select"
                        onchange="this.form.submit()" id="dateRange">
                        <option value="week" @if($date_range=="week" ) selected @endif>This Week</option>
                        <option value="month" @if($date_range=="month" ) selected @endif>This Month</option>
                        <option value="year" @if($date_range=="year" ) selected @endif>This Year</option>
                        <option value="custom" @if($date_range=="custom" ) selected @endif>Custom</option>
                    </select>
                </div>
                <div class="form-group mr-2">
                    <input type="date" class="form-control" name="from_date" value="{{ $from_date }}"
                        onchange="this.form.submit()" id="fromDate" />
                </div>
                <div class="form-group mr-2">
                    <input type="date" class="form-control" name="to_date" value="{{ $to_date }}"
                        onchange="this.form.submit()" id="toDate" />
                </div>
            </form>
            <a class="btn btn-warning mt-1 ml-2" href="{{ route('admin.dashboard.export') }}"
                onclick="exportChart()">Export</a>
        </div>
    </div>

    <!-- Dashboard Content -->
<div class="row">
    <div class="col-12 mb-5 pl-5 pr-5 mt--4">
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
    <!-- Collection Quantity Per Cooperative (KGs) Card -->
    <div class="col-lg-6">
        <div class="card" style="height: 100%; min-height: 500px;">
            <div class="card-body">
                <div class="col pb-2">
                    <h2 class="mb-0">Collection Quantity Per Cooperative/Aggregator (KGs)</h2>
                </div>
                <div>
                    <canvas id="CooperativeCollectionsLineChart" class="mb-4 mb-md-0" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Collection By Gender Card (Bar Chart) -->
    <div class="col-lg-6">
        <div class="card" style="height: 100%; min-height: 500px;">
            <div class="card-body">
                <div class="col">
                    <h6 class="text-uppercase text-muted ls-1 mb-1">Collections Weight (KGs) By Gender</h6>
                    <h2 class="mb-0">Collection By Gender</h2>
                </div>
                <div class="chart">
                    <canvas id="CollectionsGenderBarChart" class="chart-canvas" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row pl-4 pr-4 mt-4">
    <!-- Collection Gender Distribution Card (Pie Chart) -->
    <div class="col-lg-6">
        <div class="card" style="height: 100%; min-height: 500px;">
            <div class="card-body">
                <div class="col">
                    <h6 class="text-uppercase text-muted ls-1 mb-1">Gender Distribution</h6>
                    <h2 class="mb-0">Farmers Gender Distribution</h2>
                </div>
                <div class="chart">
                    <canvas id="CollectionsGenderPieChart" class="chart-canvas" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Farmer Age Distribution Card -->
    <div class="col-lg-6">
        <div class="card" style="height: 100%; min-height: 500px;">
            <div class="card-body">
                <div class="col">
                    <h6 class="text-uppercase text-muted ls-1 mb-1">Age Distribution</h6>
                    <h2 class="mb-0">Farmer Age Distribution</h2>
                </div>
                <div class="chart">
                    <canvas id="AgeDistributionBarChart" class="chart-canvas" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row pl-4 pr-4 mt-4">
    <!-- Grade Distribution KGs Card -->
    <div class="col-lg-12">
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
    // Collections chart with comma formatting on y-axis
    let collectionsData = @json($data['collections']);
    let collectionsLabels = collectionsData.map(c => c.x);
    let collectionsValues = collectionsData.map(c => c.y);

    let collectionsBarChartCanvas = document.getElementById("CollectionsBarChart");

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
        scales: {
            yAxes: [{
                gridLines: { zeroLineColor: 'rgba(77, 77, 77, 0.5)' },
                ticks: {
                    callback: function(value) { return value.toLocaleString() },
                    beginAtZero: true,
                },
            }],
            xAxes: [{ gridLines: { display: false } }],
        },
        responsive: true,
        maintainAspectRatio: false
    };
    let collectionsBarChart = new Chart(collectionsBarChartCanvas, {
        type: "line",
        data: collectionsBarData,
        options: collectionsBarOptions
    });

    // Cooperative collections chart with comma formatting on y-axis
    let coopCollectionsArrData = @json($data['collections_by_cooperative']);
    let coopCollectionsLabels = coopCollectionsArrData[Object.keys(coopCollectionsArrData)[0]].map(c => c.x);
    let coopCollectionsBarChartCanvas = document.getElementById("CooperativeCollectionsLineChart");

    const colors = ['rgba(255, 99, 132, 0.8)', 'rgba(54, 162, 235, 0.8)', 'rgba(75, 192, 192, 0.8)', 'rgba(153, 102, 255, 0.8)', 'rgba(255, 159, 64, 0.8)'];
    let coopCollectionsBarData = { labels: coopCollectionsLabels, datasets: [] };
    let colorIndex = 0;
    for (let key in coopCollectionsArrData) {
        let coopCollections = coopCollectionsArrData[key];
        let values = coopCollections.map(c => c.y);
        let color = colors[colorIndex % colors.length];
        coopCollectionsBarData.datasets.push({
            label: key,
            data: values,
            borderColor: color,
            backgroundColor: color.replace('0.8', '0.3'),
            fill: false
        });
        colorIndex++;
    }
    let coopCollectionsBarOptions = {
        scales: {
            yAxes: [{
                gridLines: { zeroLineColor: 'rgba(77, 77, 77, 0.5)' },
                ticks: { callback: function(value) { return value.toLocaleString() }, beginAtZero: true },
            }],
            xAxes: [{ gridLines: { display: false } }],
        },
        responsive: true,
        maintainAspectRatio: false
    };
    let coopCollectionsBarChart = new Chart(coopCollectionsBarChartCanvas, {
        type: "line",
        data: coopCollectionsBarData,
        options: coopCollectionsBarOptions
    });

    // Grade distribution chart with comma formatting on x-axis
    let gradeDistributionData = @json($data['grade_distribution']);
    let gradeDistributionLabels = gradeDistributionData.map(c => c.name);
    let gradeDistributionValues = gradeDistributionData.map(c => c.quantity);
    let gradeDistributionBarChartCanvas = document.getElementById("GradeDistributionBarChart");

    let gradeDistributionBarData = {
        datasets: [{
            data: gradeDistributionValues,
            backgroundColor: ['rgba(65, 47, 38, 1)', 'rgba(165, 113, 80, 1)', 'rgba(184, 134, 11, 1)', 'rgba(245, 245, 220, 1)']
        }],
        labels: gradeDistributionLabels
    };
    let gradeDistributionBarOptions = { responsive: true, maintainAspectRatio: true };
    let gradeDistributionChart = new Chart(gradeDistributionBarChartCanvas, {
        type: "horizontalBar",
        data: gradeDistributionBarData,
        options: gradeDistributionBarOptions
    });

    // Collections by gender bar chart
    let maleCollectionsData = @json($data['male_collections']);
    let maleCollectionValues = maleCollectionsData.map(c => c.y);
    let collectionsGenderLabels = maleCollectionsData.map(c => c.x);
    let femaleCollectionsData = @json($data['female_collections']);
    let femaleCollectionValues = femaleCollectionsData.map(c => c.y);
    let collectionsGenderBarChartCanvas = document.getElementById("CollectionsGenderBarChart");

    let collectionsGenderBarData = {
        labels: collectionsGenderLabels,
        datasets: [
            { label: 'Male', data: maleCollectionValues, borderColor: 'rgba(54, 162, 235, 1)', backgroundColor: 'rgba(54, 162, 235, 1)', tension: 0.4, fill: true },
            { label: 'Female', data: femaleCollectionValues, borderColor: '#f53794', backgroundColor: '#f53794', tension: 0.4, fill: true }
        ],
    };

    let collectionsGenderBarOptions = {
        scales: {
            yAxes: [{ gridLines: { zeroLineColor: 'rgba(77, 77, 77, 0.5)' }, ticks: { callback: function(value) { return value.toLocaleString() }, beginAtZero: true } }],
            xAxes: [{ gridLines: { display: false } }]
        },
        responsive: true,
        maintainAspectRatio: false
    };
    let collectionsGenderBarChart = new Chart(collectionsGenderBarChartCanvas, { type: 'bar', data: collectionsGenderBarData, options: collectionsGenderBarOptions });

    // Collections by gender pie chart
    let collectionsGenderPieChartCanvas = document.getElementById("CollectionsGenderPieChart");
    let collectionsGenderPieData = {
        labels: ["Male", "Female"],
        datasets: [{
            data: [
                maleCollectionValues.reduce((a, b) => a + b, 0),
                femaleCollectionValues.reduce((a, b) => a + b, 0)
            ],
            backgroundColor: ['rgba(54, 162, 235, 1)', '#f53794'],
            hoverBackgroundColor: ['rgba(54, 162, 235, 0.8)', '#f53794']
        }]
    };
    let collectionsGenderPieOptions = { responsive: true, maintainAspectRatio: false, animation: { animateScale: true, animateRotate: true } };
    let collectionsGenderPieChart = new Chart(collectionsGenderPieChartCanvas, { type: 'pie', data: collectionsGenderPieData, options: collectionsGenderPieOptions });

    // Age Distribution Bar Chart with Dummy Data
    let ageDistributionData = @json($age_distribution);

    let ageDistributionLabels = ageDistributionData.map(c => c.age_group);
    let ageDistributionValues = ageDistributionData.map(c => c.quantity);

    let ageDistributionBarChartCanvas = document.getElementById("AgeDistributionBarChart");

    let ageDistributionBarData = {
        labels: ageDistributionLabels,
        datasets: [{
            label: 'Quantity',
            data: ageDistributionValues,
            borderColor: 'rgba(153, 102, 255, 1)',
            backgroundColor: 'rgba(153, 102, 255, 0.5)',
            borderWidth: 2
        }],
    };

    let ageDistributionBarOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            yAxes: [{
                gridLines: { color: 'rgba(0, 0, 0, 0.1)' },
                ticks: { beginAtZero: true }
            }],
            xAxes: [{ gridLines: { display: false } }]
        },
        plugins: {
            legend: { display: false }
        }
    };

    // Initialize the Age Distribution chart
    let ageDistributionBarChart = new Chart(ageDistributionBarChartCanvas, {
        type: "bar",
        data: ageDistributionBarData,
        options: ageDistributionBarOptions
    });

</script>
@endpush
