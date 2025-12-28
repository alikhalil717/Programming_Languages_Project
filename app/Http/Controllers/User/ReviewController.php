<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Review;
use App\Models\User;
use App\Models\Rental;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);
        $user = $request->user();
        if (!$user) {
            return response()->json()->json(['message' => 'Unauthorized', 'success' => false], 403);
        }
        $aparment = Apartment::find($id);
        if (!$aparment) {
            return response()->json(['message' => 'Apartment not found', 'success' => false], 404);
        }
        $rental = Rental::where('apartment_id', $id)->where('renter_id', $user->id)->where('status', 'finished')->first();
        if (!$rental) {
            return response()->json(['message' => 'You have not rented this apartment before', 'success' => false], 400);
        }
        $review = Review::create([
            'apartment_id' => $id,
            'user_id' => $user->id,
            'rating' => $request->input('rating'),
        ]);
        $aparment->rate = $aparment->rating();
        $aparment->save();
        return response()->json(['message' => 'Review created successfully', 'success' => true], 201);

    }

}
