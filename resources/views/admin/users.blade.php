@extends('layouts.admin')

@section('title','Users Management')

@section('content')
<div x-data>
    <h1 class="text-3xl font-bold mb-6" x-text="language === 'ar' ? 'إدارة المستخدمين' : 'Users Management'">Users Management</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl shadow border-t-4 border-indigo-500">
            <p class="text-sm text-gray-500" x-text="language === 'ar' ? 'إجمالي المستخدمين' : 'Total Users'">Total Users</p>
            <p id="total-users" class="text-2xl font-bold">0</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow border-t-4 border-green-500">
            <p class="text-sm text-gray-500" x-text="language === 'ar' ? 'المستخدمين النشطين' : 'Active Users'">Active Users</p>
            <p id="active-users" class="text-2xl font-bold">0</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow border-t-4 border-yellow-500">
            <p class="text-sm text-gray-500" x-text="language === 'ar' ? 'بانتظار التحقق من الهوية' : 'Pending ID Verification'">Pending ID Verification</p>
            <p id="pending-id-users" class="text-2xl font-bold">0</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow border-t-4 border-red-500">
            <p class="text-sm text-gray-500" x-text="language === 'ar' ? 'هوية مرفوضة' : 'Rejected ID'">Rejected ID</p>
            <p id="rejected-id-users" class="text-2xl font-bold">0</p>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-lg mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <input type="text"
                           id="search-input"
                           :placeholder="language === 'ar' ? 'ابحث بالاسم أو رقم الهاتف...' : 'Search by name or phone number...'"
                           class="w-full pr-10 pl-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <div class="flex gap-2">
                <select id="role-filter" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="" x-text="language === 'ar' ? 'جميع الصلاحيات' : 'All Roles'">All Roles</option>
                    <option value="user" x-text="language === 'ar' ? 'مستخدم' : 'User'">User</option>
                    <option value="admin" x-text="language === 'ar' ? 'مدير' : 'Admin'">Admin</option>
                </select>

                <select id="status-filter" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="" x-text="language === 'ar' ? 'جميع الحالات' : 'All Statuses'">All Statuses</option>
                    <option value="active" x-text="language === 'ar' ? 'نشط' : 'Active'">Active</option>
                    <option value="inactive" x-text="language === 'ar' ? 'غير نشط' : 'Inactive'">Inactive</option>
                </select>

                <select id="id-status-filter" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="" x-text="language === 'ar' ? 'جميع حالات الهوية' : 'All ID Status'">All ID Status</option>
                    <option value="verified" x-text="language === 'ar' ? 'هوية موثقة' : 'Verified ID'">Verified ID</option>
                    <option value="pending" x-text="language === 'ar' ? 'هوية قيد الانتظار' : 'Pending ID'">Pending ID</option>
                    <option value="rejected" x-text="language === 'ar' ? 'هوية مرفوضة' : 'Rejected ID'">Rejected ID</option>
                    <option value="not_uploaded" x-text="language === 'ar' ? 'لا يوجد هوية' : 'No ID'">No ID</option>
                </select>

                <button onclick="loadUsers()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center">
                    <i class="fas fa-filter" :class="language === 'ar' ? 'mr-2 ml-0' : 'ml-2'"></i>
                    <span x-text="language === 'ar' ? 'تصفية' : 'Filter'">Filter</span>
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg">
        <div id="loading" class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            <p class="mt-2 text-gray-500" x-text="language === 'ar' ? 'جاري تحميل المستخدمين...' : 'Loading users...'">Loading users...</p>
        </div>

        <div id="error" class="hidden bg-red-50 text-red-700 p-4 rounded-lg mb-4">
            <p id="error-message"></p>
            <button onclick="loadUsers()" class="mt-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 flex items-center">
                <i class="fas fa-redo" :class="language === 'ar' ? 'mr-2 ml-0' : 'ml-2'"></i>
                <span x-text="language === 'ar' ? 'حاول مرة أخرى' : 'Try Again'">Try Again</span>
            </button>
        </div>

        <div id="users-content" class="hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="p-3 font-medium text-gray-500" :class="language === 'ar' ? 'text-right' : 'text-left'">
                                <span x-text="language === 'ar' ? 'المستخدم' : 'User'">User</span>
                            </th>
                            <th class="p-3 font-medium text-gray-500" :class="language === 'ar' ? 'text-right' : 'text-left'">
                                <span x-text="language === 'ar' ? 'رقم الهاتف' : 'Phone Number'">Phone Number</span>
                            </th>
                            <th class="p-3 font-medium text-gray-500" :class="language === 'ar' ? 'text-right' : 'text-left'">
                                <span x-text="language === 'ar' ? 'البريد الإلكتروني' : 'Email'">Email</span>
                            </th>
                            <th class="p-3 font-medium text-gray-500" :class="language === 'ar' ? 'text-right' : 'text-left'">
                                <span x-text="language === 'ar' ? 'الصلاحية' : 'Role'">Role</span>
                            </th>
                            <th class="p-3 font-medium text-gray-500" :class="language === 'ar' ? 'text-right' : 'text-left'">
                                <span x-text="language === 'ar' ? 'الحالة' : 'Status'">Status</span>
                            </th>
                            <th class="p-3 font-medium text-gray-500" :class="language === 'ar' ? 'text-right' : 'text-left'">
                                <span x-text="language === 'ar' ? 'حالة الهوية' : 'ID Status'">ID Status</span>
                            </th>
                            <th class="p-3 font-medium text-gray-500" :class="language === 'ar' ? 'text-right' : 'text-left'">
                                <span x-text="language === 'ar' ? 'تاريخ التسجيل' : 'Registration Date'">Registration Date</span>
                            </th>
                            <th class="p-3 font-medium text-gray-500" :class="language === 'ar' ? 'text-right' : 'text-left'">
                                <span x-text="language === 'ar' ? 'الإجراءات' : 'Actions'">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="users-body">
                    </tbody>
                </table>
            </div>

            <div id="pagination" class="mt-6 flex justify-between items-center hidden">
                <div class="text-sm text-gray-600" id="pagination-info"></div>
                <div class="flex gap-2">
                    <button id="prev-btn" onclick="changePage(-1)"
                            class="px-4 py-2 border rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                        <i class="fas fa-arrow-right" :class="language === 'ar' ? 'mr-2 ml-0' : 'ml-2'"></i>
                        <span x-text="language === 'ar' ? 'السابق' : 'Previous'">Previous</span>
                    </button>
                    <button id="next-btn" onclick="changePage(1)"
                            class="px-4 py-2 border rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                        <span x-text="language === 'ar' ? 'التالي' : 'Next'">Next</span>
                        <i class="fas fa-arrow-left" :class="language === 'ar' ? 'ml-2 mr-0' : 'mr-2'"></i>
                    </button>
                </div>
            </div>

            <div id="no-results" class="text-center p-8 hidden">
                <div class="inline-block p-4 bg-gray-100 rounded-full mb-3">
                    <i class="fas fa-users text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500" x-text="language === 'ar' ? 'لم يتم العثور على مستخدمين' : 'No users found'">No users found</p>
            </div>
        </div>
    </div>

    <div id="user-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6" :class="language === 'ar' ? 'flex-row-reverse' : ''">
                    <h3 class="text-xl font-bold" x-text="language === 'ar' ? 'تفاصيل المستخدم' : 'User Details'">User Details</h3>
                    <button onclick="closeUserModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div id="user-details-content" class="max-h-[70vh] overflow-y-auto">
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button onclick="closeUserModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                        <span x-text="language === 'ar' ? 'إغلاق' : 'Close'">Close</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="charge-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6" :class="language === 'ar' ? 'flex-row-reverse' : ''">
                    <h3 class="text-xl font-bold" x-text="language === 'ar' ? 'شحن المحفظة' : 'Charge Wallet'">Charge Wallet</h3>
                    <button onclick="closeChargeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div id="charge-modal-content">
                    <div class="mb-4" :class="language === 'ar' ? 'text-right' : ''">
                        <p class="text-gray-600 mb-2" x-text="language === 'ar' ? 'أدخل المبلغ لشحن محفظة المستخدم:' : 'Enter amount to charge user\'s wallet:'">Enter amount to charge user's wallet:</p>
                        <div class="relative">
                            <input type="number"
                                   id="charge-amount"
                                   :placeholder="language === 'ar' ? 'أدخل المبلغ' : 'Enter amount'"
                                   class="w-full pl-4 pr-12 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                   min="1"
                                   step="0.01">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1" x-text="language === 'ar' ? 'الحد الأدنى: $1.00' : 'Minimum amount: $1.00'">Minimum amount: $1.00</p>
                    </div>

                    <div id="wallet-info" class="mb-6 p-3 bg-gray-50 rounded-lg hidden" :class="language === 'ar' ? 'text-right' : ''">
                        <p class="text-sm text-gray-600">
                            <span x-text="language === 'ar' ? 'رصيد المحفظة الحالي:' : 'Current wallet balance:'">Current wallet balance:</span>
                            <span id="current-wallet" class="font-semibold">$0</span>
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            <span x-text="language === 'ar' ? 'الرصيد الجديد:' : 'New balance:'">New balance:</span>
                            <span id="new-wallet" class="font-semibold text-green-600">$0</span>
                        </p>
                    </div>

                    <div class="flex gap-3 mt-6" :class="language === 'ar' ? 'flex-row-reverse' : ''">
                        <button onclick="closeChargeModal()" class="flex-1 px-4 py-2 border rounded-lg hover:bg-gray-50">
                            <span x-text="language === 'ar' ? 'إلغاء' : 'Cancel'">Cancel</span>
                        </button>
                        <button onclick="confirmChargeWallet()" id="charge-confirm-btn" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center justify-center">
                            <i class="fas fa-wallet" :class="language === 'ar' ? 'mr-2 ml-0' : 'ml-2'"></i>
                            <span x-text="language === 'ar' ? 'شحن المحفظة' : 'Charge Wallet'">Charge Wallet</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentPage = 1;
    let totalPages = 1;
    let currentUserIdForCharge = null;
    let currentUserWallet = 0;

    function formatDate(dateString) {
        if (!dateString) return 'Not specified';
        const date = new Date(dateString);
        const language = window.Alpine.$data.language || 'en';
        return date.toLocaleDateString(language === 'ar' ? 'ar-SA' : 'en-US');
    }

    function getProfilePictureUrl(profilePicture) {
        if (!profilePicture) return null;

        if (typeof profilePicture === 'object' && profilePicture !== null) {
            if (profilePicture.image_path) {
                return profilePicture.image_path.startsWith('http') ?
                    profilePicture.image_path :
                    `/storage/${profilePicture.image_path}`;
            }
        }

        if (typeof profilePicture === 'string' && profilePicture.trim() !== '') {
            return profilePicture.startsWith('http') ?
                profilePicture :
                `/storage/${profilePicture}`;
        }

        return null;
    }

    function getPersonalIdUrl(personalId) {
        if (!personalId) return null;

        if (typeof personalId === 'object' && personalId !== null) {
            if (personalId.image_path) {
                return personalId.image_path.startsWith('http') ?
                    personalId.image_path :
                    `/storage/${personalId.image_path}`;
            }
        }

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
            const language = window.Alpine.$data.language || 'en';
            Swal.fire({
                icon: 'success',
                title: language === 'ar' ? 'نجاح' : 'Success',
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

    function closeChargeModal() {
        document.getElementById('charge-modal').classList.add('hidden');
        document.getElementById('charge-amount').value = '';
        currentUserIdForCharge = null;
        currentUserWallet = 0;
        document.getElementById('wallet-info').classList.add('hidden');
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
        const language = window.Alpine.$data.language || 'en';
        switch(status) {
            case 'active': return language === 'ar' ? 'نشط' : 'Active';
            case 'inactive': return language === 'ar' ? 'غير نشط' : 'Inactive';
            case 'suspended': return language === 'ar' ? 'موقوف' : 'Suspended';
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
        const language = window.Alpine.$data.language || 'en';
        switch(status) {
            case 'verified': return language === 'ar' ? 'موثقة' : 'Verified';
            case 'pending': return language === 'ar' ? 'قيد الانتظار' : 'Pending';
            case 'rejected': return language === 'ar' ? 'مرفوضة' : 'Rejected';
            default: return language === 'ar' ? 'غير مرفوعة' : 'Not Uploaded';
        }
    }

    async function loadUsers() {
        try {
            console.log(' Starting to load users...');
            showLoading();

            let endpoint = 'users?';
            const roleFilter = document.getElementById('role-filter').value;
            const statusFilter = document.getElementById('status-filter').value;
            const idStatusFilter = document.getElementById('id-status-filter').value;

            if (roleFilter) endpoint += `role=${roleFilter}&`;
            if (statusFilter) endpoint += `status=${statusFilter}&`;
            if (idStatusFilter) endpoint += `id_status=${idStatusFilter}&`;

            const response = await fetchData(endpoint.slice(0, -1));
            console.log(' API response:', response);

            if (response && response.success) {
                displayUsers(response);
                updateStats(response.users);
            } else {
                throw new Error(response?.message || 'Failed to load users');
            }

        } catch (error) {
            console.error(' Error in loadUsers:', error);
            showError(error.message);
        } finally {
            hideLoading();
        }
    }

    function displayUsers(response) {
        const usersBody = document.getElementById('users-body');
        const noResults = document.getElementById('no-results');
        const usersContent = document.getElementById('users-content');
        const pagination = document.getElementById('pagination');
        const paginationInfo = document.getElementById('pagination-info');

        const language = window.Alpine.$data.language || 'en';

        if (!response.users || response.users.length === 0) {
            usersContent.classList.remove('hidden');
            usersBody.innerHTML = '';
            noResults.classList.remove('hidden');
            pagination.classList.add('hidden');
            return;
        }

        noResults.classList.add('hidden');
        usersContent.classList.remove('hidden');

        usersBody.innerHTML = '';
        response.users.forEach((user, index) => {
            const profilePictureUrl = getProfilePictureUrl(user.profile_picture);
            const hasProfilePicture = profilePictureUrl !== null;

            const hasPersonalId = user.personal_id && typeof user.personal_id === 'object';
            const personalIdUrl = getPersonalIdUrl(user.personal_id);
            const personalIdStatus = hasPersonalId ? (user.personal_id.status || 'pending') : 'none';

            const row = `
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">
                    <div class="flex items-center" ${language === 'ar' ? 'style="direction: rtl; text-align: right; flex-direction: row-reverse;"' : ''}>
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-indigo-100 flex items-center justify-center" ${language === 'ar' ? 'style="margin-right: 0.75rem; margin-left: 0;"' : 'style="margin-left: 0.75rem; margin-right: 0;"'}>
                            ${hasProfilePicture ?
                                `<img src="${profilePictureUrl}"
                                      alt="${user.first_name || 'User'}"
                                      class="w-full h-full object-cover"
                                      onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'font-semibold text-indigo-600\'>${user.first_name ? user.first_name.charAt(0).toUpperCase() : 'U'}</span>';">` :
                                `<span class="font-semibold text-indigo-600">${user.first_name ? user.first_name.charAt(0).toUpperCase() : 'U'}</span>`
                            }
                        </div>
                        <div ${language === 'ar' ? 'style="text-align: right; margin-right: 0.75rem;"' : ''}>
                            <p class="font-medium">${user.first_name+' '+user.last_name || 'Not specified'}</p>
                            <p class="text-sm text-gray-500">#${user.id}</p>
                        </div>
                    </div>
                </td>
                <td class="p-3">
                    <div class="flex items-center" ${language === 'ar' ? 'style="direction: rtl; text-align: right; flex-direction: row-reverse;"' : ''}>
                        <i class="fas fa-phone text-gray-400 text-sm" ${language === 'ar' ? 'style="margin-right: 0.5rem; margin-left: 0;"' : 'style="margin-left: 0.5rem; margin-right: 0;"'}></i>
                        <span>${user.phone_number || 'Not specified'}</span>
                    </div>
                </td>
                <td class="p-3">${user.email || 'Not specified'}</td>
                <td class="p-3">
                    <span class="px-3 py-1 rounded-full text-sm ${user.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'}">
                        ${user.role === 'admin' ? (language === 'ar' ? 'مدير النظام' : 'System Admin') : (language === 'ar' ? 'مستخدم' : 'User')}
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
                    <div class="flex gap-2" ${language === 'ar' ? 'style="direction: rtl; text-align: right; flex-direction: row-reverse;"' : ''}>
                        <button onclick="viewUserDetails(${user.id})"
                                class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm flex items-center">
                            <i class="fas fa-eye" ${language === 'ar' ? 'style="margin-right: 0.25rem; margin-left: 0;"' : 'style="margin-left: 0.25rem; margin-right: 0;"'}></i>
                            ${language === 'ar' ? 'عرض' : 'View'}
                        </button>

                        ${user.role !== 'admin' ?
                            `
                            <button onclick="chargeUserWallet(${user.id})"
                                    class="px-3 py-1 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm flex items-center">
                                <i class="fas fa-wallet" ${language === 'ar' ? 'style="margin-right: 0.25rem; margin-left: 0;"' : 'style="margin-left: 0.25rem; margin-right: 0;"'}></i>
                                ${language === 'ar' ? 'شحن' : 'Charge'}
                            </button>

                            ${user.status !== 'active' ?
                                `<button onclick="approveUser(${user.id})"
                                        class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm flex items-center">
                                    <i class="fas fa-check" ${language === 'ar' ? 'style="margin-right: 0.25rem; margin-left: 0;"' : 'style="margin-left: 0.25rem; margin-right: 0;"'}></i>
                                    ${language === 'ar' ? 'تفعيل' : 'Activate'}
                                </button>`
                            : ''}

                            ${user.status !== 'inactive' ?
                                `<button onclick="rejectUser(${user.id})"
                                        class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm flex items-center">
                                    <i class="fas fa-times" ${language === 'ar' ? 'style="margin-right: 0.25rem; margin-left: 0;"' : 'style="margin-left: 0.25rem; margin-right: 0;"'}></i>
                                    ${language === 'ar' ? 'تعطيل' : 'Deactivate'}
                                </button>`
                            : ''}

                            <button onclick="deleteUser(${user.id})"
                                    class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm flex items-center">
                                <i class="fas fa-trash" ${language === 'ar' ? 'style="margin-right: 0.25rem; margin-left: 0;"' : 'style="margin-left: 0.25rem; margin-right: 0;"'}></i>
                                ${language === 'ar' ? 'حذف' : 'Delete'}
                            </button>
                            `
                        : ''}

                        ${hasPersonalId && personalIdStatus === 'pending' ?
                            `<button onclick="verifyUserIdentity(${user.id})"
                                    class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm flex items-center">
                                <i class="fas fa-id-card" ${language === 'ar' ? 'style="margin-right: 0.25rem; margin-left: 0;"' : 'style="margin-left: 0.25rem; margin-right: 0;"'}></i>
                                ${language === 'ar' ? 'التحقق من الهوية' : 'Verify ID'}
                            </button>`
                        : ''}
                    </div>
                </td>
            </tr>
        `;
            usersBody.innerHTML += row;
        });

        if (response.pagination) {
            totalPages = response.pagination.total_pages || 1;
            currentPage = response.pagination.current_page || 1;

            pagination.classList.remove('hidden');
            const showingText = language === 'ar'
                ? `عرض ${response.users.length} من ${response.pagination.total || 0} مستخدم`
                : `Showing ${response.users.length} of ${response.pagination.total || 0} users`;
            paginationInfo.textContent = showingText;

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

            if (user.personal_id && typeof user.personal_id === 'object') {
                const idStatus = user.personal_id.status || 'pending';
                if (idStatus === 'pending') pendingId++;
                if (idStatus === 'rejected') rejectedId++;
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

    async function viewUserDetails(userId) {
        try {
            showLoading();
            const response = await fetchData(`users/${userId}`);
            const language = window.Alpine.$data.language || 'en';

            if (response && response.user) {
                const user = response.user;

                const profilePictureUrl = getProfilePictureUrl(user.profile_picture);
                const hasProfilePicture = profilePictureUrl !== null;

                const personalIdUrl = getPersonalIdUrl(user.personal_id);
                const hasPersonalId = personalIdUrl !== null;

                let imagePathDisplay = language === 'ar' ? 'لا توجد صورة شخصية' : 'No profile picture';
                if (user.profile_picture) {
                    if (typeof user.profile_picture === 'object' && user.profile_picture.image_path) {
                        imagePathDisplay = user.profile_picture.image_path;
                    } else if (typeof user.profile_picture === 'string') {
                        imagePathDisplay = user.profile_picture;
                    }
                }

                let personalIdPathDisplay = language === 'ar' ? 'لم يتم رفع وثيقة هوية' : 'No ID document uploaded';
                if (user.personal_id) {
                    if (typeof user.personal_id === 'object' && user.personal_id.image_path) {
                        personalIdPathDisplay = user.personal_id.image_path;
                    } else if (typeof user.personal_id === 'string') {
                        personalIdPathDisplay = user.personal_id;
                    }
                }

                let personalIdStatus = language === 'ar' ? 'غير مرفوعة' : 'Not uploaded';
                let personalIdStatusClass = 'bg-gray-100 text-gray-800';
                if (user.personal_id && typeof user.personal_id === 'object') {
                    personalIdStatus = user.personal_id.status || 'Pending';
                    switch(personalIdStatus.toLowerCase()) {
                        case 'verified':
                            personalIdStatusClass = 'bg-green-100 text-green-800';
                            personalIdStatus = language === 'ar' ? 'موثقة' : 'Verified';
                            break;
                        case 'pending':
                            personalIdStatusClass = 'bg-yellow-100 text-yellow-800';
                            personalIdStatus = language === 'ar' ? 'قيد الانتظار' : 'Pending';
                            break;
                        case 'rejected':
                            personalIdStatusClass = 'bg-red-100 text-red-800';
                            personalIdStatus = language === 'ar' ? 'مرفوضة' : 'Rejected';
                            break;
                        default:
                            personalIdStatusClass = 'bg-gray-100 text-gray-800';
                            personalIdStatus = language === 'ar' ? 'غير مرفوعة' : 'Not uploaded';
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
                        <div class="bg-gray-50 p-3 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                            <p class="text-sm text-gray-500">${language === 'ar' ? 'الاسم الكامل' : 'Full Name'}</p>
                            <p class="font-medium">${user.first_name+' '+user.last_name || 'Not specified'}</p>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                            <p class="text-sm text-gray-500">${language === 'ar' ? 'رقم الهاتف' : 'Phone Number'}</p>
                            <p class="font-medium">${user.phone_number || 'Not specified'}</p>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                            <p class="text-sm text-gray-500">${language === 'ar' ? 'البريد الإلكتروني' : 'Email'}</p>
                            <p class="font-medium">${user.email || 'Not specified'}</p>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                            <p class="text-sm text-gray-500">${language === 'ar' ? 'الصلاحية' : 'Role'}</p>
                            <p class="font-medium ${user.role === 'admin' ? 'text-purple-600' : 'text-blue-600'}">
                                ${user.role === 'admin' ? (language === 'ar' ? 'مدير النظام' : 'Admin') : (language === 'ar' ? 'مستخدم' : 'User')}
                            </p>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                            <p class="text-sm text-gray-500">${language === 'ar' ? 'الحالة' : 'Status'}</p>
                            <p class="font-medium ${user.status === 'active' ? 'text-green-600' : 'text-gray-600'}">
                                ${getStatusText(user.status)}
                            </p>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                            <p class="text-sm text-gray-500">${language === 'ar' ? 'تاريخ التسجيل' : 'Registration Date'}</p>
                            <p class="font-medium">${formatDate(user.created_at)}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-3 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                        <p class="text-sm text-gray-500">${language === 'ar' ? 'رصيد المحفظة' : 'Wallet Balance'}</p>
                        <p class="font-bold text-lg ${user.wallet > 0 ? 'text-green-600' : 'text-gray-600'}">
                            $${parseFloat(user.wallet || 0).toFixed(2)}
                        </p>
                    </div>

                    ${hasProfilePicture ? `
                    <div class="bg-gray-50 p-3 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                        <p class="text-sm text-gray-500">${language === 'ar' ? 'صورة الملف الشخصي' : 'Profile Picture'}</p>
                        <div class="mt-2 flex items-center" ${language === 'ar' ? 'style="direction: rtl; text-align: right; flex-direction: row-reverse;"' : ''}>
                            <img src="${profilePictureUrl}"
                                 alt="${user.first_name || 'User'} profile"
                                 class="w-16 h-16 rounded-full object-cover border-2 border-white shadow"
                                 onerror="this.style.display='none';">
                            <div ${language === 'ar' ? 'style="margin-right: 0.75rem;"' : 'style="margin-left: 0.75rem;"'}>
                                <p class="text-xs text-gray-500 truncate max-w-[200px]">${imagePathDisplay}</p>
                                <button onclick="window.open('${profilePictureUrl}', '_blank')"
                                        class="text-xs text-blue-600 hover:text-blue-800 mt-1 flex items-center">
                                    <i class="fas fa-external-link-alt" ${language === 'ar' ? 'style="margin-right: 0.25rem; margin-left: 0;"' : 'style="margin-left: 0.25rem; margin-right: 0;"'}></i>
                                    ${language === 'ar' ? 'فتح الصورة كاملة' : 'Open full image'}
                                </button>
                            </div>
                        </div>
                    </div>
                    ` : ''}

                    <!-- Personal ID Section -->
                    <div class="bg-gray-50 p-3 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                        <div class="flex justify-between items-center mb-2" ${language === 'ar' ? 'style="direction: rtl; text-align: right; flex-direction: row-reverse;"' : ''}>
                            <p class="text-sm text-gray-500">${language === 'ar' ? 'التحقق من الهوية' : 'Identity Verification'}</p>
                            <span class="px-3 py-1 rounded-full text-xs ${personalIdStatusClass}">
                                ${personalIdStatus}
                            </span>
                        </div>

                        ${hasPersonalId ? `
                            <div class="mt-2">
                                <div class="flex items-center" ${language === 'ar' ? 'style="direction: rtl; text-align: right; flex-direction: row-reverse;"' : ''}>
                                    <img src="${personalIdUrl}"
                                         alt="Personal ID Document"
                                         class="w-32 h-20 object-cover border rounded-lg shadow cursor-pointer"
                                         onclick="window.open('${personalIdUrl}', '_blank')"
                                         onerror="this.style.display='none';">
                                    <div ${language === 'ar' ? 'style="margin-right: 0.75rem;"' : 'style="margin-left: 0.75rem;"'}>
                                        <p class="text-xs text-gray-500 truncate max-w-[200px]">${personalIdPathDisplay}</p>
                                        <button onclick="window.open('${personalIdUrl}', '_blank')"
                                                class="text-xs text-blue-600 hover:text-blue-800 mt-1 flex items-center">
                                            <i class="fas fa-external-link-alt" ${language === 'ar' ? 'style="margin-right: 0.25rem; margin-left: 0;"' : 'style="margin-left: 0.25rem; margin-right: 0;"'}></i>
                                            ${language === 'ar' ? 'عرض وثيقة الهوية' : 'View ID Document'}
                                        </button>
                                    </div>
                                </div>
                                ${user.personal_id && typeof user.personal_id === 'object' ? `
                                    <div class="mt-2 space-y-1 text-xs text-gray-600" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                                        ${user.personal_id.created_at ? `<p>${language === 'ar' ? 'تم الرفع' : 'Uploaded'}: ${formatDate(user.personal_id.created_at)}</p>` : ''}
                                        ${user.personal_id.verified_at ? `<p>${language === 'ar' ? 'تم التحقق' : 'Verified'}: ${formatDate(user.personal_id.verified_at)}</p>` : ''}
                                        ${user.personal_id.rejected_at ? `<p>${language === 'ar' ? 'تم الرفض' : 'Rejected'}: ${formatDate(user.personal_id.rejected_at)}</p>` : ''}
                                        ${user.personal_id.notes ? `<p>${language === 'ar' ? 'ملاحظات' : 'Notes'}: ${user.personal_id.notes}</p>` : ''}
                                    </div>
                                ` : ''}
                            </div>
                        ` : `
                            <div class="text-center py-4">
                                <div class="inline-block p-3 bg-gray-200 rounded-full mb-2">
                                    <i class="fas fa-id-card text-gray-400"></i>
                                </div>
                                <p class="text-sm text-gray-500">${language === 'ar' ? 'لم يتم رفع وثيقة هوية' : 'No ID document uploaded'}</p>
                            </div>
                        `}
                    </div>

                    ${user.profile_picture && typeof user.profile_picture === 'object' ? `
                    <div class="bg-gray-50 p-3 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                        <p class="text-sm text-gray-500">${language === 'ar' ? 'تفاصيل الصورة الشخصية' : 'Profile Image Details'}</p>
                        <div class="space-y-1 text-xs text-gray-600 mt-1" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                            ${user.profile_picture.id ? `<p>ID: ${user.profile_picture.id}</p>` : ''}
                            ${user.profile_picture.created_at ? `<p>${language === 'ar' ? 'تم الرفع' : 'Uploaded'}: ${formatDate(user.profile_picture.created_at)}</p>` : ''}
                            ${user.profile_picture.type ? `<p>${language === 'ar' ? 'النوع' : 'Type'}: ${user.profile_picture.type}</p>` : ''}
                        </div>
                    </div>
                    ` : ''}

                    ${user.personal_id && typeof user.personal_id === 'object' ? `
                    <div class="bg-gray-50 p-3 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                        <p class="text-sm text-gray-500">${language === 'ar' ? 'تفاصيل وثيقة الهوية' : 'ID Document Details'}</p>
                        <div class="space-y-1 text-xs text-gray-600 mt-1" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                            ${user.personal_id.id ? `<p>ID: ${user.personal_id.id}</p>` : ''}
                            ${user.personal_id.created_at ? `<p>${language === 'ar' ? 'تم الرفع' : 'Uploaded'}: ${formatDate(user.personal_id.created_at)}</p>` : ''}
                            ${user.personal_id.type ? `<p>${language === 'ar' ? 'النوع' : 'Type'}: ${user.personal_id.type}</p>` : ''}
                        </div>
                    </div>
                    ` : ''}

                    <!-- Action Buttons in Modal -->
                    <div class="flex gap-3 pt-4" ${language === 'ar' ? 'style="direction: rtl; text-align: right; flex-direction: row-reverse;"' : ''}>
                        ${user.role !== 'admin' ?
                            `
                            <button onclick="openChargeModal(${user.id}, ${user.wallet || 0})"
                                    class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center justify-center">
                                <i class="fas fa-wallet" ${language === 'ar' ? 'style="margin-right: 0.5rem; margin-left: 0;"' : 'style="margin-left: 0.5rem; margin-right: 0;"'}></i>
                                ${language === 'ar' ? 'شحن المحفظة' : 'Charge Wallet'}
                            </button>

                            ${user.status !== 'active' ?
                                `<button onclick="approveUser(${user.id})"
                                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center justify-center">
                                    <i class="fas fa-check" ${language === 'ar' ? 'style="margin-right: 0.5rem; margin-left: 0;"' : 'style="margin-left: 0.5rem; margin-right: 0;"'}></i>
                                    ${language === 'ar' ? 'تفعيل المستخدم' : 'Activate User'}
                                </button>`
                            : ''}

                            ${user.status !== 'inactive' ?
                                `<button onclick="rejectUser(${user.id})"
                                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center justify-center">
                                    <i class="fas fa-times" ${language === 'ar' ? 'style="margin-right: 0.5rem; margin-left: 0;"' : 'style="margin-left: 0.5rem; margin-right: 0;"'}></i>
                                    ${language === 'ar' ? 'تعطيل المستخدم' : 'Deactivate User'}
                                </button>`
                            : ''}

                            <button onclick="deleteUserPrompt(${user.id})"
                                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center justify-center">
                                <i class="fas fa-trash" ${language === 'ar' ? 'style="margin-right: 0.5rem; margin-left: 0;"' : 'style="margin-left: 0.5rem; margin-right: 0;"'}></i>
                                ${language === 'ar' ? 'حذف المستخدم' : 'Delete User'}
                            </button>
                            `
                        : ''}

                        ${hasPersonalId && personalIdStatus === 'pending' ?
                            `<button onclick="verifyUserIdentity(${user.id})"
                                    class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center justify-center">
                                <i class="fas fa-id-card" ${language === 'ar' ? 'style="margin-right: 0.5rem; margin-left: 0;"' : 'style="margin-left: 0.5rem; margin-right: 0;"'}></i>
                                ${language === 'ar' ? 'التحقق من الهوية' : 'Verify ID'}
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
        const language = window.Alpine.$data.language || 'en';
        const confirmMsg = language === 'ar' ? 'هل تريد تفعيل هذا المستخدم؟' : 'Do you want to activate this user?';

        if (!confirm(confirmMsg)) return;

        try {
            showLoading();
            const response = await fetchData(`approve-user/${userId}`, { method: 'GET' });

            if (response && response.success) {
                showSuccess(language === 'ar' ? 'تم تفعيل المستخدم بنجاح' : 'User activated successfully');
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
        const language = window.Alpine.$data.language || 'en';
        const confirmMsg = language === 'ar' ? 'هل تريد تعطيل هذا المستخدم؟' : 'Do you want to deactivate this user?';

        if (!confirm(confirmMsg)) return;

        try {
            showLoading();
            const response = await fetchData(`reject-user/${userId}`, { method: 'GET' });

            if (response && response.success) {
                showSuccess(language === 'ar' ? 'تم تعطيل المستخدم بنجاح' : 'User deactivated successfully');
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
        const language = window.Alpine.$data.language || 'en';
        const confirmMsg = language === 'ar' ? 'هل تريد التحقق من هوية هذا المستخدم؟' : 'Do you want to verify this user\'s identity?';

        if (!confirm(confirmMsg)) return;

        try {
            showLoading();
            const response = await fetchData(`verify-identity/${userId}`, { method: 'POST' });

            if (response && response.success) {
                showSuccess(language === 'ar' ? 'تم التحقق من الهوية بنجاح' : 'Identity verified successfully');
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

    async function chargeUserWallet(userId) {
        try {
            showLoading();
            const response = await fetchData(`users/${userId}`);

            if (response && response.user) {
                currentUserIdForCharge = userId;
                currentUserWallet = parseFloat(response.user.wallet || 0);

                openChargeModal(userId, currentUserWallet);
            } else {
                throw new Error('Failed to load user data');
            }
        } catch (error) {
            showError('Error loading user data: ' + error.message);
        } finally {
            hideLoading();
        }
    }

    function openChargeModal(userId, walletBalance) {
        currentUserIdForCharge = userId;
        currentUserWallet = parseFloat(walletBalance || 0);

        document.getElementById('charge-modal').classList.remove('hidden');
        document.getElementById('charge-amount').focus();
        document.getElementById('current-wallet').textContent = `$${currentUserWallet.toFixed(2)}`;

        document.getElementById('charge-amount').addEventListener('input', function() {
            updateWalletPreview();
        });
    }

    function updateWalletPreview() {
        const amountInput = document.getElementById('charge-amount');
        const amount = parseFloat(amountInput.value) || 0;

        if (amount > 0) {
            const newBalance = currentUserWallet + amount;
            document.getElementById('new-wallet').textContent = `$${newBalance.toFixed(2)}`;
            document.getElementById('wallet-info').classList.remove('hidden');
        } else {
            document.getElementById('wallet-info').classList.add('hidden');
        }
    }

    async function confirmChargeWallet() {
        const language = window.Alpine.$data.language || 'en';
        const amountInput = document.getElementById('charge-amount');
        const amount = parseFloat(amountInput.value);

        if (!amount || amount < 1) {
            showError('Please enter a valid amount (minimum $1)');
            return;
        }

        if (!currentUserIdForCharge) {
            showError('User ID not found');
            return;
        }

        const confirmMsg = (language === 'ar' ? 'هل أنت متأكد من أنك تريد شحن $' : 'Are you sure you want to charge $') + `${amount.toFixed(2)}` + (language === 'ar' ? ' إلى محفظة المستخدم؟' : ' to user\'s wallet?');
        if (!confirm(confirmMsg)) {
            return;
        }

        try {
            showLoading();

            const token = localStorage.getItem('admin_token');
            if (!token) {
                window.location.href = '/admin/login';
                return;
            }

            const apiUrl = `/api/admin/users/${currentUserIdForCharge}/charge-wallet`;
            console.log(' Sending charge request to:', apiUrl);
            console.log(' Amount:', amount);

            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    amount: amount
                })
            });

            console.log(' Response status:', response.status);

            if (response.status === 401) {
                localStorage.removeItem('admin_token');
                window.location.href = '/admin/login';
                return;
            }

            const data = await response.json();
            console.log('📦 Response data:', data);

            if (data.success) {
                const successMsg = (language === 'ar' ? 'تم شحن المحفظة بنجاح! الرصيد الجديد: $' : 'Wallet charged successfully! New balance: $') + `${data.wallet_balance.toFixed(2)}`;
                showSuccess(successMsg);
                closeChargeModal();
                closeUserModal();
                loadUsers();
            } else {
                throw new Error(data.message || 'Failed to charge wallet');
            }
        } catch (error) {
            console.error(' Error charging wallet:', error);
            showError(error.message);
        } finally {
            hideLoading();
        }
    }

    async function deleteUser(userId) {
        const language = window.Alpine.$data.language || 'en';
        const confirmMsg = language === 'ar' ? 'هل أنت متأكد من حذف هذا المستخدم؟ لا يمكن التراجع عن هذا الإجراء!' : 'Are you sure you want to delete this user? This action cannot be undone!';

        if (!confirm(confirmMsg)) {
            return;
        }

        try {
            showLoading();
            const response = await fetchData(`users/${userId}`, { method: 'DELETE' });

            if (response && response.success) {
                showSuccess(language === 'ar' ? 'تم حذف المستخدم بنجاح' : 'User deleted successfully');
                loadUsers();
            } else {
                throw new Error(response?.message || 'Failed to delete user');
            }
        } catch (error) {
            showError(error.message);
        } finally {
            hideLoading();
        }
    }

    function deleteUserPrompt(userId) {
        const language = window.Alpine.$data.language || 'en';
        const confirmMsg = language === 'ar' ? 'هل أنت متأكد من حذف هذا المستخدم؟ لا يمكن التراجع عن هذا الإجراء!' : 'Are you sure you want to delete this user? This action cannot be undone!';

        if (confirm(confirmMsg)) {
            deleteUser(userId);
            closeUserModal();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log(' Users page loaded');

        const token = localStorage.getItem('admin_token');
        if (!token) {
            console.error(' No token - redirecting to login');
            window.location.href = '/admin/login';
            return;
        }

        console.log(' Token exists, loading users...');

        const searchInput = document.getElementById('search-input');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentPage = 1;
                loadUsers();
            }, 500);
        });

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

        loadUsers();
    });
</script>
@endsection