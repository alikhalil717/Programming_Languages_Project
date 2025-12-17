<?php
namespace App\Services;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage ;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use phpDocumentor\Reflection\Types\Null_;
use function PHPUnit\Framework\returnArgument;


class AuthService
{
    /**
     * Register a new user.
     *
     * @param array $data
     * @return User
     */
    public function register(array $data): array
    {
        $user=User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'phone_number' => $data['phone_number'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'personal_id' => $data['personal_id'] ?? null,
           
        ]);
      $data['success']=true;
      $data['message']='User registered successfully';

        return $data;
    }

    /**
     * Authenticate a user and generate an API token.
     *
     * @param string $email
     * @param string $password
     * @return array
     */



    
    public function login(array $data): array
    {
        $user = null;

       try{
      $user = User::query()->where('phone_number', '=' ,$data['phone_number'])->firstOrFail();

       }
         catch (\Exception $e){
           throw new AuthenticationException('invalid input credentials');
         }
        
        if (!$user) {
            throw new AuthenticationException('invalid input credentials');
        }   

        if (!Hash::check($data['password'], $user->password)) {
            throw new AuthenticationException('invalid input credentials');
         
        }
       $token = $user->createToken('token')->plainTextToken;
          $data= [ 'token' => $token, 'massage' => 'User logged in successfully' ,'success' => true ];
        return $data;     
    }
    /**
     * Logout the authenticated user.
     *
     * @return void
     */
    public function logout(Request $request ): void
    {
          $request->user()->currentAccessToken()->delete();
          return ;

    }
    public function updateProfile(UpdateProfileRequest $request)
    {
       $request->validated();

       $user = $request->user();

        $user->first_name = $request->input('first_name', $user->first_name);
        $user->last_name = $request->input('last_name', $user->last_name);
        $user->username = $request->input('username', $user->username);
        $user->phone_number = $request->input('phone_number', $user->phone_number);
        $user->date_of_birth = $request->input('date_of_birth', $user->date_of_birth);
  

        if ($request->hasFile('profile_picture')) {
                  //  $user = $request->user();
            if($user->profile_picture){  
                        $image = $user->profile_picture;
                        
                // Delete old profile picture

            try {
            $imagepath= $image->image_path;
          
          $deleted = false;
            // if (File::exists($path)) {
            //     File::delete($path);
            //     $deleted = true;
            //     \Log::info("Deleted file from: " . $path);
            // }

            Storage::disk('public')->delete(  $imagepath);
        
          if (!$deleted) {
            \Log::warning("Image file not found in any path for UserID: " . $user->id);
          }
        
          $image->delete();
        
          $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                     $user->profile_picture()->create([
                        'user_id' => $user->id,
                        'image_path' => $path]);

                      $user->save();

                     return $user;
      }

        catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error deleting image',
            'error' => $e->getMessage()
        ], 500);
      }

            }
            else{

                  $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                     $user->profile_picture()->create([
                        'user_id' => $user->id,
                        'image_path' => $path]);
            }
          
       }
          $user->save();

                     return $user;
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
            throw ValidationException::withMessages([
                'current_password' => 'The provided password does not match your current password.',
            ]);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();
    }

    public function verifyEmail(Request $request)
    {
    


    }

}
   
   