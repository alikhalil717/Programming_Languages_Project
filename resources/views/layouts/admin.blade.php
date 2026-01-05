<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') | Tanzan Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] { display: none; }
        .menu-link {
            transition: all 0.3s ease;
        }
        .menu-link:hover {
            background-color: #eef2ff;
            color: #4f46e5;
            font-weight: 600;
        }
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        :root {
            --dir: ltr;
        }
        [dir="rtl"] {
            direction: rtl;
            text-align: right;
        }
        [dir="ltr"] {
            direction: ltr;
            text-align: left;
        }
    </style>
</head>

<body class="min-h-screen text-slate-800 bg-cover bg-center bg-fixed"
      style="background-image: url('{{ asset('assets/background.jpg') }}');"
      x-data="{ sidebar: true, loading: false, language: 'en' }"
      :dir="language === 'ar' ? 'rtl' : 'ltr'"
      x-init="language = localStorage.getItem('admin_language') || 'en'">

<div class="flex min-h-screen bg-white/50 backdrop-blur-sm">

    <aside x-cloak
           x-show="sidebar"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="-translate-x-full opacity-0"
           x-transition:enter-end="translate-x-0 opacity-100"
           x-transition:leave="transition ease-in duration-300"
           x-transition:leave-start="translate-x-0 opacity-100"
           x-transition:leave-end="-translate-x-full opacity-0"
           class="bg-white/90 shadow-xl w-64 p-6 space-y-6 border-r fixed inset-y-0 left-0 z-50"
           :class="language === 'ar' ? 'right-0 left-auto border-l border-r-0' : ''">

        <div class="flex items-center gap-3" :class="language === 'ar' ? 'flex-row-reverse' : ''">
            <img src="{{ asset('assets/logo.png') }}" class="w-12 h-12 rounded-xl">
            <div :class="language === 'ar' ? 'text-right' : ''">
                <h1 class="text-xl font-bold text-indigo-600" x-text="translations.app_name[language]">Tanzan Admin</h1>
                <p class="text-xs text-gray-500" x-text="translations.control_panel[language]">Control Panel</p>
            </div>
        </div>

        <div id="user-info" class="p-3 bg-indigo-50 rounded-lg hidden" :class="language === 'ar' ? 'text-right' : ''">
            <p class="text-sm font-medium text-indigo-700" id="user-name"></p>
            <p class="text-xs text-indigo-500" id="user-role"></p>
        </div>

        <nav class="space-y-3 mt-6">
            <a href="{{ route('admin.dashboard') }}"
               class="menu-link block p-3 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition flex items-center"
               :class="language === 'ar' ? 'flex-row-reverse justify-end' : ''">
                <i class="fas fa-home" :class="language === 'ar' ? 'ml-2' : 'mr-2'"></i>
                <span x-text="translations.dashboard[language]">Dashboard</span>
            </a>
            <a href="{{ route('admin.users') }}"
               class="menu-link block p-3 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition flex items-center"
               :class="language === 'ar' ? 'flex-row-reverse justify-end' : ''">
                <i class="fas fa-users" :class="language === 'ar' ? 'ml-2' : 'mr-2'"></i>
                <span x-text="translations.users[language]">Users</span>
            </a>
            <a href="{{ route('admin.apartments') }}"
               class="menu-link block p-3 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition flex items-center"
               :class="language === 'ar' ? 'flex-row-reverse justify-end' : ''">
                <i class="fas fa-building" :class="language === 'ar' ? 'ml-2' : 'mr-2'"></i>
                <span x-text="translations.apartments[language]">Apartments</span>
            </a>
            <a href="{{ route('admin.bookings') }}"
               class="menu-link block p-3 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition flex items-center"
               :class="language === 'ar' ? 'flex-row-reverse justify-end' : ''">
                <i class="fas fa-calendar-check" :class="language === 'ar' ? 'ml-2' : 'mr-2'"></i>
                <span x-text="translations.bookings[language]">Bookings</span>
            </a>
            <a href="{{ route('admin.messages') }}"
               class="menu-link block p-3 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition flex items-center"
               :class="language === 'ar' ? 'flex-row-reverse justify-end' : ''">
                <i class="fas fa-envelope" :class="language === 'ar' ? 'ml-2' : 'mr-2'"></i>
                <span x-text="translations.messages[language]">Messages</span>
            </a>
            <a href="{{ route('admin.reviews') }}"
               class="menu-link block p-3 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition flex items-center"
               :class="language === 'ar' ? 'flex-row-reverse justify-end' : ''">
                <i class="fas fa-star" :class="language === 'ar' ? 'ml-2' : 'mr-2'"></i>
                <span x-text="translations.reviews[language]">Reviews</span>
            </a>
            <a href="{{ route('admin.settings') }}"
               class="menu-link block p-3 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition flex items-center"
               :class="language === 'ar' ? 'flex-row-reverse justify-end' : ''">
                <i class="fas fa-cog" :class="language === 'ar' ? 'ml-2' : 'mr-2'"></i>
                <span x-text="translations.settings[language]">Settings</span>
            </a>

            <!-- Language Switcher -->
            <div class="p-3 border-t mt-6">
                <div class="flex items-center justify-between" :class="language === 'ar' ? 'flex-row-reverse' : ''">
                    <span class="text-sm text-gray-600" x-text="translations.language[language]">Language</span>
                    <div class="flex items-center gap-2">
                        <button @click="switchLanguage('ar')"
                                class="w-8 h-8 rounded-full overflow-hidden border-2"
                                :class="language === 'ar' ? 'border-indigo-500' : 'border-gray-300'">
                            <span class="block w-full h-full bg-green-500 text-white flex items-center justify-center text-xs">ع</span>
                        </button>
                        <button @click="switchLanguage('en')"
                                class="w-8 h-8 rounded-full overflow-hidden border-2"
                                :class="language === 'en' ? 'border-indigo-500' : 'border-gray-300'">
                            <span class="block w-full h-full bg-blue-500 text-white flex items-center justify-center text-xs">EN</span>
                        </button>
                    </div>
                </div>
            </div>

            <button onclick="logout()"
                    class="w-full text-left p-3 rounded-lg hover:bg-red-50 hover:text-red-600 text-red-500 transition mt-4 flex items-center"
                    :class="language === 'ar' ? 'flex-row-reverse justify-end' : ''">
                <i class="fas fa-sign-out-alt" :class="language === 'ar' ? 'ml-2' : 'mr-2'"></i>
                <span x-text="translations.logout[language]">Logout</span>
            </button>
        </nav>
    </aside>

    <main class="flex-1 p-6 transition-all duration-300"
          :class="language === 'ar' ? (sidebar ? 'mr-64 md:mr-64' : 'mr-0') : (sidebar ? 'ml-64 md:ml-64' : 'ml-0')">
        <div class="flex justify-between items-center mb-6">
            <button @click="sidebar = !sidebar"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition flex items-center"
                    :class="language === 'ar' ? 'flex-row-reverse' : ''">
                <i class="fas fa-bars" :class="language === 'ar' ? 'ml-2' : 'mr-2'"></i>
                <span x-text="translations.menu[language]">Menu</span>
            </button>

            <div class="flex items-center gap-3">
                <div class="text-sm text-gray-600 hidden md:block" id="current-time"></div>
                <div id="user-welcome" class="hidden" :class="language === 'ar' ? 'text-right' : ''">
                    <span x-text="translations.welcome[language]">Welcome</span>,
                    <span id="welcome-name" class="font-semibold text-indigo-600"></span>
                </div>
            </div>
        </div>

        <div id="global-loading"
             class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
            <div class="bg-white p-8 rounded-xl shadow-2xl text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mb-4"></div>
                <p class="text-gray-700" x-text="translations.loading[language]">Loading...</p>
            </div>
        </div>

        <div class="bg-white/80 p-6 rounded-xl shadow-lg backdrop-blur-sm min-h-[70vh]">
            @yield('content')
        </div>

        <footer class="mt-10 text-center text-sm bg-white/80 p-4 rounded-xl shadow">
            <p>© {{ date('Y') }} <span x-text="translations.app_name[language]">Tanzan</span> - <span x-text="translations.system_name[language]">Hotel Apartment Management System</span></p>
            <p class="text-xs text-gray-500 mt-1" x-text="translations.rights_reserved[language]">All rights reserved</p>
        </footer>
    </main>
