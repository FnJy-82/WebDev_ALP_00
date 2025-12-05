<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'banner_image',
        'status',
        'user_id',
        'venue_id'
    ];

    // Relasi ke User (Organizer)
    public function organizer() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Venue (1 Event punya 1 Venue)
    public function venue() {
        return $this->belongsTo(Venue::class);
    }

    // Relasi ke Category (1 Event bisa punya banyak Kategori)
    // Contoh: Konser Amal (Masuk kategori 'Musik' dan 'Sosial')
    public function categories() {
        return $this->belongsToMany(Category::class);
    }
}
