<?php

namespace App\Repositories;

use App\Models\Country;

class CountryRepository extends EloquentRepository
{
    protected $model;

    function __construct(Country $country)
    {
        $this->model = $country;
    }

    public function totalRecords()
    {
        return $this->model->count();
    }

    public function getForDropdown()
    {
        return $this->model->get(['id', 'name', 'code']);
    }

    public function deleteAll()
    {
        return $this->model->truncate();
    }

    public function insert($data_arr)
    {
        return $this->model->insert($data_arr);
    }

}