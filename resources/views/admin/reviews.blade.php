@extends('layouts.admin')

@section('title','Reviews')

@section('content')
<h1 class="text-3xl font-bold mb-6">Reviews Management</h1>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl shadow border-t-4 border-yellow-500">
        <p class="text-sm text-gray-500">Total Reviews</p>
        <p id="total-reviews" class="text-2xl font-bold">0</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow border-t-4 border-green-500">
        <p class="text-sm text-gray-500">Average Rating</p>
        <p id="average-rating" class="text-2xl font-bold">0.0</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow border-t-4 border-blue-500">
        <p class="text-sm text-gray-500">Users Reviewed</p>
        <p id="users-reviewed" class="text-2xl font-bold">0</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow border-t-4 border-purple-500">
        <p class="text-sm text-gray-500">Apartments Reviewed</p>
        <p id="apartments-reviewed" class="text-2xl font-bold">0</p>
    </div>
</div>

<!-- Search and Filters -->
<div class="bg-white p-4 rounded-xl shadow-lg mb-6">
    <div class="flex flex-col md:flex-row gap-4">
        <!-- Search -->
        <div class="flex-1">
            <div class="relative">
                <input type="text"
                       id="search-input"
                       placeholder="Search in reviews..."
                       class="w-full pr-10 pl-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex gap-2">
            <select id="rating-filter" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Ratings</option>
                <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                <option value="4">⭐⭐⭐⭐ (4)</option>
                <option value="3">⭐⭐⭐ (3)</option>
                <option value="2">⭐⭐ (2)</option>
                <option value="1">⭐ (1)</option>
            </select>

            <select id="status-filter" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Statuses</option>
                <option value="approved">Approved</option>
                <option value="pending">Pending Review</option>
                <option value="rejected">Rejected</option>
            </select>

            <button onclick="loadReviews()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i class="fas fa-filter ml-2"></i> Filter
            </button>
        </div>
    </div>
</div>

<!-- Reviews Table -->
<div class="bg-white p-6 rounded-xl shadow-lg">
    <!-- Loading -->
    <div id="loading" class="text-center py-8">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        <p class="mt-2 text-gray-500">Loading reviews...</p>
    </div>

    <!-- Error -->
    <div id="error" class="hidden bg-red-50 text-red-700 p-4 rounded-lg mb-4">
        <p id="error-message"></p>
        <button onclick="loadReviews()" class="mt-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
            <i class="fas fa-redo ml-2"></i> Try Again
        </button>
    </div>

    <!-- Reviews Table Content -->
    <div id="reviews-content" class="hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="p-3 text-right font-medium text-gray-500">User</th>
                        <th class="p-3 text-right font-medium text-gray-500">Apartment</th>
                        <th class="p-3 text-right font-medium text-gray-500">Rating</th>
                        <th class="p-3 text-right font-medium text-gray-500">Comment</th>
                        <th class="p-3 text-right font-medium text-gray-500">Status</th>
                        <th class="p-3 text-right font-medium text-gray-500">Date</th>
                        <th class="p-3 text-right font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody id="reviews-body">
                    <!-- Will be filled with data -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
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

        <!-- No Results -->
        <div id="no-results" class="text-center p-8 hidden">
            <div class="inline-block p-4 bg-gray-100 rounded-full mb-3">
                <i class="fas fa-star text-gray-400 text-2xl"></i>
            </div>
            <p class="text-gray-500">No reviews to display</p>
        </div>
    </div>
</div>

<!-- Review Details Modal -->
<div id="review-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Review Details</h3>
                <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div id="review-details-content">
                <!-- Will be filled with data -->
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button onclick="closeReviewModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let totalPages = 1;

document.addEventListener('DOMContentLoaded', function() {
    loadReviews();
    loadReviewStats();

    // Add search event listeners
    const searchInput = document.getElementById('search-input');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            loadReviews();
        }, 500);
    });

    // Add filter event listeners
    document.getElementById('rating-filter').addEventListener('change', function() {
        currentPage = 1;
        loadReviews();
    });

    document.getElementById('status-filter').addEventListener('change', function() {
        currentPage = 1;
        loadReviews();
    });
});

