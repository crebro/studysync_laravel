<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStreak extends Model
{
    use HasFactory;

    protected $table = 'user_streaks';

    protected $fillable = [
        'user_id',
        'streak',
        'last_practiced_at',
    ];
}
