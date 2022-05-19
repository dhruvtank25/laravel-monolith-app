<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\CoachCompanyRepository;
use App\Repositories\CountryRepository;
use App\Repositories\TransactionRepository;
use App\Models\Role;
use App\Models\CoachCompany;
use App\Helpers\FileUploadHelper;
use Illuminate\Auth\Events\Registered;
use DB;
use Hash;
use DataTables;
use Auth;
use App\Mail\ProfileActivated;
use App\Mail\CoachCredsMail;
use Mail;

class UserController extends Controller
{
    function __construct(
                        UserRepository $userRepo, 
                        CategoryRepository $categoryRepo,
                        CoachCompanyRepository $coachCompanyRepo,
                        CompanyRepository $companyRepo, 
                        CountryRepository $countryRepo,
                        TransactionRepository $transactionRepo)
    {
        $this->userRepo         = $userRepo;
        $this->categoryRepo     = $categoryRepo;
        $this->coachCompanyRepo = $coachCompanyRepo;
        $this->companyRepo      = $companyRepo;
        $this->countryRepo      = $countryRepo;
        $this->transactionRepo  = $transactionRepo;
    }

    public function getDataTables(Request $request, $userRole='')
    {
        $inputs = $request->all();
        $users = $this->userRepo->getDataTable($userRole, $inputs);
        return DataTables::of($users)->toJson();
    }

    public function getTransactionDataTables($user_id)
    {
        $transactions = $this->transactionRepo->getUserDataTable($user_id);
        return DataTables::of($transactions)->toJson();
    }

    public function index()
    {
        $type       = 'user';
    	$page_title = 'Users';
    	return view('users.index',compact('page_title', 'type'));
    }

    public function indexGuests()
    {
        $type       = 'guest';
        $page_title = 'Guests';
        return view('users.index', compact('page_title', 'type'));
    }

    public function indexCoaches()
    {
        $page_title = 'Coaches';
        return view('coaches.index',compact('page_title'));
    }

    public function show(Request $request, $id)
    {
        $user       = $this->userRepo->get($id);
                
        $user_role  = $user->roles->name;
        $user_companies = $user->companies;
                
        $user_categories= $user->categories;       
        if ($request->expectsJson()) {
            $html = view('users.show_html', compact('user', 'user_role', 'user_companies', 'user_categories'))->render();
            return response()->json(['success'=>'true', 'user' => $user, 'user_role' => $user_role, 'html'=>$html], 200);
        }
        $page_title = ucfirst($user_role).' Details';
        return view('users.show',compact('page_title','user', 'user_role', 'user_companies', 'user_categories'));
    }

    public function create(Request $request)
    {
        $page_title = 'Add User';
        $countries  =  $this->countryRepo->getForDropdown();
        $role_name = isset($request->type)?$request->type:'user';
        //$role_obj  = $this->userRepo->getRoles($role_name);
        $role_obj  = Role::where('name', $role_name)->get();
        if(count($role_obj)==0)
            abort(404);
        $role_id = $role_obj[0]->id;
        if($role_name=='coach'){
            $page_title  = 'Add Coach';
            $categories  =  $this->categoryRepo->get_search_custom([]);
            $companies  =  $this->companyRepo->get_search_custom([]);
            return view('coaches.create',compact('page_title', 'role_id', 'categories', 'companies', 'countries'));
        }
        return view('users.create',compact('page_title', 'role_id', 'countries'));
    }

    private function setWorkAddress(&$inputs)
    {
        if(!isset($inputs['different_work']) && isset($inputs['street'])) {
            $inputs['different_work']    = 0;
            $inputs['work_street']       = $inputs['street'];
            //$inputs['work_house_no']   = $inputs['house_no'];
            $inputs['work_post_code']    = $inputs['post_code'];
            $inputs['work_place']        = $inputs['place'];
            $inputs['work_latitude']     = $inputs['latitude'];
            $inputs['work_longitude']    = $inputs['longitude'];
            $inputs['work_country']      = $inputs['country'];
            $inputs['work_country_code'] = $inputs['country_code'];
        }
        return $this;
    }

    private function setCoachingMethod(&$inputs)
    {
        if(isset($inputs['offline_coaching']) && isset($inputs['online_coaching']))
            $inputs['coaching_method'] = 'both';
        else if(isset($inputs['offline_coaching']))
            $inputs['coaching_method'] = 'offline';
        else if(isset($inputs['online_coaching']))
            $inputs['coaching_method'] = 'online';
        else 
            $inputs['coaching_method'] = 'both';
        unset($inputs['offline_coaching']);
        unset($inputs['online_coaching']);
        return $this;
    }

