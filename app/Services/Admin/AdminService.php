<?php
namespace App\Services\Admin;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
class AdminService
{



    public function login(LoginUserRequest $request)
    {
        $credentials = $request->validated();



        try {
            $user = User::query()->where('phone_number', '=', $request['phone_number'])->firstOrFail();
        } catch (\Exception $e) {

            throw new AuthenticationException('invalid input credentials');

        }


        if (!$user) {
            throw new AuthenticationException('invalid input credentials');
        }


        if ($user && Hash::check($credentials['password'], $user->password)) {
            if ($user->role !== 'admin') {
                return ['success' => false, 'message' => 'Unauthorized access.'];
            }
        }
        $token = $user->createToken('admin-token', ['admin-access'])->plainTextToken;
        $data = ['token' => $token, 'massage' => 'User logged in successfully', 'admin' => $user, 'success' => true];
        return $data;
        // $user = User::where('phone_number', $credentials['phone_number'])->first();

        // if ($user && Hash::check($credentials['password'], $user->password)) {
        //     if ($user->role !== 'admin') {
        //         return ['success' => false, 'message' => 'Unauthorized access.'];
        //     }

        //     return ['success' => true, 'user' => $user];
        // }

        // return ['success' => false, 'message' => 'Invalid credentials.'];
    }


}