</div>

<script>
    // الترجمة
    const translations = {
        app_name: {
            en: "Tanzan Admin",
            ar: "إدارة تنزان"
        },
        control_panel: {
            en: "Control Panel",
            ar: "لوحة التحكم"
        },
        dashboard: {
            en: "Dashboard",
            ar: "لوحة التحكم"
        },
        users: {
            en: "Users",
            ar: "المستخدمين"
        },
        apartments: {
            en: "Apartments",
            ar: "الشقق"
        },
        bookings: {
            en: "Bookings",
            ar: "الحجوزات"
        },
        messages: {
            en: "Messages",
            ar: "الرسائل"
        },
        reviews: {
            en: "Reviews",
            ar: "التقييمات"
        },
        settings: {
            en: "Settings",
            ar: "الإعدادات"
        },
        language: {
            en: "Language",
            ar: "اللغة"
        },
        logout: {
            en: "Logout",
            ar: "تسجيل الخروج"
        },
        menu: {
            en: "Menu",
            ar: "القائمة"
        },
        welcome: {
            en: "Welcome",
            ar: "مرحباً"
        },
        loading: {
            en: "Loading...",
            ar: "جاري التحميل..."
        },
        system_name: {
            en: "Hotel Apartment Management System",
            ar: "نظام إدارة شقق الفنادق"
        },
        rights_reserved: {
            en: "All rights reserved",
            ar: "جميع الحقوق محفوظة"
        },
        // ترجمة نص لوحة التحكم
        dashboard_title: {
            en: "System Management Dashboard",
            ar: "لوحة إدارة النظام"
        },
        total_apartments: {
            en: "Total Apartments",
            ar: "إجمالي الشقق"
        },
        total_users: {
            en: "Total Users",
            ar: "إجمالي المستخدمين"
        },
        total_bookings: {
            en: "Total Bookings",
            ar: "إجمالي الحجوزات"
        },
        total_revenue: {
            en: "Total Revenue",
            ar: "إجمالي الإيرادات"
        },
        this_month: {
            en: "this month",
            ar: "هذا الشهر"
        },
        recent_bookings: {
            en: "Recent Bookings",
            ar: "الحجوزات الأخيرة"
        },
        quick_statistics: {
            en: "Quick Statistics",
            ar: "إحصائيات سريعة"
        },
        user: {
            en: "User",
            ar: "المستخدم"
        },
        apartment: {
            en: "Apartment",
            ar: "الشقة"
        },
        date: {
            en: "Date",
            ar: "التاريخ"
        },
        status: {
            en: "Status",
            ar: "الحالة"
        },
        active_apartments: {
            en: "Active Apartments",
            ar: "الشقق النشطة"
        },
        active_bookings: {
            en: "Active Bookings",
            ar: "الحجوزات النشطة"
        },
        active_users: {
            en: "Active Users",
            ar: "المستخدمين النشطين"
        },
        average_apartment_price: {
            en: "Average Apartment Price",
            ar: "متوسط سعر الشقة"
        }
    };

    function updateTime() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        const dateStr = now.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = `${dateStr} - ${timeStr}`;
        }
    }

    setInterval(updateTime, 1000);
    updateTime();

    document.addEventListener('DOMContentLoaded', function() {
        const userData = localStorage.getItem('admin_user');
        if (userData) {
            try {
                const user = JSON.parse(userData);

                const userNameElement = document.getElementById('user-name');
                const userRoleElement = document.getElementById('user-role');
                const welcomeNameElement = document.getElementById('welcome-name');
                const userInfoElement = document.getElementById('user-info');
                const userWelcomeElement = document.getElementById('user-welcome');

                if (userNameElement) userNameElement.textContent = user.name;
                if (userRoleElement) userRoleElement.textContent = user.role === 'admin' ? 'System Admin' : 'User';
                if (welcomeNameElement) welcomeNameElement.textContent = user.name;
                if (userInfoElement) userInfoElement.classList.remove('hidden');
                if (userWelcomeElement) userWelcomeElement.classList.remove('hidden');

            } catch (e) {
                console.error('Error parsing user data:', e);
            }
        }

        checkAuth();

        const savedLanguage = localStorage.getItem('admin_language') || 'en';
        if (window.Alpine) {
            Alpine.data('language', savedLanguage);
        }
    });

    function switchLanguage(lang) {
        localStorage.setItem('admin_language', lang);

        if (window.Alpine && Alpine.$data) {
            Alpine.$data.language = lang;
        }

        window.location.reload();
    }

    async function checkAuth() {
        const token = localStorage.getItem('admin_token');

        if (!token) {
            redirectToLogin();
            return;
        }

        try {
            const response = await fetch('/api/admin/profile', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                if (response.status === 401) {
                    redirectToLogin();
                }
            }
        } catch (error) {
            console.error('Auth check failed:', error);
        }
    }

    async function logout() {
        const currentLanguage = localStorage.getItem('admin_language') || 'en';
        const confirmMsg = currentLanguage === 'ar'
            ? 'هل تريد تسجيل الخروج؟'
            : 'Do you want to logout?';

        if (!confirm(confirmMsg)) return;

        showLoading();

        try {
            const token = localStorage.getItem('admin_token');

            const response = await fetch('/api/admin/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });

            localStorage.removeItem('admin_token');
            localStorage.removeItem('admin_user');
            localStorage.removeItem('admin_language');

            hideLoading();

            window.location.href = '/admin/login';

        } catch (error) {
            hideLoading();

            localStorage.removeItem('admin_token');
            localStorage.removeItem('admin_user');
            localStorage.removeItem('admin_language');
            window.location.href = '/admin/login';
        }
    }

    function redirectToLogin() {
        localStorage.removeItem('admin_token');
        localStorage.removeItem('admin_user');
        localStorage.removeItem('admin_language');
        window.location.href = '/admin/login';
    }

    function showLoading() {
        const loadingElement = document.getElementById('global-loading');
        if (loadingElement) {
            loadingElement.classList.remove('hidden');
        }
    }

    function hideLoading() {
        const loadingElement = document.getElementById('global-loading');
        if (loadingElement) {
            loadingElement.classList.add('hidden');
        }
    }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
            confirmButtonText: 'OK'
        });
    }

    function showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: message,
            timer: 2000,
            showConfirmButton: false
        });
    }

    async function fetchData(endpoint, options = {}) {
        const token = localStorage.getItem('admin_token');

        if (!token) {
            redirectToLogin();
            return null;
        }

        const defaultOptions = {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        };

        try {
            const response = await fetch(`/api/admin/${endpoint}`, {
                ...defaultOptions,
                ...options
            });

            if (response.status === 401) {
                redirectToLogin();
                return null;
            }

            const data = await response.json();
            return data;

        } catch (error) {
            console.error('Fetch error:', error);
            return null;
        }
    }

    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        const token = localStorage.getItem('admin_token');

        if (token && args[0].includes('/api/admin/')) {
            if (!args[1]) args[1] = {};
            if (!args[1].headers) args[1].headers = {};

            args[1].headers = {
                ...args[1].headers,
                'Authorization': `Bearer ${token}`
            };
        }

        return originalFetch(...args);
    };
</script>

@yield('scripts')
</body>
</html>