    public function store(UserRequest $request)
    {
        $inputs = $request->all();
        // echo"<pre>";print_r($inputs);exit;
        if(isset($request->avatar)){
            // Upload avatar image file
            $inputs['avatar']   = FileUploadHelper::uploadUserAvatar($inputs);
        }

        if(isset($request->banner)){
            // Upload banner image file
            $inputs['banner']   = FileUploadHelper::uploadUserBanner($inputs);
        }

        // Encrypt Password
        $password               = $inputs['password'];
        $inputs['password']     = bcrypt($password);

        // Set work address And Set coaching method (for Coach)
        $this->setWorkAddress($inputs)
            ->setCoachingMethod($inputs);

        // Set status to incomplete for Coach
        if($inputs['role_id']==3)
            $inputs['status'] = 'incomplete';

        // Save to DB
        $user_id = $this->userRepo->add($inputs, false);

        $user    = $this->userRepo->get($user_id);

        if($user->roles->name=='coach') {
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->saveQuietly();

            // Send email with username and password
            Mail::to($user->email)->send(
                new CoachCredsMail($user, $password)
            );
        } else {
            // Trigger Registered event, this by default will send verification email
            event(new Registered($user));
        }
       
        // Sync companies
        if(isset($request->companies)){
            if($request->companies == 'other'){
                $date = $request->doj_year.'-'.$request->doj_month.'-01';
                $coach_companies = array(
                                        'coach_id'      => $user_id,
                                        'company_id'    => $request->companies,
                                        'company_name'  => $request->company_name,
                                        'joining_date'  => date("Y-m-d",strtotime($date)),
                                        'designation'   => $request->designation
                                    );
                $coach_company = $this->coachCompanyRepo->add($coach_companies, false);
            }else{
                $coach_companies = array(
                    'coach_id'      => $user_id,
                    'company_id'    => $request->companies,
                    'company_name'  => '',
                    'joining_date'  => '',
                    'designation'   => $request->designation
                );
                $coach_company = $this->coachCompanyRepo->add($coach_companies, false);
            }                        
        }

        $message = 'User added successfully.';
        
        // Adding Categories to Coach
        if(isset($request->categories) && count($request->categories)>0) {
            // Sync categories
            $user->categories()->sync($request->categories);
        }
        return redirect()->back()->withSuccess($message);
    }

    public function edit($user_id)
    {
        $page_title = 'User Update';
        $user       = $this->userRepo->get($user_id);
        $countries  =  $this->countryRepo->getForDropdown();
        if($user->roles->name=='coach') {
            $page_title  = 'Coach Update';
            $categories  =  $this->categoryRepo->get_search_custom([]);
            $companies  =  $this->companyRepo->get_search_custom([]);
            $user_cats   =  $user->categories->pluck('id')->toArray();
            return view('coaches.edit',compact('page_title', 'user', 'user_cats', 'categories' , 'companies', 'countries'));
        }
        return view('users.edit',compact('page_title', 'user', 'countries'));
    }

    public function update(UserRequest $request, $user_id, CoachCompany $CoachCompany)
    {
        $inputs = $request->all();
        $user   = $this->userRepo->get($user_id);
        $inputs['id'] = $user_id;
        if(isset($request->avatar)){
            // Upload avatar image file
            $inputs['avatar']   = FileUploadHelper::uploadUserAvatar($inputs);
            
            if($user->avatar) // Delete previous image
                FileUploadHelper::deleteAvatar($user->avatar);
        }

        if(isset($request->banner)){
            // Upload banner image file
            $inputs['banner']   = FileUploadHelper::uploadUserBanner($inputs);
            
            if($user->banner) // Delete previous image
                FileUploadHelper::deleteUserBanner($user->banner);
        }
        
        // Encrypt Password
        if(isset($inputs['password'])) {
            $password               = $inputs['password'];
            $inputs['password']     = bcrypt($password);

            if($user->roles->name=='coach') {
                // Send email with username and password
                Mail::to($user->email)->send(
                    new CoachCredsMail($user, $password)
                );
            }
        }else{
            unset($inputs['password']);
        }

        // Set work address And Set coaching method (for Coach)
        $this->setWorkAddress($inputs)
            ->setCoachingMethod($inputs);

        // Update to DB                
        $this->userRepo->edit($inputs, false);

        // print_r($request->companies);exit;

        // updating companies and coach
        if(isset($request->companies)){
            if($request->companies == 'other'){
                $date = $request->doj_year.'-'.$request->doj_month.'-01';
                DB::table('coach_companies')
                    ->where('coach_id', $user_id)
                    ->update( ['company_id'    => $request->companies, 'company_name'  => $request->company_name,'joining_date'  => date("Y-m-d",strtotime($date)),'designation'   => $request->designation]);
            }else{
                DB::table('coach_companies')
                    ->where('coach_id', $user_id)
                    ->update( ['company_id'    => $request->companies, 'company_name'  => '','joining_date'  => '','designation'   => $request->designation]);
            }                        
        }

        // Updating Categories to Coach
        if(isset($request->categories) && count($request->categories)>0) {
            // Sync categories
            $user->categories()->sync($request->categories);
        }

        // Check for complete profile status changes
        if($user->roles->name=='coach') {
            $is_complete = $user->profile_complete;
            if($is_complete) {
                if($user->status=='incomplete') {
                    $user->status = 'kyc pending';
                    $user->save();
                }
            } else {
                if($user->status!='incomplete' && $user->status!='inactive') {
                    $user->status = 'incomplete';
                    $user->save();
                }
            }
        }

        $message = 'User updated successfully.';
        return redirect()->back()->withSuccess($message);
    }

    public function updateStatus(Request $request, $id)
    {
        $new_status = $request->new_status;
        $this->userRepo->updateStatus($id, $new_status);

        if($new_status=='inactive')
            $message = 'User blocked successfully.';
        else
            $message = 'User status updated successfully.';
        if($new_status=='active') {
            $user       = $this->userRepo->get($id);
            if($user->roles->name=='coach') {
                // Send profile activated email
                Mail::to($user->email)->send(
                    new ProfileActivated($user)
                );
            }
        }
        if ($request->expectsJson()) {
            return response()->json(['success'=>'true', 'message'=>$message], 200);
        }
        return redirect()->back()->withSuccess($message);
    }

    public function destroy($id)
    {
        return $this->userRepo->delete($id);
    }

}
