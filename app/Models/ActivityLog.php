<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'initiated_by', 'initiated_by_id', 'activity_type', 'activity', 'activitable_type', 'activitable_id'
    ];
    
    public function initiater()
    {
        return $this->belongsTo('App\Models\User', 'initiated_by_id');
    }

    /**
     * Get all of the owning activitable models.
     */
    public function activitable()
    {
       return $this->morphTo();
    }

}
