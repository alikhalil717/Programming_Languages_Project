<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. الأولوية للغة في Query Parameter
        if ($request->has('lang')) {
            $locale = $request->get('lang');
            Session::put('locale', $locale);
        }
        // 2. ثم اللغة في Session
        elseif (Session::has('locale')) {
            $locale = Session::get('locale');
        }
        // 3. ثم Accept-Language header
        elseif ($request->hasHeader('Accept-Language')) {
            $locale = $request->header('Accept-Language');
        }
        // 4. ثم اللغة الافتراضية من .env
        else {
            $locale = config('app.locale', 'ar');
        }

        // تحقق أن اللغة متوفرة
        if (in_array($locale, ['ar', 'en'])) {
            App::setLocale($locale);
        } else {
            App::setLocale(config('app.fallback_locale', 'en'));
        }

        // تمرير اللغة للـ response
        $response = $next($request);

        return $response->header('Content-Language', App::getLocale());
    }
}