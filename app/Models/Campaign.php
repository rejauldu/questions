<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'institution_id', 
        'tagline', 
        'headline', 
        'button_text', 
        'is_active',
        'post_id',
        'created_at'
    ];

    public $timestamps = ["created_at"]; //only want to used created_at column
    const UPDATED_AT = null;

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}