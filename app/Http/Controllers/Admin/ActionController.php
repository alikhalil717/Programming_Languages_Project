<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;

class ActionController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $response = Http::post(url('/api/login'), [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'invalid_credentials',
        ]);
    }
}
