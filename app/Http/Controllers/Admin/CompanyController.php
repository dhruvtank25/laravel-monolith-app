<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Repositories\CompanyRepository;

class CompanyController extends Controller
{
    function __construct(CompanyRepository $companyRepo)
    {
        $this->companyRepo = $companyRepo;
    }
    public function index()
    {
        $page_title = 'Companies';
        $companies = $this->companyRepo->get_search_custom([]);
        return view('companies.index',compact('page_title', 'companies'));
    }

    public function create()
    {
        $page_title = 'Add Company';
        return view('companies.create', compact('page_title'));
    }

    public function store(CompanyRequest $request)
    {
        $inputs = $request->all();

        if(isset($request->image)) {
            $inputs['image']   = FileUploadHelper::uploadCompanyImage($inputs);
        }

        $company_id = $this->companyRepo->add($inputs, false);

        $message = 'Company added successfully.';
        return redirect()->back()->withSuccess($message);
    }


    public function show(Request $request, $id)
    {
        $page_title = 'Company Details';
        $company = $this->companyRepo->get($id);
        return view('companies.show',compact('page_title','company'));
    }

    public function edit($company_id)
    {
        $page_title = 'Update Category';
        $company   = $this->companyRepo->get($company_id);    
        return view('companies.edit',compact('page_title','company'));
    }

    public function update(CompanyRequest $request, $company_id)
    {
        $inputs   = $request->all();
        $company = $this->companyRepo->get($company_id);
        $inputs['id'] = $company_id;

        if(isset($request->image)) {
            $inputs['image']   = FileUploadHelper::uploadCompanyImage($inputs);
            if($company->image) // Delete previous image
                FileUploadHelper::deleteCompanyImage($company->image);
        }
               
        $this->companyRepo->edit($inputs, false);

        $message = 'Company updated successfully.';
        return redirect()->back()->withSuccess($message);
    }

    public function destroy($id)
    {
        return $this->companyRepo->delete($id);
    }
}
