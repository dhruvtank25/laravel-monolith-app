<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachAvailability extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'coach_id', 'status', 'is_requestable', 'date_on', 'time_from', 'time_to', 'recurring', 'recurring_weeks', 'recurring_week_days', 'recurring_end'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date_on', 'recurring_end'];
    
    /**
     * Get Coach
     */
    public function coach()
    {
       return $this->belongsTo('App\Models\User');
    }
    
}
