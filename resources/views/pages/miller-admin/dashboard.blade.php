@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')

<!-- <style>
    .grid {
        display: grid;
    }

    .grid-cols-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .dashgrid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 10px;
    }

    .span-8 {
        grid-column: span 8;
    }

    .span-4 {
        grid-column: span 4;
    }

    .span-2 {
        grid-column: span 2;
    }

    .span-6 {
        grid-column: span 6;
    }


    .row-span-2 {
        grid-row: span 2;
    }
</style> -->

@php
$total_gender_distribution = 0;
@endphp

<div class="header bg-custom-green pb-8 pt-5 pt-md-5">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0">Coffee In Marketplace</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$data["coffee_in_marketplace"] ?? "0"}} KG</span>
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
                                    <h5 class="text-uppercase text-muted mb-0">Cooperative Partnerships
                                        <br>
                                    <span class="h2 font-weight-bold mb-0">{{$data["cooperative_partnerships"] ?? "0"}}</span>
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
                                    <h5 class="text-uppercase text-muted mb-0">Current Orders</h5>
                                    <span class="h2 font-weight-bold mb-0">924</span>
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
                <!-- <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0">Performance</h5>
                                    <span class="h2 font-weight-bold mb-0">49,65%</span>
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
                </div> -->
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between w-100 mt-6 pb-6">
    <div class="d-flex align-items-start">
        <form class="d-flex">
            <div class="form-group">
                <select name="date_range" placeholder="Select Date Range" class="form-control select2bs4" onchange="this.form.submit()" id="dateRange">
                    <option value="week" @if($date_range=="week" ) selected @endif)>This Week</option>
                    <option value="month" @if($date_range=="month" ) selected @endif>This Month</option>
                    <option value="year" @if($date_range=="year" ) selected @endif>This Year</option>
                    <option value="custom" @if($date_range=="custom" ) selected @endif>Custom</option>
                </select>
            </div>
            <div class="form-group">
                <input type="date" class="form-control" name="from_date" value="{{$from_date}}" onchange="this.form.submit()" id="fromDate" />
            </div>
            <div class="form-group">
                <input type="date" class="form-control" name="to_date" value="{{$to_date}}" onchange="this.form.submit()" id="toDate" />
            </div>
        </form>
        <button class="btn btn-warning mt-1 ml-2" href="{{route('miller-admin.dashboard.export')}}" onclick="exportChart()">Export</button>
    </div>
</div>

<div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-8 mb-5 mb-xl-0">
                <div class="card bg-gradient-default shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
                                <h2 class="text-white mb-0">Inventory Vs Sales</h2>
                            </div>
                            <div class="col">
                                <ul class="nav nav-pills justify-content-end">
                                    <li class="nav-item mr-2 mr-md-0" data-toggle="chart" data-target="#chart-sales" data-update='{"data":{"datasets":[{"data":[0, 20, 10, 30, 15, 40, 20, 60, 60]}]}}' data-prefix="$" data-suffix="k">
                                        <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                            <span class="d-none d-md-block">Inventory</span>
                                            <span class="d-md-none">M</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" data-toggle="chart" data-target="#chart-sales" data-update='{"data":{"datasets":[{"data":[0, 20, 5, 25, 10, 30, 15, 40, 40]}]}}' data-prefix="$" data-suffix="k">
                                        <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                                            <span class="d-none d-md-block">Sales</span>
                                            <span class="d-md-none">W</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <!-- Chart wrapper -->
                            <canvas id="InventoryVsSalesChart" class="mb-4 mb-md-0" data-inventory-series="{{ json_encode($data['inventory_series']) }}" 
                            data-sales-series="{{ json_encode($data['sales_series']) }}" style="height: 200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                                <h2 class="mb-0">Total orders</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <canvas id="chart-orders" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-xl-8 mb-5 mb-xl-0">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="mb-0">Grade Distribution</h2>
                            </div>
                            <div class="col text-right">
                                <a href="#!" class="btn btn-sm btn-primary">See all</a>
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
                    <!-- <div class="table-responsive">
                         Projects table -->
                        <!-- <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Page name</th>
                                    <th scope="col">Visitors</th>
                                    <th scope="col">Unique users</th>
                                    <th scope="col">Bounce rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        /argon/
                                    </th>
                                    <td>
                                        4,569
                                    </td>
                                    <td>
                                        340
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-up text-success mr-3"></i> 46,53%
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        /argon/index.html
                                    </th>
                                    <td>
                                        3,985
                                    </td>
                                    <td>
                                        319
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-down text-warning mr-3"></i> 46,53%
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        /argon/charts.html
                                    </th>
                                    <td>
                                        3,513
                                    </td>
                                    <td>
                                        294
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-down text-warning mr-3"></i> 36,49%
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        /argon/tables.html
                                    </th>
                                    <td>
                                        2,050
                                    </td>
                                    <td>
                                        147
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-up text-success mr-3"></i> 50,87%
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        /argon/profile.html
                                    </th>
                                    <td>
                                        1,795
                                    </td>
                                    <td>
                                        190
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-down text-danger mr-3"></i> 46,53%
                                    </td>
                                </tr>
                            </tbody>
                        </table> -->
    
            <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Milled vs Pre Milled</h3>
                            </div>
                            <div class="col text-right">
                                <a href="#!" class="btn btn-sm btn-primary">See all</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Milled</th>
                                    <th scope="col">Pre Milled</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        Arabica
                                    </th>
                                    <td>
                                        1,480
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">60%</span>
                                            <div>
                                                <div class="progress">
                                                <div class="progress-bar bg-gradient-danger" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        Robusta
                                    </th>
                                    <td>
                                        5,480
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">70%</span>
                                            <div>
                                                <div class="progress">
                                                <div class="progress-bar bg-gradient-success" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 70%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        Ruiru2
                                    </th>
                                    <td>
                                        4,807
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">80%</span>
                                            <div>
                                                <div class="progress">
                                                <div class="progress-bar bg-gradient-primary" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <th scope="row">
                                        Instagram
                                    </th>
                                    <td>
                                        3,678
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">75%</span>
                                            <div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        twitter
                                    </th>
                                    <td>
                                        2,645
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">30%</span>
                                            <div>
                                                <div class="progress">
                                                <div class="progress-bar bg-gradient-warning" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>


