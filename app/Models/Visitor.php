<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model {
    protected $fillable = ['visitor_uuid', 'user_id', 'updated_at'];

    // Relation: A visitor might belong to a registered user
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relation: A visitor has many activity logs
    public function activities() {
        return $this->hasMany(ActivityLog::class, 'visitor_uuid', 'visitor_uuid');
    }
}
