<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestSlot extends Model
{
    
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'appointment_request_id', 'start', 'end', 'mode', 'location', 'requested_by', 'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start','end'];
    
}
