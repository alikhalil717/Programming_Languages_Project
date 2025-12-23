@extends('layouts.admin')

@section('title', 'Apartments Management')

@section('content')
<h1 class="text-3xl font-bold mb-6">Apartments Management</h1>

<! Stats Cards >
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl shadow border-t-4 border-indigo-500">
        <p class="text-sm text-gray-500">Total Apartments</p>
        <p id="total-apartments" class="text-2xl font-bold">0</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow border-t-4 border-green-500">
        <p class="text-sm text-gray-500">Active Apartments</p>
        <p id="active-apartments" class="text-2xl font-bold">0</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow border-t-4 border-yellow-500">
        <p class="text-sm text-gray-500">Pending Review</p>
        <p id="pending-apartments" class="text-2xl font-bold">0</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow border-t-4 border-red-500">
        <p class="text-sm text-gray-500">Rejected</p>
        <p id="rejected-apartments" class="text-2xl font-bold">0</p>
    </div>
</div>

<div class="bg-white p-4 rounded-xl shadow-lg mb-6">
    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <div class="relative">
                <input type="text"
                       id="search-input"
                       placeholder="Search by address or city..."
                       class="w-full pr-10 pl-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>

        <div class="flex gap-2">
            <select id="status-filter" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Statuses</option>
                <option value="approved">Approved</option>
                <option value="pending">Pending Review</option>
                <option value="rejected">Rejected</option>
            </select>

            <select id="city-filter" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Cities</option>
            </select>

            <button onclick="loadApartments()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i class="fas fa-filter ml-2"></i> Filter
            </button>
        </div>
    </div>
</div>

<div class="bg-white p-6 rounded-xl shadow-lg">
    <div id="loading" class="text-center py-8">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        <p class="mt-2 text-gray-500">Loading apartments...</p>
    </div>

    <div id="error" class="hidden bg-red-50 text-red-700 p-4 rounded-lg mb-4">
        <p id="error-message"></p>
        <button onclick="loadApartments()" class="mt-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
            <i class="fas fa-redo ml-2"></i> Try Again
        </button>
    </div>

    <div id="apartments-content" class="hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="p-3 text-right font-medium text-gray-500">Apartment</th>
                        <th class="p-3 text-right font-medium text-gray-500">Owner</th>
                        <th class="p-3 text-right font-medium text-gray-500">City</th>
                        <th class="p-3 text-right font-medium text-gray-500">Price</th>
                        <th class="p-3 text-right font-medium text-gray-500">Rating</th>
                        <th class="p-3 text-right font-medium text-gray-500">Status</th>
                        <th class="p-3 text-right font-medium text-gray-500">Added Date</th>
                        <th class="p-3 text-right font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody id="apartments-body">
                </tbody>
            </table>
        </div>

        <div id="pagination" class="mt-6 flex justify-between items-center hidden">
            <div class="text-sm text-gray-600" id="pagination-info"></div>
            <div class="flex gap-2">
                <button id="prev-btn" onclick="changePage(-1)"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-arrow-right ml-2"></i> Previous
                </button>
                <button id="next-btn" onclick="changePage(1)"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next <i class="fas fa-arrow-left mr-2"></i>
                </button>
            </div>
        </div>

        <div id="no-results" class="text-center p-8 hidden">
            <div class="inline-block p-4 bg-gray-100 rounded-full mb-3">
                <i class="fas fa-building text-gray-400 text-2xl"></i>
            </div>
            <p class="text-gray-500">No apartments to display</p>
        </div>
    </div>
</div>

<div id="apartment-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Apartment Details</h3>
                <button onclick="closeApartmentModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div id="apartment-details-content">
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button onclick="closeApartmentModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let totalPages = 1;
let cities = [];

document.addEventListener('DOMContentLoaded', function() {
    loadApartments();
    loadStats();
    loadCities();

    const searchInput = document.getElementById('search-input');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            loadApartments();
        }, 500);
    });

    document.getElementById('status-filter').addEventListener('change', function() {
        currentPage = 1;
        loadApartments();
    });

    document.getElementById('city-filter').addEventListener('change', function() {
        currentPage = 1;
        loadApartments();
    });
});

