<?php

namespace App\Repositories;

use App\Models\CoachCompany;

class CoachCompanyRepository extends EloquentRepository
{
    
    protected $model;

    function __construct(CoachCompany $CoachCompany)
    {
        $this->model = $CoachCompany;
    }
}