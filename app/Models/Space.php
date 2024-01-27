<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'space_identifier',
        'invitation_code',
        'creator_id',
    ];

    /**
     * Get all of the notes for the Space
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class, 'space_id', 'id');
    }

    public function questionBanks()
    {
        return $this->hasMany(QuestionBank::class, 'space_id', 'id');
    }
}
