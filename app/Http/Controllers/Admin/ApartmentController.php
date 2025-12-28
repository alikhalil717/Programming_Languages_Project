<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apartment;
use Illuminate\Routing\Controller as BaseController;

class ApartmentController extends Controller
{
    public function index()
    {
        $apartments = Apartment::with(['owner', 'images'])->get();
        return response()->json(['apartments' => $apartments, 'success' => true], 200);
    }

    public function show($id)
    {
        $apartment = Apartment::with(['owner', 'images', 'reviews'])->find($id);

        if (!$apartment) {
            return response()->json(['message' => 'Apartment not found'], 404);
        }

        return response()->json(['apartment' => $apartment], 200);
    }

    public function destroy($id)
    {
        $apartment = Apartment::find($id);

        if (!$apartment) {
            return response()->json(['message' => 'Apartment not found'], 404);
        }

        $apartment->delete();

        return response()->json(['message' => 'Apartment deleted successfully'], 200);
    }

    public function approve($id)
    {
        $apartment = Apartment::find($id);

        if (!$apartment) {
            return response()->json(['message' => 'Apartment not found', 'success' => false], 404);
        }

        $apartment->update(['status' => 'approved']);

        return response()->json(['message' => 'Apartment approved successfully', 'success' => true], 200);
    }

    public function reject($id)
    {
        $apartment = Apartment::find($id);

        if (!$apartment) {
            return response()->json(['message' => 'Apartment not found', 'success' => false], 404);
        }
        if (!$apartment->status)

        $apartment->update(['status' => 'rejected']);

        return response()->json(['message' => 'Apartment rejected successfully', 'success' => true], 200);
    }
}

