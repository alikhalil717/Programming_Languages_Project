<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\UpdateRentalRequest;
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
    //! renter Methods



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
    public function cancel(Request $request, $id)
    {
        $result = $this->rentalService->cancelRental($request, $id);
        return response()->json($result, $result['success'] ? 200 : 422);
    }
    public function update(UpdateRentalRequest $request, $id)
    {
        $result = $this->rentalService->updateRental($request, $id);
        return response()->json($result, $result['success'] ? 200 : 422);
    }




    //! Owner Methods




    public function ownerRentals(Request $request)
    {
        $result = $this->rentalService->listOwnerRentals($request);
        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function ownershow(Request $request, $id)
    {
        $result = $this->rentalService->ownershow($request, $id);
        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function approveRental(Request $request, $id)
    {
        $result = $this->rentalService->approveOwnerRental($request, $id);
        return response()->json($result, $result['success'] ? 200 : 422);
    }
    public function rejectRental(Request $request, $id)
    {
        $result = $this->rentalService->rejectOwnerRental($request, $id);
        return response()->json($result, $result['success'] ? 200 : 422);
    }
    public function ownerRentalsUpdate(Request $request)
    {
        $result = $this->rentalService->ownerRentalsUpdate($request);
        return response()->json($result, $result['success'] ? 200 : 422);
    }
    public function getRentalForUpdate(Request $request, $id)
    {
        $result = $this->rentalService->getRentalForUpdate($request, $id);
        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function approveRentalupdate(Request $request, $id)
    {
        $result = $this->rentalService->approveOwnerRentalupdate($request, $id);
        return response()->json($result, $result['success'] ? 200 : 422);
    }
    public function rejectRentalupdate(Request $request, $id)
    {
        $result = $this->rentalService->rejectOwnerRentalupdate($request, $id);
        return response()->json($result, $result['success'] ? 200 : 422);
    }

}
