<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\ApartmentRequest;
use App\Http\Requests\IndexApartmentRequest;
use Illuminate\Http\Request;
use App\Models\Apartment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\ApartmentImage;
use App\Services\User\ApartmentService;
use App\Http\Controllers\Controller;

class ApartmentController extends Controller
{
    private ApartmentService $apartmentService;

    public function __construct(ApartmentService $apartmentService)
    {
        $this->apartmentService = $apartmentService;
    }

    public function index(IndexApartmentRequest $request)
    {
        $result = $this->apartmentService->filterApartments($request);
        return response()->json($result, $result['success'] ? 200 : 422);

    }

    public function store(ApartmentRequest $request)
    {
        $result = $this->apartmentService->createApartment($request);
        return response()->json($result, $result['success'] ? 201 : 422);

    }

    public function show($id)
    {
        $result = $this->apartmentService->getApartmentDetails($id);
        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function update(Request $request, $id)
    {
        $result = $this->apartmentService->updateApartment($request, $id);
        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function destroy(Request $request, $id)
    {
        $result = $this->apartmentService->deleteApartment($request, $id);
        return response()->json($result, $result['success'] ? 200 : 422);

    }
    public function ownerapartments(Request $request)
    {
        $result = $this->apartmentService->ownerapartments($request);
        return response()->json($result, $result['success'] ? 200 : 422);

    }



}
