<?php

namespace App\Repositories;

use App\Models\Company;

class CompanyRepository extends EloquentRepository
{
    protected $model;

    function __construct(Company $company)
    {
        $this->model = $company;
    }

    public function getCompanies()
    {
        return $this->model->get();
    }
}