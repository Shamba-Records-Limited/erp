@extends('layouts.app')

@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush

@section('content')
<div class="header bg-custom-green pb-8 pt-5 pt-md-5">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Summary Cards -->
            <div class="row">
                <!-- Total Products -->
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0" style="height: 140px;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0">Total Products</h5>
                                    <span class="h2 font-weight-bold mb-0">3,200</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-box"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Units Available -->
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0" style="height: 140px;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0">Units Available</h5>
                                    <span class="h2 font-weight-bold mb-0">15,800</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-cubes"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories Count -->
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0" style="height: 140px;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0">Categories Count</h5>
                                    <span class="h2 font-weight-bold mb-0">4</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                        <i class="fas fa-list"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graded Products -->
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0" style="height: 140px;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0">Graded Products</h5>
                                    <span class="h2 font-weight-bold mb-0">4,200 KG</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                        <i class="fas fa-tags"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Summary Cards -->
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="container-fluid mt-7">
    <div class="row">
        <!-- Available Stock Levels Bar Chart -->
        <div class="col-xl-6">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <h2 class=" mb-0">Available Stock (Kgs)</h2>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="availableStockBarChart" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Categories Pie Chart -->
        <div class="col-xl-6">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <h2 class="mb-0">Product Categories Distribution (Kgs)</h2>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="productCategoriesPieChart" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Grading Distribution Horizontal Bar Chart -->
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <h2 class="mb-0">Grading Distribution (Kgs)</h2>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="gradingDistributionChart" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('custom-scripts')
<script>
    // Available Stock Chart
    const availableStockData = {
        labels: ['Coffee', 'Cocoa', 'Tea', 'Dairy Products'],
        datasets: [{
            label: 'Stock (KG)',
            data: [3200, 1500, 1800, 2400],
            backgroundColor: 'rgba(75, 192, 192, 0.5)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };
    const availableStockOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' KG'; // Add KG and comma formatting to y-axis values
                    }
                }
            },
            x: {
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' KG'; // Add KG and comma formatting to x-axis values
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.raw.toLocaleString() + ' KG'; // Add KG to tooltip
                    }
                }
            }
        }
    };
    new Chart(document.getElementById("availableStockBarChart"), {
        type: 'bar',
        data: availableStockData,
        options: availableStockOptions
    });

    // Product Categories Distribution Chart
    const productCategoriesData = {
        labels: ['Beverages', 'Dairy', 'Farm Products', 'Vegetables'],
        datasets: [{
            data: [300, 500, 200, 100],
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)'
            ]
        }]
    };
    const productCategoriesOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.raw.toLocaleString() + ' KG'; // Add KG to tooltip
                    }
                }
            }
        }
    };
    new Chart(document.getElementById("productCategoriesPieChart"), {
        type: 'pie',
        data: productCategoriesData,
        options: productCategoriesOptions
    });

    // Grading Distribution Chart
    const gradingDistributionData = {
        labels: ['Grade A', 'Grade B', 'Grade C'],
        datasets: [{
            data: [1200, 1800, 900],
            backgroundColor: 'rgba(153, 102, 255, 0.5)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1
        }]
    };
    const gradingDistributionOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' KG'; // Add KG and comma formatting to x-axis values
                    }
                }
            },
            y: {
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' KG'; // Add KG and comma formatting to y-axis values
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.raw.toLocaleString() + ' KG'; // Add KG to tooltip
                    }
                }
            }
        }
    };
    new Chart(document.getElementById("gradingDistributionChart"), {
        type: 'horizontalBar',
        data: gradingDistributionData,
        options: gradingDistributionOptions
    });
</script>
@endpush
