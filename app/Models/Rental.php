<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    
}
