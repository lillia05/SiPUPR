<?php

namespace App\Models;

use App\Notifications\NasabahVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username', 
        'email',
        'password',
        'email_verified_at',
        'role', 
        'status',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function sendEmailVerificationNotification()
    {
        // Cek Role, jika Nasabah pakai template khusus
        if ($this->role === 'Nasabah') {
            $this->notify(new NasabahVerifyEmail); 
        } else {
            // Jika Admin/Funding (misal register lewat admin), pakai template default Laravel
            // Atau Anda bisa parent::sendEmailVerificationNotification(); jika tidak di-override
            $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail); 
        }
    }
}
