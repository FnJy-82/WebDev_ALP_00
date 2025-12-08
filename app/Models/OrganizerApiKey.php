<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrganizerApiKey extends Model
{
    protected $fillable = ['user_id', 'api_key'];
    public static function generate($userId) {
        return self::create([
            'user_id' => $userId,
            'api_key' => Str::random(40)
        ]);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}