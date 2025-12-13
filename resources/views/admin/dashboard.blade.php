@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-12">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3 id="totalUsers">0</h3>
                    <p>Total Users</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-info">
                    <h3 id="totalApartments">0</h3>
                    <p>Total Apartments</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h3 id="totalRentals">0</h3>
                    <p>Total Rentals</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-info">
                    <h3 id="totalRevenue">$0</h3>
                    <p>Total Revenue</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Charts -->
    <div class="col-lg-8">
        <div class="chart-container">
            <h3>Monthly Revenue</h3>
            <canvas id="revenueChart" height="250"></canvas>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="chart-container">
            <h3>User Status</h3>
            <canvas id="userStatusChart" height="250"></canvas>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Recent Activities -->
    <div class="col-lg-6">
        <div class="data-table">
            <div class="table-header">
                <h4 class="mb-0">Recent Activities</h4>
                <a href='admin.activities' class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>User</th>
                            <th>Activity</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="recentActivities">
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <div class="loading-spinner"></div> Loading activities...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Pending Approvals -->
    <div class="col-lg-6">
        <div class="data-table">
            <div class="table-header">
                <h4 class="mb-0">Pending Approvals</h4>
                <div>
                    <a href='admin.users' class="btn btn-sm btn-outline-warning me-2">
                        Users: <span id="pendingUsersBadge" class="badge bg-warning">0</span>
                    </a>
                    <a href='admin.rentals?status=pending' class="btn btn-sm btn-outline-warning">
                        Rentals: <span id="pendingRentalsBadge" class="badge bg-warning">0</span>
                    </a>
                </div>
            </div>
            <div class="table-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Details</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="pendingApprovals">
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <div class="loading-spinner"></div> Loading pending approvals...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="data-table">
            <div class="table-header">
                <h4 class="mb-0">Quick Actions</h4>
            </div>
            <div class="table-body p-4">
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <a href='admin.users.create' class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Add User
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href='admin.apartments.create' class="btn btn-success w-100">
                            <i class="fas fa-building me-2"></i>Add Apartment
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href='admin.reports' class="btn btn-info w-100">
                            <i class="fas fa-chart-bar me-2"></i>Generate Report
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href='admin.settings' class="btn btn-warning w-100">
                            <i class="fas fa-cog me-2"></i>System Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Load dashboard data
    function loadDashboardData() {
        $.ajax({
            url: '/api/admin/dashboard-stats',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    
                    // Update stats
                    $('#totalUsers').text(data.total_users || 0);
                    $('#totalApartments').text(data.total_apartments || 0);
                    $('#totalRentals').text(data.total_rentals || 0);
                    $('#totalRevenue').text('$' + (data.total_revenue || 0).toLocaleString());
                    
                    $('#pendingUsersBadge').text(data.pending_users || 0);
                    $('#pendingRentalsBadge').text(data.pending_rentals || 0);
                    
                    // Update charts
                    updateRevenueChart(data.revenue_data);
                    updateUserStatusChart(data.user_status_data);
                    
                    // Update recent activities
                    updateRecentActivities(data.recent_activities);
                    
                    // Update pending approvals
                    updatePendingApprovals(data.pending_approvals);
                }
            },
            error: function() {
                console.error('Failed to load dashboard data');
            }
        });
    }
    
    // Revenue Chart
    let revenueChart = null;
    function updateRevenueChart(data) {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        if (revenueChart) {
            revenueChart.destroy();
        }
        
        revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: data.values || [0, 0, 0, 0, 0, 0],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // User Status Chart
    let userStatusChart = null;
    function updateUserStatusChart(data) {
        const ctx = document.getElementById('userStatusChart').getContext('2d');
        
        if (userStatusChart) {
            userStatusChart.destroy();
        }
        
        userStatusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels || ['Active', 'Pending', 'Inactive'],
                datasets: [{
                    data: data.values || [0, 0, 0],
                    backgroundColor: [
                        '#43e97b',
                        '#ffd93d',
                        '#ff6b6b'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    
    // Update recent activities
    function updateRecentActivities(activities) {
        const tbody = $('#recentActivities');
        tbody.empty();
        
        if (!activities || activities.length === 0) {
            tbody.html('<tr><td colspan="4" class="text-center py-4">No recent activities</td></tr>');
            return;
        }
        
        activities.forEach(activity => {
            const timeAgo = new Date(activity.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            tbody.append(`
                <tr>
                    <td>${timeAgo}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" 
                                 style="width: 30px; height: 30px;">
                                <i class="fas fa-user text-white" style="font-size: 14px;"></i>
                            </div>
                            ${activity.user_name || 'Unknown'}
                        </div>
                    </td>
                    <td>${activity.description || 'No description'}</td>
                    <td><span class="badge badge-${activity.status || 'pending'}">${activity.status || 'Pending'}</span></td>
                </tr>
            `);
        });
    }
    
    // Update pending approvals
    function updatePendingApprovals(approvals) {
        const tbody = $('#pendingApprovals');
        tbody.empty();
        
        if (!approvals || approvals.length === 0) {
            tbody.html('<tr><td colspan="4" class="text-center py-4">No pending approvals</td></tr>');
            return;
        }
        
        approvals.forEach(approval => {
            const date = new Date(approval.created_at).toLocaleDateString();
            
            tbody.append(`
                <tr>
                    <td><span class="badge bg-${approval.type === 'user' ? 'primary' : 'info'}">${approval.type}</span></td>
                    <td>
                        <strong>${approval.name || 'Unknown'}</strong><br>
                        <small class="text-muted">${approval.email || approval.apartment || ''}</small>
                    </td>
                    <td>${date}</td>
                    <td>
                        <button class="btn btn-sm btn-success" onclick="approveItem('${approval.type}', ${approval.id})">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="rejectItem('${approval.type}', ${approval.id})">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }
    
    // Approve item
    function approveItem(type, id) {
        if (!confirm('Are you sure you want to approve this item?')) return;
        
        const endpoint = type === 'user' ? `/api/admin/users/${id}/approve` : `/api/admin/rentals/${id}/confirm`;
        
        $.ajax({
            url: endpoint,
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    alert('Item approved successfully');
                    loadDashboardData();
                } else {
                    alert('Failed to approve item: ' + (response.message || 'Unknown error'));
                }
            },
            error: function() {
                alert('Failed to approve item. Please try again.');
            }
        });
    }
    
    // Reject item
    function rejectItem(type, id) {
        if (!confirm('Are you sure you want to reject this item?')) return;
        
        const endpoint = type === 'user' ? `/api/admin/users/${id}/reject` : `/api/admin/rentals/${id}/cancel`;
        
        $.ajax({
            url: endpoint,
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    alert('Item rejected successfully');
                    loadDashboardData();
                } else {
                    alert('Failed to reject item: ' + (response.message || 'Unknown error'));
                }
            },
            error: function() {
                alert('Failed to reject item. Please try again.');
            }
        });
    }
    
    // Load data on page load
    $(document).ready(function() {
        loadDashboardData();
        
        // Refresh data every 60 seconds
        setInterval(loadDashboardData, 60000);
    });
</script>
@endpush
@endsection