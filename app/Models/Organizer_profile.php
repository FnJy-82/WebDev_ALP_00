<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organizer_profile extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'bank_account_number',
        'bank_name',
        'document_path',
        'verification_status'
    ];

    public function users() {
        return $this->belongsTo(User::class);
    }
}
