<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shareholder extends Model
{
    
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = ['user_id', 'first_name', 'last_name', 'street', 'post_code', 'place', 'country', 'nationality', 'birth_date', 'birth_place', 'birth_land'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['birth_date'];
    
}
