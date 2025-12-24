<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use App\Services\UserAuth\AuthService;
use App\Http\Resources\UserProfileResource;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    private AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function register(CreateUserRequest $request)
    {
        $result = $this->authService->register($request);
        return response()->json($result, $result['success'] ? 201 : 422);

    }

    public function login(LoginUserRequest $request)
    {

        $result = $this->authService->login($request);
        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function logout(Request $request)
    {
        $result = $this->authService->logout($request);
        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function profile(Request $request)
    {
        $result = $this->authService->getProfile($request);
        return response()->json($result, $result['success'] ? 200 : 422);

    }

    public function updateProfile(UpdateProfileRequest $request)
    {

        $result = $this->authService->updateProfile($request);
        return response()->json($result, $result['success'] ? 200 : 422);

    }

    public function changePassword(Request $request)
    {
        $result =$this->authService->changePassword($request);
        return response()->json($result, $result['success'] ? 200 : 422);}

    public function verifyEmail(Request $request)
    {

    }

    public function resendVerification(Request $request)
    {
    }
}
