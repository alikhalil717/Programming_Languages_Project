<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
       
        'first_name',
        'last_name',
        'phone_number',
        'username',
        'date_of_birth',
        'profile_picture',
        'role',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
      
    ];

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }
    public function profileImages()
    {
        return $this->hasMany(Profileimage::class, 'user_id');
    }   


public function apartments()
    {
        return $this->hasMany(Apartment::class, 'owner_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }
    public function adminActions()
    {
        return $this->hasMany(Adminaction::class, 'admin_id');
    }
    public function targetAdminActions()
    {
        return $this->hasMany(Adminaction::class, 'target_user_id');
    }


    



    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    



    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

}
