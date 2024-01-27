<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteAnnotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'note_id',
        'creator_id',
        'annotation_identifier',
        'content',
        'page',
        'yloc',
    ];
}
