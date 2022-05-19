<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends EloquentRepository
{
    protected $model;

    function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function getBySlug($url_slug)
    {
        return $this->model->where('url_slug', 'like', '%'.$url_slug.'%')->first();
    }

}