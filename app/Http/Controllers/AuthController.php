<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Resources\UserProfileResource;
class AuthController extends Controller
{
    private AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function register(CreateUserRequest $request)
    {
        $data=$this->authService->register($request) ;
     if ($data['success']===false){
        return response()->json(['message' => $data['message'],'success' => false],400);
     }
        return response()->json(['message' => 'User registered successfully','success' => true],201);
        
    }

    public function login(LoginUserRequest $request)
    {

        $data=$this->authService->login($request);
        if($data['success']===false){
            return response()->json(['message' => $data['message'],'success' => false],401);
         }  
        return response()->json(['message' => 'User logged in successfully', 'success' => true , 'token' => $data['token']],200);

    }

    public function logout(Request $request)
    {
         $this->authService->logout($request);
         return response()->json(['message' => 'User logged out successfully'], 200);

    }

    public function profile(Request $request)
    {
     return['user' => new UserProfileResource($request->user())];
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        
       $user=$this->authService->updateProfile($request);

       return response()->json(['message' => 'User profile updated successfully', 'user' => new UserProfileResource($user)], 200);
    }

    public function changePassword(Request $request)
    {
        $this->authService->changePassword($request);
        return response()->json(['message' => 'Password changed successfully'], 200);
    }
    public function verifyEmail(Request $request)
    {
     

    }

    public function resendVerification(Request $request)
    {
        // Logic to resend email verification
    }
}
