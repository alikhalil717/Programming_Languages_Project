<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApartmentRequest;
use Illuminate\Http\Request;
use App\Models\Apartment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\ApartmentImage;
use App\Services\ApartmentService;

class ApartmentController extends Controller
{
    private ApartmentService $apartmentService;

    public function __construct(ApartmentService $apartmentService)
    {
        $this->apartmentService = $apartmentService;
    }

    public function index(Request $request)
    {
        $apartments = Apartment::with('owner', 'images')->get();
        return response()->json([
            'message' => 'Successfully retrieved user apartments.'
            ,
            'apartments' => $apartments,
            'success' => true
        ], 200);

    }

    public function store(ApartmentRequest $request)
    {
        $result = $this->apartmentService->createApartment($request);
        return response()->json($result, $result['success'] ? 201 : 422);

    }

    public function show($id)
    {

        $apartment = Apartment::with('owner', 'images', 'reviews')->find($id);
        if (!$apartment) {
            return response()->json([
                'message' => 'Apartment not found.',
                'success' => false
            ], 404);
        }
        return response()->json([
            'message' => 'Successfully retrieved apartment details.',
            'apartment' => $apartment,
            'success' => true
        ], 200);
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        
    }



}
