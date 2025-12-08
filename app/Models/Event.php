<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    // Sesuaikan dengan kolom di migration 'create_events_table'
    protected $fillable = [
        'user_id', 
        'venue_id', 
        'title', 
        'description', 
        'start_time', 
        'end_time', 
        'banner_image', 
        'status'
    ];

    // Relasi ke User (Organizer)
    public function organizer() { 
        return $this->belongsTo(User::class, 'user_id'); 
    }

    // Relasi ke Venue
    public function venue() {
        return $this->belongsTo(Venue::class);
    }

    // Relasi Many-to-Many ke Category (Penyebab Error)
    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    // Relasi ke Tiket
    public function tickets() { 
        return $this->hasMany(Ticket::class); 
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}