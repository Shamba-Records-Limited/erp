@extends('layouts.app')

@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush

@section('content')


<!-- Charts Section -->

<div class="container-fluid mt-7">
    <div class="row">

        <!-- Milled  Chart -->
        <div class="col-xl-6">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <h2 class="mb-0">Milled Inventories</h2>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="milledQuantitiesChart" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
           <!-- PreMilled  Chart -->
           <div class="col-xl-6">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <h2 class="mb-0">PreMilled Inventories</h2>
                </div>
                <div class="card-body">
                    <div class="chart">
                    <canvas id="preMilledQuantitiesChart" class="chart-canvas"></canvas>
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
                    <h2 class="mb-0">Milled Grades Across Millers</h2>
                </div>
                <div class="card-body">
                    <div class="chart">
                      <canvas id="milledGradesChart" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('custom-scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Render MIlled hart
document.addEventListener('DOMContentLoaded', function () {
   const milledData = @json($data['milledData']);
// Extract labels (Miller Names) and data (Total Milled Quantities)
    const labels = milledData.map(item => item.miller_name);
     const data = milledData.map(item => item.total_milled);

    const ctx = document.getElementById('milledQuantitiesChart')?.getContext('2d');
    if (!ctx) {
        console.error('Canvas element with id "milledQuantitiesChart" not found.');
        return;
    }
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: milledData.map(item => item.miller_name),
            datasets: [{
                label: 'Total Milled Quantity',
                data: milledData.map(item => item.total_milled),
                backgroundColor: 'rgba(54, 162, 235, 0.6)', // Light blue bars
                borderColor: 'rgba(54, 162, 235, 1)', // Blue border
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Quantity (kg)'
                    }
                },
                x: {

                    title: {
                        display: true,
                        text: 'Millers'
                    }
                }
            }
        }
    });
});
//Reender Premilled
document.addEventListener('DOMContentLoaded', function () {
        // Data passed from the controller
        const premilledData = @json($data['premilledData']);
        // Extract labels (Miller Names) and data (Total Pre-Milled Quantities)
        const labels = premilledData.map(item => item.miller_name);
        const data = premilledData.map(item => item.total_quantity);
        // Render the chart
        const ctx = document.getElementById('preMilledQuantitiesChart')?.getContext('2d');
        if (!ctx) {
            console.error('Canvas element with id "preMilledQuantitiesChart" not found.');
            return;
        }
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Pre-Milled Quantity',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)', // Light green bars
                    borderColor: 'rgba(75, 192, 192, 1)', // Green border
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity (kg)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Millers'
                        }
                    }
                }
            }
        });
    });

    //Milled Grades Across millers
    document.addEventListener('DOMContentLoaded', function () {
    const milledInventories = @json($data['milledGradeInventories']);

    // Process data to format it for Chart.js
    const groupedData = {};
    const grades = new Set();

    milledInventories.forEach(item => {
        if (!groupedData[item.miller_name]) {
            groupedData[item.miller_name] = {};
        }
        groupedData[item.miller_name][item.grade_name] = item.total_quantity;
        grades.add(item.grade_name);
    });

    const labels = Object.keys(groupedData);
    const datasets = Array.from(grades).map(grade => ({
        label: grade,
        data: labels.map(label => groupedData[label][grade] || 0),
        backgroundColor: getRandomColor(), // Function to generate random colors
        borderWidth: 1
    }));

    // Generate random colors for each grade
    function getRandomColor() {
        const r = Math.floor(Math.random() * 255);
        const g = Math.floor(Math.random() * 255);
        const b = Math.floor(Math.random() * 255);
        return `rgba(${r}, ${g}, ${b}, 0.6)`;
    }

    // Render the chart
    const ctx = document.getElementById('milledGradesChart')?.getContext('2d');
    if (!ctx) {
        console.error('Canvas element with id "milledGradesChart" not found.');
        return;
    }

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                x: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Millers'
                    }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Milled Quantity (kg)'
                    }
                }
            }
        }
    });
});


 </script>
@endpush
