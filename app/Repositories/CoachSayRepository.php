<?php

namespace App\Repositories;

use App\Models\CoachSay;

class CoachSayRepository extends EloquentRepository
{
    protected $model;

    function __construct(CoachSay $coachSay)
    {
        $this->model = $coachSay;
    }

    public function getAll()
    {
        return $this->model->with('coach')->get();
    }
    
}