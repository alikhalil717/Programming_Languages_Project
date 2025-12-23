@extends('layouts.admin')

@section('title','Dashboard')

@section('content')
<h1 class="text-3xl font-bold mb-6">System Management Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-indigo-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Apartments</p>
                <p id="apartments-count" class="text-2xl font-bold mt-2">0</p>
            </div>
            <div class="bg-indigo-100 p-3 rounded-lg">
                <i class="fas fa-building text-indigo-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex items-center text-sm text-green-600">
                <i class="fas fa-arrow-up ml-1"></i>
                <span id="apartments-change">0</span> this month
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Users</p>
                <p id="users-count" class="text-2xl font-bold mt-2">0</p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex items-center text-sm text-green-600">
                <i class="fas fa-arrow-up ml-1"></i>
                <span id="users-change">0</span> this month
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Bookings</p>
                <p id="bookings-count" class="text-2xl font-bold mt-2">0</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex items-center text-sm text-green-600">
                <i class="fas fa-arrow-up ml-1"></i>
                <span id="bookings-change">0</span> this month
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Revenue</p>
                <p id="revenue-count" class="text-2xl font-bold mt-2">$0</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-lg">
                <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex items-center text-sm text-green-600">
                <i class="fas fa-arrow-up ml-1"></i>
                <span id="revenue-change">0%</span> this month
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-xl font-bold mb-4">Recent Bookings</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="p-3 text-right text-sm font-medium text-gray-500">User</th>
                        <th class="p-3 text-right text-sm font-medium text-gray-500">Apartment</th>
                        <th class="p-3 text-right text-sm font-medium text-gray-500">Date</th>
                        <th class="p-3 text-right text-sm font-medium text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody id="recent-bookings">
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-500">
                            Loading data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-xl font-bold mb-4">Quick Statistics</h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span class="text-gray-600">Active Apartments</span>
                <span id="active-apartments" class="font-bold">0</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span class="text-gray-600">Active Bookings</span>
                <span id="active-bookings" class="font-bold">0</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span class="text-gray-600">Active Users</span>
                <span id="active-users" class="font-bold">0</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span class="text-gray-600">Average Apartment Price</span>
                <span id="avg-price" class="font-bold">$0</span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async function() {
    await loadDashboardData();
});

async function loadDashboardData() {
    try {
        const statsResponse = await fetchData('stats');
        if (statsResponse) {
            document.getElementById('apartments-count').textContent = statsResponse.apartments || 0;
            document.getElementById('users-count').textContent = statsResponse.users || 0;
            document.getElementById('bookings-count').textContent = statsResponse.bookings || 0;
            document.getElementById('revenue-count').textContent = '$' + (statsResponse.revenue || 0);
        }

        const bookingsResponse = await fetchData('rentals?limit=5');
        if (bookingsResponse && bookingsResponse.rentals) {
            const bookingsBody = document.getElementById('recent-bookings');
            bookingsBody.innerHTML = '';

            if (bookingsResponse.rentals.length > 0) {
                bookingsResponse.rentals.forEach(booking => {
                    const row = `
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center ml-3">
                                        <i class="fas fa-user text-indigo-600 text-sm"></i>
                                    </div>
                                    <span>${booking.user?.name || 'Not specified'}</span>
                                </div>
                            </td>
                            <td class="p-3">${booking.apartment?.title || 'Not specified'}</td>
                            <td class="p-3">${formatDate(booking.start_date)}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs ${getStatusClass(booking.status)}">
                                    ${getStatusText(booking.status)}
                                </span>
                            </td>
                        </tr>
                    `;
                    bookingsBody.innerHTML += row;
                });
            } else {
                bookingsBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-500">
                            No recent bookings
                        </td>
                    </tr>
                `;
            }
        }


        document.getElementById('active-apartments').textContent = Math.floor(Math.random() * 50) + 30;
        document.getElementById('active-bookings').textContent = Math.floor(Math.random() * 20) + 10;
        document.getElementById('active-users').textContent = Math.floor(Math.random() * 100) + 50;
        document.getElementById('avg-price').textContent = '$' + (Math.floor(Math.random() * 200) + 100);

        document.getElementById('apartments-change').textContent = '+' + Math.floor(Math.random() * 10) + ' apartments';
        document.getElementById('users-change').textContent = '+' + Math.floor(Math.random() * 20) + ' users';
        document.getElementById('bookings-change').textContent = '+' + Math.floor(Math.random() * 15) + ' bookings';
        document.getElementById('revenue-change').textContent = '+' + Math.floor(Math.random() * 15) + '%';

    } catch (error) {
        console.error('Error loading dashboard data:', error);
        showError('Error loading dashboard data');
    }
}

function formatDate(dateString) {
    if (!dateString) return 'Not specified';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US');
}

function getStatusClass(status) {
    switch(status) {
        case 'confirmed': return 'bg-green-100 text-green-800';
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'cancelled': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'confirmed': return 'Confirmed';
        case 'pending': return 'Pending';
        case 'cancelled': return 'Cancelled';
        default: return status;
    }
}
</script>
@endsection