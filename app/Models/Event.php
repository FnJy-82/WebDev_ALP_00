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
        'user_id'
    ];

    public function organizer_profiles() {
        return $this->hasOne(Organizer_profile::class);
    }

    public function organizer() {
        return $this->belongsTo(User::class, 'user_id')
                ->whereIn('role', ['event_organizer', 'admin']);
    }
}
