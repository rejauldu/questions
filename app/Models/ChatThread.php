<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatThread extends Model
{
    use HasFactory;

    /**
     * ULID is a string primary key.
     */
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Fields that can be mass assigned.
     */
    protected $fillable = [
        'user_id',
        'title',
        'is_pending',
    ];

    /**
     * Auto-generate ULID when creating.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::ulid();
            }
        });
    }

    /**
     * A ChatThread belongs to one User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A ChatThread has many Messages.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'thread_id');
    }
}