<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model {
    
    public $timestamps = false;

    protected $fillable = ['visitor_uuid', 'institution_id', 'subject_id', 'action_type'];

    // Relation: Get the post/institution related to this log
    public function institution() {
        return $this->belongsTo(Institution::class, 'institution_id');
    }
}
