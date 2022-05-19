<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CoachAvailabilityRepository;
use App\Traits\AvailabilityTrait;

class AvailabilityController extends Controller
{
    use AvailabilityTrait;

    function __construct(CoachAvailabilityRepository $availabilityRepo)
    {
        $this->availabilityRepo = $availabilityRepo;
    }

    public function index($coach_id)
    {
        $page_title    = 'Coach Availability';
        $data          = $this->availabilityRepo->getAvailableDates($coach_id);
        return view('coaches.availability', compact('page_title', 'coach_id'));
    }

}