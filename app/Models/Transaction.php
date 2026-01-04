<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // protected $fillable = [
    //     'user_id', 'event_id', 'transaction_date', 'total_amount', 'status', 'snap_token'
    // ];

    protected $fillable = [
        'user_id', 'event_id', 'ticket_id', 'seat_number', 'identity_number', 
        'face_image_path', 'transaction_date', 'total_amount', 'status',
        'midtrans_booking_code', 'snap_token', 'payment_type'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function ticket() {
        return $this->hasOne(Ticket::class);
    }
}