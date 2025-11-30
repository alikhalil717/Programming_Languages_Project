<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apartmentimage extends Model
{
    
    protected $fillable = [
        'apartment_id',
        'image_path',
    ];
    

    public function apartment()
    {
        return $this->belongsTo(Apartment::class, 'apartment_id');
    }



}
