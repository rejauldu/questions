<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Institution;
use App\Models\Subject;
use App\Models\Answer;
use App\Models\Board;


class Post extends Model
{

    protected $fillable = [
        'article',
        'a',
        'b',
        'c',
        'd',
        'answer',
        'answer_id',
        'subject_id',
        'topic',
        'sub_topic',
        'section',
        'sub_section',
        'category',
        'board_id',
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
    
    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
    
    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
