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
        'price_per_night',
        'number_of_bedrooms',
        'number_of_bathrooms',
    ];
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
