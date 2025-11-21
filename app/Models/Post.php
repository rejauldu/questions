<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * Table name (optional if table name = posts)
     */
    protected $table = 'posts';

    /**
     * Allow mass assignment
     */
    protected $fillable = [
        'article',
        'a',
        'b',
        'c',
        'd',
        'answer',
        'subject',
        'topic',
        'sub_topic',
        'section',
        'sub_section',
        'category',
        'board',
        'year',
        'class',
    ];

    /**
     * Casts (optional)
     * Use this only if you later want arrays, JSON, or booleans.
     */
    protected $casts = [
        'year' => 'integer',
        'class' => 'string',
    ];
}