@extends('layouts.admin')

@section('title','Users Management')

@section('content')
<h1 class="text-3xl font-bold mb-6">Users Management</h1>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl shadow border-t-4 border-indigo-500">
        <p class="text-sm text-gray-500">Total Users</p>
        <p id="total-users" class="text-2xl font-bold">0</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow border-t-4 border-green-500">
        <p class="text-sm text-gray-500">Active Users</p>
        <p id="active-users" class="text-2xl font-bold">0</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow border-t-4 border-yellow-500">
        <p class="text-sm text-gray-500">Pending ID Verification</p>
        <p id="pending-id-users" class="text-2xl font-bold">0</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow border-t-4 border-red-500">
        <p class="text-sm text-gray-500">Rejected ID</p>
        <p id="rejected-id-users" class="text-2xl font-bold">0</p>
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
                       placeholder="Search by name or phone number..."
                       class="w-full pr-10 pl-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex gap-2">
            <select id="role-filter" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Roles</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <select id="status-filter" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>

            <select id="id-status-filter" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All ID Status</option>
                <option value="verified">Verified ID</option>
                <option value="pending">Pending ID</option>
                <option value="rejected">Rejected ID</option>
                <option value="not_uploaded">No ID</option>
            </select>

            <button onclick="loadUsers()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i class="fas fa-filter ml-2"></i> Filter
            </button>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="bg-white p-6 rounded-xl shadow-lg">
    <!-- Loading -->
    <div id="loading" class="text-center py-8">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        <p class="mt-2 text-gray-500">Loading users...</p>
    </div>

    <!-- Error -->
    <div id="error" class="hidden bg-red-50 text-red-700 p-4 rounded-lg mb-4">
        <p id="error-message"></p>
        <button onclick="loadUsers()" class="mt-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
            <i class="fas fa-redo ml-2"></i> Try Again
        </button>
    </div>

    <!-- Users Table Content -->
    <div id="users-content" class="hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="p-3 text-right font-medium text-gray-500">User</th>
                        <th class="p-3 text-right font-medium text-gray-500">Phone Number</th>
                        <th class="p-3 text-right font-medium text-gray-500">Email</th>
                        <th class="p-3 text-right font-medium text-gray-500">Role</th>
                        <th class="p-3 text-right font-medium text-gray-500">Status</th>
                        <th class="p-3 text-right font-medium text-gray-500">ID Status</th>
                        <th class="p-3 text-right font-medium text-gray-500">Registration Date</th>
                        <th class="p-3 text-right font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody id="users-body">
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
                <i class="fas fa-users text-gray-400 text-2xl"></i>
            </div>
            <p class="text-gray-500">No users found</p>
        </div>
    </div>
</div>

<!-- User Details Modal -->
<div id="user-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">User Details</h3>
                <button onclick="closeUserModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div id="user-details-content" class="max-h-[70vh] overflow-y-auto">
                <!-- Will be filled with data -->
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button onclick="closeUserModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// 🔧 **First: Define fetchData function - should be the first function in the script**
async function fetchData(endpoint, options = {}) {
    try {
        const token = localStorage.getItem('admin_token');
        console.log('🔑 Token:', token ? token.substring(0, 15) + '...' : 'Not found');

        if (!token) {
            console.error('❌ No token - please login first');
            window.location.href = '/admin/login';
            return { success: false, message: 'Unauthorized' };
        }

        // Build API URL
        const apiUrl = `/api/admin/${endpoint}`;
        console.log('📡 Requesting data from:', apiUrl);

        // Set headers
        const headers = {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            ...options.headers
        };

        // Set fetch options
        const fetchOptions = {
            method: options.method || 'GET',
            headers: headers,
            ...options
        };

        // If there's a body, convert to JSON
        if (options.body && typeof options.body !== 'string') {
            fetchOptions.body = JSON.stringify(options.body);
        }

        // Make request
        const response = await fetch(apiUrl, fetchOptions);
        console.log('📊 Response status:', response.status, response.statusText);

        // Handle common errors
        if (response.status === 401) {
            localStorage.removeItem('admin_token');
            localStorage.removeItem('admin_user');
            window.location.href = '/admin/login';
            return { success: false, message: 'Session expired' };
        }

        if (response.status === 404) {
            console.warn('⚠️ API not found:', endpoint);
            return {
                success: false,
                message: 'Service currently unavailable'
            };
        }

        // Try to parse JSON
        let data;
        try {
            data = await response.json();
        } catch (jsonError) {
            console.error('❌ JSON parse error:', jsonError);
            return {
                success: false,
                message: 'Invalid data format'
            };
        }

        console.log('✅ Response data:', data);
        return data;

    } catch (error) {
        console.error('🔥 Connection error:', error);
        return {
            success: false,
            message: 'Connection error: ' + error.message
        };
    }
}

