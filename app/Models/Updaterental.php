<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Updaterental extends Model
{

    protected $fillable = [
        'rental_id',
        'new_start_date',
        'new_end_date',
        'status',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
