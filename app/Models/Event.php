<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\TicketCategory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'venue_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'banner_image',
        'status',
        'price',
        'quota',
    ];

    public function organizer() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function venue() {
        return $this->belongsTo(Venue::class);
    }

    public function ticketCategories() {
        return $this->hasMany(TicketCategory::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }
}
