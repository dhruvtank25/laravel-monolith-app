<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CoachSayRepository;
use App\Repositories\UserRepository;

class CoachSayController extends Controller
{

    function __construct(CoachSayRepository $coachSayRepo, UserRepository $userRepo)
    {
        $this->coachSayRepo = $coachSayRepo;
        $this->userRepo     = $userRepo;
    }

    public function index()
    {
        $page_title = 'What Coach Says';
        $coachsays = $this->coachSayRepo->getAll();
        return view('coachsays.index',compact('page_title', 'coachsays'));
    }

    public function create()
    {
        $page_title = 'Add Coach Says';
        $coaches    = $this->userRepo->getActiveCoaches();
        return view('coachsays.create', compact('page_title', 'coaches'));
    }

    public function store(Request $request)
    {
        $inputs = $request->all();

        $id = $this->coachSayRepo->add($inputs, false);

        $message = 'Coach Feedback added successfully.';
        return redirect()->back()->withSuccess($message);
    }

    public function show($id)
    {
        $page_title = 'Coach Feedback Details';
        $coachsay = $this->coachSayRepo->get($id);
        $coach    = $coachsay->coach;
        return view('coachsays.show',compact('page_title', 'coachsay', 'coach'));
    }

    public function edit($id)
    {
        $page_title = 'Update Coach Says';
        $coachsay   = $this->coachSayRepo->get($id);
        $coaches    = $this->userRepo->getActiveCoaches();
        return view('coachsays.edit',compact('page_title','coachsay', 'coaches'));
    }

    public function update(Request $request, $id)
    {
        $inputs   = $request->all();
        $inputs['id'] = $id;

        $this->coachSayRepo->edit($inputs, false);

        $message = 'Coach Says updated successfully.';
        return redirect()->back()->withSuccess($message);
    }

    public function destroy($id)
    {
        return $this->coachSayRepo->delete($id);
    }
}
