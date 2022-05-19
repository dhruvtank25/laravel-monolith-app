<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\CategoryRequest;
use App\Repositories\CategoryRepository;
use Storage;

class CategoryController extends Controller
{
    function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function index()
    {
        $page_title = 'Categories';
        $categories = $this->categoryRepo->get_search_custom([]);
        return view('categories.index',compact('page_title', 'categories'));
    }

    public function show(Request $request, $id)
    {
        $page_title = 'Category Details';
        $category = $this->categoryRepo->get($id);
        return view('categories.show',compact('page_title','category'));
    }

    public function create()
    {
        $page_title = 'Add Category';
        return view('categories.create', compact('page_title'));
    }

    public function store(CategoryRequest $request)
    {
        $inputs = $request->all();

        if(isset($request->icon)){
            $svg_file = file_get_contents($request->icon);

            $find_string   = '<svg';
            $position = strpos($svg_file, $find_string);

            $svg_html_tag = substr($svg_file, $position);
            $inputs['icon'] = $svg_html_tag;
        }

        if(isset($request->banner)) {
            $inputs['banner']   = FileUploadHelper::uploadCategoryBanner($inputs);
        }

        $category_id = $this->categoryRepo->add($inputs, false);

        $message = 'Category added successfully.';
        return redirect()->back()->withSuccess($message);
    }

    public function edit($category_id)
    {
        $page_title = 'Update Category';
        $category   = $this->categoryRepo->get($category_id);    
        return view('categories.edit',compact('page_title','category'));
    }

    public function update(CategoryRequest $request, $category_id)
    {
        $inputs   = $request->all();
        $category = $this->categoryRepo->get($category_id);
        $inputs['id'] = $category_id;
        if(isset($request->avatar)){
            // Upload image file
            $inputs['avatar']   = FileUploadHelper::uploadUserAvatar($inputs);
            
            if($category->avatar) // Delete previous image
                FileUploadHelper::deleteAvatar($category->avatar);
        }

        if(isset($request->banner)) {
            $inputs['banner']   = FileUploadHelper::uploadCategoryBanner($inputs);
            if($category->banner) // Delete previous image
                FileUploadHelper::deleteCategoryBanner($category->banner);
        }

        if(isset($request->icon)){
            $svg_file = file_get_contents($request->icon);

            $find_string   = '<svg';
            $position = strpos($svg_file, $find_string);

            $svg_html_tag = substr($svg_file, $position);
            $inputs['icon'] = $svg_html_tag;
        }
                
        $this->categoryRepo->edit($inputs, false);

        $message = 'Category updated successfully.';
        return redirect()->back()->withSuccess($message);
    }

    public function destroy($id)
    {
        return $this->categoryRepo->delete($id);
    }

    public function uploadCkeditorImage(Request $request)
    {
        $CKEditor = $request->CKEditor;
        $funcNum = $request->CKEditorFuncNum;
        $message = $url = '';
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            if ($file->isValid()) {
                $image_name = FileUploadHelper::uploadCategoryContentImage($file);
                $url        = Storage::disk('s3')->url('categories/'.$image_name);
            } else {
                $message = 'An error occured while uploading the file.';
            }
        } else {
            $message = 'No file uploaded.';
        }
        return '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>';
    }


}
