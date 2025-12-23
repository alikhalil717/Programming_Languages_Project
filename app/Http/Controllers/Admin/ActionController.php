<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // استخدام HTTP Client للتواصل مع API
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class ActionController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // إرسال طلب POST إلى API login
        $response = Http::post(url('/api/login'), [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            // تخزين بيانات المصادقة في الجلسة إذا احتجت
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة',
        ]);
    }
}
