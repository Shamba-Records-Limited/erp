@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Hero Section -->
    <div class="hero-section bg-custom-green">
        <div class="hero-content">
            <h1 class="branch-name">{{ $branch->name }}</h1>
            <div class="quick-stats">
                <div class="stat-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span class="stat-value">{{ $branch->location }}</span>
                    <span class="stat-label">Location</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-home"></i>
                    <span class="stat-value">{{ $branch->address }}</span>
                    <span class="stat-label">Address</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-clock"></i>
                    <span class="stat-value">{{ $branch->created_at->format('d M Y') }}</span>
                    <span class="stat-label">Opened On</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Miller Branch Details Section -->
    <div class="details-section">
        <div class="section-header">
            <h2><i class="fas fa-info-circle"></i> Details for {{ $branch->name }}</h2>
            <p>Overview of the branch and its related information.</p>
        </div>

        <div class="table-container">
            <table class="details-table">
                <tbody>
                    <tr>
                        <th>Miller</th>
                        <td>{{ $branch->miller->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>County</th>
                        <td>{{ $branch->county->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Sub-County</th>
                        <td>{{ $branch->subCounty->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Code</th>
                        <td>{{ $branch->code ?? 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Additional Data Points Section -->
    <div class="row mt-5">
        <!-- Monthly Sales (Bar Chart) -->
        <div class="col-lg-6">
            <div class="card p-4">
            <h3>Monthly Sales (Kshs)</h3>
            <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>

        <!-- Product Distribution (Pie Chart) -->
        <div class="col-lg-6" >
            <div class="card p-4" style="height:400px">
            <h3>Product Distribution</h3>
            <canvas id="productDistributionChart" width="300" height="300"></canvas>
        </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="row mt-5 card p-4">
        <div class="col-lg-12">
            <h3>Recent Orders</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order Date</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2024-10-04</td>
                        <td>Coffee Beans</td>
                        <td>500 KG</td>
                        <td style="background-color: #d4edda; color: #155724;">Completed</td>
                    </tr>
                    <tr>
                        <td>2024-10-03</td>
                        <td>Tea Leaves</td>
                        <td>300 KG</td>
                        <td style="background-color: #fff3cd; color: #856404;">Pending</td>
                    </tr>
                    <tr>
                        <td>2024-09-28</td>
                        <td>Dairy Products</td>
                        <td>200 KG</td>
                        <td style="background-color: #f8d7da; color: #721c24;">Cancelled</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Sales (Bar Chart)
    const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
    const monthlySalesChart = new Chart(monthlySalesCtx, {
        type: 'bar',
        data: {
            labels: ['June', 'July', 'August', 'September', 'October', 'November'],
            datasets: [{
                label: 'Sales (Kshs)',
                data: [120000, 150000, 110000, 130000, 90000, 140000],
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });

    // Product Distribution (Pie Chart)
    const productDistributionCtx = document.getElementById('productDistributionChart').getContext('2d');
    const productDistributionChart = new Chart(productDistributionCtx, {
        type: 'pie',
        data: {
            labels: ['Coffee Beans', 'Tea Leaves', 'Dairy Products'],
            datasets: [{
                data: [45, 30, 25],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: { responsive: true }
    });
</script>
@endpush



<style>
:root {
    --primary-color: #2563eb;
    --secondary-color: #64748b;
    --success-color: #22c55e;
    --danger-color: #ef4444;
    --background-color: #f1f5f9;
    --card-background: rgba(255, 255, 255, 0.9);
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --border-radius: 16px;
    --transition: all 0.3s ease;
}

.dashboard-container {
    padding: 2rem;
    background-color: var(--background-color);
    min-height: 100vh;
}

/* Hero Section */
.hero-section {
    border-radius: 30px;
    padding: 1rem;
    margin-bottom: 2rem;
    color: white;
    position: relative;
    overflow: hidden;
    text-align: center;
}

.branch-name {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.quick-stats {
    display: flex;
    justify-content: center;
    gap: 2rem;
}

.stat-item {
    text-align: center;
    background: rgba(255, 255, 255, 0.1);
    padding: 1rem 2rem;
    border-radius: var(--border-radius);
    backdrop-filter: blur(10px);
}

.stat-item i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.8;
}

/* Details Section */
.details-section {
    background: var(--card-background);
    border-radius: var(--border-radius);
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.section-header {
    margin-bottom: 2rem;
}

.section-header h2 {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.table-container {
    overflow-x: auto;
}

.details-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.details-table th, .details-table td {
    padding: 1rem;
    text-align: left;
}

.details-table th {
    background-color: rgba(0, 0, 0, 0.05);
    font-weight: 600;
    color: var(--text-secondary);
}

.details-table td {
    color: var(--text-primary);
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem;
    }

    .hero-section {
        padding: 2rem 1rem;
    }

    .quick-stats {
        flex-direction: column;
        gap: 1rem;
    }

    .details-table {
        display: block;
        overflow-x: auto;
    }
}
</style>
