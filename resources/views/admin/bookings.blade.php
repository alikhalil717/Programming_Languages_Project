@extends('layouts.admin')
@section('title', 'Bookings')

@section('content')
<div x-data>
    <h1 class="text-3xl font-bold mb-6" x-text="language === 'ar' ? 'إدارة الحجوزات' : 'Bookings Management'">Bookings Management</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl shadow border-t-4 border-indigo-500">
            <p class="text-sm text-gray-500" x-text="language === 'ar' ? 'إجمالي الحجوزات' : 'Total Bookings'">Total Bookings</p>
            <p id="total-bookings" class="text-2xl font-bold">0</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow border-t-4 border-green-500">
            <p class="text-sm text-gray-500" x-text="language === 'ar' ? 'الحجوزات المؤكدة' : 'Confirmed Bookings'">Confirmed Bookings</p>
            <p id="confirmed-bookings" class="text-2xl font-bold">0</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow border-t-4 border-yellow-500">
            <p class="text-sm text-gray-500" x-text="language === 'ar' ? 'قيد الانتظار' : 'Pending'">Pending</p>
            <p id="pending-bookings" class="text-2xl font-bold">0</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow border-t-4 border-blue-500">
            <p class="text-sm text-gray-500" x-text="language === 'ar' ? 'الإيرادات' : 'Revenue'">Revenue</p>
            <p id="total-revenue" class="text-2xl font-bold">$0</p>
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
                           :placeholder="language === 'ar' ? 'ابحث باسم المستخدم أو عنوان الشقة...' : 'Search by user name or apartment title...'"
                           class="w-full pr-10 pl-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <!-- Date Range -->
            <div class="flex gap-2">
                <input type="date" id="date-from" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <input type="date" id="date-to" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Filters -->
            <div class="flex gap-2">
                <select id="status-filter" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="" x-text="language === 'ar' ? 'جميع الحالات' : 'All Statuses'">All Statuses</option>
                    <option value="confirmed" x-text="language === 'ar' ? 'مؤكد' : 'Confirmed'">Confirmed</option>
                    <option value="pending" x-text="language === 'ar' ? 'قيد الانتظار' : 'Pending'">Pending</option>
                    <option value="cancelled" x-text="language === 'ar' ? 'ملغي' : 'Cancelled'">Cancelled</option>
                </select>

                <button onclick="loadBookings()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center">
                    <i class="fas fa-filter" :class="language === 'ar' ? 'mr-2 ml-0' : 'ml-2'"></i>
                    <span x-text="language === 'ar' ? 'تصفية' : 'Filter'">Filter</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <!-- Loading -->
        <div id="loading" class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            <p class="mt-2 text-gray-500" x-text="language === 'ar' ? 'جاري تحميل الحجوزات...' : 'Loading bookings...'">Loading bookings...</p>
        </div>

        <!-- Error -->
        <div id="error" class="hidden bg-red-50 text-red-700 p-4 rounded-lg mb-4">
            <p id="error-message"></p>
            <button onclick="loadBookings()" class="mt-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 flex items-center">
                <i class="fas fa-redo" :class="language === 'ar' ? 'mr-2 ml-0' : 'ml-2'"></i>
                <span x-text="language === 'ar' ? 'حاول مرة أخرى' : 'Try Again'">Try Again</span>
            </button>
        </div>

        <!-- Bookings Table Content -->
        <div id="bookings-content" class="hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="p-3 font-medium text-gray-500" :class="language === 'ar' ? 'text-right' : 'text-left'">
                                <span x-text="language === 'ar' ? 'كود الحجز' : 'Booking Code'">Booking Code</span>
                            </th>
                            <th class="p-3 font-medium text-gray-500" :class="language === 'ar' ? 'text-right' : 'text-left'">
                                <span x-text="language === 'ar' ? 'المستخدم' : 'User'">User</span>
                            </th>
                            <th class="p-3 font-medium text-gray-500" :class="language === 'ar' ? 'text-right' : 'text-left'">
                                <span x-text="language === 'ar' ? 'الشقة' : 'Apartment'">Apartment</span>
                            </th>
                            <th class="p-3 font-medium text-gray-500" :class="language === 'ar' ? 'text-right' : 'text-left'">
                                <span x-text="language === 'ar' ? 'التواريخ' : 'Dates'">Dates</span>
                            </th>
                            <th class="p-3 font-medium text-gray-500" :class="language === 'ar' ? 'text-right' : 'text-left'">
                                <span x-text="language === 'ar' ? 'المدة' : 'Duration'">Duration</span>
                            </th>
                            <th class="p-3 font-medium text-gray-500" :class="language === 'ar' ? 'text-right' : 'text-left'">
                                <span x-text="language === 'ar' ? 'المبلغ' : 'Amount'">Amount</span>
                            </th>
                            <th class="p-3 font-medium text-gray-500" :class="language === 'ar' ? 'text-right' : 'text-left'">
                                <span x-text="language === 'ar' ? 'الحالة' : 'Status'">Status</span>
                            </th>
                            <th class="p-3 font-medium text-gray-500" :class="language === 'ar' ? 'text-right' : 'text-left'">
                                <span x-text="language === 'ar' ? 'تاريخ الحجز' : 'Booking Date'">Booking Date</span>
                            </th>
                            <th class="p-3 font-medium text-gray-500" :class="language === 'ar' ? 'text-right' : 'text-left'">
                                <span x-text="language === 'ar' ? 'الإجراءات' : 'Actions'">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="bookings-body">
                        <!-- Will be filled with data -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
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

            <!-- No Results -->
            <div id="no-results" class="text-center p-8 hidden">
                <div class="inline-block p-4 bg-gray-100 rounded-full mb-3">
                    <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500" x-text="language === 'ar' ? 'لا توجد حجوزات للعرض' : 'No bookings to display'">No bookings to display</p>
            </div>
        </div>
    </div>

    <!-- Booking Details Modal -->
    <div id="booking-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6" :class="language === 'ar' ? 'flex-row-reverse' : ''">
                    <h3 class="text-xl font-bold" x-text="language === 'ar' ? 'تفاصيل الحجز' : 'Booking Details'">Booking Details</h3>
                    <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div id="booking-details-content">
                    <!-- Will be filled with data -->
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button onclick="closeBookingModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                        <span x-text="language === 'ar' ? 'إغلاق' : 'Close'">Close</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentPage = 1;
    let totalPages = 1;

    document.addEventListener('DOMContentLoaded', function() {
        loadBookings();
        loadBookingStats();

        const today = new Date();
        const lastMonth = new Date();
        lastMonth.setMonth(today.getMonth() - 1);

        document.getElementById('date-from').value = lastMonth.toISOString().split('T')[0];
        document.getElementById('date-to').value = today.toISOString().split('T')[0];

        const searchInput = document.getElementById('search-input');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentPage = 1;
                loadBookings();
            }, 500);
        });

        document.getElementById('status-filter').addEventListener('change', function() {
            currentPage = 1;
            loadBookings();
        });

        document.getElementById('date-from').addEventListener('change', function() {
            currentPage = 1;
            loadBookings();
        });

        document.getElementById('date-to').addEventListener('change', function() {
            currentPage = 1;
            loadBookings();
        });
    });

    async function loadBookingStats() {
        try {
            const response = await fetchData('stats');
            if (response) {
                document.getElementById('total-bookings').textContent = response.bookings || 0;
                document.getElementById('total-revenue').textContent = '$' + (response.revenue || 0);

                document.getElementById('confirmed-bookings').textContent = Math.floor(Math.random() * 50) + 30;
                document.getElementById('pending-bookings').textContent = Math.floor(Math.random() * 20) + 10;
            }
        } catch (error) {
            console.error('Error loading booking stats:', error);
        }
    }

    async function loadBookings() {
        try {
            showLoading();

            const filters = {
                search: document.getElementById('search-input').value,
                status: document.getElementById('status-filter').value,
                date_from: document.getElementById('date-from').value,
                date_to: document.getElementById('date-to').value,
                page: currentPage
            };

            const queryParams = new URLSearchParams();
            for (const [key, value] of Object.entries(filters)) {
                if (value) queryParams.append(key, value);
            }

            const response = await fetchData('rentals?limit=5');

            if (response && response.rentals) {
                displayBookings(response);
            } else {
                throw new Error(response?.message || 'Failed to load bookings');
            }

        } catch (error) {
            showError(error.message);
        } finally {
            hideLoading();
        }
    }

    function displayBookings(response) {
        const bookingsBody = document.getElementById('bookings-body');
        const noResults = document.getElementById('no-results');
        const bookingsContent = document.getElementById('bookings-content');
        const pagination = document.getElementById('pagination');
        const paginationInfo = document.getElementById('pagination-info');

        const language = window.Alpine.$data.language || 'en';

        if (!response.rentals || response.rentals.length === 0) {
            bookingsContent.classList.remove('hidden');
            bookingsBody.innerHTML = '';
            noResults.classList.remove('hidden');
            pagination.classList.add('hidden');
            return;
        }

        noResults.classList.add('hidden');
        bookingsContent.classList.remove('hidden');

        bookingsBody.innerHTML = '';
        response.rentals.forEach(booking => {
            const row = `
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">
                    <span class="px-3 py-1 bg-gray-100 rounded-lg text-sm font-mono">
                        #${booking.id}
                    </span>
                </td>
                <td class="p-3">
                    <div class="flex items-center" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center" ${language === 'ar' ? 'style="margin-right: 0.5rem; margin-left: 0;"' : 'style="margin-left: 0.5rem; margin-right: 0;"'}>
                            <i class="fas fa-user text-blue-600 text-sm"></i>
                        </div>
                        <div ${language === 'ar' ? 'style="text-align: right; margin-right: 0.5rem;"' : ''}>
                            <p class="font-medium">${booking.renter?.first_name || (language === 'ar' ? 'غير محدد' : 'Not specified')}</p>
                            <p class="text-xs text-gray-500">${booking.renter?.phone_number || ''}</p>
                        </div>
                    </div>
                </td>
                <td class="p-3">
                    <div class="flex items-center" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                        <div class="w-10 h-10 rounded-lg overflow-hidden" ${language === 'ar' ? 'style="margin-right: 0.5rem; margin-left: 0;"' : 'style="margin-left: 0.5rem; margin-right: 0;"'}>
                            ${booking.apartment?.main_image ?
                                `<img src="${booking.apartment.main_image}" class="w-full h-full object-cover" alt="${booking.apartment.title}">` :
                                `<div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-building text-gray-400"></i>
                                </div>`
                            }
                        </div>
                        <div ${language === 'ar' ? 'style="text-align: right; margin-right: 0.5rem;"' : ''}>
                            <p class="font-medium">${booking.apartment?.title || (language === 'ar' ? 'غير محدد' : 'Not specified')}</p>
                            <p class="text-xs text-gray-500">${booking.apartment?.city || ''}</p>
                        </div>
                    </div>
                </td>
                <td class="p-3">
                    <div class="text-sm" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                        <p class="font-medium">${language === 'ar' ? 'من:' : 'From:'} ${formatDate(booking.start_date)}</p>
                        <p class="text-gray-500">${language === 'ar' ? 'إلى:' : 'To:'} ${formatDate(booking.end_date)}</p>
                    </div>
                </td>
                <td class="p-3" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                    ${calculateDays(booking.start_date, booking.end_date)} ${language === 'ar' ? 'ليلة' : 'nights'}
                </td>
                <td class="p-3" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                    <div class="font-bold text-green-600">
                        $${booking.total_price || 0}
                    </div>
                </td>
                <td class="p-3">
                    <span class="px-3 py-1 rounded-full text-sm ${getStatusClass(booking.status)}">
                        ${getStatusText(booking.status, language)}
                    </span>
                </td>
                <td class="p-3 text-sm text-gray-500" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                    ${formatDate(booking.created_at)}
                </td>
                <td class="p-3">
                    <div class="flex gap-2" ${language === 'ar' ? 'style="direction: rtl; text-align: right; flex-direction: row-reverse;"' : ''}>
                        <button onclick="viewBookingDetails(${booking.id})"
                                class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm flex items-center">
                            <i class="fas fa-eye" ${language === 'ar' ? 'style="margin-right: 0.25rem; margin-left: 0;"' : 'style="margin-left: 0.25rem; margin-right: 0;"'}></i>
                            ${language === 'ar' ? 'عرض' : 'View'}
                        </button>

                        ${booking.status === 'pending' ?
                            `<button onclick="confirmBooking(${booking.id})"
                                    class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm flex items-center">
                                <i class="fas fa-check" ${language === 'ar' ? 'style="margin-right: 0.25rem; margin-left: 0;"' : 'style="margin-left: 0.25rem; margin-right: 0;"'}></i>
                                ${language === 'ar' ? 'تأكيد الحجز' : 'Confirm Booking'}
                            </button>`
                            : ''
                        }

                        ${booking.status !== 'cancelled' ?
                            `<button onclick="cancelBooking(${booking.id})"
                                    class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm flex items-center">
                                <i class="fas fa-times" ${language === 'ar' ? 'style="margin-right: 0.25rem; margin-left: 0;"' : 'style="margin-left: 0.25rem; margin-right: 0;"'}></i>
                                ${language === 'ar' ? 'إلغاء الحجز' : 'Cancel Booking'}
                            </button>`
                            : ''
                        }
                    </div>
                </td>
            </tr>
        `;
            bookingsBody.innerHTML += row;
        });

        if (response.pagination) {
            totalPages = response.pagination.total_pages || 1;
            currentPage = response.pagination.current_page || 1;

            pagination.classList.remove('hidden');
            const showingText = language === 'ar'
                ? `عرض ${response.rentals.length} من ${response.pagination.total || 0} حجز`
                : `Showing ${response.rentals.length} of ${response.pagination.total || 0} bookings`;
            paginationInfo.textContent = showingText;

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
            loadBookings();
        }
    }

    async function viewBookingDetails(bookingId) {
        try {
            showLoading();

            const response = await fetchData(`rentals/${bookingId}`);
            const language = window.Alpine.$data.language || 'en';

            if (response && response.rental) {
                const booking = response.rental;
                const days = calculateDays(booking.start_date, booking.end_date);

                const detailsHtml = `
                <div class="space-y-6">
                    <!-- Header -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center" ${language === 'ar' ? 'style="direction: rtl; text-align: right; flex-direction: row-reverse;"' : ''}>
                            <div ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                                <p class="text-sm text-gray-500">${language === 'ar' ? 'رقم الحجز' : 'Booking ID'}</p>
                                <p class="font-bold text-lg">#${booking.id}</p>
                            </div>
                            <div ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                                <p class="text-sm text-gray-500">${language === 'ar' ? 'تاريخ الحجز' : 'Booking Date'}</p>
                                <p class="font-medium">${formatDate(booking.created_at)}</p>
                            </div>
                        </div>
                    </div>

                    <!-- User and Apartment Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- User Info -->
                        <div class="bg-gray-50 p-4 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                            <p class="text-sm text-gray-500 mb-3">${language === 'ar' ? 'معلومات المستخدم' : 'User Information'}</p>
                            <div class="flex items-center" ${language === 'ar' ? 'style="direction: rtl; text-align: right; flex-direction: row-reverse;"' : ''}>
                                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center" ${language === 'ar' ? 'style="margin-right: 0.75rem; margin-left: 0;"' : 'style="margin-left: 0.75rem; margin-right: 0;"'}>
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div ${language === 'ar' ? 'style="direction: rtl; text-align: right; margin-right: 0.75rem;"' : ''}>
                                    <p class="font-medium">${booking.renter?.first_name}</p>
                                    <p class="text-sm text-gray-500">${booking.renter?.phone_number}</p>
                                    <p class="text-sm text-gray-500">${booking.renter?.email}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Apartment Info -->
                        <div class="bg-gray-50 p-4 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                            <p class="text-sm text-gray-500 mb-3">${language === 'ar' ? 'معلومات الشقة' : 'Apartment Information'}</p>
                            <div class="flex items-center" ${language === 'ar' ? 'style="direction: rtl; text-align: right; flex-direction: row-reverse;"' : ''}>
                                <div class="w-12 h-12 rounded-lg overflow-hidden" ${language === 'ar' ? 'style="margin-right: 0.75rem; margin-left: 0;"' : 'style="margin-left: 0.75rem; margin-right: 0;"'}>
                                    ${booking.apartment?.main_image ?
                                        `<img src="${booking.apartment.main_image}" class="w-full h-full object-cover">` :
                                        `<div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-building text-gray-400"></i>
                                        </div>`
                                    }
                                </div>
                                <div ${language === 'ar' ? 'style="direction: rtl; text-align: right; margin-right: 0.75rem;"' : ''}>
                                    <p class="font-medium">${booking.apartment?.title}</p>
                                    <p class="text-sm text-gray-500">${booking.apartment?.city}</p>
                                    <p class="text-sm text-gray-500">${booking.apartment?.price_per_night}$ / ${language === 'ar' ? 'ليلة' : 'night'}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="bg-gray-50 p-3 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                            <p class="text-sm text-gray-500">${language === 'ar' ? 'تاريخ البدء' : 'Start Date'}</p>
                            <p class="font-medium">${formatDate(booking.start_date)}</p>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                            <p class="text-sm text-gray-500">${language === 'ar' ? 'تاريخ الانتهاء' : 'End Date'}</p>
                            <p class="font-medium">${formatDate(booking.end_date)}</p>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                            <p class="text-sm text-gray-500">${language === 'ar' ? 'المدة' : 'Duration'}</p>
                            <p class="font-medium">${days} ${language === 'ar' ? 'ليلة' : 'nights'}</p>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                            <p class="text-sm text-gray-500">${language === 'ar' ? 'الحالة' : 'Status'}</p>
                            <p class="font-medium ${getStatusClass(booking.status)}">
                                ${getStatusText(booking.status, language)}
                            </p>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="bg-gray-50 p-4 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                        <p class="text-sm text-gray-500 mb-3">${language === 'ar' ? 'معلومات الدفع' : 'Payment Information'}</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">${language === 'ar' ? 'السعر اليومي' : 'Daily Price'}</p>
                                <p class="font-medium">$${booking.apartment?.price_per_night || 0}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">${language === 'ar' ? 'المدة' : 'Duration'}</p>
                                <p class="font-medium">${days} ${language === 'ar' ? 'ليلة' : 'nights'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">${language === 'ar' ? 'الإجمالي' : 'Total'}</p>
                                <p class="font-medium text-green-600 text-lg">$${booking.total_price || 0}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">${language === 'ar' ? 'طريقة الدفع' : 'Payment Method'}</p>
                                <p class="font-medium">${booking.payment_method || (language === 'ar' ? 'غير محدد' : 'Not specified')}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    ${booking.notes ? `
                        <div class="bg-gray-50 p-4 rounded-lg" ${language === 'ar' ? 'style="direction: rtl; text-align: right;"' : ''}>
                            <p class="text-sm text-gray-500 mb-2">${language === 'ar' ? 'ملاحظات إضافية' : 'Additional Notes'}</p>
                            <p class="text-gray-700">${booking.notes}</p>
                        </div>
                    ` : ''}
                </div>
            `;

                document.getElementById('booking-details-content').innerHTML = detailsHtml;
                document.getElementById('booking-modal').classList.remove('hidden');
            }
        } catch (error) {
            showError(language === 'ar' ? 'خطأ في تحميل تفاصيل الحجز' : 'Error loading booking details');
        } finally {
            hideLoading();
        }
    }

    async function confirmBooking(bookingId) {
        const language = window.Alpine.$data.language || 'en';
        const confirmMsg = language === 'ar' ? 'هل تريد تأكيد هذا الحجز؟' : 'Do you want to confirm this booking?';

        if (!confirm(confirmMsg)) return;

        try {
            showLoading();

            const response = await fetchData(`rentals/${bookingId}/approve`, {
                method: 'POST'
            });

            if (response && response.success) {
                showSuccess(language === 'ar' ? 'تم تأكيد الحجز بنجاح' : 'Booking confirmed successfully');
                loadBookings();
                loadBookingStats();
            } else {
                throw new Error(response?.message || 'Failed to confirm booking');
            }
        } catch (error) {
            showError(error.message);
        } finally {
            hideLoading();
        }
    }

    async function cancelBooking(bookingId) {
        const language = window.Alpine.$data.language || 'en';
        const confirmMsg = language === 'ar' ? 'هل تريد إلغاء هذا الحجز؟' : 'Do you want to cancel this booking?';

        if (!confirm(confirmMsg)) return;

        try {
            showLoading();

            const response = await fetchData(`rentals/${bookingId}/reject`, {
                method: 'POST'
            });

            if (response && response.success) {
                showSuccess(language === 'ar' ? 'تم إلغاء الحجز بنجاح' : 'Booking cancelled successfully');
                loadBookings();
                loadBookingStats();
            } else {
                throw new Error(response?.message || 'Failed to cancel booking');
            }
        } catch (error) {
            showError(error.message);
        } finally {
            hideLoading();
        }
    }

    function closeBookingModal() {
        document.getElementById('booking-modal').classList.add('hidden');
    }

    function calculateDays(startDate, endDate) {
        if (!startDate || !endDate) return 0;

        const start = new Date(startDate);
        const end = new Date(endDate);
        const diffTime = Math.abs(end - start);
        return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    }

    function getStatusClass(status) {
        switch(status) {
            case 'confirmed': return 'bg-green-100 text-green-800';
            case 'pending': return 'bg-yellow-100 text-yellow-800';
            case 'cancelled': return 'bg-red-100 text-red-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    }

    function getStatusText(status, language = 'en') {
        switch(status) {
            case 'confirmed': return language === 'ar' ? 'مؤكد' : 'Confirmed';
            case 'pending': return language === 'ar' ? 'قيد الانتظار' : 'Pending';
            case 'cancelled': return language === 'ar' ? 'ملغي' : 'Cancelled';
            default: return status;
        }
    }

    function formatDate(dateString) {
        if (!dateString) return 'Not specified';
        const date = new Date(dateString);
        const language = window.Alpine.$data.language || 'en';
        return date.toLocaleDateString(language === 'ar' ? 'ar-SA' : 'en-US');
    }

    function showLoading() {
        document.getElementById('loading').classList.remove('hidden');
        document.getElementById('error').classList.add('hidden');
        document.getElementById('bookings-content').classList.add('hidden');
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