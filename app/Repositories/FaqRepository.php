<?php

namespace App\Repositories;

use App\Models\Faq;

class FaqRepository extends EloquentRepository
{
    protected $model;

    function __construct(Faq $faq)
    {
        $this->model = $faq;
    }

    public function getFaqs($type='')
    {
        return $this->model->when($type, function($q) use ($type){
                                $q->where('type', $type);   
                            })
                            ->get();
    }
}