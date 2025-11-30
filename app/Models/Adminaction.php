<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adminaction extends Model
{
    /** @use HasFactory<\Database\Factories\AdminactionFactory> */
    use HasFactory;
    protected $fillable = [
        'admin_id',
        'action_type',
        'description',
        'target_user_id',
    ];
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
    



}
