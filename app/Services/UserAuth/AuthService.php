<?php
namespace App\Services\UserAuth;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use League\CommonMark\Exception\IOException;
use phpDocumentor\Reflection\Types\Null_;
use function Laravel\Prompts\error;
use function PHPUnit\Framework\returnArgument;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Resources\UserProfileResource;
class AuthService
{
    /**
     * Register a new user.
     *
     * @param array $data
     * @return User
     */
    public function register(CreateUserRequest $request): array
    {
        $request->validated();
        $user = User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'phone_number' => $request['phone_number'],
            'date_of_birth' => $request['date_of_birth'],
            'personal_id' => $request['personal_id'],

        ]);


        if ($request->hasFile('profile_picture')) {
            //  $user = $request->user();


            try {
                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                $user->profile_picture()->create([
                    'user_id' => $user->id,
                    'image_path' => $path
                ]);
            } catch (IOException $e) {

                throw new HttpResponseException(
                    response()->json([
                        'success' => false,
                        'message' => 'Files errors',
                        'errors' => ['profile_picture' => 'faild to save your photo'],
                    ], 422)
                );
            }


        }


        if ($request->hasFile('personal_id')) {
            //  $user = $request->user();


            try {
                $path = $request->file('personal_id')->store('personal_ids', 'public');
                $user->personal_id()->create([
                    'user_id' => $user->id,
                    'image_path' => $path
                ]);
            } catch (IOException $e) {

                throw new HttpResponseException(
                    response()->json([
                        'success' => false,
                        'message' => 'Files errors',
                        'errors' => ['personal_id' => 'faild to save your photo'],
                    ], 422)
                );
            }


        }




        $data['success'] = true;
        $data['message'] = 'User registered successfully';

        return $data;
    }

    /**
     * Authenticate a user and generate an API token.
     *
     * @param string $email
     * @param string $password
     * @return array
     */




    public function login(LoginUserRequest $request): array
    {
        $request->validated();
        $user = null;

        try {
            $user = User::query()->where('phone_number', '=', $request['phone_number'])->firstOrFail();
        } catch (\Exception $e) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'invalid input',
                    'errors' => ['phone_number' => 'faild to find the number'],
                ], 422)
            );
        }


        if (!Hash::check($request['password'], $user->password)) {

            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'wrong password ',
                    'errors' => ['password' => 'wrong password'],
                ], 422)
            );

        }
        if (!($user->status === 'active')) {

            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'unactive User',
                ], 422)
            );

        }

        $token = $user->createToken('token')->plainTextToken;
        $data = ['token' => $token, 'massage' => 'User logged in successfully', 'success' => true];
        return $data;
    }
    /**
     * Logout the authenticated user.
     *
     * @return void
     */
    public function logout(Request $request): array
    {
        $user = $request->user();
        if (!$user) {
            $data = ['message' => 'No authenticated user found', 'success' => false];
            return $data;
        }
        $request->user()->currentAccessToken()->delete();

        return $data = ['message' => 'User logged out successfully', 'success' => true];

    }
    public function getProfile(Request $request): array
    {
        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'No authenticated user found',
            ];
        }

        return [
            'success' => true,
            'message' => 'User profile retrieved successfully',
            'user' => new UserProfileResource($request->user()),
        ];
    }
    public function updateProfile(UpdateProfileRequest $request)
    {
        $request->validated();

        $user = $request->user();

        $user->first_name = $request->input('first_name', $user->first_name);
        $user->last_name = $request->input('last_name', $user->last_name);
        $user->phone_number = $request->input('phone_number', $user->phone_number);
        $user->date_of_birth = $request->input('date_of_birth', $user->date_of_birth);


        if ($request->hasFile('profile_picture')) {
            //  $user = $request->user();
            if ($user->profile_picture) {
                $image = $user->profile_picture;

                // Delete old profile picture

                try {
                    $imagepath = $image->image_path;

                    $deleted = false;
                    // if (File::exists($path)) {
                    //     File::delete($path);
                    //     $deleted = true;
                    //     \Log::info("Deleted file from: " . $path);
                    // }

                    Storage::disk('public')->delete($imagepath);

                    if (!$deleted) {
                        \Log::warning("Image file not found in any path for UserID: " . $user->id);
                    }

                    $image->delete();

                    $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                    $user->profile_picture()->create([
                        'user_id' => $user->id,
                        'image_path' => $path
                    ]);

                    $user->save();
                    return [
                        'success' => true,
                        'message' => 'User profile updated successfully',
                        'user' => new UserProfileResource($user)
                    ];
                } catch (Exception $e) {
                    return [
                        'success' => false,
                        'message' => 'Failed to update profile picture: ' . $e->getMessage()
                    ];
                }

            } else {

                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                $user->profile_picture()->create([
                    'user_id' => $user->id,
                    'image_path' => $path
                ]);
            }

        }
        $user->save();

        return [
            'success' => true,
            'message' => 'User profile updated successfully',
            'user' => new UserProfileResource($user)
        ];
        ;
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required',
        ]);

        $user = $request->user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return [
                'success' => false,
                'message' => 'Current password is incorrect'
            ];
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();
        return [
            'success' => true,
            'message' => 'Password changed successfully'
        ];
    }

    public function verifyEmail(Request $request)
    {



    }

}