@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush











<!-- dashgrid -->
<div class="dashgrid">
    

    
    
    <!-- <div class="card span-6">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="mr-2">
                    <i class="mdi mdi-basket-outline" style="font-size: 35px;color: #4bc0c0;"></i>
                </div>
                <div>Inventory vs Sales</div>
            </div> -->
            <div class="row">
                <div class="col-12 d-flex align-items-center">                      
                    <canvas id="InventoryVsSalesChart" class="mb-4 mb-md-0" data-inventory-series="{{ json_encode($data['inventory_series']) }}" 
                    data-sales-series="{{ json_encode($data['sales_series']) }}" style="height: 200px;"></canvas>
                </div>
            </div>
        </div>
    </div>



    

    



   
</div>
<!-- orders vs delivery -->

@endsection

@push('plugin-scripts')
<script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script>

    let milledSeries = @json($data['milled_series']);
    let preMilledSeries = @json($data['pre_milled_series']);
    let milledVsPremilledLables = milledSeries.map(c => c.x)
    let milledValues = milledSeries.map(c => c.y)
    let preMilledValues = preMilledSeries.map(c => c.y)

    let milledVsPremilledChartCanvas = document.getElementById("MilledVsPremilledChart")
    let milledVsPremilledData = {
        labels: milledVsPremilledLables,
        datasets: [{
            label: 'Milled',
            data: milledValues,
            backgroundColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
            ]
        }, {
            label: 'Premilled',
            data: preMilledValues,
            backgroundColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
            ]
        }]
    };

    let milledVsPremilledOptions = {
        animationEasing: "easeOutBounce",
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
    let milledVsPremilledChart = new Chart(milledVsPremilledChartCanvas, {
        type: "line",
        data: milledVsPremilledData,
        options: milledVsPremilledOptions
    });

    let incomeSeries = @json($data['income_series']);
    let expensesSeries = @json($data['expenses_series']);
    let incomeVsExpensesLables = incomeSeries.map(c => c.x)
    let incomeValues = incomeSeries.map(c => c.y)
    let expensesValues = expensesSeries.map(c => c.y)

    let incomeVsExpensesChartCanvas = document.getElementById("IncomeVsExpenseChart")
    let incomeVsExpensesData = {
        labels: incomeVsExpensesLables,
        datasets: [{
            label: 'Income',
            data: incomeValues,
            backgroundColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
            ]
        }, {
            label: 'Expenses',
            data: expensesValues,
            backgroundColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
            ]
        }]
    };

    let incomeVsExpensesOptions = {
        animationEasing: "easeOutBounce",
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
    let incomeVsExpensesChart = new Chart(incomeVsExpensesChartCanvas, {
        type: "line",
        data: incomeVsExpensesData,
        options: incomeVsExpensesOptions
    });


    let inventorySeries = @json($data['inventory_series']);
    let salesSeries = @json($data['sales_series']);
    let inventoryVsSalesLables = inventorySeries.map(c => c.x)
    let inventoryValues = inventorySeries.map(c => c.y)
    let salesValues = salesSeries.map(c => c.y)
    let inventoryVsSalesChartCanvas = document.getElementById("InventoryVsSalesChart")
    let inventoryVsSalesData = {
        labels: inventoryVsSalesLables,
        datasets: [{
            label: 'Inventory',
            data: inventoryValues,
            backgroundColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
            ]
        }, {
            label: 'Sales',
            data: salesValues,
            backgroundColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
            ]
        }]
    };

    let inventoryVsSalesOptions = {
        animationEasing: "easeOutBounce",
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
    let inventoryVsSalesChart = new Chart(inventoryVsSalesChartCanvas, {
        type: "line",
        data: inventoryVsSalesData,
        options: inventoryVsSalesOptions
    });

    let gradeDistribution = @json($data['grade_distribution']);
    let gradeDistributionLables = gradeDistribution.map(c => c.name)
    let gradeDistributionValues = gradeDistribution.map(c => c.quantity)

    let gradeDistributionChartCanvas = document.getElementById("GradeDistributionChart")
    let gradeDistributionChartData = {
        labels: gradeDistributionLables,
        datasets: [{
            label: 'Milled',
            data: gradeDistributionValues,
            backgroundColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
            ]
        }]
    };

    let gradeDistributionChartOptions = {
        animationEasing: "easeOutBounce",
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
    let gradeDistributionChart = new Chart(gradeDistributionChartCanvas, {
        type: "horizontalBar",
        data: gradeDistributionChartData,
        options: gradeDistributionChartOptions
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
                        '%</span>'; // Format tooltip to show percentages
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

        fetch('/miller-admin/dashboard/export', {
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