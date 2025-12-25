<?php
namespace App\Services\User;
use App\Http\Requests\CreateRentalRequest;
use App\Models\Rental;
use Illuminate\Http\Request;
use App\Models\Apartment;
class RentalService
{
    public function listRentals(Request $request): array
    {
        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Unauthenticated'
            ];
        }
        $rentals = Rental::with('apartment', 'apartment.images')
            ->where('user_id', $user->id)
            ->get();
        return [
            'success' => true,
            'message' => 'Rentals retrieved successfully',
            'rentals' => $rentals
        ];
    }
    public function getRentalDetails(Request $request, $id): array
    {
        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Unauthenticated'
            ];
        }


        $rental = Rental::with('apartment', 'apartment.images')->find($id);
        if (!$rental) {
            return [
                'success' => false,
                'message' => 'Rental not found'
            ];
        }
        if ($rental->user_id !== $user->id) {
            return [
                'success' => false,
                'message' => 'Unauthorized access to this rental'
            ];
        }
        return [
            'success' => true,
            'message' => 'Rental details retrieved successfully',
            'rental' => $rental
        ];
    }
    public function createRental(CreateRentalRequest $request, $id): array
    {
        $request->validated();
        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Unauthenticated'
            ];
        }
        $apartmentId = $id;
        $apartment = Apartment::find($apartmentId);
        if (!$apartment) {
            return [
                'success' => false,
                'message' => 'Apartment ID is required'
            ];
        }
        if ($user->id === $apartment->owner_id) {
            return [
                'success' => false,
                'message' => 'You cannot rent your own apartment'
            ];
        }
        $rentalDatesAvailable = Rental::checkAvailability(
            $apartmentId,
            $request->input('start_date'),
            $request->input('end_date')
        );
        if (!$rentalDatesAvailable) {
            $availablePeriod = Rental::getAvailablePeriods(
                $request->input('apartment_id')
            );
            return [
                'success' => false,
                'AvailablePeriod' => $availablePeriod,
                'message' => 'The selected dates are not available for this apartment.'
            ];
        }
        $totalPrice = $apartment->calculateTotalPrice(
            $request->input('start_date'),
            $request->input('end_date')
        );
        $paymentMethod = $request->input('payment_method');

        if ($request->input('payment_method') === null) {
            $paymentMethod = 'wallet';
        }
        if ($paymentMethod === 'wallet') {
            if ($user->wallet < $totalPrice) {
                return [
                    'success' => false,
                    'message' => 'Insufficient wallet balance.'
                ];
            } else {
                $user->wallet -= $totalPrice;
                $user->save();
            }
        }

        $rental = new Rental();
        $rental->renter_id = $user->id;
        $rental->apartment_id = $apartmentId;
        $rental->start_date = $request->input('start_date');
        $rental->end_date = $request->input('end_date');
        $rental->total_price = $totalPrice;
        $rental->special_requests = $request->input('special_requests');
        $rental->payment_method = $request->input('payment_method');
        $rental->status = 'pending';
        $rental->save();
        return [
            'success' => true,
            'message' => 'Rental created successfully',
            'rental' => $rental
        ];
    }
    public function checkIfAvailable(CreateRentalRequest $request, $id): array
    {
        $apartment = Apartment::find($id);
        if (!$apartment) {
            return [
                'success' => false,
                'message' => 'Apartment not found.'
            ];
        }
        $apartmentId = $id;
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $available = Rental::checkAvailability($apartmentId, $startDate, $endDate);
        if (!$available) {
            $availablePeriod = Rental::getAvailablePeriods(
                $apartmentId
            );
            return [
                'success' => false,
                'AvailablePeriod' => $availablePeriod,
                'message' => 'The selected dates are not available for this apartment.'
            ];
        }
        return [
            'success' => true,
            'message' => 'The selected dates are available for this apartment.'
        ];
    }
}