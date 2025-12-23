<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rental;
use Illuminate\Routing\Controller as BaseController;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::with(['apartment', 'renter'])->get();
        return response()->json(['rentals' => $rentals], 200);
    }

    public function show($id)
    {
        $rental = Rental::with(['apartment', 'renter'])->find($id);

        if (!$rental) {
            return response()->json(['message' => 'Rental not found'], 404);
        }

        return response()->json(['rental' => $rental], 200);
    }

    public function destroy($id)
    {
        $rental = Rental::find($id);

        if (!$rental) {
            return response()->json(['message' => 'Rental not found'], 404);
        }

        $rental->delete();

        return response()->json(['message' => 'Rental deleted successfully'], 200);
    }

    public function approve($id)
    {
        $rental = Rental::find($id);

        if (!$rental) {
            return response()->json(['message' => 'Rental not found'], 404);
        }

        $rental->update(['status' => 'approved']);

        return response()->json(['message' => 'Rental approved successfully'], 200);
    }

    public function reject($id)
    {
        $rental = Rental::find($id);

        if (!$rental) {
            return response()->json(['message' => 'Rental not found'], 404);
        }

        $rental->update(['status' => 'rejected']);

        return response()->json(['message' => 'Rental rejected successfully'], 200);
    }
}

