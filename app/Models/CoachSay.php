<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachSay extends Model
{
    
    protected $fillable = [
        'coach_id', 'comment'
    ];

    /**
     * Get coach
     */
    public function coach()
    {
        return $this->belongsTo('App\Models\User', 'coach_id')->withTrashed();
    }
    
}
