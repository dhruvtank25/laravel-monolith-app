<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name', 'image'
    ];


    /**
     * The coaches that belong to the company.
     */
    public function coaches()
    {
       return $this->belongsToMany('App\Models\User', 'coach_companies', 'company_id', 'coach_id');
    }
    
}
