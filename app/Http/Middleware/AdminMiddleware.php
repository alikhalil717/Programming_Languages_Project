<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 🔧 **التشخيص: طباعة معلومات المصادقة**
        if (app()->environment('local')) {
            \Log::info('AdminMiddleware - Request Path: ' . $request->path());
            \Log::info('AdminMiddleware - User: ' . json_encode(Auth::user()));
            \Log::info('AdminMiddleware - Sanctum User: ' . json_encode($request->user('sanctum')));
            \Log::info('AdminMiddleware - Token: ' . $request->bearerToken());
            \Log::info('AdminMiddleware - Headers: ' . json_encode($request->headers->all()));
        }

        // 🔧 **الحل المؤقت: تعطيل الـ Middleware مؤقتاً**
        // return $next($request); // 🔓 قم بفك التعليق عن هذا السطر مؤقتاً

        // 🔧 **التحقق من المصادقة**
        $user = $request->user('sanctum');

        if (!$user) {
            \Log::warning('AdminMiddleware - No authenticated user');
            return redirect()->route('admin.login');
        }

        // 🔧 **التحقق من الدور**
        if ($user->role !== 'admin') {
            \Log::warning('AdminMiddleware - User is not admin. Role: ' . $user->role);
            abort(403, 'غير مصرح بالوصول. يجب أن تكون أدمن.');
        }

        \Log::info('AdminMiddleware - User authenticated: ' . $user->id);
        return $next($request);
    }
}