// 🔧 **Second: Helper variables and functions**
let currentPage = 1;
let totalPages = 1;

function formatDate(dateString) {
    if (!dateString) return 'Not specified';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US');
}

function getProfilePictureUrl(profilePicture) {
    if (!profilePicture) return null;

    // Check if profile_picture is an object with image_path property
    if (typeof profilePicture === 'object' && profilePicture !== null) {
        if (profilePicture.image_path) {
            return profilePicture.image_path.startsWith('http') ?
                profilePicture.image_path :
                `/storage/${profilePicture.image_path}`;
        }
    }

    // Check if it's a string
    if (typeof profilePicture === 'string' && profilePicture.trim() !== '') {
        return profilePicture.startsWith('http') ?
            profilePicture :
            `/storage/${profilePicture}`;
    }

    return null;
}

// 🔧 NEW FUNCTION: Get personal ID URL
function getPersonalIdUrl(personalId) {
    if (!personalId) return null;

    // Check if personal_id is an object with image_path property
    if (typeof personalId === 'object' && personalId !== null) {
        if (personalId.image_path) {
            return personalId.image_path.startsWith('http') ?
                personalId.image_path :
                `/storage/${personalId.image_path}`;
        }
    }

    // Check if it's a string
    if (typeof personalId === 'string' && personalId.trim() !== '') {
        return personalId.startsWith('http') ?
            personalId :
            `/storage/${personalId}`;
    }

    return null;
}

function showLoading() {
    document.getElementById('loading').classList.remove('hidden');
    document.getElementById('error').classList.add('hidden');
    document.getElementById('users-content').classList.add('hidden');
}

function hideLoading() {
    document.getElementById('loading').classList.add('hidden');
}

function showError(message) {
    document.getElementById('error-message').textContent = message;
    document.getElementById('error').classList.remove('hidden');
}

function showSuccess(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: message,
            timer: 3000,
            showConfirmButton: false
        });
    } else {
        alert('✅ ' + message);
    }
}

function closeUserModal() {
    document.getElementById('user-modal').classList.add('hidden');
}

function getStatusClass(status) {
    switch(status) {
        case 'active': return 'bg-green-100 text-green-800';
        case 'inactive': return 'bg-red-100 text-red-800';
        case 'suspended': return 'bg-yellow-100 text-yellow-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'active': return 'Active';
        case 'inactive': return 'Inactive';
        case 'suspended': return 'Suspended';
        default: return status;
    }
}

