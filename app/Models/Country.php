<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = ['name', 'code', 'alpha3Code', 'capital', 'region', 'native_name', 'currency_code', 'currency_name', 'currency_symbol'];
    
}
