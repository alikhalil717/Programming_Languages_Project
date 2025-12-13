<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Routing\Controller as BaseController;
class AdminController extends BaseController
{
  

    public function __construct()
    {
       $this->middleware(['auth:sanctum', 'admin']);
    }
    

    public function listUsers()
    {
        $users = User::get();
      return response()->json(['users' => $users], 200);
    }

    public function showUser( $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json(['user' => $user], 200);
    }

     public function deleteUser( $id)
    {    
        $user = User::find($id);

        if ($user->role == 'admin') {
            return response()->json(['message' => 'Cannot delete admin users'], 403);
        }
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);}
    public function approveUser(User $user)
    {
        $user->update(['status' => 'active']);
        return back()->with('success', 'User approved successfully!');
    }
    
    public function rejectUser(User $user)
    {
        $user->update(['status' => 'inactive']);
        return back()->with('success', 'User rejected successfully!');
    }
    
   
    
 
}