<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFlashCardsPractice extends Model
{
    use HasFactory;

    protected $table = 'user_flashcards_practice';

    protected $fillable = [
        'user_id',
        'question_bank_id',
        'last_practiced_at',
    ];

}
