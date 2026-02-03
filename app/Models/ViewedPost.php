<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViewedPost extends Model
{
    
    // Disable standard timestamps if you only use 'viewed_at'
    public $timestamps = false; 

    protected $fillable = ['user_id', 'post_id', 'viewed_at'];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];
    
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}