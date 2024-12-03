@extends('layouts.app')

@push('plugin-styles')
@endpush

@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dummy Data for Charts
        var collectionData = [
            { x: '2024-01-01', y: 500 },
            { x: '2024-02-01', y: 700 },
            { x: '2024-03-01', y: 600 },
            { x: '2024-04-01', y: 800 }
        ];

        var collectionByCooperative = [
            { label: 'Cooperative A', data: [500, 700, 600, 800], color: '#2dce89' },  // Green for Cooperative A
            { label: 'Cooperative B', data: [400, 600, 500, 700], color: '#17a2b8' },  // Blue for Cooperative B
            { label: 'Cooperative C', data: [350, 550, 450, 650], color: '#f4c542' }   // Yellow for Cooperative C
        ];

        var collectionsGradeDistribution = {
            'A': 10,
            'B': 8,
            'C': 5
        };

        // 1. Line Chart: Collection Quantity Over Time
        var ctx1 = document.getElementById('collectionQuantityChart').getContext('2d');
        var gradient = ctx1.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(244, 216, 240, 1)');
        gradient.addColorStop(0.5, 'rgba(244, 216, 240, 0.5)');
        gradient.addColorStop(1, 'rgba(244, 216, 240, 0.1)');

        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: collectionData.map(item => item.x),
                datasets: [{
                    label: 'Collection Quantity',
                    data: collectionData.map(item => item.y),
                    borderColor: '#2dce89',  // Custom green line color
                    backgroundColor: gradient,
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // 2. Bar Chart: Collection Quantity per Cooperative
        var ctx2 = document.getElementById('collectionsPerCooperativeChart').getContext('2d');
        var datasets = collectionByCooperative.map(cooperative => ({
            label: cooperative.label,
            data: cooperative.data,
            backgroundColor: cooperative.color,
            borderColor: cooperative.color,
            borderWidth: 1
        }));

        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: collectionData.map(item => item.x),  // Use the same date labels as collectionData
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                legend: {
                    display: true
                }
            }
        });

        // 3. Bar Graph: Collections Grade Distribution
        var ctx3 = document.getElementById('collectionsGradeDistributionChart').getContext('2d');
        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: Object.keys(collectionsGradeDistribution),
                datasets: [{
                    label: 'Grade Distribution',
                    data: Object.values(collectionsGradeDistribution),
                    backgroundColor: ['#2dce89', '#17a2b8', '#f4c542'],  // Different shades for each grade
                    borderColor: ['#2dce89', '#17a2b8', '#f4c542'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush

@section('content')
    <div class="header bg-custom-green pb-8 pt-5 pt-md-8 ">
    <div class="row ">
        <!-- Card 1: Total Money Owed -->
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0 ml-8">
                <div class="card-body">
                    <div class="row">
                        <div class="col-auto">
                            <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h5 class="text-muted mb-0">Total Money Owed</h5>
                            <span class="h2 font-weight-bold mb-0">$10,000</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Money Paid -->
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="fas fa-credit-card"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h5 class="text-muted mb-0">Total Money Paid</h5>
                            <span class="h2 font-weight-bold mb-0">$7,500</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Number of Collections -->
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-archive"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h5 class="text-muted mb-0">Number of Collections</h5>
                            <span class="h2 font-weight-bold mb-0">120</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Row for Graphs -->
    <div class="row mt-5">
        <!-- Collection Quantity Over Time (Line Graph) -->
        <div class="col-xl-6 col-lg-12">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <h5 class="text-center">Collection Quantity Over Time</h5>
                </div>
                <div class="card-body">
                    <canvas id="collectionQuantityChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Collection Quantity per Cooperative (Bar Graph) -->
        <div class="col-xl-6 col-lg-12">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <h5 class="text-center">Collection Quantity per Cooperative</h5>
                </div>
                <div class="card-body">
                    <canvas id="collectionsPerCooperativeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Grade Distribution (Bar Graph) -->
    <div class="row mt-5">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <h5 class="text-center">Collections Grade Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="collectionsGradeDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
