@extends('layout.master')

@push('plugin-styles')

@endpush

@push('chartjs')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
@endpush

@push('plugin-styles')
<style>
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
</style>
@endpush

@section('content')
@php
$total_gender_distribution = 0;
@endphp
<div class="d-flex justify-content-between w-100">
    <div>Dashboard</div>
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
        <button class="btn btn-warning mt-1 ml-2" href="{{route('cooperative-admin.dashboard.export')}}" onclick="exportChart()">Export</button>
    </div>

</div>

<!-- dashgrid -->
<div class="dashgrid">
    <div class="card span-4">
        <div class="card-body">
            <div class="card-title d-flex align-items-center">
                <div class="mr-2">
                    <i class="mdi mdi-basket-outline" style="font-size: 35px;color: #4bc0c0;"></i>
                </div>
                <div>Coffee in Marketplace</div>
            </div>
            <h3 class="card-subtitle" id="coffeeInMarketplace">{{$data["coffee_in_marketplace"] ?? "0"}} KG</h3>
        </div>
    </div>

    <div class="card span-4">
        <div class="card-body">
            <div class="card-title d-flex align-items-center">
                <div class="mr-2">
                    <i class="mdi mdi-basket-outline" style="font-size: 35px;color: #4bc0c0;"></i>
                </div>
                <div>Cooperative Partnerships</div>
            </div>
            <h3 class="card-subtitle" id="cooperativePartnerships">{{$data["cooperative_partnerships"] ?? "0"}} KG</h3>
        </div>
    </div>

    <div class="card span-4">
        <div class="card-body">
            <div class="card-title d-flex align-items-center">
                <div class="mr-2">
                    <i class="mdi mdi-basket-outline" style="font-size: 35px;color: #4bc0c0;"></i>
                </div>
                <div>Current Orders</div>
            </div>
            <h3 class="card-subtitle" id="premilledInventory">Chart here</h3>
        </div>
    </div>

    <div class="card span-6">
        <div class="card-body">
            <div class="card-title d-flex align-items-center">
                <div class="mr-2">
                    <i class="mdi mdi-basket-outline" style="font-size: 35px;color: #4bc0c0;"></i>
                </div>
                <div>Inventory vs Sales</div>
            </div>
            <div class="row">
                <div class="col-12 d-flex align-items-center">
                    <canvas id="InventoryVsSalesChart" class="mb-4 mb-md-0" style="height: 200px;"></canvas>
                </div>
            </div>
            <h3 class="card-subtitle" id="premilledInventory">Chart here</h3>
        </div>
    </div>



    <div class="card span-6">
        <div class="card-body">
            <div class="card-title d-flex align-items-center">
                <div class="mr-2">
                    <i class="mdi mdi-basket-outline" style="font-size: 35px;color: #4bc0c0;"></i>
                </div>
                <div>Income vs Expense</div>
            </div>
            <div class="row">
                <div class="col-12 d-flex align-items-center">
                    <canvas id="IncomeVsExpenseChart" class="mb-4 mb-md-0" style="height: 200px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card span-6">
        <div class="card-body">
            <div class="card-title d-flex align-items-center">
                <div class="mr-2">
                    <i class="mdi mdi-basket-outline" style="font-size: 35px;color: #4bc0c0;"></i>
                </div>
                <div>Grade Distribution</div>
            </div>
            <div class="row">
                <div class="col-12 d-flex align-items-center">
                    <canvas id="GradeDistributionChart" class="mb-4 mb-md-0" style="height: 200px;"></canvas>
                </div>
            </div>
        </div>
    </div>



    <div class="card span-6">
        <div class="card-body">
            <div class="card-title d-flex align-items-center">
                <div class="mr-2">
                    <i class="mdi mdi-basket-outline" style="font-size: 35px;color: #4bc0c0;"></i>
                </div>
                <div>Milled vs Pre-milled</div>
            </div>
            <div class="row">
                <div class="col-12 d-flex align-items-center">
                    <canvas id="MilledVsPremilledChart" class="mb-4 mb-md-0" style="height: 200px;"></canvas>
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
</script>

@endpush