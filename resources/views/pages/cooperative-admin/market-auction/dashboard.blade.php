@extends('layouts.app')

@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush

@section('content')
<div class="header bg-custom-green pb-8 pt-5 pt-md-5">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Marketplace Stats Cards -->
            <div class="row">
                @php
                    $cardHeight = '170px';
                    $h5FontSize = '0.9rem';
                @endphp

                <!-- Available Stock for Sale -->
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0" style="height: {{ $cardHeight }};">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0" style="font-size: {{ $h5FontSize }};">Available Stock for Sale</h5>
                                    <span class="h2 font-weight-bold mb-0 d-block">{{ $data['stock_availabe'] ?? '0' }}</span>
                                    <span class="small text-muted">KG</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-boxes"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                @if($data['stock_percent']>0)
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> {{$data['stock_percent']}}%</span>
                                 @else
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> {{$data['stock_percent']}}%</span>
                                 @endif
                                <span class="text-nowrap">Since last week</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Sales Value -->
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0" style="height: {{ $cardHeight }};">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0" style="font-size: {{ $h5FontSize }};">Total Sales Value</h5>
                                    <span class="h2 font-weight-bold mb-0 d-block">Ksh {{ $data['totalsales'] ?? '0' }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                @if($data['sale_percent']>0)
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> {{$data['sale_percent']}}%</span>
                                @else
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> {{$data['sale_percent']}}%</span>
                                 @endif
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Orders Processed -->
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0" style="height: {{ $cardHeight }};">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0" style="font-size: {{ $h5FontSize }};">Orders Processed</h5>
                                    <span class="h2 font-weight-bold mb-0 d-block">{{ $data['totalOrdersCount'] ?? '0' }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                 @if($data['order_percent']>0)
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> {{$data['order_percent']}}%</span>
                                 @else
                                <span class="text-warning mr-2"><i class="fas fa-arrow-down"></i> {{$data['order_percent']}}%</span>
                                 @endif
                                <span class="text-nowrap">Since last week</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Marketplace Stats Cards -->
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="container-fluid mt-7">
    <div class="row">
        <!-- Sales Trend Line Chart -->
        <div class="col-xl-6">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <h2 class="mb-0">Sales Trend Over Time</h2>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="salesTrendChart1" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Category Sales Pie Chart -->
        <div class="col-xl-6">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <h2 class="mb-0">Sales by Product Category</h2>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="categorySalesPieChart" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Revenue by Product Horizontal Bar Chart -->
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <h2 class="mb-0">Revenue Generated by Product (KG)</h2>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="revenueByProductChart" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('custom-scripts')
<script>
    //sales Trend Chart 1
    // Prepare data from the server
    const salesData = @json($data['salesChart']);
        // Extract labels (months) and data (sales amounts)
        const labels = salesData.map(sale => sale.month);
        const data = salesData.map(sale => sale.total_sales);
        // Create Chart.js line chart
        const ctx = document.getElementById('salesTrendChart1').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales Amount',
                    data: data,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Sales Amount'
                        }
                    }
                }
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
        // Sales by Product Category Chart
        const categorySalesData = {
            labels: @json($data['prodcatlabels']), // Dynamic product category labels
            datasets: [{
                data: @json($data['prodcatrevenues']), // Dynamic revenue data
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(255, 159, 64, 0.5)'
                ]
            }]
        };

        const categorySalesOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': Ksh ' + context.raw.toLocaleString(); // Add currency to tooltip
                        }
                    }
                }
            }
        };

        new Chart(document.getElementById("categorySalesPieChart"), {
            type: 'pie',
            data: categorySalesData,
            options: categorySalesOptions
        });
    });

    // Sales by Product Category Chart
  /*  const categorySalesData = {
        labels: ['Beverages', 'Dairy', 'Snacks', 'Bakery'],
        datasets: [{
            data: [30000, 20000, 15000, 10000],
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)'
            ]
        }]
    };
    const categorySalesOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': Ksh ' + context.raw.toLocaleString(); // Add currency to tooltip
                    }
                }
            }
        }
    };
    new Chart(document.getElementById("categorySalesPieChart"), {
        type: 'pie',
        data: categorySalesData,
        options: categorySalesOptions
    });
*/


    // Revenue by Product Chart
document.addEventListener("DOMContentLoaded", function () {
    const revenueByProductData = {
        labels: @json($data['prodlabels']), // Dynamically inject product names
        datasets: [{
            label: 'Revenue (Ksh)',
            data: @json($data['prodrevenues']), // Dynamically inject revenue data
            backgroundColor: 'rgba(153, 102, 255, 0.5)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1
        }]
    };
    const revenueByProductOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Ksh ' + value.toLocaleString(); // Format axis labels
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Ksh ' + context.raw.toLocaleString(); // Format tooltip values
                    }
                }
            }
        }
    };

    new Chart(document.getElementById("revenueByProductChart"), {
        type: 'horizontalBar',
        data: revenueByProductData,
        options: revenueByProductOptions
    });
});
 
</script>
@endpush
