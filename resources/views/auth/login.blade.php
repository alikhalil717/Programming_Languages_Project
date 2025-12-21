<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل دخول الأدمن | Tanzan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine JS -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* تحسين تأثير ظهور الفورم */
        [x-cloak] { display: none; }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-cover bg-center relative"
      style="background-image: url('{{ asset('assets/background.png') }}');">

    <!-- Overlay أسود خفيف -->
    <div class="absolute inset-0 bg-black/40"></div>

    <!-- Login Form Container -->
    <div class="relative z-10 w-full max-w-md bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl p-8"
         x-data="{ show: false }"
         x-init="setTimeout(() => show = true, 100)"
         x-show="show"
         x-transition
         x-cloak>

        <!-- Logo -->
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('assets/logo.png') }}"
                 class="w-20 h-20 rounded-2xl shadow-lg mb-3">
            <h1 class="text-2xl font-bold text-indigo-600">Tanzan Admin</h1>
        </div>

        <!-- ✅ LOGIN FORM -->
        <form method="POST" action="{{ route('admin.login.post') }}">

            @csrf <!-- CSRF Token -->

            <!-- رقم الهاتف -->
            <div>
                <label class="block mb-1 text-sm font-semibold">رقم الهاتف</label>
                <div class="relative">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <input
                        type="text"
                        name="phone_number"
                        value="{{ old('phone_number') }}"
                        required
                        placeholder="أدخل رقم الأدمن"
                        class="w-full pr-10 pl-4 py-3 rounded-xl border focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>
                @error('phone_number')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- كلمة السر -->
            <div>
                <label class="block mb-1 text-sm font-semibold">كلمة السر</label>
                <div class="relative">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input
                        type="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        class="w-full pr-10 pl-4 py-3 rounded-xl border focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>
                @error('password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold shadow-lg transition transform hover:-translate-y-1 flex justify-center items-center">
                <i class="fa-solid fa-right-to-bracket ml-2"></i>
                تسجيل الدخول
            </button>

            <!-- عرض أي خطأ عام -->
            @if ($errors->any() && !$errors->has('phone_number') && !$errors->has('password'))
                <div class="text-red-600 text-sm mt-2 text-center">
                    {{ $errors->first() }}
                </div>
            @endif
        </form>

        <!-- Footer -->
        <div class="mt-6 text-center text-xs text-gray-500">
            © {{ date('Y') }} Tanzan Admin
        </div>

    </div>

</body>
</html>
