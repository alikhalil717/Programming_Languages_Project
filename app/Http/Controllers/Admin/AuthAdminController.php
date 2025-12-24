<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LoginUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Apartment;
use App\Models\Rental;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controllers\Middleware;
use App\Services\Admin\AdminService;
class AuthAdminController extends Controller
{
    private AdminService $adminService;
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('admin', except: ['showLoginForm', 'login']),
        ];
    }




    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(LoginUserRequest $request)
    {

        $data = $this->adminService->login($request);
        if ($data['success'] === true) {
            return response()->json([
                'message' => 'Admin logged in successfully',
                'success' => true,
                'user' => $data['admin'],
                'token' => $data['token'],
                'redirect' => route('admin.dashboard')
            ], 200);
        } else {
            return response()->json(['message' => $data['message'], 'success' => false], 401);
        }
    }
    public function profile(Request $request)
    {
        $admin = $request->user();
        if ($admin->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json(['admin' => $admin, 'message' => 'Profile retrieved successfully', 'success' => true], 200);
    }

    public function listUsers()
    {
        $users = User::with('profile_picture', 'personal_id')->where('role', 'user')->get();
        return response()->json(['users' => $users, 'success' => true], 200);
    }

    public function showUser($id)
    {
        $user = User::with('profile_picture', 'personal_id')->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json(['user' => $user], 200);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found', 'success' => false], 404);
        }
        if ($user->role == 'admin') {
            return response()->json(['message' => 'Cannot delete admin users', 'success' => false], 403);
        }
        $user->delete();

        return response()->json(['message' => 'User deleted successfully', 'success' => true], 200);
    }
    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->update(['status' => 'active']);
        return response()->json([
            'success' => true,
            'message' => 'تم تفعيل المستخدم بنجاح',
            'user' => $user
        ], 200);
    }

    public function rejectUser($id)
    {
        $user = User::findOrFail($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->update(['status' => 'inactive']);
        return response()->json([
            'success' => true,
            'message' => 'تم رفض المستخدم بنجاح',
            'user' => $user
        ], 200);
    }

    public function viewReports()
    {
        // TODO: Implement reports view
        return response()->json(['message' => 'Reports endpoint'], 200);
    }

    public function getStats()
    {
        $apartments = Apartment::count();
        $bookings = Rental::count();
        $users = User::count();
        $revenue = Rental::sum('total_price') ?? 0;

        return response()->json([
            'apartments' => $apartments,
            'bookings' => $bookings,
            'users' => $users,
            'revenue' => $revenue,
        ], 200);
    }

    public function chargeWallet(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found', 'success' => false], 404);
        }
        if ($user->status !== 'active') {
            return response()->json(['message' => 'Cannot charge wallet of inactive user', 'success' => false], 403);
        }
        $user->wallet += $request->input('amount');
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Wallet charged successfully',
            'wallet_balance' => $user->wallet
        ], 200);
    }
}