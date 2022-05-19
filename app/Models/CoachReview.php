<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoachReview extends Model
{

    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'user_id', 'coach_id', 'appointment_id', 'comment', 'rating'
    ];

    /**
     * Get user
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    
    /**
     * Get coach
     */
    public function coach()
    {
        return $this->belongsTo('App\Models\User', 'coach_id');
    }
    
}
