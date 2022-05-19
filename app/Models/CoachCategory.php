<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachCategory extends Model
{
    
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'coach_id', 'category_id'
    ];

}
