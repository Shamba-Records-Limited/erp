@extends('layouts.app')
@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js">
</script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush

@section('content')
@include('layouts.headers.cards')


@php
$total_gender_distribution = 0;
@endphp

<div class="header bg-custom-green pb-8 pt-5 pt-md-5">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0">Coffee In Marketplace</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {{ number_format($data["total_remaining_quantity"] ?? 0) }} KG
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                @if($data["percentageRemaining"]>0)
                                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{$data["percentageRemaining"]}}%</span>
                                @else
                                <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i> {{$data["percentageRemaining"]}}%</span>
                                @endif
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0">Cooperative Partnerships
                                        <br>
                                        <span
                                            class="h2 font-weight-bold mb-0">{{$data["cooperative_partnerships"] ?? "0"}}</span>
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
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0">Current Orders</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$data["count_order"]}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                @if($data["order_percent"]>0)
                                <span class="text-warning mr-2"><i class="fas fa-arrow-up"></i>{{$data["order_percent"]}}%</span>
                                @else
                                <span class="text-warning mr-2"><i class="fas fa-arrow-down"></i>{{$data["order_percent"]}}%</span>
                                 @endif
                                <span class="text-nowrap">Since yesterday</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-start w-100 mt-6 pb-6">
    <div class="d-flex align-items-start">
        <form class="d-flex" >
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
        <button class="btn btn-warning mt-1 ml-2" href="{{route('miller-admin.dashboard.export')}}"
            onclick="exportChart()">Export</button>
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
                                <li class="nav-item mr-2 mr-md-0" data-toggle="chart" data-target="#chart-sales"
                                    data-update='{"data":{"datasets":[{"data":[0, 20, 10, 30, 15, 40, 20, 60, 60]}]}}'
                                    data-prefix="$" data-suffix="k">
                                    <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                        <span class="d-none d-md-block">Inventory</span>
                                        <span class="d-md-none">M</span>
                                    </a>
                                </li>
                                <li class="nav-item" data-toggle="chart" data-target="#chart-sales"
                                    data-update='{"data":{"datasets":[{"data":[0, 20, 5, 25, 10, 30, 15, 40, 40]}]}}'
                                    data-prefix="$" data-suffix="k">
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
                        <canvas id="InventoryVsSalesChart" class="mb-4 mb-md-0"
                            data-inventory-series="{{ json_encode($data['inventory_series']) }}"
                            data-sales-series="{{ json_encode($data['sales_series']) }}"
                            style="height: 200px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1">Total orders</h6>
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
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1">Income Vs Expenses</h6>
                            <h2 class="mb-0">Income Vs Expenses</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart">
                        <canvas id="IncomeVsExpenseChart" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Milled Vs Premilled -->
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1">Milled Vs Premilled</h6>
                            <h2 class="mb-0">Milled Vs Premilled</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart">
                        <canvas id="MilledVsPremilledChart" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>



        
    </div>

</div>


@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush

@endsection

@push('plugin-scripts')
<script src="{{ asset('/assets/plugins/chartjs/chart.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    type: "bar",
    data: milledVsPremilledData,
    options: milledVsPremilledOptions
});



//Income vs expenses
document.addEventListener("DOMContentLoaded", function() {

let incomeSeries = @json($data['income_series']);
let expensesSeries = @json($data['expenses_series']);

if (!incomeSeries || !expensesSeries) {
    console.error('Income or Expenses series data is empty or undefined.');
    return; // Exit early if the data is invalid
}

console.log('incomeSeries:', incomeSeries);
console.log('expensesSeries:', expensesSeries);

// Map the labels and values for the chart
let incomeVsExpensesLables = incomeSeries.map(c => c.x); // Use the "x" values for the labels
let incomeValues = incomeSeries.map(c => c.y);
let expensesValues = expensesSeries.map(c => c.y);

// Set up the chart
let incomeVsExpensesChartCanvas = document.getElementById("IncomeVsExpenseChart");

// Data for the chart
let incomeVsExpensesData = {
    labels: incomeVsExpensesLables, // Use the x values as labels
    datasets: [
        {
            label: 'Income',
            data: incomeValues,
            backgroundColor: 'rgba(54, 162, 235, 1)',
            borderColor: 'rgba(54, 162, 235, 1)',
            fill: false
        },
        {
            label: 'Expenses',
            data: expensesValues,
            backgroundColor: 'rgba(255, 99, 132, 1)',
            borderColor: 'rgba(255, 99, 132, 1)',
            fill: false
        }
    ]
};
// Chart options
let incomeVsExpensesOptions = {
    animationEasing: "easeOutBounce",
    responsive: true,
    maintainAspectRatio: true,
    legend: {
        display: true
    },
    scales: {
        x: {
            type: 'category', // Make sure x-axis uses category type for strings like "2024-10"
            title: {
                display: true,
                text: 'Month'
            }
        },
        y: {
            beginAtZero: true,
            title: {
                display: true,
                text: 'Amount'
            }
        }
    }
};
// Create the chart
let incomeVsExpensesChart = new Chart(incomeVsExpensesChartCanvas, {
    type: "line", // Line chart type
    data: incomeVsExpensesData,
    options: incomeVsExpensesOptions
});


});


//ordes chart
document.addEventListener("DOMContentLoaded", function() {
 // Inject order series data from the server
 const orderSeries = @json($data['orders_series']);

// Extract labels and values
const labels = orderSeries.map(data => data.x);
const values = orderSeries.map(data => data.y);

// Create a Chart.js bar chart
const ctx = document.getElementById('chart-orders').getContext('2d');
const ordersChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels, // Month labels
        datasets: [{
            label: 'Orders',
            data: values, // Order counts
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Order Count'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Months'
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            title: {
                display: true,
                text: 'Orders Per Month'
            }
        }
    }
});

});

/*
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
}*/
</script>

@endpush