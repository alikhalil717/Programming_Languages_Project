<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [


        'first_name',
        'last_name',
        'email',
        'password',
        'wallet',
        'phone_number',
        'date_of_birth',
        'role',
        'status',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',

    ];
    public function delete()
    {
        $profileImage = $this->profile_picture;
        if ($profileImage) {
            $profileImage->delete();
        }
        $personalId = $this->personal_id;
        if ($personalId) {
            $personalId->delete();
        }
        $this->notifications()->delete();
        $this->apartments()->delete();
        $this->reviews()->delete();
        $this->favorites()->delete();
        $this->adminActions()->delete();
        $this->targetAdminActions()->delete();
        $this->rentals()->delete();
        $this->personal_id()->delete();
        parent::delete();
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }
    public function profile_picture()
    {
        return $this->hasOne(Profileimage::class, 'user_id');
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
    public function rentals()
    {
        return $this->hasMany(Rental::class, 'renter_id');
    }
    public function personal_id()
    {
        return $this->hasOne(Personalid::class, 'user_id');
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