async function loadReviewStats() {
    try {
        // These are default values - need to modify API to return them
        document.getElementById('total-reviews').textContent = Math.floor(Math.random() * 200) + 100;
        document.getElementById('average-rating').textContent = (Math.random() * 2 + 3).toFixed(1);
        document.getElementById('users-reviewed').textContent = Math.floor(Math.random() * 150) + 80;
        document.getElementById('apartments-reviewed').textContent = Math.floor(Math.random() * 50) + 30;
    } catch (error) {
        console.error('Error loading review stats:', error);
    }
}

async function loadReviews() {
    try {
        showLoading();

        const filters = {
            search: document.getElementById('search-input').value,
            rating: document.getElementById('rating-filter').value,
            status: document.getElementById('status-filter').value,
            page: currentPage
        };

        const queryParams = new URLSearchParams();
        for (const [key, value] of Object.entries(filters)) {
            if (value) queryParams.append(key, value);
        }

        const response = await fetchData(`reviews?${queryParams.toString()}`);

        if (response && response.success) {
            displayReviews(response);
        } else {
            throw new Error(response?.message || 'Failed to load reviews');
        }

    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

function displayReviews(response) {
    const reviewsBody = document.getElementById('reviews-body');
    const noResults = document.getElementById('no-results');
    const reviewsContent = document.getElementById('reviews-content');
    const pagination = document.getElementById('pagination');
    const paginationInfo = document.getElementById('pagination-info');

    if (!response.reviews || response.reviews.length === 0) {
        reviewsContent.classList.remove('hidden');
        reviewsBody.innerHTML = '';
        noResults.classList.remove('hidden');
        pagination.classList.add('hidden');
        return;
    }

    noResults.classList.add('hidden');
    reviewsContent.classList.remove('hidden');

    // Display reviews
    reviewsBody.innerHTML = '';
    response.reviews.forEach(review => {
        const row = `
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center ml-2">
                            <i class="fas fa-user text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium">${review.user?.name || 'Not specified'}</p>
                            <p class="text-xs text-gray-500">${review.user?.email || ''}</p>
                        </div>
                    </div>
                </td>
                <td class="p-3">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg overflow-hidden ml-2">
                            ${review.apartment?.main_image ?
                                `<img src="${review.apartment.main_image}" class="w-full h-full object-cover" alt="${review.apartment.title}">` :
                                `<div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-building text-gray-400"></i>
                                </div>`
                            }
                        </div>
                        <div>
                            <p class="font-medium">${review.apartment?.title || 'Not specified'}</p>
                            <p class="text-xs text-gray-500">${review.apartment?.city || ''}</p>
                        </div>
                    </div>
                </td>
                <td class="p-3">
                    <div class="flex items-center">
                        <span class="text-yellow-500 text-lg">
                            ${getStarRating(review.rating)}
                        </span>
                        <span class="mr-2 font-bold">${review.rating}</span>
                    </div>
                </td>
                <td class="p-3">
                    <p class="text-sm text-gray-600 line-clamp-2">${review.comment || 'No comment'}</p>
                </td>
                <td class="p-3">
                    <span class="px-3 py-1 rounded-full text-sm ${getStatusClass(review.status)}">
                        ${getStatusText(review.status)}
                    </span>
                </td>
                <td class="p-3 text-sm text-gray-500">
                    ${formatDate(review.created_at)}
                </td>
                <td class="p-3">
                    <div class="flex gap-2">
                        <button onclick="viewReviewDetails(${review.id})"
                                class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                            <i class="fas fa-eye ml-1"></i> View
                        </button>

                        ${review.status !== 'approved' ?
                            `<button onclick="approveReview(${review.id})"
                                    class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                                <i class="fas fa-check ml-1"></i> Approve
                            </button>`
                            : ''
                        }

                        ${review.status !== 'rejected' ?
                            `<button onclick="rejectReview(${review.id})"
                                    class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                                <i class="fas fa-times ml-1"></i> Reject
                            </button>`
                            : ''
                        }

                        <button onclick="deleteReview(${review.id})"
                                class="px-3 py-1 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm">
                            <i class="fas fa-trash ml-1"></i> Delete
                        </button>
                    </div>
                </td>
            </tr>
        `;
        reviewsBody.innerHTML += row;
    });

    // Setup pagination
    if (response.pagination) {
        totalPages = response.pagination.total_pages || 1;
        currentPage = response.pagination.current_page || 1;

        pagination.classList.remove('hidden');
        paginationInfo.textContent = `Showing ${response.reviews.length} of ${response.pagination.total || 0} reviews`;

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
        loadReviews();
    }
}

async function viewReviewDetails(reviewId) {
    try {
        showLoading();

        const response = await fetchData(`reviews/${reviewId}`);

        if (response && response.review) {
            const review = response.review;

            const detailsHtml = `
                <div class="space-y-6">
                    <!-- Header -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500">Review ID</p>
                                <p class="font-bold text-lg">#${review.id}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Date</p>
                                <p class="font-medium">${formatDateTime(review.created_at)}</p>
                            </div>
                        </div>
                    </div>

                    <!-- User and Apartment Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- User Info -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500 mb-3">User</p>
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center ml-3">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium">${review.user?.name}</p>
                                    <p class="text-sm text-gray-500">${review.user?.email}</p>
                                    <p class="text-sm text-gray-500">${review.user?.phone_number}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Apartment Info -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500 mb-3">Apartment</p>
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg overflow-hidden ml-3">
                                    ${review.apartment?.main_image ?
                                        `<img src="${review.apartment.main_image}" class="w-full h-full object-cover">` :
                                        `<div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-building text-gray-400"></i>
                                        </div>`
                                    }
                                </div>
                                <div>
                                    <p class="font-medium">${review.apartment?.title}</p>
                                    <p class="text-sm text-gray-500">${review.apartment?.city}</p>
                                    <p class="text-sm text-gray-500">${review.apartment?.price_per_night}$ / night</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rating -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500 mb-2">Rating</p>
                        <div class="flex items-center">
                            <span class="text-yellow-500 text-2xl">
                                ${getStarRating(review.rating)}
                            </span>
                            <span class="mr-3 text-xl font-bold">${review.rating} / 5</span>
                        </div>
                    </div>

                    <!-- Comment -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500 mb-2">Comment</p>
                        <div class="bg-white p-4 rounded border">
                            <p class="text-gray-700 whitespace-pre-line">${review.comment || 'No comment'}</p>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500">Review Status</p>
                                <p class="font-medium ${getStatusClass(review.status)}">
                                    ${getStatusText(review.status)}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Last Updated</p>
                                <p class="font-medium">${formatDate(review.updated_at)}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('review-details-content').innerHTML = detailsHtml;
            document.getElementById('review-modal').classList.remove('hidden');
        }
    } catch (error) {
        showError('Error loading review details');
    } finally {
        hideLoading();
    }
}

async function approveReview(reviewId) {
    if (!confirm('Do you want to approve this review?')) return;

    try {
        showLoading();

        const response = await fetchData(`reviews/${reviewId}/approve`, {
            method: 'POST'
        });

        if (response && response.success) {
            showSuccess('Review approved successfully');
            loadReviews();
            loadReviewStats();
        } else {
            throw new Error(response?.message || 'Failed to approve review');
        }
    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

async function rejectReview(reviewId) {
    if (!confirm('Do you want to reject this review?')) return;

    try {
        showLoading();

        const response = await fetchData(`reviews/${reviewId}/reject`, {
            method: 'POST'
        });

        if (response && response.success) {
            showSuccess('Review rejected successfully');
            loadReviews();
            loadReviewStats();
        } else {
            throw new Error(response?.message || 'Failed to reject review');
        }
    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

async function deleteReview(reviewId) {
    if (!confirm('Are you sure you want to delete this review?')) return;

    try {
        showLoading();

        const response = await fetchData(`reviews/${reviewId}`, {
            method: 'DELETE'
        });

        if (response && response.success) {
            showSuccess('Review deleted successfully');
            loadReviews();
            loadReviewStats();
        } else {
            throw new Error(response?.message || 'Failed to delete review');
        }
    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

function closeReviewModal() {
    document.getElementById('review-modal').classList.add('hidden');
}

function getStarRating(rating) {
    const fullStars = Math.floor(rating);
    const halfStar = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);

    let stars = '';
    for (let i = 0; i < fullStars; i++) stars += '★';
    if (halfStar) stars += '½';
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

function formatDateTime(dateString) {
    if (!dateString) return 'Not specified';
    const date = new Date(dateString);
    return date.toLocaleString('en-US');
}

function showLoading() {
    document.getElementById('loading').classList.remove('hidden');
    document.getElementById('error').classList.add('hidden');
    document.getElementById('reviews-content').classList.add('hidden');
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