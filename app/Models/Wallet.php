<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    // Model ini mencatat RIWAYAT transaksi (Mutasi)
    protected $fillable = ['user_id', 'type', 'amount', 'description'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}