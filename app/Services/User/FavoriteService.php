<?php
namespace App\Services\User;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Apartment;
use App\Models\User;
class FavoriteService
{

    // index
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }
        $apartments = Favorite::with('apartment')->where('user_id', $user->id)->get();
        return [
            'success' => true,
            'message' => 'Favorites retrieved successfully',
            'apartments' => $apartments
        ];
    }
    public function add(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }
        $apartment = Apartment::find($id);
        $favorite = Favorite::create([
            'user_id' => $user->id,
            'apartment_id' => $apartment->id
        ]);
        return [
            'success' => true,
            'message' => 'Favorite added successfully',
            'favorite' => $favorite
        ];

    }
    public function remove(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }
        $favorite = Favorite::where('user_id', $user->id)->where('apartment_id', $id)->first();
        if (!$favorite) {
            return [
                'success' => false,
                'message' => 'Favorite not found'
            ];
        }
        $favorite->delete();
        return [
            'success' => true,
            'message' => 'Favorite deleted successfully',
            'favorite' => $favorite
        ];
    }

}