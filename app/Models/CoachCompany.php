<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachCompany extends Model
{
    
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'coach_id', 'company_id', 'company_name', 'joining_date', 'designation', 'document'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['joining_date'];

    /**
     * Get Coach
     */
    public function coach()
    {
       return $this->belongsTo('App\Models\User');
    }

}
