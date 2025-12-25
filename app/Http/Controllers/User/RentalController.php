<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\User\RentalService;
use App\Http\Requests\CreateRentalRequest;
class RentalController extends Controller
{
    private RentalService $rentalService;
    public function __construct(RentalService $rentalService)
    {
        $this->rentalService = $rentalService;
    }
    public function index(Request $request)
    {
        $result = $this->rentalService->listRentals($request);
        return response()->json($result, $result['success'] ? 200 : 422);
    }
    public function show(Request $request, $id)
    {
        $result = $this->rentalService->getRentalDetails($request, $id);
        return response()->json($result, $result['success'] ? 200 : 422);
    }
    public function store(CreateRentalRequest $request, $id)
    {
        $result = $this->rentalService->createRental($request, $id);
        return response()->json($result, $result['success'] ? 201 : 422);
    }
    public function checkifAvailable(CreateRentalRequest $request, $id)
    {
        $result = $this->rentalService->checkIfAvailable($request, $id);
        return response()->json($result, $result['success'] ? 200 : 422);
    }

}
