@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Hero Section -->
    <div class="hero-section bg-custom-green">
        <div class="hero-content">
            @if($cooperative->logo)
                <img src="{{ $cooperative->logo }}" alt="Cooperative Logo" class="coop-logo">
            @else
                <div class="logo-placeholder">
                    <i class="fas fa-building"></i>
                </div>
            @endif
            <h1 class="coop-name">{{ $cooperative->name }}</h1>
            <div class="quick-stats">
                <div class="stat-item">
                    <i class="fas fa-users"></i>
                    <span class="stat-value">{{ count($farmers) }}</span>
                    <span class="stat-label">Farmers</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-map-marked-alt"></i>
                    <span class="stat-value">{{ $cooperative->country_code }}</span>
                    <span class="stat-label">Country</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Cards Section -->
    <div class="info-grid">
        <!-- Cooperative Details Card -->
        <div class="info-card">
            <div class="card-glass">
                <div class="card-header">
                    <div class="header-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3>Cooperative Details</h3>
                </div>
                <div class="card-content">
                    <div class="detail-group">
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="detail-info">
                                <label>Contact</label>
                                <span>{{ $cooperative->contact_details }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon">
                            <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div class="detail-info">
                                <label>Location</label>
                                <span>{{ $cooperative->location }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon">
                            <i class="fas fa-envelope"></i>
                            </div>
                            <div class="detail-info">
                                <label>Email</label>
                                <span>{{ $cooperative->email }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon">
                            <i class="fas fa-coins"></i>
                            </div>
                            <div class="detail-info">
                                <label>Currency</label>
                               <span>{{ strtoupper($cooperative->currency) }}</span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Details Card -->
        <div class="info-card">
            <div class="card-glass">
                <div class="card-header">
                    <div class="header-icon admin">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3>Administrator</h3>
                </div>
                <div class="card-content">
                    <div class="admin-profile">
                        <div class="admin-avatar">
                            <span>{{ substr($admin->first_name, 0, 1) }}{{ substr($admin->other_names, 0, 1) }}</span>
                        </div>
                        <h4>{{ $admin->first_name }} {{ $admin->other_names }}</h4>
                        <span class="admin-role">Cooperative Administrator</span>
                    </div>
                    <div class="detail-group">
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="detail-info">
                                <label>Email</label>
                                <span>{{ $admin->email }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="detail-info">
                                <label>Username</label>
                                <span>{{ $admin->username }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Farmers Section -->
    <div class="farmers-section">
        <div class="section-header">
            <div class="header-left">
                <h2><i class="fas fa-users"></i> Farmers Directory</h2>
                <p>Manage cooperative members and their information</p>
            </div>
            <!-- <div class="header-actions">
                <button class="btn-add">
                    <i class="fas fa-plus"></i>
                    <span>Add Farmer</span>
                </button>
            </div> -->
        </div>

        <div class="table-container">
            <table class="farmers-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Member</th>
                        <th>Member Number</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($farmers as $key => $farmer)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>
                            <div class="member-info">
                                <div class="member-avatar">
                                    {{ substr($farmer->first_name, 0, 1) }}
                                </div>
                                <div class="member-details">
                                    <span class="member-name">{{ $farmer->first_name }} {{ $farmer->other_names }}</span>
                                    <span class="member-username">{{ $farmer->username }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="member-number">{{ $farmer->member_no }}</span>
                        </td>
                        <td>
                            <span class="status-badge active">Active</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.farmers.detail', $farmer->id) }}" class="btn-action view">
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
                        <td colspan="5" class="empty-state">
                            <div class="empty-state-content">
                                <i class="fas fa-users-slash"></i>
                                <h3>No Farmers Found</h3>
                                <p>This cooperative doesn't have any registered farmers yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
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