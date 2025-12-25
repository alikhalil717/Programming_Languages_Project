<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    /** @use HasFactory<\Database\Factories\ApartmentFactory> */
    use HasFactory;


    protected $fillable = [
        'owner_id',
        'title',
        'description',
        'address',
        'city',
        'state',
        'rental_status',
        'price_per_night',
        'number_of_bedrooms',
        'number_of_bathrooms',
        'status',
    ];

    public function scopeFilter($query, array $filters)
    {

        $query->when($filters['city'] ?? null, function ($query) use ($filters) {
            $query->where('city', 'like', '%' . $filters['city'] . '%');
        });
        $query->when($filters['min_price'] ?? null, function ($query) use ($filters) {
            $query->where('price_per_night', '>=', $filters['min_price']);
        });
        $query->when($filters['max_price'] ?? null, function ($query) use ($filters) {
            $query->where('price_per_night', '<=', $filters['max_price']);
        });
        $query->when($filters['state'] ?? null, function ($query) use ($filters) {
            $query->where('state', 'like', '%' . $filters['state'] . '%');
        });
        $query->when($filters['number_of_bedrooms'] ?? null, function ($query) use ($filters) {
            $query->where('number_of_bedrooms', '>=', $filters['number_of_bedrooms']);
        });
        $query->when($filters['number_of_bathrooms'] ?? null, function ($query) use ($filters) {
            $query->where('number_of_bathrooms', '>=', $filters['number_of_bathrooms']);
        });


    }
    public function isAvailable($startDate, $endDate): bool
    {
        return Rental::checkAvailability($this->id, $startDate, $endDate);
    }
    public function calculateTotalPrice($startDate, $endDate): float
    {
        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);
        $nights = $start->diffInDays($end);
        return $nights * $this->price_per_night;
    }

    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return null;
    }
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }


    public function images()
    {
        return $this->hasMany(Apartmentimage::class, 'apartment_id');
    }


    public function reviews()
    {
        return $this->hasMany(Review::class, 'apartment_id');
    }


    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'apartment_id');
    }



}
