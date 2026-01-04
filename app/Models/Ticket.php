<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id', 'event_id', 'category_id', // Pastikan category_id ada
        'transaction_date', 'seat_number', 
        'face_photo_path', 'status', 'qr_code_hash', 'transaction_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }

    // TAMBAHAN: Relasi ke Category untuk ambil harga
    public function category() {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }
}