function getIdStatusClass(status) {
    switch(status) {
        case 'verified': return 'bg-green-100 text-green-800';
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'rejected': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function getIdStatusText(status) {
    switch(status) {
        case 'verified': return 'Verified';
        case 'pending': return 'Pending';
        case 'rejected': return 'Rejected';
        default: return 'Not Uploaded';
    }
}

// 🔧 **Third: Main users loading function**
async function loadUsers() {
    try {
        console.log('🚀 Starting to load users...');
        showLoading();

        // Build query parameters
        let endpoint = 'users?';
        const roleFilter = document.getElementById('role-filter').value;
        const statusFilter = document.getElementById('status-filter').value;
        const idStatusFilter = document.getElementById('id-status-filter').value;

        if (roleFilter) endpoint += `role=${roleFilter}&`;
        if (statusFilter) endpoint += `status=${statusFilter}&`;
        if (idStatusFilter) endpoint += `id_status=${idStatusFilter}&`;

        // Fetch users data
        const response = await fetchData(endpoint.slice(0, -1)); // Remove last '&'
        console.log('📦 API response:', response);

        if (response && response.success) {
            displayUsers(response);
            updateStats(response.users);
        } else {
            throw new Error(response?.message || 'Failed to load users');
        }

    } catch (error) {
        console.error('❌ Error in loadUsers:', error);
        showError(error.message);
    } finally {
        hideLoading();
    }
}

// 🔧 **Fourth: Function to display users in table**
function displayUsers(response) {
    const usersBody = document.getElementById('users-body');
    const noResults = document.getElementById('no-results');
    const usersContent = document.getElementById('users-content');
    const pagination = document.getElementById('pagination');
    const paginationInfo = document.getElementById('pagination-info');

    // Check if data exists
    if (!response.users || response.users.length === 0) {
        usersContent.classList.remove('hidden');
        usersBody.innerHTML = '';
        noResults.classList.remove('hidden');
        pagination.classList.add('hidden');
        return;
    }

    // Hide "no results" message and show table
    noResults.classList.add('hidden');
    usersContent.classList.remove('hidden');

    // Display users
    usersBody.innerHTML = '';
    response.users.forEach((user, index) => {
        // Get profile picture URL
        const profilePictureUrl = getProfilePictureUrl(user.profile_picture);
        const hasProfilePicture = profilePictureUrl !== null;

        // Get personal ID info
        const hasPersonalId = user.personal_id && typeof user.personal_id === 'object';
        const personalIdUrl = getPersonalIdUrl(user.personal_id);
        const personalIdStatus = hasPersonalId ? (user.personal_id.status || 'pending') : 'none';

        const row = `
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-indigo-100 flex items-center justify-center ml-3">
                            ${hasProfilePicture ?
                                `<img src="${profilePictureUrl}"
                                      alt="${user.first_name || 'User'}"
                                      class="w-full h-full object-cover"
                                      onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'font-semibold text-indigo-600\'>${user.first_name ? user.first_name.charAt(0).toUpperCase() : 'U'}</span>';">` :
                                `<span class="font-semibold text-indigo-600">${user.first_name ? user.first_name.charAt(0).toUpperCase() : 'U'}</span>`
                            }
                        </div>
                        <div>
                            <p class="font-medium">${user.first_name+' '+user.last_name || 'Not specified'}</p>
                            <p class="text-sm text-gray-500">#${user.id}</p>
                        </div>
                    </div>
                </td>
                <td class="p-3">
                    <div class="flex items-center">
                        <i class="fas fa-phone text-gray-400 ml-2 text-sm"></i>
                        <span>${user.phone_number || 'Not specified'}</span>
                    </div>
                </td>
                <td class="p-3">${user.email || 'Not specified'}</td>
                <td class="p-3">
                    <span class="px-3 py-1 rounded-full text-sm ${user.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'}">
                        ${user.role === 'admin' ? 'Admin' : 'User'}
                    </span>
                </td>
                <td class="p-3">
                    <span class="px-3 py-1 rounded-full text-sm ${getStatusClass(user.status)}">
                        ${getStatusText(user.status)}
                    </span>
                </td>
                <td class="p-3">
                    <span class="px-3 py-1 rounded-full text-sm ${getIdStatusClass(personalIdStatus)}">
                        ${getIdStatusText(personalIdStatus)}
                    </span>
                </td>
                <td class="p-3 text-sm text-gray-500">
                    ${formatDate(user.created_at)}
                </td>
                <td class="p-3">
                    <div class="flex gap-2">
                        <button onclick="viewUserDetails(${user.id})"
                                class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                            <i class="fas fa-eye ml-1"></i> View
                        </button>

                        ${user.role !== 'admin' ?
                            `
                            ${user.status !== 'active' ?
                                `<button onclick="approveUser(${user.id})"
                                        class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                                    <i class="fas fa-check ml-1"></i> Activate
                                </button>`
                            : ''}

                            ${user.status !== 'inactive' ?
                                `<button onclick="rejectUser(${user.id})"
                                        class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                                    <i class="fas fa-times ml-1"></i> Deactivate
                                </button>`
                            : ''}
                            `
                        : ''}

                        ${hasPersonalId && personalIdStatus === 'pending' ?
                            `<button onclick="verifyUserIdentity(${user.id})"
                                    class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                                <i class="fas fa-id-card ml-1"></i> Verify ID
                            </button>`
                        : ''}
                    </div>
                </td>
            </tr>
        `;
        usersBody.innerHTML += row;
    });

    // Setup pagination
    if (response.pagination) {
        totalPages = response.pagination.total_pages || 1;
        currentPage = response.pagination.current_page || 1;

        pagination.classList.remove('hidden');
        paginationInfo.textContent = `Showing ${response.users.length} of ${response.pagination.total || 0} users`;

        document.getElementById('prev-btn').disabled = currentPage <= 1;
        document.getElementById('next-btn').disabled = currentPage >= totalPages;
    } else {
        pagination.classList.add('hidden');
    }
}

function updateStats(users) {
    if (!users) return;

    let total = users.length;
    let active = 0;
    let pendingId = 0;
    let rejectedId = 0;

    users.forEach(user => {
        if (user.status === 'active') active++;

        // Check personal ID status
        if (user.personal_id && typeof user.personal_id === 'object') {
            const idStatus = user.personal_id.status || 'pending';
            if (idStatus === 'pending') pendingId++;
            if (idStatus === 'rejected') rejectedId++;
        } else {
            // No ID uploaded - could count as pending if needed
        }
    });

    document.getElementById('total-users').textContent = total;
    document.getElementById('active-users').textContent = active;
    document.getElementById('pending-id-users').textContent = pendingId;
    document.getElementById('rejected-id-users').textContent = rejectedId;
}

function changePage(direction) {
    const newPage = currentPage + direction;
    if (newPage >= 1 && newPage <= totalPages) {
        currentPage = newPage;
        loadUsers();
    }
}

// 🔧 **Fifth: Functions to view details and change status**
async function viewUserDetails(userId) {
    try {
        showLoading();
        const response = await fetchData(`users/${userId}`);

        if (response && response.user) {
            const user = response.user;

            // Get profile picture URL
            const profilePictureUrl = getProfilePictureUrl(user.profile_picture);
            const hasProfilePicture = profilePictureUrl !== null;

            // Get personal ID URL
            const personalIdUrl = getPersonalIdUrl(user.personal_id);
            const hasPersonalId = personalIdUrl !== null;

            // Get image path for display
            let imagePathDisplay = 'No profile picture';
            if (user.profile_picture) {
                if (typeof user.profile_picture === 'object' && user.profile_picture.image_path) {
                    imagePathDisplay = user.profile_picture.image_path;
                } else if (typeof user.profile_picture === 'string') {
                    imagePathDisplay = user.profile_picture;
                }
            }

            // Get personal ID path for display
            let personalIdPathDisplay = 'No ID uploaded';
            if (user.personal_id) {
                if (typeof user.personal_id === 'object' && user.personal_id.image_path) {
                    personalIdPathDisplay = user.personal_id.image_path;
                } else if (typeof user.personal_id === 'string') {
                    personalIdPathDisplay = user.personal_id;
                }
            }

            // Get personal ID status
            let personalIdStatus = 'Not uploaded';
            let personalIdStatusClass = 'bg-gray-100 text-gray-800';
            if (user.personal_id && typeof user.personal_id === 'object') {
                personalIdStatus = user.personal_id.status || 'Pending';
                switch(personalIdStatus.toLowerCase()) {
                    case 'verified':
                        personalIdStatusClass = 'bg-green-100 text-green-800';
                        break;
                    case 'pending':
                        personalIdStatusClass = 'bg-yellow-100 text-yellow-800';
                        break;
                    case 'rejected':
                        personalIdStatusClass = 'bg-red-100 text-red-800';
                        break;
                    default:
                        personalIdStatusClass = 'bg-gray-100 text-gray-800';
                }
            }

            const detailsHtml = `
                <div class="space-y-4">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-24 h-24 rounded-full overflow-hidden ${!hasProfilePicture ? 'bg-indigo-100 flex items-center justify-center' : ''}">
                            ${hasProfilePicture ?
                                `<img src="${profilePictureUrl}"
                                      alt="${user.first_name || 'User'}"
                                      class="w-full h-full object-cover"
                                      onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'font-bold text-indigo-600 text-3xl\'>${user.first_name ? user.first_name.charAt(0).toUpperCase() : 'U'}</span>'; this.parentElement.classList.add(\'bg-indigo-100\', \'flex\', \'items-center\', \'justify-center\'); this.parentElement.classList.remove(\'overflow-hidden\');">` :
                                `<span class="font-bold text-indigo-600 text-3xl">
                                    ${user.first_name ? user.first_name.charAt(0).toUpperCase() : 'U'}
                                </span>`
                            }
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-500">Full Name</p>
                            <p class="font-medium">${user.first_name+' '+user.last_name || 'Not specified'}</p>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-500">Phone Number</p>
                            <p class="font-medium">${user.phone_number || 'Not specified'}</p>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">${user.email || 'Not specified'}</p>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-500">Role</p>
                            <p class="font-medium ${user.role === 'admin' ? 'text-purple-600' : 'text-blue-600'}">
                                ${user.role === 'admin' ? 'Admin' : 'User'}
                            </p>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="font-medium ${user.status === 'active' ? 'text-green-600' : 'text-gray-600'}">
                                ${user.status === 'active' ? 'Active' : 'Inactive'}
                            </p>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-500">Registration Date</p>
                            <p class="font-medium">${formatDate(user.created_at)}</p>
                        </div>
                    </div>

                    ${hasProfilePicture ? `
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">Profile Picture</p>
                        <div class="mt-2 flex items-center">
                            <img src="${profilePictureUrl}"
                                 alt="${user.first_name || 'User'} profile"
                                 class="w-16 h-16 rounded-full object-cover border-2 border-white shadow"
                                 onerror="this.style.display='none';">
                            <div class="mr-3">
                                <p class="text-xs text-gray-500 truncate max-w-[200px]">${imagePathDisplay}</p>
                                <button onclick="window.open('${profilePictureUrl}', '_blank')"
                                        class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                    <i class="fas fa-external-link-alt ml-1"></i> Open full image
                                </button>
                            </div>
                        </div>
                    </div>
                    ` : ''}

                    <!-- NEW: Personal ID Section -->
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-sm text-gray-500">Identity Verification</p>
                            <span class="px-3 py-1 rounded-full text-xs ${personalIdStatusClass}">
                                ${personalIdStatus}
                            </span>
                        </div>

                        ${hasPersonalId ? `
                            <div class="mt-2">
                                <div class="flex items-center">
                                    <img src="${personalIdUrl}"
                                         alt="Personal ID Document"
                                         class="w-32 h-20 object-cover border rounded-lg shadow cursor-pointer"
                                         onclick="window.open('${personalIdUrl}', '_blank')"
                                         onerror="this.style.display='none';">
                                    <div class="mr-3">
                                        <p class="text-xs text-gray-500 truncate max-w-[200px]">${personalIdPathDisplay}</p>
                                        <button onclick="window.open('${personalIdUrl}', '_blank')"
                                                class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                            <i class="fas fa-external-link-alt ml-1"></i> View ID Document
                                        </button>
                                    </div>
                                </div>
                                ${user.personal_id && typeof user.personal_id === 'object' ? `
                                    <div class="mt-2 space-y-1 text-xs text-gray-600">
                                        ${user.personal_id.created_at ? `<p>Uploaded: ${formatDate(user.personal_id.created_at)}</p>` : ''}
                                        ${user.personal_id.verified_at ? `<p>Verified: ${formatDate(user.personal_id.verified_at)}</p>` : ''}
                                        ${user.personal_id.rejected_at ? `<p>Rejected: ${formatDate(user.personal_id.rejected_at)}</p>` : ''}
                                        ${user.personal_id.notes ? `<p>Notes: ${user.personal_id.notes}</p>` : ''}
                                    </div>
                                ` : ''}
                            </div>
                        ` : `
                            <div class="text-center py-4">
                                <div class="inline-block p-3 bg-gray-200 rounded-full mb-2">
                                    <i class="fas fa-id-card text-gray-400"></i>
                                </div>
                                <p class="text-sm text-gray-500">No ID document uploaded</p>
                            </div>
                        `}
                    </div>

                    ${user.profile_picture && typeof user.profile_picture === 'object' ? `
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">Profile Image Details</p>
                        <div class="space-y-1 text-xs text-gray-600 mt-1">
                            ${user.profile_picture.id ? `<p>ID: ${user.profile_picture.id}</p>` : ''}
                            ${user.profile_picture.created_at ? `<p>Uploaded: ${formatDate(user.profile_picture.created_at)}</p>` : ''}
                            ${user.profile_picture.type ? `<p>Type: ${user.profile_picture.type}</p>` : ''}
                        </div>
                    </div>
                    ` : ''}

                    ${user.personal_id && typeof user.personal_id === 'object' ? `
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">ID Document Details</p>
                        <div class="space-y-1 text-xs text-gray-600 mt-1">
                            ${user.personal_id.id ? `<p>ID: ${user.personal_id.id}</p>` : ''}
                            ${user.personal_id.created_at ? `<p>Uploaded: ${formatDate(user.personal_id.created_at)}</p>` : ''}
                            ${user.personal_id.type ? `<p>Type: ${user.personal_id.type}</p>` : ''}
                        </div>
                    </div>
                    ` : ''}

                    <!-- Action Buttons in Modal -->
                    <div class="flex gap-3 pt-4">
                        ${user.role !== 'admin' ?
                            `
                            ${user.status !== 'active' ?
                                `<button onclick="approveUser(${user.id})"
                                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    <i class="fas fa-check ml-2"></i> Activate User
                                </button>`
                            : ''}

                            ${user.status !== 'inactive' ?
                                `<button onclick="rejectUser(${user.id})"
                                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                    <i class="fas fa-times ml-2"></i> Deactivate User
                                </button>`
                            : ''}
                            `
                        : ''}

                        ${hasPersonalId && personalIdStatus === 'pending' ?
                            `<button onclick="verifyUserIdentity(${user.id})"
                                    class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                <i class="fas fa-id-card ml-2"></i> Verify ID
                            </button>`
                        : ''}
                    </div>
                </div>
            `;

            document.getElementById('user-details-content').innerHTML = detailsHtml;
            document.getElementById('user-modal').classList.remove('hidden');
        }
    } catch (error) {
        showError('Error loading user details');
    } finally {
        hideLoading();
    }
}

async function approveUser(userId) {
    if (!confirm('Do you want to activate this user?')) return;

    try {
        showLoading();
        const response = await fetchData(`approve-user/${userId}`, { method: 'GET' });

        if (response && response.success) {
            showSuccess('User activated successfully');
            closeUserModal();
            loadUsers();
        } else {
            throw new Error(response?.message || 'Failed to activate user');
        }
    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

async function rejectUser(userId) {
    if (!confirm('Do you want to deactivate this user?')) return;

    try {
        showLoading();
        const response = await fetchData(`reject-user/${userId}`, { method: 'GET' });

        if (response && response.success) {
            showSuccess('User deactivated successfully');
            closeUserModal();
            loadUsers();
        } else {
            throw new Error(response?.message || 'Failed to deactivate user');
        }
    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

async function verifyUserIdentity(userId) {
    if (!confirm('Do you want to verify this user\'s identity?')) return;

    try {
        showLoading();
        const response = await fetchData(`verify-identity/${userId}`, { method: 'POST' });

        if (response && response.success) {
            showSuccess('Identity verified successfully');
            closeUserModal();
            loadUsers();
        } else {
            throw new Error(response?.message || 'Failed to verify identity');
        }
    } catch (error) {
        showError(error.message);
    } finally {
        hideLoading();
    }
}

// 🔧 **Sixth: Page initialization on load**
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Users page loaded');

    // Check token
    const token = localStorage.getItem('admin_token');
    if (!token) {
        console.error('❌ No token - redirecting to login');
        window.location.href = '/admin/login';
        return;
    }

    console.log('✅ Token exists, loading users...');

    // Setup search event listeners
    const searchInput = document.getElementById('search-input');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            loadUsers();
        }, 500);
    });

    // Setup filter event listeners
    document.getElementById('role-filter').addEventListener('change', function() {
        currentPage = 1;
        loadUsers();
    });

    document.getElementById('status-filter').addEventListener('change', function() {
        currentPage = 1;
        loadUsers();
    });

    document.getElementById('id-status-filter').addEventListener('change', function() {
        currentPage = 1;
        loadUsers();
    });

    // Load users on page open
    loadUsers();
});

// 🔧 **Seventh: API test function (for debugging only)**
async function testUsersAPI() {
    console.log('🧪 Starting users API test...');
    const token = localStorage.getItem('admin_token');

    if (!token) {
        console.error('❌ No token');
        return;
    }

    try {
        const response = await fetch('/api/admin/users', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        console.log('📊 Status:', response.status, response.statusText);

        if (response.ok) {
            const data = await response.json();
            console.log('✅ Users data:', data);
            console.log(`👥 Number of users: ${data.users ? data.users.length : 0}`);

            // Debug profile pictures and personal ID
            if (data.users && data.users.length > 0) {
                const firstUser = data.users[0];
                console.log('📸 First user profile_picture:', firstUser.profile_picture);
                console.log('🆔 First user personal_id:', firstUser.personal_id);

                if (firstUser.profile_picture) {
                    console.log('📸 Type:', typeof firstUser.profile_picture);
                    if (typeof firstUser.profile_picture === 'object') {
                        console.log('📸 Object keys:', Object.keys(firstUser.profile_picture));
                    }
                }

                if (firstUser.personal_id) {
                    console.log('🆔 Type:', typeof firstUser.personal_id);
                    if (typeof firstUser.personal_id === 'object') {
                        console.log('🆔 Object keys:', Object.keys(firstUser.personal_id));
                    }
                }
            }

            alert(`✅ API is working! Number of users: ${data.users?.length || 0}`);
        } else {
            const errorText = await response.text();
            console.error('❌ API error:', errorText);
            alert('❌ API error: ' + response.status);
        }
    } catch (error) {
        console.error('🔥 Connection error:', error);
        alert('🔥 Connection error: ' + error.message);
    }
}

// 🔧 **Eighth: Keyboard shortcuts (for development only)**
document.addEventListener('keydown', function(e) {
    // Ctrl+Alt+T for API test
    if (e.ctrlKey && e.altKey && e.key === 't') {
        e.preventDefault();
        testUsersAPI();
    }

    // Ctrl+R to reload users
    if (e.ctrlKey && e.key === 'r') {
        e.preventDefault();
        loadUsers();
        console.log('🔄 Reloading users...');
    }
});

console.log('✅ users.blade.js loaded successfully');
</script>
@endsection