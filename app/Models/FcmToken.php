<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FcmToken extends Model
{
    protected $fillable = ['user_id', 'fcm_token', 'device_type', 'is_active'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}