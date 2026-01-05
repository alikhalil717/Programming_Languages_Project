
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | Tanzan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
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

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 p-4"
     style="background-image: url('{{ asset('assets/background.png') }}');"
     id="login-page"
     dir="ltr">

    <div class="w-full max-w-md bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl p-8">
        <!-- Language Switcher -->
        <div class="flex justify-end mb-4">
            <div class="flex items-center gap-2 bg-gray-100 rounded-full p-1">
                <button onclick="switchLanguage('ar')"
                        class="px-3 py-1 rounded-full transition text-sm"
                        :class="currentLanguage === 'ar' ? 'bg-green-500 text-white' : 'text-gray-600'">
                    العربية
                </button>
                <button onclick="switchLanguage('en')"
                        class="px-3 py-1 rounded-full transition text-sm"
                        :class="currentLanguage === 'en' ? 'bg-blue-500 text-white' : 'text-gray-600'">
                    English
                </button>
            </div>
        </div>

        <div class="text-center mb-8">
            <img src="{{ asset('assets/logo.png') }}" class="w-24 h-24 mx-auto rounded-xl shadow-lg mb-4" alt="Logo">
            <h1 class="text-2xl font-bold text-gray-800" id="app-title">Tanzan Admin</h1>
        </div>

        <form id="loginForm">
            @csrf
            <div class="mb-6">
                <label class="block text-gray-700 mb-2" id="phone-label">Phone Number</label>
                <div class="relative">
                    <i class="fas fa-phone absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="phone_number" required
                           class="w-full pl-4 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                           placeholder="Enter phone number">
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 mb-2" id="password-label">Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="password" id="password" required
                           class="w-full pl-4 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                           placeholder="Enter password">
                </div>
            </div>

            <button type="submit" id="submitBtn"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg font-semibold transition duration-300 flex items-center justify-center">
                <i class="fas fa-sign-in-alt ml-2"></i>
                <span id="btnText">Login</span>
            </button>
        </form>
    </div>

    <script>
        let currentLanguage = localStorage.getItem('admin_language') || 'en';

        const loginTranslations = {
            app_title: {
                en: "Tanzan Admin",
                ar: "إدارة تنزان"
            },
            phone_label: {
                en: "Phone Number",
                ar: "رقم الهاتف"
            },
            phone_placeholder: {
                en: "Enter phone number",
                ar: "أدخل رقم الهاتف"
            },
            password_label: {
                en: "Password",
                ar: "كلمة المرور"
            },
            password_placeholder: {
                en: "Enter password",
                ar: "أدخل كلمة المرور"
            },
            login_btn: {
                en: "Login",
                ar: "تسجيل الدخول"
            },
            logging_in: {
                en: "Logging in...",
                ar: "جاري تسجيل الدخول..."
            },
            fill_fields: {
                en: "Please fill in all fields",
                ar: "يرجى ملء جميع الحقول"
            },
            login_success: {
                en: "Login successful",
                ar: "تم تسجيل الدخول بنجاح"
            },
            invalid_credentials: {
                en: "Invalid login credentials",
                ar: "بيانات الدخول غير صحيحة"
            },
            connection_error: {
                en: "Connection error occurred",
                ar: "حدث خطأ في الاتصال"
            }
        };

        function switchLanguage(lang) {
            currentLanguage = lang;
            localStorage.setItem('admin_language', lang);
            applyLoginTranslations();

            document.getElementById('login-page').dir = lang === 'ar' ? 'rtl' : 'ltr';
        }

        function applyLoginTranslations() {
            document.getElementById('app-title').textContent = loginTranslations.app_title[currentLanguage];
            document.getElementById('phone-label').textContent = loginTranslations.phone_label[currentLanguage];
            document.getElementById('password-label').textContent = loginTranslations.password_label[currentLanguage];
            document.getElementById('btnText').textContent = loginTranslations.login_btn[currentLanguage];

            document.getElementById('phone_number').placeholder = loginTranslations.phone_placeholder[currentLanguage];
            document.getElementById('password').placeholder = loginTranslations.password_placeholder[currentLanguage];
        }

        document.addEventListener('DOMContentLoaded', function() {
            applyLoginTranslations();

            if (currentLanguage === 'ar') {
                document.getElementById('login-page').dir = 'rtl';
            }

            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');

            let isProcessing = false;

            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (isProcessing) return;
                isProcessing = true;

                const phoneNumber = document.getElementById('phone_number').value.trim();
                const password = document.getElementById('password').value;

                if (!phoneNumber || !password) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning',
                        text: loginTranslations.fill_fields[currentLanguage]
                    });
                    isProcessing = false;
                    return;
                }

                submitBtn.disabled = true;
                btnText.textContent = loginTranslations.logging_in[currentLanguage];
                submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin ml-2"></i> ${loginTranslations.logging_in[currentLanguage]}`;

                try {
                    console.log('Logging in...');
                    const response = await fetch('/api/admin/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            phone_number: phoneNumber,
                            password: password
                        })
                    });

                    const data = await response.json();
                    console.log('API response:', data);

                    if (data.success) {
                        await createWebSession(data.token);

                        if (data.token) {
                            localStorage.setItem('admin_token', data.token);
                            console.log('Token saved for API');
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: loginTranslations.login_success[currentLanguage],
                            timer: 1000,
                            showConfirmButton: false
                        }).then(() => {
                            const redirectUrl = data.redirect || '/admin/dashboard';
                            console.log('Redirecting to:', redirectUrl);
                            window.location.href = redirectUrl;
                        });

                    } else {
                        throw new Error(data.message || loginTranslations.invalid_credentials[currentLanguage]);
                    }

                } catch (error) {
                    console.error('Error:', error);
                    isProcessing = false;
                    submitBtn.disabled = false;
                    btnText.textContent = loginTranslations.login_btn[currentLanguage];
                    submitBtn.innerHTML = `<i class="fas fa-sign-in-alt ml-2"></i> ${loginTranslations.login_btn[currentLanguage]}`;

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || loginTranslations.connection_error[currentLanguage]
                    });
                }
            });

            async function createWebSession(apiToken) {
                try {
                    console.log('Creating Web Session...');

                    const csrfResponse = await fetch('/sanctum/csrf-cookie', {
                        credentials: 'include'
                    });

                    const sessionResponse = await fetch('/api/admin/create-session', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${apiToken}`,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        credentials: 'include'
                    });

                    console.log('Session Response:', sessionResponse.status);

                } catch (error) {
                    console.warn('Could not create Session, but can proceed:', error);
                }
            }
        });
    </script>
</body>
</html>
