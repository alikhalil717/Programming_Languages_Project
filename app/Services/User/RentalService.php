<?php
namespace App\Services\User;
use App\Http\Requests\CreateRentalRequest;
use App\Http\Requests\UpdateRentalRequest;
use App\Models\Rental;
use App\Models\Updaterental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\User;
class RentalService
{

    // Renter Methods
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
            ->where('renter_id', $user->id)
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
        if ($rental->renter_id !== $user->id) {
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
    public function cancelRental(Request $request, $id): array
    {
        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Unauthenticated'
            ];
        }
        $rental = Rental::find($id);
        if (!$rental) {
            return [
                'success' => false,
                'message' => 'Rental not found'
            ];
        }
        if ($rental->renter_id !== $user->id) {
            return [
                'success' => false,
                'message' => 'Unauthorized access to this rental'
            ];
        }
        $today = Carbon::today();
        $startDate = Carbon::parse($rental->start_date);
        if ($today->greaterThanOrEqualTo($startDate)) {
            return [
                'success' => false,
                'message' => 'Cancellations are only allowed before the rental start date.'
            ];
        }
        $remainingmoney = $rental->total_price;
        $user->wallet += $remainingmoney;
        $user->save();
        $rental->status = 'canceled';
        $rental->save();
        return [
            'success' => true,
            'message' => 'Rental canceled successfully'
        ];
    }
    public function updateRental(UpdateRentalRequest $request, $id): array
    {
        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Unauthenticated'
            ];
        }
        $rental = Rental::find($id);
        if (!$rental) {
            return [
                'success' => false,
                'message' => 'Rental not found'
            ];
        }
        if ($rental->renter_id !== $user->id) {
            return [
                'success' => false,
                'message' => 'Unauthorized access to this rental'
            ];
        }
        $RentalAvailable = Rental::checkAvailability(
            $rental->apartment_id,
            $request->input('start_date'),
            $request->input('end_date'),
            $rental->id
        );
        if (!$RentalAvailable) {
            return [
                'success' => false,
                'message' => 'The selected dates are not available for this rental.'
            ];
        }
        if ($rental->status == 'ongoing') {
            return [
                'success' => false,
                'message' => ' you can not update this rental because it is ongoing'
            ];
        }
        if ($rental->status == 'canceled') {
            return [
                'success' => false,
                'message' => ' you can not update this rental because it is canceled'
            ];
        }
        if ($rental->status == 'completed') {
            return [
                'success' => false,
                'message' => ' you can not update this rental because it is completed'
            ];
        }


        $pendingUpdateRental = Updaterental::with('rental')->where('status', 'pending')->where('rental_id', $rental->id)->first();
        if ($pendingUpdateRental) {
            return [
                'success' => false,
                'message' => 'You already have a pending update rental request for this rental.'
            ];
        }
        $oldPrice = $rental->total_price;
        $apartment = Apartment::find($rental->apartment_id);
        $newtotalPrice = $apartment->calculateTotalPrice($request->input('start_date'), $request->input('end_date'));
        if ($oldPrice < $newtotalPrice) {
            if ($user->wallet < $newtotalPrice - $oldPrice) {
                return [
                    'success' => false,
                    'message' => 'You do not have enough money to update this rental.'
                ];
            }
            $user->wallet -= $newtotalPrice - $oldPrice;
            $user->save();
        } else {
            $user->wallet += $oldPrice - $newtotalPrice;
            $user->save();
        }
        if ($rental->status == 'pending') {
            $rental->start_date = $request->input('start_date');
            $rental->end_date = $request->input('end_date');
            $rental->total_price = $newtotalPrice;
            $rental->save();
            return [
                'success' => true,
                'message' => 'Rental updated successfully',
                'rental' => $rental
            ];

        }
        $updateRental = new Updaterental();
        $updateRental->rental_id = $rental->id;
        $updateRental->new_start_date = $request->input('start_date', $rental->start_date);
        $updateRental->new_end_date = $request->input('end_date', $rental->end_date);
        $updateRental->status = 'pending';
        $updateRental->save();
        return [
            'success' => true,
            'message' => 'Rental update request submitted successfully',
            'updaterental' => $updateRental
        ];
    }

    // Owner Methods
    public function listOwnerRentals(Request $request): array
    {
        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Unauthenticated'
            ];
        }
        $rentals = Rental::with('apartment', 'apartment.images')
            ->whereHas('apartment', function ($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->get();

        return [
            'success' => true,
            'message' => 'Owner rentals retrieved successfully',
            'rentals' => $rentals
        ];
    }
    public function ownershow(Request $request, $id): array
    {

        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Unauthenticated'
            ];
        }
        $rental = Rental::with('apartment', 'apartment.images')->findOrFail($id);
        if (!$rental) {
            return [
                'success' => false,
                'message' => 'unknown Rental'
            ];


        }
        if ($rental->apratment->owner_id !== $user->id) {
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

    public function approveOwnerRental(Request $request, $id): array
    {
        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Unauthenticated'
            ];
        }
        $rental = Rental::find($id);
        if (!$rental) {
            return [
                'success' => false,
                'message' => 'Rental not found'
            ];
        }
        if ($rental->status !== 'pending') {
            return [
                'success' => false,
                'message' => 'you can change only pending Rentals '
            ];
        }
        $apartment = Apartment::find($rental->apartment_id);
        if ($apartment->owner_id !== $user->id) {
            return [
                'success' => false,
                'message' => 'Unauthorized access to this rental'
            ];
        }
        $chickAvailability = Rental::checkAvailability(
            $rental->apartment_id,
            $rental->start_date,
            $rental->end_date
        );
        if (!$chickAvailability) {
            $renter = User::findOrFail($rental->renter_id);
            if (!$renter)
                $renter->wallet += $rental->total_price;
            $renter->save();
            $rental->status = 'rejected';
            $rental->save();
            return [
                'success' => false,
                'message' => 'The selected dates are not available for this rental so the rental rejected '
            ];
        }
        $rental->status = 'confirmed';
        $rental->save();
        return [
            'success' => true,
            'message' => 'Rental approved successfully'
        ];
    }
    public function rejectOwnerRental(Request $request, $id): array
    {
        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Unauthenticated'
            ];
        }
        $rental = Rental::find($id);
        if (!$rental) {
            return [
                'success' => false,
                'message' => 'Rental not found'
            ];
        }
        $apartment = Apartment::find($rental->apartment_id);
        if ($apartment->owner_id !== $user->id) {
            return [
                'success' => false,
                'message' => 'Unauthorized access to this rental'
            ];
        }
        if ($rental->status !== 'pending') {
            return [
                'success' => false,
                'message' => 'you can change only pending Rentals '
            ];
        }

        $renter = User::findOrFail($rental->renter_id);
        if (!$renter) {
            return [
                'success' => false,
                'message' => 'renter not found'

            ];
        }
        $renter->wallet += $rental->total_price;
        $renter->save();
        $rental->status = 'rejected';
        $rental->save();
        return [
            'success' => true,
            'message' => 'Rental rejected successfully'
        ];
    }
    public function ownerRentalsUpdate(Request $request)
    {

        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Unauthenticated'
            ];
        }
        $updateRentals = Updaterental::with('rental', 'rental.apartment', 'rental.apartment.images')
            ->whereHas('rental.apartment', function ($query) use ($user) {
                $query->where('owner_id', $user->id);
            });
        return [
            'success' => true,
            'message' => 'Rentals retrieved successfully',
            'updaterentals' => $updateRentals
        ];
    }
    public function getRentalForUpdate(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Unauthenticated'
            ];
        }
        $updaterental = Rental::findOrFail($id);
        if (!$updaterental) {
            return [
                'success' => false,
                'message' => 'Rental not found'
            ];
        }
        $rental = Rental::with('apartment', 'apartment.images')->findOrFail($updaterental->rental_id);
        if ($rental->apartment->owner_id !== $user->id) {
            return [
                'success' => false,
                'message' => 'Unauthorized access to this rental'
            ];
        }
        return [
            'success' => true,
            'message' => 'Rental retrieved successfully',
            'rental' => $rental
        ];

    }
    public function approveOwnerRentalupdate(Request $request, $id): array
    {
        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Unauthenticated'
            ];
        }
        $updaterental = Updaterental::find($id);
        if (!$updaterental) {
            return [
                'success' => false,
                'message' => 'Update rental request not found'
            ];
        }
        $rental = Rental::find($updaterental->rental_id);
        if (!$rental) {
            return [
                'success' => false,
                'message' => 'Rental not found'
            ];
        }
        $apartment = Apartment::find($rental->apartment_id);
        if (!$apartment) {
            return [
                'success' => false,
                'message' => 'Apartment not found'
            ];
        }
        if ($apartment->owner_id !== $user->id) {
            return [
                'success' => false,
                'message' => 'Unauthorized access to this update rental request'
            ];
        }

        $chickAvailability = Rental::checkAvailability($rental->apartment_id, $updaterental->new_start_date, $updaterental->new_end_date, $rental->id);
        if (!$chickAvailability || ($rental->status !== 'pending' && $rental->status !== 'confirmed')) {
            $new_total_price = $apartment::calculateTotalPrice($updaterental->new_start_date, $updaterental->new_end_date);
            $renter = User::findOrFail($rental->renter_id);
            if ($new_total_price < $rental->total_price) {
                $remaining_price = $rental->total_price - $new_total_price;
                if ($renter->wallet < $remaining_price) {
                    $renter->wallet += $new_total_price;
                    $updaterental->status = 'rejected';
                    $rental->status = 'rejected';
                    $renter->save();
                    $updaterental->save();
                    $rental->save();
                    return [
                        'success' => false,
                        'message' => 'update rental request is rejected and you dont have enough money to pay the remaining price so the rental is rejected also and your wallet is updated'
                    ];
                }
                $renter->wallet -= $remaining_price;
                $renter->save();

            }
            $renter->wallet += $rental->total_price - $new_total_price;
            $renter->save();
            $updaterental->status = 'rejected';
            $updaterental->save();
            return [
                'success' => false,
                'message' => 'Rental dates are not available so the update request is rejected'
            ];
        }
        $new_total_price = $apartment::calculateTotalPrice($updaterental->new_start_date, $updaterental->new_end_date);
        $rental->start_date = $updaterental->new_start_date;
        $rental->end_date = $updaterental->new_end_date;
        $rental->total_price = $new_total_price;
        $rental->save();
        $updaterental->status = 'approved';
        $updaterental->save();
        return [
            'success' => true,
            'message' => 'Rental update approved successfully'
        ];
    }
    public function rejectOwnerRentalupdate(Request $request, $id): array
    {
        $user = $request->user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Unauthenticated'
            ];
        }
        $updaterental = Updaterental::find($id);
        if (!$updaterental) {
            return [
                'success' => false,
                'message' => 'Update rental request not found'
            ];
        }
        $rental = Rental::find($updaterental->rental_id);
        if (!$rental) {
            return [
                'success' => false,
                'message' => 'Rental not found'
            ];
        }

        $apartment = Apartment::find($rental->apartment_id);
        if (!$apartment) {
            return [
                'success' => false,
                'message' => 'Apartment not found'
            ];
        }
        if ($apartment->owner_id !== $user->id) {
            return [
                'success' => false,
                'message' => 'Unauthorized access to this update rental request'
            ];
        }
        $new_total_price = $apartment::calculateTotalPrice($updaterental->new_start_date, $updaterental->new_end_date);
        $renter = User::findOrFail($rental->renter_id);
        if ($new_total_price < $rental->total_price) {
            $remaining_price = $rental->total_price - $new_total_price;
            if ($renter->wallet < $remaining_price) {
                $renter->wallet += $new_total_price;
                $updaterental->status = 'rejected';
                $rental->status = 'rejected';
                $renter->save();
                $updaterental->save();
                $rental->save();
                return [
                    'success' => false,
                    'message' => 'update rental request is rejected and you dont have enough money to pay the remaining price so the rental is rejected also and your wallet is updated'
                ];
            }
            $renter->wallet -= $remaining_price;
            $renter->save();

        }
        $renter->wallet += $rental->total_price - $new_total_price;
        $renter->save();




        $updaterental->status = 'rejected';
        $updaterental->save();
        return [
            'success' => true,
            'message' => 'Rental update rejected successfully'
        ];
    }
}