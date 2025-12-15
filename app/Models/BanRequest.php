<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BanRequest extends Model
{
    protected $fillable = ['organizer_id', 'target_user_id', 'reason', 'status'];

    public function organizer() {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function targetUser() {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
