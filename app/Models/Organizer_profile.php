<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organizer_profile extends Model
{
    protected $fillable = [
        'company_name',
        'bank_account_number',
        'bank_name',
        'verification_status'
    ];

    public function users() {
        return $this->belongsTo(User::class);
    }
}
