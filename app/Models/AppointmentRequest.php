<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentRequest extends Model
{
    
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'user_id', 'coach_id', 'appointment_id', 'category_id', 'notes', 'price_per_hour', 'status'
    ];
    
    /**
     * get catogories
     */
    public function categories()
    {
        return $this->belongsTo('App\Models\category','category_id', 'id');
    }

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

    public function slots()
    {
        return $this->hasMany('App\Models\RequestSlot');
    }

    public function getAcceptedSlot()
    {
        return $this->slots()->where('status', 'accepted');
    }
    
}
