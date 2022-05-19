<?php

namespace App\Repositories;

use App\Models\CoachReview;

class CoachReviewRepository extends EloquentRepository
{
    protected $model;

    function __construct(CoachReview $review)
    {
        $this->model = $review;
    }

    public function getDataTable()
    {
        return $this->model
                    ->select(['coach_reviews.*'])
                    ->with('coach:id,first_name,last_name', 'user:id,first_name,last_name')
                    ->orderBy('coach_reviews.created_at', 'desc');
    }

}