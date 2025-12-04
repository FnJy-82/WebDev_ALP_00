<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = [
        'name',
        'address',
        'city',
        'role',
        'capacity',
        'layout_image_url'
    ];

    public function categories() {
        return $this->belongsToMany(Category::class);
    }
}
