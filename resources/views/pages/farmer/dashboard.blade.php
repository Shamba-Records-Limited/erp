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
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0">Total Colection Weight</h5>
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
                                @if($data)
                                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i>{{$data["percentageWeight"]}}%</span>
                                @else
                                <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i>{{$data["percentageWeight"]}}%</span>
                                 @endif
                                <span class="text-nowrap">This month Agaisnt Last Month</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0">Total Money Paid</h5>
                                    <span class="h2 font-weight-bold mb-0" id="farmerCount">{{$data["total_amount_paid"]}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-user-friends"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                @if($data)
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i>{{$data["percentageAmountPaidChange"]}}%</span>
                                 @else
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> {{$data["percentageAmountPaidChange"]}}%</span>
                                @endif
                                <span class="text-nowrap">This month Against Last</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
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
                                 @if($data)
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> {{$data["percentageCollectionCountChange"]}}%</span>
                                 @else
                                <span class="text-warning mr-2"><i class="fas fa-arrow-down"></i>{{$data["percentageCollectionCountChange"]}}%</span>
                                @endif
                                <span class="text-nowrap">This Month Agains Last</span>
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
                    <canvas id="CooperativeCollectionsBarChart" class="mb-4 mb-md-0" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Grade Distribution KGs Card -->
    <div class="col-lg-6">
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

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
    document.addEventListener('DOMContentLoaded', function() {
        // Pass the PHP data to JavaScript
        let coopCollectionsData = @json($data['collections_by_cooperative']);
        // Prepare labels and data for the chart
        let coopLabels = coopCollectionsData.map(c => c.cooperative_name); // Cooperative names
        let coopQuantities = coopCollectionsData.map(c => c.total_quantity); // Quantities
        // Get the canvas element
        let coopCollectionsBarChartCanvas = document.getElementById("CooperativeCollectionsBarChart");
        if (coopCollectionsBarChartCanvas) {
            // Chart configuration
            let coopCollectionsBarData = {
                labels: coopLabels, // Cooperative names
                datasets: [{
                    label: 'Total Quantity',
                    data: coopQuantities,
                    backgroundColor: 'rgba(54, 162, 235, 0.8)', // Bar color
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            };
            let coopCollectionsBarOptions = {
                scales: {
                    yAxes: [{
                        ticks: { 
                            callback: value => value.toLocaleString(), // Add comma formatting to y-axis
                            beginAtZero: true 
                        },
                        gridLines: { zeroLineColor: 'rgba(77, 77, 77, 0.5)' }
                    }],
                    xAxes: [{ gridLines: { display: false } }]
                },
                responsive: true,
                maintainAspectRatio: false
            };

            // Create the chart
            let coopCollectionsBarChart = new Chart(coopCollectionsBarChartCanvas, {
                type: 'bar', // Chart type (bar chart)
                data: coopCollectionsBarData,
                options: coopCollectionsBarOptions
            });
        } else {
            console.error('Canvas element not found');
        }
    });
        

         //3.Grade distributions
         let gradeDistributionData = @json($data['grade_distribution']);
        // Map data for labels and values
        let gradeDistributionLabels = gradeDistributionData.map(c => c.name);
        let gradeDistributionValues = gradeDistributionData.map(c => c.quantity);
        console.log("Labels:", gradeDistributionLabels); // Debugging
        console.log("Values:", gradeDistributionValues); // Debugging
        // Get the canvas
        let gradeDistributionBarChartCanvas = document.getElementById("GradeDistributionBarChart");
        // Chart data
        let gradeDistributionBarData = {
            datasets: [{
                label: 'Quantities',
                data: gradeDistributionValues,
                backgroundColor: [
                    'rgba(65, 47, 38, 1)', 
                    'rgba(165, 113, 80, 1)', 
                    'rgba(184, 134, 11, 1)', 
                    'rgba(245, 245, 220, 1)'
                ]
            }],
            labels: gradeDistributionLabels
        };
        // Chart options
        let gradeDistributionBarOptions = {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                xAxes: [{
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString(); // Format with commas
                        },
                        beginAtZero: true
                    }
                }],
                yAxes: [{
                    ticks: { autoSkip: false }
                }]
            }
        };

        // Initialize the chart
        let gradeDistributionChart = new Chart(gradeDistributionBarChartCanvas, {
            type: "horizontalBar",
            data: gradeDistributionBarData,
            options: gradeDistributionBarOptions
        });

</script>
@endpush
