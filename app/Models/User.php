<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. Wajib ada
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable; // 2. Wajib dipanggil di sini

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role', 
        'identity_number', 
        'phone_number', 
        'face_photo', 
        'balance', 
        'is_banned'
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

    // --- RELATIONS ---

    // Relasi ke Profile Organizer
    public function organizer_profile() {
        return $this->hasOne(Organizer_profile::class);
    }

    // Relasi ke API Key
    public function apiKey() {
        return $this->hasOne(OrganizerApiKey::class);
    }

    // Relasi ke Tiket yang dimiliki
    public function tickets() {
        return $this->hasMany(Ticket::class);
    }
    
    // Relasi ke Riwayat Saldo
    public function wallet_mutations() {
        return $this->hasMany(Wallet::class);
    }
}