@extends('layouts.app')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@section('content')
<div class="dashboard-container">
    <!-- Hero Section -->
    <div class="hero-section bg-custom-green">
        <div class="hero-content">
            <div class="logo-placeholder">
                <i class="fas fa-industry"></i>
            </div>
            <h1 class="coop-name">{{ $miller->name }} ({{ $miller->abbreviation }})</h1>
            <div class="quick-stats">
                <div class="stat-item">
                    <i class="fas fa-envelope"></i>
                    <span class="stat-value">{{ $miller->email }}</span>
                    <span class="stat-label">Email</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-phone"></i>
                    <span class="stat-value">{{ $miller->phone_no }}</span>
                    <span class="stat-label">Phone</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-flag"></i>
                    <span class="stat-value">{{ $miller->country_code }}</span>
                    <span class="stat-label">Country Code</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Cards Section -->
    <div class="info-grid">
        <!-- Miller Contact Details Card -->
            <div class="card-glass">
                <div class="card-header">
                    <div class="header-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3>Contact Information</h3>
                </div>
                <div class="card-content">
                    <div class="detail-group">
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="detail-info">
                                <label>Email</label>
                                <span>{{ $miller->email }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="detail-info">
                                <label>Phone Number</label>
                                <span>{{ $miller->phone_no }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="detail-info">
                                <label>Address</label>
                                <span>{{ $miller->address }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-flag"></i>
                            </div>
                            <div class="detail-info">
                                <label>Country Code</label>
                                <span>{{ $miller->country_code }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- New Charts and Tables Section -->
 <!-- New Charts and Tables Section -->
 <div class="row ml-3">
     <!-- Total Miller Sales (Bar Chart) -->
     <div class="col-lg-6 mt-4">
         <div class="card p-4">
             <h3 class="chart-title">Total Miller Sales</h3>
             <canvas id="totalSalesChart"></canvas>
         </div>
     </div>

     <!-- Milled Quantity vs Premilled Quantity (Bar Chart) -->
     <div class="col-lg-6 mt-4">
         <div class="card p-4">
             <h3 class="chart-title">Milled Quantity vs Premilled Quantity</h3>
             <canvas id="milledVsPremilledChart"></canvas>
         </div>
     </div>

     <!-- Orders (Line Chart) -->
     <div class="col-lg-6 mt-4">
         <div class="card p-4">
             <h3 class="chart-title">Orders Over Time</h3>
             <canvas id="ordersChart"></canvas>
         </div>
     </div>

     <!-- Final Products (Pie Chart) -->
     <div class="col-lg-6 mt-4">
         <div class="card p-4" style="height:400px">
             <h3 class="chart-title">Final Products Distribution</h3>
             <canvas id="finalProductsChart"></canvas>
         </div>
     </div>
 </div>



        <!-- Payments Table -->
<div class="col-lg-12 mt-5 p-4 card">
    <h3>Payments Made</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Method</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>2024-06-11</td>
                <td>Ksh 56,000</td>
                <td style="background-color: #d4edda; color: #155724;">Completed</td>
                <td>Bank Transfer</td>
            </tr>
            <tr>
                <td>2024-05-11</td>
                <td>Ksh 38,300</td>
                <td style="background-color: #fff3cd; color: #856404;">Pending</td>
                <td>Credit Card</td>
            </tr>
            <tr>
                <td>2024-04-22</td>
                <td>Ksh 45,500</td>
                <td style="background-color: #d4edda; color: #155724;">Completed</td>
                <td>Mobile Payment</td>
            </tr>
            <tr>
                <td>2024-04-15</td>
                <td>Ksh 50,000</td>
                <td style="background-color: #d4edda; color: #155724;">Completed</td>
                <td>Cash</td>
            </tr>
            <tr>
                <td>2024-04-01</td>
                <td>Ksh 30,000</td>
                <td style="background-color: #f8d7da; color: #721c24;">Failed</td>
                <td>Credit Card</td>
            </tr>
            <tr>
                <td>2024-03-20</td>
                <td>Ksh 27,800</td>
                <td style="background-color: #d4edda; color: #155724;">Completed</td>
                <td>Bank Transfer</td>
            </tr>
            <tr>
                <td>2024-03-05</td>
                <td>Ksh 43,200</td>
                <td style="background-color: #fff3cd; color: #856404;">Pending</td>
                <td>Mobile Payment</td>
            </tr>
            <tr>
                <td>2024-02-28</td>
                <td>Ksh 29,000</td>
                <td style="background-color: #d4edda; color: #155724;">Completed</td>
                <td>Credit Card</td>
            </tr>
            <tr>
                <td>2024-02-10</td>
                <td>Ksh 55,400</td>
                <td style="background-color: #d4edda; color: #155724;">Completed</td>
                <td>Bank Transfer</td>
            </tr>
        </tbody>
    </table>
</div>
</div>
</div>

@endsection

@push('custom-scripts')
<script>
    // Total Miller Sales (Bar Chart)
    const totalSalesCtx = document.getElementById('totalSalesChart').getContext('2d');
    const totalSalesChart = new Chart(totalSalesCtx, {
        type: 'bar',
        data: {
            labels: ['August', 'September', 'October', 'November'],
            datasets: [{
                label: 'Total Sales (Kshs)',
                data: [12000, 15000, 11000, 13000],
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });

    // Milled Quantity vs Premilled Quantity (Bar Chart)
    const milledVsPremilledCtx = document.getElementById('milledVsPremilledChart').getContext('2d');
    const milledVsPremilledChart = new Chart(milledVsPremilledCtx, {
        type: 'bar',
        data: {
            labels: ['Coffee', 'Tea', 'Dairy'],
            datasets: [
                { label: 'Milled Quantity', data: [300, 500, 400], backgroundColor: 'rgba(153, 102, 255, 0.5)', borderColor: 'rgba(153, 102, 255, 1)', borderWidth: 1 },
                { label: 'Premilled Quantity', data: [200, 400, 300], backgroundColor: 'rgba(255, 159, 64, 0.5)', borderColor: 'rgba(255, 159, 64, 1)', borderWidth: 1 }
            ]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });

    // Orders (Line Chart)
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    const ordersChart = new Chart(ordersCtx, {
        type: 'line',
        data: {
            labels: ['May', 'June', 'July', 'August', 'September', 'October', 'November'],
            datasets: [{
                label: 'Orders',
                data: [10, 12, 15, 9, 17, 23, 14],
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });

    // Final Products (Pie Chart)
    const finalProductsCtx = document.getElementById('finalProductsChart').getContext('2d');
    const finalProductsChart = new Chart(finalProductsCtx, {
        type: 'pie',
        data: {
            labels: ['Tea', 'Coffee', 'Dairy'],
            datasets: [{
                data: [45, 25, 30],
                backgroundColor: ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(255, 206, 86, 0.5)'],
                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)'],
                borderWidth: 1
            }]
        },
        options: {
        responsive: true,
 }
    });
</script>
@endpush



<style>


.dashboard-container {
    padding: 2rem;
    background-color: var(--background-color);
    min-height: 100vh;
}

/* Hero Section */
.hero-section {
    /* background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); */
    border-radius: 30px;
    padding: 1rem;
    margin-bottom: 2rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('path/to/pattern.svg') center/cover;
    opacity: 0.1;
}

.hero-content {
    position: relative;
    z-index: 1;
    text-align: center;
}

.coop-logo {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid rgba(255, 255, 255, 0.2);
    margin-bottom: 1.5rem;
}

.logo-placeholder {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.logo-placeholder i {
    font-size: 2rem;
    color: rgba(255, 255, 255, 0.8);
}

.coop-name {
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

/* Info Cards */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.card-glass {
    margin-top: 10px;
    background: var(--card-background);
    backdrop-filter: blur(10px);
    border-radius: var(--border-radius);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: var(--transition);
}

.card-glass:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.card-header {
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.header-icon {
    width: 48px;
    height: 48px;
    background: var(--primary-color);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.header-icon.admin {
    background: var(--success-color);
}

.card-content {
    padding: 1.5rem;
}

.detail-group {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(0, 0, 0, 0.02);
    border-radius: 12px;
}

.detail-icon {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
}

.detail-info {
    flex: 1;
}

.detail-info label {
    display: block;
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin-bottom: 0.25rem;
}

.admin-profile {
    text-align: center;
    margin-bottom: 2rem;
}

.admin-avatar {
    width: 80px;
    height: 80px;
    background: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.5rem;
    font-weight: 600;
}

.admin-role {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: rgba(34, 197, 94, 0.1);
    color: var(--success-color);
    border-radius: 20px;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

/* Farmers Section */
.farmers-section {
    background: var(--card-background);
    border-radius: var(--border-radius);
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.header-left h2 {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.header-left p {
    color: var(--text-secondary);
}

.btn-add {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: var(--transition);
}

.btn-add:hover {
    background: var(--primary-dark);
}

/* Table Styles */
.farmers-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 0.75rem;
}

.farmers-table th {
    padding: 1rem;
    color: var(--text-secondary);
    font-weight: 600;
    text-align: left;
}

.farmers-table td {
    padding: 1rem;
    background: white;
}

.farmers-table tr td:first-child {
    border-radius: 10px 0 0 10px;
}

.farmers-table tr td:last-child {
    border-radius: 0 10px 10px 0;
}

.member-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.member-avatar {
    width: 30px;
    height: 30px;
    background: var(--primary-color);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
}

.member-details {
    display: flex;
    flex-direction: column;
}

.member-name {
    color: var(--text-primary);
    font-weight: 600;
}

.member-username {
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.member-number {
    font-family: monospace;
    background: rgba(0, 0, 0, 0.05);
    padding: 0.5rem 1rem;
    border-radius: 6px;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-badge.active {
    background: rgba(34, 197, 94, 0.1);
    color: var(--success-color);
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
}

.btn-action.view {
    background: rgba(37, 99, 235, 0.1);
    color: var(--primary-color);
}

.btn-action.edit {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning-color);
}

.btn-action.delete {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
}

.btn-action:hover {
    transform: translateY(-2px);
}

.empty-state {
    text-align: center;
    padding: 3rem !important;
}

.empty-state-content i {
    font-size: 3rem;
    color: var(--text-secondary);
    margin-bottom: 1rem;
}

.empty-state-content h3 {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.empty-state-content p {
    color: var(--text-secondary);
}

/* Responsive Design */
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

    .info-grid {
        grid-template-columns: 1fr;
    }

    .section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .farmers-table {
        display: block;
        overflow-x: auto;
    }
}
</style>
