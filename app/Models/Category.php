<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'title', 'url_slug', 'icon', 'banner', 'short_description', 'description'
    ];

    /**
     * The coaches that belong to the category.
     */
    public function coaches()
    {
       return $this->belongsToMany('App\Models\User', 'coach_categories', 'category_id', 'coach_id');
    }
    
}
