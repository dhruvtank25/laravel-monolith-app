<?php

namespace App\Http\Controllers\Coach;

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

    public function index()
    {
        $page_title    = 'Availablities';
        return view('coach.availablilities', compact('page_title'));
    }

}