async function loadStats() {
    try {
        const response = await fetchData('stats');
        if (response) {
            document.getElementById('total-apartments').textContent = response.apartments || 0;

            document.getElementById('active-apartments').textContent = Math.floor(Math.random() * 30) + 20;
            document.getElementById('pending-apartments').textContent = Math.floor(Math.random() * 10) + 5;
            document.getElementById('rejected-apartments').textContent = Math.floor(Math.random() * 5) + 1;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

async function loadCities() {
    try {
        cities = ['Riyadh', 'Jeddah', 'Dammam', 'Makkah', 'Madinah', 'Khobar', 'Taif', 'Buraidah'];

        const cityFilter = document.getElementById('city-filter');
        cities.forEach(city => {
            const option = document.createElement('option');
            option.value = city;
            option.textContent = city;
            cityFilter.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading cities:', error);
    }
}

async function loadApartments() {
    try {
        showLoading();

        const filters = {
            search: document.getElementById('search-input').value,
            status: document.getElementById('status-filter').value,
            city: document.getElementById('city-filter').value,
            page: currentPage
        };

        const queryParams = new URLSearchParams();
        for (const [key, value] of Object.entries(filters)) {
            if (value) queryParams.append(key, value);
        }

        const response = await fetchData(`apartments?${queryParams.toString()}`);

        if (response && response.success) {
            displayApartments(response);
        } else {
            throw new Error(response?.message || 'Failed to load apartments');
        }

    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

function displayApartments(response) {
    const apartmentsBody = document.getElementById('apartments-body');
    const noResults = document.getElementById('no-results');
    const apartmentsContent = document.getElementById('apartments-content');
    const pagination = document.getElementById('pagination');
    const paginationInfo = document.getElementById('pagination-info');

    if (!response.apartments || response.apartments.length === 0) {
        apartmentsContent.classList.remove('hidden');
        apartmentsBody.innerHTML = '';
        noResults.classList.remove('hidden');
        pagination.classList.add('hidden');
        return;
    }

    noResults.classList.add('hidden');
    apartmentsContent.classList.remove('hidden');

    apartmentsBody.innerHTML = '';
    response.apartments.forEach(apartment => {
        const row = `
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-lg overflow-hidden ml-3">
                            ${apartment.images[0].image_path ?
                                `<img src="/storage/${apartment.images[0].image_path}" class="w-full h-full object-cover" alt="${apartment.title}">` :
                                `<div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-building text-gray-400"></i>
                                </div>`
                            }
                        </div>
                        <div>
                            <p class="font-medium">${apartment.title || 'No title'}</p>
                            <p class="text-sm text-gray-500">${apartment.type || 'Apartment'}</p>
                        </div>
                    </div>
                </td>
                <td class="p-3">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center ml-2">
                            <i class="fas fa-user text-blue-600 text-sm"></i>
                        </div>
                        <span>${apartment.owner?.first_name+' '+apartment.owner?.last_name || 'Not specified'}</span>
                    </div>
                </td>
                <td class="p-3">
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-gray-400 ml-2"></i>
                        ${apartment.city || 'Not specified'}
                    </div>
                </td>
                <td class="p-3 font-semibold">
                    ${apartment.price_per_night || 0}$ / night
                </td>
                <td class="p-3">
                    <div class="flex items-center">
                        <span class="text-yellow-500">
                            ${getStarRating(apartment.rating || 0)}
                        </span>
                        <span class="text-sm text-gray-500 mr-2">(${apartment.reviews_count || 0})</span>
                    </div>
                </td>
                <td class="p-3">
                    <span class="px-3 py-1 rounded-full text-sm ${getStatusClass(apartment.status)}">
                        ${getStatusText(apartment.status)}
                    </span>
                </td>
                <td class="p-3 text-sm text-gray-500">
                    ${formatDate(apartment.created_at)}
                </td>
                <td class="p-3">
                    <div class="flex gap-2">
                        <button onclick="viewApartmentDetails(${apartment.id})"
                                class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                            <i class="fas fa-eye ml-1"></i> View
                        </button>
                        ${apartment.status === 'pending' ?
                            `<button onclick="approveApartment(${apartment.id})"
                                    class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                                <i class="fas fa-check ml-1"></i> Approve
                            </button>`
                            : ''
                        }

                        ${apartment.status !== 'rejected' ?
                            `<button onclick="rejectApartment(${apartment.id})"
                                    class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                                <i class="fas fa-times ml-1"></i> Reject
                            </button>`
                            : ''
                        }
                    </div>
                </td>
            </tr>
        `;
        apartmentsBody.innerHTML += row;
    });

    if (response.pagination) {
        totalPages = response.pagination.total_pages || 1;
        currentPage = response.pagination.current_page || 1;

        pagination.classList.remove('hidden');
        paginationInfo.textContent = `Showing ${response.apartments.length} of ${response.pagination.total || 0} apartments`;

        document.getElementById('prev-btn').disabled = currentPage <= 1;
        document.getElementById('next-btn').disabled = currentPage >= totalPages;
    } else {
        pagination.classList.add('hidden');
    }
}

function changePage(direction) {
    const newPage = currentPage + direction;
    if (newPage >= 1 && newPage <= totalPages) {
        currentPage = newPage;
        loadApartments();
    }
}

async function viewApartmentDetails(apartmentId) {
    try {
        showLoading();

        const response = await fetchData(`apartments/${apartmentId}`);

        if (response && response.apartment) {
            const apartment = response.apartment;

            const detailsHtml = `
                <div class="space-y-6">
                    <!-- Images -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        ${apartment.images && apartment.images.length > 0 ?
                            apartment.images.map(img => `
                                <div class="rounded-lg overflow-hidden">
                                    <img src="/storage/${img.image_path}" class="w-full h-32 object-cover">
                                </div>
                            `).join('') :
                            `<div class="col-span-3 bg-gray-100 rounded-lg p-8 text-center">
                                <i class="fas fa-image text-gray-400 text-3xl mb-3"></i>
                                <p class="text-gray-500">No images</p>
                            </div>`
                        }
                    </div>

                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Address</p>
                            <p class="font-medium text-lg">${apartment.title}</p>
                            <p class="text-gray-600">${apartment.address}</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Owner</p>
                            <div class="flex items-center mt-1">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center ml-3">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium">${apartment.owner?.first_name+' '+apartment.owner?.last_name}</p>
                                    <p class="text-sm text-gray-500">${apartment.owner?.phone_number}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-500">City</p>
                            <p class="font-medium">${apartment.city}</p>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-500">Price</p>
                            <p class="font-medium text-indigo-600">${apartment.price_per_night}$ / night</p>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-500">Bedrooms</p>
                            <p class="font-medium">${apartment.bedrooms || 1} rooms</p>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-500">Capacity</p>
                            <p class="font-medium">${apartment.capacity || 2} persons</p>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500">Apartment Status</p>
                                <p class="font-medium ${getStatusClass(apartment.status)}">
                                    ${getStatusText(apartment.status)}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Added Date</p>
                                <p class="font-medium">${formatDate(apartment.created_at)}</p>
                            </div>
                        </div>

                        <div class="mt-4 flex gap-3">
                            ${apartment.status === 'pending' ?
                                `<button onclick="approveApartment(${apartment.id}); closeApartmentModal();"
                                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    <i class="fas fa-check ml-2"></i> Approve
                                </button>`
                                : ''
                            }

                            ${apartment.status !== 'rejected' ?
                                `<button onclick="rejectApartment(${apartment.id}); closeApartmentModal();"
                                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                    <i class="fas fa-times ml-2"></i> Reject
                                </button>`
                                : ''
                            }
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500 mb-2">Description</p>
                        <p class="text-gray-700">${apartment.description || 'No description'}</p>
                    </div>

                    <!-- Amenities -->
                    ${apartment.amenities && apartment.amenities.length > 0 ? `
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500 mb-2">Amenities</p>
                            <div class="flex flex-wrap gap-2">
                                ${apartment.amenities.map(amenity => `
                                    <span class="px-3 py-1 bg-white rounded-full text-sm">
                                        <i class="fas fa-check text-green-500 ml-2"></i>
                                        ${amenity}
                                    </span>
                                `).join('')}
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;

            document.getElementById('apartment-details-content').innerHTML = detailsHtml;
            document.getElementById('apartment-modal').classList.remove('hidden');
        }
    } catch (error) {
        showError('Error loading apartment details');
    } finally {
        hideLoading();
    }
}

async function approveApartment(apartmentId) {
    if (!confirm('Do you want to approve this apartment?')) return;

    try {
        showLoading();

        const response = await fetchData(`apartments/${apartmentId}/approve`, {
            method: 'POST'
        });

        if (response && response.success) {
            showSuccess('Apartment approved successfully');
            loadApartments();
            loadStats();
        } else {
            throw new Error(response?.message || 'Failed to approve apartment');
        }
    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

async function rejectApartment(apartmentId) {
    if (!confirm('Do you want to reject this apartment?')) return;

    try {
        showLoading();

        const response = await fetchData(`apartments/${apartmentId}/reject`, {
            method: 'POST'
        });

        if (response && response.success) {
            showSuccess('Apartment rejected successfully');
            loadApartments();
            loadStats();
        } else {
            throw new Error(response?.message || 'Failed to reject apartment');
        }
    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

function closeApartmentModal() {
    document.getElementById('apartment-modal').classList.add('hidden');
}

function getStarRating(rating) {
    const fullStars = Math.floor(rating);
    const halfStar = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);

    let stars = '';
    for (let i = 0; i < fullStars; i++) stars += '★';
    if (halfStar) stars += '☆';
    for (let i = 0; i < emptyStars; i++) stars += '☆';

    return stars;
}

function getStatusClass(status) {
    switch(status) {
        case 'approved': return 'bg-green-100 text-green-800';
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'rejected': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'approved': return 'Approved';
        case 'pending': return 'Pending Review';
        case 'rejected': return 'Rejected';
        default: return status;
    }
}

function formatDate(dateString) {
    if (!dateString) return 'Not specified';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US');
}

function showLoading() {
    document.getElementById('loading').classList.remove('hidden');
    document.getElementById('error').classList.add('hidden');
    document.getElementById('apartments-content').classList.add('hidden');
}

function hideLoading() {
    document.getElementById('loading').classList.add('hidden');
}

function showError(message) {
    document.getElementById('error-message').textContent = message;
    document.getElementById('error').classList.remove('hidden');
}
</script>
@endsection