<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Institution;
use App\Models\Subject;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'article',
        'a',
        'b',
        'c',
        'd',
        'answer',
        'subject_id',
        'topic',
        'sub_topic',
        'section',
        'sub_section',
        'category',
        'board',
        'year',
        'class',
        'institution_id',
        'url'
    ];

    protected $casts = [
        'year' => 'integer',
        'class' => 'string',
    ];

    /**
     * ---------------------------
     * Institution Relationship
     * ---------------------------
     * Each Post belongs to one Institution.
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
