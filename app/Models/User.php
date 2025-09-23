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
        'name',
        'email',
        'password',
         'profile_photo_path',
         'role_uuid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

   public function getProfilePhotoUrlAttribute()
{
    if ($this->profile_photo_path) {
        // Return the URL for the stored profile photo if exists
        return asset('storage/' . $this->profile_photo_path);
    }

    // Return a default avatar if no profile photo exists
   return asset('assets/img/avatar/avatar-25.png');
}

public function role()
{
    return $this->belongsTo(Role::class, 'role_uuid', 'role_uuid');
}


}

