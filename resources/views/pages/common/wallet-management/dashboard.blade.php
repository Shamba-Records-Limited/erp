@extends('layouts.app')

@push('plugin-styles')
<style>
    .header {
        background-color: #28a745;
        padding: 2rem 0;
    }

    .icon-shape {
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

</style>
@endpush

@section('content')
@include('layout.export-dialog')

<div class="header bg-custom-green pb-4 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <!-- Income Card -->
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Total Income</h5>
                                    <span class="h2 font-weight-bold mb-0">KSH {{ number_format($data['totals']['income'], 2) }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> +5%</span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Expenses Card -->
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Total Expenses</h5>
                                    <span class="h2 font-weight-bold mb-0">KSH {{ number_format($data['totals']['expenses'], 2) }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> -3%</span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Account Receivables Card -->
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Account Receivables</h5>
                                    <span class="h2 font-weight-bold mb-0">KSH {{ number_format($data['totals']['accountReceivables'], 2) }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-hand-holding-usd"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-warning mr-2"><i class="fas fa-arrow-up"></i> +2%</span>
                                <span class="text-nowrap">Since last week</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Account Payables Card -->
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Account Payables</h5>
                                    <span class="h2 font-weight-bold mb-0">KSH {{ number_format($data['totals']['accountPayables'], 2) }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                        <i class="fas fa-file-invoice"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-primary mr-2"><i class="fas fa-arrow-up"></i> +4%</span>
                                <span class="text-nowrap">Since last week</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <!-- Deposits Card -->
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Deposits</h5>
                                    <span class="h2 font-weight-bold mb-0">KSH {{ number_format($data['totals']['deposits'], 2) }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-piggy-bank"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-info mr-2"><i class="fas fa-arrow-up"></i> +1%</span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </div>

               <!-- Withdrawals Card -->
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-muted mb-0" style="font-size:1rem">Withdrawals</h5>
                                    <span class="h2 font-weight-bold mb-0">KSH {{ number_format($data['totals']['withdrawals'], 2) }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-custom-green text-white rounded-circle shadow">
                                        <i class="fas fa-money-bill-wave"></i> <!-- Updated Icon -->
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> -2%</span> <!-- Updated icon -->
                                <span class="text-nowrap">Since last week</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end w-100 ">
        <form class="d-flex">
            <div class="form-group mr-2">
                <select name="date_range" class="form-control form-select" onchange="this.form.submit()">
                    <option value="week" @if($date_range=="week") selected @endif>This Week</option>
                    <option value="month" @if($date_range=="month") selected @endif>This Month</option>
                    <option value="year" @if($date_range=="year") selected @endif>This Year</option>
                    <option value="custom" @if($date_range=="custom") selected @endif>Custom</option>
                </select>
            </div>
            <div class="form-group mr-2">
                <input type="date" class="form-control" name="from_date" value="{{$from_date}}" onchange="this.form.submit()" />
            </div>
            <div class="form-group mr-2">
                <input type="date" class="form-control" name="to_date" value="{{$to_date}}" onchange="this.form.submit()" />
            </div>
            <button type="button" class="btn btn-warning btn-compact mt-1" onclick="exportChart()">Export</button>
        </form>
    </div>
</div>

<!-- Graphs Section -->
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Income and Expenses Bar Chart -->
        <div class="col-xl-6 col-lg-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="col mb-2">
                            <h6 class="text-uppercase text-muted ls-1 mb-1"> Income and Expenses Trends
                            </h6>
                            <h2 class=" mb-0">Income and Expenses</h2>
                    </div>
                    <canvas id="IncomeExpensesBarChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <!-- Account Breakdown Pie Chart -->
        <div class="col-xl-6 col-lg-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="col mb-2">
                            <h6 class="text-uppercase text-muted ls-1 mb-1"> Account Breakdown Trends
                            </h6>
                            <h2 class=" mb-0">Account Breakdown</h2>
                    </div>
                    <canvas id="AccountBreakdownPieChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <!-- Income Line Chart -->
        <div class="col-xl-6 col-lg-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="col mb-2">
                            <h6 class="text-uppercase text-muted ls-1 mb-1"> Income Trends
                            </h6>
                            <h2 class=" mb-0">Income</h2>
                    </div>
                    <canvas id="IncomeLineChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <!-- Expenses Line Chart -->
        <div class="col-xl-6 col-lg-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="col mb-2">
                            <h6 class="text-uppercase text-muted ls-1 mb-1"> Expenses Trends
                            </h6>
                            <h2 class=" mb-0"> Expenses</h2>
                    </div>
                    <canvas id="ExpensesLineChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@push('custom-scripts')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>

<script>
    // Data from the backend
    const incomeData = @json($data['charts']['income']);
    const expenseData = @json($data['charts']['expenses']);
    const labels = @json($data['charts']['labels']);
    const totals = @json($data['totals']);

    // Bar Chart for Income and Expenses
    new Chart(document.getElementById("IncomeExpensesBarChart"), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Income',
                    data: incomeData,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Expenses',
                    data: expenseData,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: { enabled: true }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Pie Chart for Account Breakdown
    new Chart(document.getElementById("AccountBreakdownPieChart"), {
        type: 'pie',
        data: {
            labels: ['Total Income', 'Total Expenses', 'Account Receivables', 'Account Payables'],
            datasets: [
                {
                    data: [
                        totals.income,
                        totals.expenses,
                        totals.accountReceivables,
                        totals.accountPayables
                    ],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(54, 162, 235, 0.6)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: { enabled: true }
            }
        }
    });

    // Line Chart for Income
    new Chart(document.getElementById("IncomeLineChart"), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Income',
                    data: incomeData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    borderWidth: 2,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: { enabled: true }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Line Chart for Expenses
    new Chart(document.getElementById("ExpensesLineChart"), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Expenses',
                    data: expenseData,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: true,
                    borderWidth: 2,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: { enabled: true }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

@endpush

