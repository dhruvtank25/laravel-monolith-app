<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\FAQRequest;
use App\Repositories\CoachReviewRepository;
use DataTables;

class ReviewController extends Controller
{

    function __construct(CoachReviewRepository $reviewRepo)
    {
        $this->reviewRepo = $reviewRepo;
    }

    public function index()
    {
        $page_title = 'Reviews';
        return view('reviews.index',compact('page_title'));
    }

    public function getDataTables(Request $request, $userRole='')
    {
        $inputs = $request->all();
        $reviews = $this->reviewRepo->getDataTable();
        return DataTables::of($reviews)->toJson();
    }

    public function show($id)
    {
        $page_title = 'Review Details';
        $review = $this->reviewRepo->get($id);
        return view('reviews.show',compact('page_title','review'));
    }

    public function edit($id)
    {
        $page_title = 'Update Review';
        $review   = $this->reviewRepo->get($id);    
        return view('reviews.edit',compact('page_title','review'));
    }

    public function update(ReviewRequest $request, $id)
    {
        $inputs   = $request->all();
        $review = $this->reviewRepo->get($id);
        $inputs['id'] = $id;

        $this->reviewRepo->edit($inputs, false);

        $message = 'Review updated successfully.';
        return redirect()->back()->withSuccess($message);
    }

    public function destroy($id)
    {
        return $this->reviewRepo->delete($id);
    }
}
