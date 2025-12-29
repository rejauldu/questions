<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Institution;
use App\Models\Subject;
use App\Models\Answer;
use App\Models\Board;
use App\Models\Comment;


class Post extends Model
{

    protected $fillable = [
        'article',
        'a',
        'b',
        'c',
        'd',
        'ans',
        'explanation',
        'answer_id',
        'subject_id',
        'chapter',
        'category',
        'board_id',
        'year',
        'class',
        'institution_id',
        'image1',
        'image2',
        'image3',
        'image4'
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
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
}
