<?php

namespace App\Http\Controllers\Coach;

use Illuminate\Http\Request;
use App\Http\Requests\CoachRequest;
use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;
use App\Repositories\CountryRepository;
use App\Repositories\MangoPayRepository;
use App\Mail\deleteCoachProfileMail;
use Auth;
use Talk;
use Mail;

class UserController extends Controller
{

    function __construct(MangoPayRepository $mangoPayRepo, CompanyRepository $companyRepo, CountryRepository $countryRepo)
    {
        $this->companyRepo     = $companyRepo;
        $this->countryRepo     = $countryRepo;
        $this->mangoPayRepo    = $mangoPayRepo;
    }

    public function index()
    {
        $page_title      = 'My Profile';
        $coach           = Auth::guard('coach')->user();
                
        $coach_cat       = $coach->categories->pluck('id');
        $coach_lang      = collect(explode(', ', $coach->language));
        $coach_priority  = collect(explode(', ', $coach->priorities));
        $companies       =  $this->companyRepo->getCompanies();
        $languages       = ['Deutsch', 'Englisch', 'Französisch'];
        $priorities      = ['Liebe', 'Beziehungen', 'Vergebung', 'Familienstreit'];
        $countries       = $this->countryRepo->getForDropdown();
        return view('coach.profile', compact('page_title', 'companies', 'languages', 'priorities', 'coach', 'coach_cat', 'coach_lang', 'coach_priority', 'countries'));
    }

    public function dashboard()
    {
        $page_title = 'Dashboard';
        return view('coach.dashboard',compact('page_title'));
    }

    public function blog()
    {
        $page_title = 'Blogs';
        return view('coach.blogs', compact('page_title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(CoachRequest $request)
    {
        $inputs = $request->all(); 

        
        $user   = Auth::guard('coach')->user();

        // Shareholders
        if(isset($inputs['sh_first_name']) && isset($inputs['sh_first_name'][0])) {
            $shareholder_arr = array();
            foreach ($inputs['sh_first_name'] as $key => $value) {
                $shareholder_arr[] = array(
                                    'first_name'  => $inputs['sh_first_name'][$key],
                                    'last_name'   => $inputs['sh_last_name'][$key],
                                    'street'      => $inputs['sh_street'][$key],
                                    'post_code'   => $inputs['sh_post_code'][$key],
                                    'place'       => $inputs['sh_place'][$key],
                                    'country'     => $inputs['sh_country'][$key],
                                    'nationality' => $inputs['sh_nationality'][$key],
                                    'birth_date'  => $inputs['sh_birth_date'][$key],
                                    'birth_place' => $inputs['sh_birth_place'][$key],
                                    'birth_land'  => $inputs['sh_birth_country'][$key],
                                );
            }
            // Delete all existing shareholders
            $user->shareholders()->delete();
            // Save new shareholders
            $user->shareholders()->createMany($shareholder_arr);
        }

        if(isset($inputs['old_password']))
            if(!\Hash::check($inputs['old_password'], $user->password))
                return response()->json(['success'=> 'false', 'message'=> 'Invalid current password.'], 200);
        
        // Encrypt Password
        if(isset($inputs['password']))
            $inputs['password'] = bcrypt($inputs['password']);
        else
            unset($inputs['password']);

        // Set work address
        if(isset($inputs['street']) && !isset($inputs['different_work'])) {
            $inputs['different_work'] = 0;
            $inputs['work_street']    = $inputs['street'];
            //$inputs['work_house_no']  = $inputs['house_no'];
            $inputs['work_post_code'] = $inputs['post_code'];
            $inputs['work_place']     = $inputs['place'];
        }

        if(isset($inputs['language']) && is_array($inputs['language']))
            $inputs['language'] = implode(', ', $inputs['language']);

        if(isset($inputs['priorities']) && is_array($inputs['priorities']))
            $inputs['priorities'] = implode(', ', $inputs['priorities']);

        // Set coaching method (for Coach)
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

        // Check has bank details updated
        $bank_changed = false;
        if(isset($inputs['iban'])) {
        //if((isset($inputs['iban']) && $user->iban!=$inputs['iban']) || (isset($inputs['bic']) && $user->bic!=$inputs['bic'])) {
            $bank_changed = true;
        }

        // Update to DB
        $user->fill($inputs);
        $user->save();

        // Updating Categories to Coach
        if(isset($request->categories) && count($request->categories)>0)
            $user->categories()->sync($request->categories);

        // Updating Companies
        if(isset($request->company_id) && count($request->company_id)>0) {
            $company_arr = array();
            foreach ($inputs['company_id'] as $key => $value) {
                $year  = $inputs['join_year'][$key];
                $month = sprintf("%02d", $inputs['join_month'][$key]);
                $company_arr[] = array(
                    'company_id'       => $inputs['company_id'][$key],
                    'company_name'     => $inputs['company_name'][$key],
                    'joining_date'     => $year.'-'.$month.'-01',
                    'designation'      => $inputs['designation'][$key],
                    'document'         => $inputs['company_doc'][$key]
                );
            }
            // Remove all existing companies first
            $user->companies()->detach();

            // Add companies
            $user->companies()->attach($company_arr);
        }

        // Save Coach's bank details on MangoPay
        if($bank_changed) {
            $bank_result = $this->mangoPayRepo->createBankAccount($user);
            if(!$bank_result['status']) {
                $user->iban = null;
                $user->bic = null;
                $user->saveQuietly();
                $error_msg = $bank_result['message'];
                /*$errors = json_decode($bank_result['errors']['Errors']);
                echo "<pre>";
                print_r($errors);
                echo "</pre>";
                exit;
                        
                if(isset($bank_result['errors'])) {
                    $error_msg = 'Following error occured!';
                    foreach ($bank_result['errors'] as $key => $value) {
                        $error_msg .= '<li>'.$key.' => '.$value.'</li>';
                    }
                }*/
                return response()->json(['success'=> 'false', 'message'=> 'Bank validation failed! '.$error_msg], 200);
            }
            else
                $user = $bank_result['user'];
        }

        // Check for complete profile status changes
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

        return response()->json(['success'=> 'true', 'message'=> 'User updated successfully.'], 200);
    }

    public function deleteProfile(Request $request)
    {
        $user   = Auth::guard('coach')->user();
        if(isset($request->existing_password)) {
            if(!\Hash::check($request->existing_password, $user->password)){
                return response()->json(['success'=> 'false', 'message'=> 'Invalid current password.'], 200);
            }
            else {
                Mail::to($user->email)->send(
                    new deleteCoachProfileMail($user)
                );
                $user->delete();
                Auth::guard('coach')->logout();
                return response()->json(['success'=> 'true', 'message'=> 'Profile deleted'], 200);
            }
        } else {
            return response()->json(['success'=> 'false', 'message'=> 'Please enter your password to delete profile.'], 200);
        }
    }

}