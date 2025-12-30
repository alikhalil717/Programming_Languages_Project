<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Apartment;
use App\Models\User;
use Carbon\Carbon;
class Rental extends Model
{
    /** @use HasFactory<\Database\Factories\RentalFactory> */
    use HasFactory;
    protected $fillable = [
        'apartment_id',
        'renter_id',
        'start_date',
        'end_date',
        'total_price',
        'status',
        'special_requests',
        'payment_method',
    ];
    public function apartment()
    {
        return $this->belongsTo(Apartment::class, 'apartment_id');
    }

    public function renter()
    {
        return $this->belongsTo(User::class, 'renter_id');
    }

    public static function checkAvailability($apartmentId, $startDate, $endDate, $excludeRentalId = null): bool
    {
        $query = self::where('apartment_id', $apartmentId)->whereIn('status', ['confirmed', 'ongoing'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            });


        if ($excludeRentalId) {
            $query = $query->where('id', '!=', $excludeRentalId);
        }
        return !$query->exists();
    }
    public static function getAllAvailableDates($apartmentId, $startDate = null, $endDate = null): array
    {
        $start = $startDate ? Carbon::parse($startDate) : Carbon::today();
        $end = $endDate ? Carbon::parse($endDate) : Carbon::today()->addMonths(6);

        $rentals = self::where('apartment_id', $apartmentId)
            ->whereIn('status', ['confirmed', 'ongoing'])
            ->get();

        $bookedDates = [];
        foreach ($rentals as $rental) {
            $period = new \DatePeriod(
                new \DateTime($rental->start_date),
                new \DateInterval('P1D'),
                (new \DateTime($rental->end_date))->modify('+1 day')
            );

            foreach ($period as $date) {
                $bookedDates[] = $date->format('Y-m-d');
            }
        }

        $bookedDates = array_unique($bookedDates);

        $allDatesPeriod = new \DatePeriod(
            new \DateTime($start->format('Y-m-d')),
            new \DateInterval('P1D'),
            (new \DateTime($end->format('Y-m-d')))->modify('+1 day')
        );

        $allDates = [];
        foreach ($allDatesPeriod as $date) {
            $allDates[] = $date->format('Y-m-d');
        }

        $availableDates = array_diff($allDates, $bookedDates);

        return array_values($availableDates);
    }

    public static function getAvailablePeriods($apartmentId, $startDate = null, $endDate = null): array
    {
        $availableDates = self::getAllAvailableDates($apartmentId, $startDate, $endDate);

        if (empty($availableDates)) {
            return [];
        }

        $periods = [];
        $currentPeriod = null;

        sort($availableDates);

        foreach ($availableDates as $date) {
            if ($currentPeriod === null) {
                $currentPeriod = ['start_date' => $date, 'end_date' => $date];
            } else {
                $previousDate = Carbon::parse($currentPeriod['end_date']);
                $currentDate = Carbon::parse($date);

                if ($previousDate->addDay()->format('Y-m-d') === $currentDate->format('Y-m-d')) {
                    $currentPeriod['end_date'] = $date;
                } else {
                    $nights = Carbon::parse($currentPeriod['start_date'])->diffInDays(Carbon::parse($currentPeriod['end_date'])) + 1;
                    $currentPeriod['nights'] = $nights;
                    $periods[] = $currentPeriod;
                    $currentPeriod = ['start_date' => $date, 'end_date' => $date];
                }
            }
        }

        if ($currentPeriod !== null) {
            $nights = Carbon::parse($currentPeriod['start_date'])->diffInDays(Carbon::parse($currentPeriod['end_date'])) + 1;
            $currentPeriod['nights'] = $nights;
            $periods[] = $currentPeriod;
        }

        return $periods;


    }
    public function updaterentals()
    {
        return $this->hasMany(Updaterental::class);
    }
    
}
