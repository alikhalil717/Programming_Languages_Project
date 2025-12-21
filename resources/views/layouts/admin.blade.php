<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* تحسين تأثير ظهور الـ sidebar */
        [x-cloak] { display: none; }
        .menu-link:hover {
            background-color: #eef2ff;
            color: #4f46e5;
            font-weight: 600;
        }
    </style>
</head>

<body class="min-h-screen text-slate-800 bg-cover bg-center bg-fixed"
      style="background-image: url('{{ asset('assets/background.jpg') }}');"
      x-data="{ sidebar: true }">

<div class="flex min-h-screen bg-white/50 backdrop-blur-sm">

    <!-- Sidebar -->
    <aside x-cloak
           x-show="sidebar"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="-translate-x-full opacity-0"
           x-transition:enter-end="translate-x-0 opacity-100"
           x-transition:leave="transition ease-in duration-300"
           x-transition:leave-start="translate-x-0 opacity-100"
           x-transition:leave-end="-translate-x-full opacity-0"
           class="bg-white/90 shadow-xl w-64 p-6 space-y-6 border-l fixed inset-y-0 right-0 z-50">

        <!-- Logo -->
        <div class="flex items-center gap-3">
            <img src="{{ asset('assets/logo.png') }}" class="w-12 h-12 rounded-xl">
            <div>
                <h1 class="text-xl font-bold text-indigo-600">Tanzan Admin</h1>
                <p class="text-xs text-gray-500">لوحة التحكم</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="space-y-3 mt-6">
            <a href="{{ route('admin.dashboard') }}" class="menu-link block p-2 rounded">🏠 الرئيسية</a>
            <a href="{{ url('admin/users') }}" class="menu-link block p-2 rounded">👤 المستخدمين</a>
            <a href="{{ url('admin/apartments') }}" class="menu-link block p-2 rounded">🏢 الشقق</a>
            <a href="{{ url('admin/bookings') }}" class="menu-link block p-2 rounded">🗓️ الحجوزات</a>
            <a href="{{ url('admin/messages') }}" class="menu-link block p-2 rounded">💬 الرسائل</a>
            <a href="{{ url('admin/reviews') }}" class="menu-link block p-2 rounded">⭐ التقييمات</a>
            <a href="{{ url('admin/settings') }}" class="menu-link block p-2 rounded">⚙️ الإعدادات</a>
        </nav>
    </aside>

    <!-- Content -->
    <main class="flex-1 p-6 mr-0 md:mr-64 transition-all duration-300">
        <!-- زر القائمة للـ sidebar -->
        <button @click="sidebar = !sidebar"
                class="mb-6 px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
            ☰ القائمة
        </button>

        <!-- محتوى الصفحة -->
        <div class="bg-white/80 p-6 rounded-xl shadow-lg backdrop-blur-sm">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="mt-10 text-center text-sm bg-white/80 p-4 rounded-xl shadow">
            © {{ date('Y') }} Tanzan
        </footer>
    </main>

</div>
</body>
</html>
