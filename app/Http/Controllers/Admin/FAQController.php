<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\FAQRequest;
use App\Repositories\FaqRepository;

class FAQController extends Controller
{

    function __construct(FaqRepository $faqRepo)
    {
        $this->faqRepo = $faqRepo;
    }

    public function index()
    {
        $page_title = 'FAQs';
        $faqs = $this->faqRepo->get_search_custom([]);
        return view('faqs.index',compact('page_title', 'faqs'));
    }

    public function create()
    {
        $page_title = 'Add Company';
        return view('faqs.create', compact('page_title'));
    }

    public function store(FAQRequest $request)
    {
        $inputs = $request->all();

        $faq_id = $this->faqRepo->add($inputs, false);

        $message = 'Faq added successfully.';
        return redirect()->back()->withSuccess($message);
    }

    public function show($id)
    {
        $page_title = 'Faq Details';
        $faq = $this->faqRepo->get($id);
        return view('faqs.show',compact('page_title','faq'));
    }

    public function edit($id)
    {
        $page_title = 'Update Category';
        $faq   = $this->faqRepo->get($id);    
        return view('faqs.edit',compact('page_title','faq'));
    }

    public function update(FAQRequest $request, $id)
    {
        $inputs   = $request->all();
        $faq = $this->faqRepo->get($id);
        $inputs['id'] = $id;

        $this->faqRepo->edit($inputs, false);

        $message = 'Faq updated successfully.';
        return redirect()->back()->withSuccess($message);
    }

    public function destroy($id)
    {
        return $this->faqRepo->delete($id);
    }
}
