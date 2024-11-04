@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Hero Section -->
    <div class="hero-section bg-custom-green">
        <div class="hero-content">
            <h1 class="branch-name">{{ $branch->name }}</h1>
            <div class="quick-stats">
                <div class="stat-item">
                    <i class="fas fa-users"></i>
                    <span class="stat-value">{{ $totalFarmers }}</span>
                    <span class="stat-label">Total Farmers</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-clipboard-list"></i>
                    <span class="stat-value">{{ count($collections) }}</span>
                    <span class="stat-label">Total Collections</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Collections Section -->
    <div class="collections-section">
        <div class="section-header">
            <h2><i class="fas fa-clipboard-list"></i> Collections for {{ $branch->name }}</h2>
            <p>Overview of collections made by farmers in this branch</p>
        </div>

        <div class="table-container">
            <table class="collections-table">
                <thead>
                    <tr>
                        <th>Collection Number</th>
                        <th>Farmer</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit</th> <!-- Add a header for the unit -->
                        <th>Collection Time</th>
                        <th>Date Collected</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collections as $collection)
                    <tr>
                        <td>{{ $collection->collection_number }}</td>
                        <td>{{ $collection->first_name }} {{ $collection->other_names }}</td>
                        <td>{{ $collection->product_name }}</td>
                        <td>{{ $collection->quantity }}</td>
                        <td>{{ $collection->unit }}</td> <!-- Display the unit of measurement -->
                        <td>{{ $collectionTimeLabels[$collection->collection_time] ?? 'N/A' }}</td>
                        <td>{{ $collection->date_collected }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.farmers.detail', $collection->farmer_id) }}"
                                    class="btn-action view">
                                    <!-- Use $collection->farmer_id -->
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn-action edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            <div class="empty-state-content">
                                <i class="fas fa-clipboard-list"></i>
                                <h3>No Collections Found</h3>
                                <p>No collections have been made in this branch yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="total-label">Total</td>
                        <td>
                            @php
                            // Calculate total quantity for the footer
                            $totalQuantity = $collections->sum('quantity');
                            $footerUnits = $collections->pluck('unit')->unique();
                            @endphp
                            @if($footerUnits->count() === 1)
                            {{ $totalQuantity }}
                            <!-- Display total quantity with unit if all are the same -->
                            @else
                            {{ $totalQuantity }} (Mixed Unit Measure)
                            <!-- Indicate mixed units -->
                            @endif
                        </td>
                        <td>{{ $footerUnits->count() === 1 ? $footerUnits->first() : 'Mixed' }}</td>
                        <td></td> <!-- Empty cell for collection time -->
                        <td></td> <!-- Empty cell for date collected -->
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
<style>
:root {
    --primary-color: #2563eb;
    --primary-dark: #1d4ed8;
    --secondary-color: #64748b;
    --success-color: #22c55e;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
    --background-color: #f1f5f9;
    --card-background: rgba(255, 255, 255, 0.9);
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --border-radius: 16px;
    --transition: all 0.3s ease;
}

.collections-table {
    width: 100%;
    border-collapse: collapse;
    /* Ensure borders are collapsed for continuity */
}

.collections-table th,
.collections-table td {
    border: 1px solid rgba(0, 0, 0, 0.1);
    /* Add faint borders to cells */
    padding: 1rem;
    /* Ensure padding is consistent */
    background-color: white;
    /* Ensure background color is consistent */
}

.collections-table th {
    background-color: rgba(0, 0, 0, 0.05);
    /* Optional: Slightly different background for headers */
}

/* Remove any margin or padding that could affect vertical continuity */
.collections-table tr {
    margin: 0;
    /* Ensure no margin on rows */
    padding: 0;
    /* Ensure no padding on rows */
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
}

.hero-content {
    position: relative;
    z-index: 1;
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

/* Collections Section */
.collections-section {
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

.collections-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 0.75rem;
}

.collections-table th {
    padding: 1rem;
    color: var(--text-secondary);
    font-weight: 600;
    text-align: left;
}

.collections-table td {
    padding: 1rem;
    background: white;
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

    .collections-table {
        display: block;
        overflow-x: auto;
    }
}

.btn-action {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
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
</style>