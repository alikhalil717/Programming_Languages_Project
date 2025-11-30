<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personalid extends Model
{
    //
    protected $fillable = [
        'user_id',
        'personal_id_number',
        'image_path',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
