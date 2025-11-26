<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Institution;
Use App\Models\Post;

class Subject extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'class',
        'institution_id',
        'year',
        'exam_at',
        'description',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'exam_at' => 'datetime', // Casts the database column to a Carbon instance
    ];

    /**
     * Get the institution that owns the subject.
     * (Assumes you have an Institution model and migration)
     */
    public function institution(): HasMany
    {
        return $this->hasMany(Institution::class);
    }
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}