<?php

namespace App\Observers;

use App\Models\Apartment;
use App\Models\Rental;

class RentalObserver
{
    public function created(Rental $rental)
    {
        $this->updateApartmentStatus($rental);
    }
    public function updated(Rental $rental)
    {
        $this->updateApartmentStatus($rental);
    }
    public function deleted(Rental $rental)
    {
        $this->updateApartmentStatus($rental);
    }

    public function updateApartmentStatus(Rental $rental)
    {

        $apartment = Apartment::findOrFail($rental->apartment_id);
        if (!$apartment) {
            return;
        }
        $Rentals = Rental::where('apartment_id', $apartment->id)
            ->whereIn('status', ['confirmed', 'ongoing'])
            ->get();
        $apartment->rental_status = 'available';

        if ($Rentals->isEmpty()) {
            return;
        } else {
            foreach ($Rentals as $rental) {
                if ($rental->status === 'ongoing') {
                    $apartment->rental_status = 'rented';
                    break;
                } elseif ($rental->status === 'confirmed') {
                    $apartment->rental_status = 'booked';
                }
            }



        }
    }
}