<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'institution_id',
        'role',
        'hsc_group',   // Science, Commerce, Arts
        'points',      // For Gamification
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'points' => 'integer',
        ];
    }

    /**
     * The Institution the user belongs to (e.g. Dhaka College)
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Track all exam attempts/results
     */
    public function examAttempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    /**
     * Questions the user has bookmarked
     */
    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function viewedPosts()
    {
        return $this->belongsToMany(Post::class, 'viewed_posts')
                    ->using(PostView::class) // Tell Laravel to use your custom pivot model
                    ->withPivot('viewed_at')
                    ->orderByPivot('viewed_at', 'desc');
    }
}