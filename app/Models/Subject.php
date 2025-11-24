<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}