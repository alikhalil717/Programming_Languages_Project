<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Apartment;
use App\Models\Rental;
use Carbon\Carbon;

class UpdateApartmentStatuses extends Command
{
    protected $signature = 'app:update-apartment-statuses {--debug}';
    protected $description = 'Update rental and apartment statuses based on current date';

    public function handle(): void
    {
        $debug = $this->option('debug');

        if ($debug) {
            $this->info(' DEBUG MODE: Starting update...');
            $this->info('Today: ' . now()->format('Y-m-d H:i:s'));
            $this->performUpdate(true);
            $this->info(' DEBUG MODE: Update completed!');
        } else {
            \Log::info('Auto-update started at ' . now()->format('Y-m-d H:i:s'));
            $this->performUpdate(false);
            \Log::info('Auto-update completed at ' . now()->format('Y-m-d H:i:s'));
        }
    }

    private function performUpdate(bool $verbose): void
    {
        $today = Carbon::today();
        $todayStr = $today->format('Y-m-d');

        $apartments = Apartment::all();

        if ($verbose) {
            $this->info(" Today's date: {$todayStr}");
            $this->info(" Processing {$apartments->count()} apartments...");
        }

        $updatedRentals = 0;
        $updatedApartments = 0;

        foreach ($apartments as $apartment) {
            if ($verbose) {
                $this->info("  Processing apartment #{$apartment->id}...");
            }

            $rentals = Rental::where('apartment_id', $apartment->id)
                ->whereIn('status', ['ongoing', 'confirmed'])
                ->get();

            foreach ($rentals as $rental) {
                $startDate = Carbon::parse($rental->start_date);
                $endDate = Carbon::parse($rental->end_date);

                if ($rental->status === 'ongoing') {
                    if ($endDate->lt($today)) {
                        $rental->status = 'finished';
                        $rental->save();
                        $updatedRentals++;

                        if ($verbose) {
                            $this->info("    Rental #{$rental->id}: ongoing → finished");
                        }
                    }
                } elseif ($rental->status === 'confirmed') {
                    if ($startDate->lte($today) && $endDate->gte($today)) {
                        $rental->status = 'ongoing';
                        $rental->save();
                        $updatedRentals++;

                        if ($verbose) {
                            $this->info("  Rental #{$rental->id}: confirmed → ongoing");
                        }
                    }
                }
            }

            if ($this->updateApartmentStatus($apartment, $today, $verbose)) {
                $updatedApartments++;
            }
        }

        if ($verbose) {
            $this->info(" Summary:");
            $this->info("  Updated rentals: {$updatedRentals}");
            $this->info("  Updated apartments: {$updatedApartments}");
        }
    }

    private function updateApartmentStatus(Apartment $apartment, Carbon $today, bool $verbose): bool
    {
        $oldStatus = $apartment->Rental_status;

        $hasOngoing = Rental::where('apartment_id', $apartment->id)
            ->where('status', 'ongoing')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->exists();

        $hasFuture = Rental::where('apartment_id', $apartment->id)
            ->where('status', 'confirmed')
            ->whereDate('start_date', '>', $today)
            ->exists();

        if ($hasOngoing) {
            $apartment->Rental_status = 'occupied';
        } elseif ($hasFuture) {
            $apartment->Rental_status = 'booked';
        } else {
            $apartment->Rental_status = 'available';
        }

        if ($oldStatus !== $apartment->Rental_status) {
            $apartment->save();

            if ($verbose) {
                $this->info(" Apartment #{$apartment->id}: {$oldStatus} → {$apartment->Rental_status}");
            }

            return true;
        }

        return false;
    }
}