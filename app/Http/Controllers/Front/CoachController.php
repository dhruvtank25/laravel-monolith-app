<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\AppointmentRequestRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CoachSayRepository;
use App\Repositories\FaqRepository;
use App\Http\Requests\CoachRequest;
use App\Helpers\FileUploadHelper;
use App\Mail\deleteUserProfileMail;
use App\Mail\appmntAlternativeRequest;
use App\Mail\appointmentInquiryForCoach;
use App\Models\Role;
use App\Models\AppointmentRequest;
use Illuminate\Auth\Events\Registered;
use App\Helpers\CommonHelper;
use Carbon\Carbon;
use Storage;
use Auth;
use Session;
use Mail;

class CoachController extends Controller
{

    function __construct(UserRepository $userRepo, CategoryRepository $categoryRepo, AppointmentRepository $appointmentRepo, AppointmentRequestRepository $appRequestRepo, CompanyRepository $companyRepo, CountryRepository $countryRepo, FaqRepository $faqRepo, CoachSayRepository $coachSayRepo)
    {
        $this->userRepo          = $userRepo;
        $this->categoryRepo      = $categoryRepo;
        $this->appointmentRepo   = $appointmentRepo;
        $this->appRequestRepo    = $appRequestRepo;
        $this->companyRepo       = $companyRepo;
        $this->countryRepo       = $countryRepo;
        $this->faqRepo           = $faqRepo;
        $this->coachSayRepo      = $coachSayRepo;
    }

    public function index()
    {
        $page_title  = 'Become a coach';
        $coach_faqs  = $this->faqRepo->getFaqs('coach');
        $coachsays   = $this->coachSayRepo->getAll();
        return view('become_a_coach', compact('page_title', 'coach_faqs', 'coachsays'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title = 'Coach Registration';
        $coach      = Auth::guard('coach')->check()?Auth::guard('coach')->user():'';
                
        if($coach) {
            $coach_cat       = $coach->categories->pluck('id');
            $coach_lang      = collect(explode(', ', $coach->language));
            $coach_priority  = collect(explode(', ', $coach->priorities));
        } else {
            $coach_cat = $coach_lang = $coach_priority = collect(array());
        }
        $companies  =  $this->companyRepo->getCompanies();
        $countries  =  $this->countryRepo->getForDropdown();
        $languages       = ['Deutsch', 'Englisch', 'Französisch'];
        $priorities      = ['Liebe', 'Beziehungen', 'Vergebung', 'Familienstreit', 'Noch ein Thema', 'Weiteres Thema'];
        return view('coach_register', compact('page_title', 'companies', 'countries', 'languages', 'priorities', 'coach', 'coach_cat', 'coach_lang', 'coach_priority'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CoachRequest $request)
    {
        $inputs = $request->all();

        $role_obj           = Role::where('name', 'coach')->first();
        $inputs['role_id']  = $role_obj->id;
        $inputs['status']   = 'incomplete';
        
        // Encrypt Password
        $inputs['password'] = bcrypt($inputs['password']);

        // Save to DB
        $user_id = $this->userRepo->add($inputs, false);

        $user = $this->userRepo->get($user_id);
        
        // Trigger Registered event, this by default will send verification email
        event(new Registered($user));

        // Login User
        Auth::guard('coach')->login($user);

        $message = 'User added successfully.';
        if ($request->expectsJson()) {
            return response()->json(['success'=>'true', 'id' => $user_id, 'message'=> $message], 200);
        }
        return redirect()->back()->withSuccess($message);
    }

    /**
     * Store coach documents on local storage/S3 bucket
     *
     * @return Response
     */
    public function uploadDocuments(Request $request)
    {
        $validation_arr = array('type' => 'required');
        $inputs = $request->all();
                
        $type   = $inputs['type'];

        $result = CommonHelper::checkUserType();
        if($result['type']!='coach' && $result['type']!='admin')
            return response()->json([
                                    'success'   => 'false', 
                                    'message'   => 'Unauthorized request'
                                ], 401);

        /*if(!Auth::guard('coach')->check())
            return response()->json([
                                    'success'   => 'false', 
                                    'message'   => 'Unauthorized request'
                                ], 401);
        $user = Auth::guard('coach')->user();*/
        $user = $result['data'];
        $inputs['first_name'] = $user->first_name;
        $inputs['last_name']  = $user->last_name;
        // 1024 = 1MB
        // 8192 = 8MB
        switch ($type) {
            case 'avatar':
                $file_name  = FileUploadHelper::uploadUserAvatar($inputs);
                $validation_arr['file'] = 'required|mimes:jpg,jpeg,png|max:8192';
                break;

            case 'banner':
                $file_name  = FileUploadHelper::uploadUserBanner($inputs);
                $validation_arr['file'] = 'required|mimes:jpg,jpeg,png|max:8192';
                break;

            case 'video':
                $file_name  = FileUploadHelper::uploadDoc($request->file, $type);
                $validation_arr['file'] = 'required|mimes:mp4,mov|max:8192';
                break;

            case 'company_doc':
            case 'commercial_doc':
            case 'ustid_doc':
            case 'id_doc':
            case 'attachment':
                $file_name  = FileUploadHelper::uploadDoc($request->file, $type);
                $validation_arr['file'] = 'required|mimes:pdf,jpg,jpeg,png|max:8192';
                break;

            default:
                return response()->json([
                                        'success'   => 'false', 
                                        'message'   => 'Unknown document type'
                                    ], 401);
        }

        $validatedData = $request->validate($validation_arr);


        $file_url   = FileUploadHelper::getDocPath($file_name, $type);
        /*$file_name = 'DFDb.jpg';
        $file_url  = 'https://customer-himmlisch-beraten.s3.eu-central-1.amazonaws.com/users/avatar/SDFFb-DFD_1562326511.png';*/
        return response()->json([
                                'success'   => 'true', 
                                'file_name' => $file_name,
                                'file_url'  => $file_url, 
                                'message'   => 'File uploaded successfully'
                            ], 200);
    }

    /**
     * Delete coach documents from local storage/S3 bucket
     *
     * @return Response
     */
    public function deleteDocuments(Request $request)
    {
        $inputs     = $request->all();
        $file_name  = $inputs['file_name'];
        $type       = $inputs['type'];
        switch ($type) {
            case 'avatar':
            case 'banner':
            case 'video':
            case 'company_doc':
            case 'ustid_doc':
            case 'id_doc':
                FileUploadHelper::deleteDoc($file_name, $type);
                break;
                
            default:
                return response()->json([
                                        'success'   => 'false', 
                                        'message'   => 'Unknown document type'
                                    ], 401);
        }
        return response()->json([
                                'success'   => 'true', 
                                'message'   => 'File deleted successfully'
                            ], 200);
    }

    /**
     * Thank you page after registration completed
     * 
     * @return Response
     */
    public function complete()
    {
        $page_title = 'Coach registration complete';
        return view('thankyoucoach', compact('page_title'));
    }

    /**
     * Search and filter coaches
     * 
     * @return Response
     */
    public function search($url_slug='alle-berater', Request $request)
    {
        $inputs = $request->all();
        // echo"<pre>";print_r($inputs);echo"</pre>";exit();
        $page_title = 'Coach search';
        $search_title = '';
        $is_offline = 0;
        $is_online = 0;
        $price_range = 0;
        $max_price = 100;
        $community = '';
        $place = '';
        $distance = 10;
        $coordinates = array();

        //set coordinates
        $coordinates['latitude'] = isset($inputs['latitude'])?$inputs['latitude']:'';
        $coordinates['longitude'] = isset($inputs['longitude'])?$inputs['longitude']:'';

        unset($start_slider_value);
        unset($end_slider_value);
        unset($availability_start_time);
        unset($availability_end_time);
        unset($availability_date);

        $availability_date  = isset($inputs['availability_date'])?$inputs['availability_date']:'';
        
        $availability_start_time = isset($inputs['availability_start_time'])?$inputs['availability_start_time']:'04:00';
        $availability_end_time = isset($inputs['availability_end_time'])?$inputs['availability_end_time']:'23:00';


        //get search input
        $search_input = isset($inputs['search_consult'])?$inputs['search_consult']:'';
        $search_title = $search_input;

        //set coaching method 
        $is_offline = isset($inputs['offline_check'])?$inputs['offline_check']:'';
        $is_online = isset($inputs['online_check'])?$inputs['online_check']:'';

        if($is_offline == 0 && $is_online == 0){
            $coaching_method = array('offline','online','both');
        }elseif($is_offline == 1 && $is_online == 0){
            $coaching_method = array('offline','both');
        }elseif($is_offline == 1 && $is_online == 1){
            $coaching_method = array('online','offline','both');
        }else{
            $coaching_method = array('online','both');
        }
        
        // Category Filter
        $category_detail = null;
        if($url_slug!='alle-berater') {
            $category_detail = $this->categoryRepo->getBySlug($url_slug);
            if(!$category_detail)
                abort(404);
            $inputs['category_id'] = $category_detail->id;
            $page_request = 'category';
        } else {
            $page_request = 'all_coach';
        }
        /*$category_id = isset($inputs['category_id'])?$inputs['category_id']:'';
        $page_request = null;
        if($category_id != '')
        {
            $category_detail = $this->categoryRepo->get($category_id);
            $page_request = 'category';
        }else{
            $page_request = 'all_coach';
        }*/

        //get coaches   
        $coaches = $this->userRepo->getCoaches($coaching_method, $inputs);

        //get max price for slider
        $max_price = $this->userRepo->getMaxPrice();
        
        //setting up price range
        if(isset($inputs['price_range'])){
            $price_range = explode(',',$inputs['price_range']);
            $start_slider_value = $price_range[0];
            $end_slider_value = $price_range[1];
        } else {
            $start_slider_value = null;
            $end_slider_value = null;
        }

        //set place
        $place =  isset($inputs['place'])?$inputs['place']:'';

        //set distance
        $distance = isset($inputs['distance'])?$inputs['distance']:'';

        //set community
        $community = isset($inputs['coach_community'])?$inputs['coach_community']:'';

        return view('search_result', compact('page_request', 'page_title', 'category_detail', 'coaches', 'place', 'distance', 'search_title' , 'max_price' , 'start_slider_value' , 'end_slider_value' , 'community', 'availability_start_time', 'availability_end_time', 'coordinates', 'availability_date'));
    }

    /** For SEO purpose */
    public function coachDetail($name, $id, Request $request)
    {
        return $this->show($id, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id, Request $request)
    {
        $page_title         = 'Coach detail';

        // Get coach details
        $coach              = $this->userRepo->get($id);
                
        if ($request->expectsJson() && $request->result=='json')
            return response()->json(['success'=>'true', 'coach' => $coach], 200);

        $coach_companies    = $coach->companies;
        $reviews            = $coach->reviews;
        
        $category_id_arr    = $coach->categories->pluck('id')->toArray();
        return view('coach_detail', compact('page_title', 'coach', 'coach_companies', 'category_id_arr', 'reviews'));
    }

    /**
     * Book an appointment with coach
     * 
     * @return Response
     */
    public function bookSlot(Request $request)
    {
        // Check if came back after registration/login
        $appointment = session('appointment_data', null);
        
        $inputs = array();
        if(isset($request->request_id)) {
            $app_request = $this->appRequestRepo->getAccepted($request->request_id, true);
            if(!$app_request|| !Auth::guard('user')->check() || Auth::guard('user')->id()!=$app_request->user_id)
                abort(401);
                    
            $slot        = $app_request->slots[0];
            $inputs = array(
                        'coach_id'    => $app_request->coach_id,
                        'category_id' => $app_request->category_id,
                        'start'       => $slot->start,
                        'end'         => $slot->end,
                        'mode'        => $slot->mode,
                        'notes'       => $app_request->mode,
                    );
        }       
        else if ($appointment!=null && $request->isMethod('get'))
            $inputs = json_decode($appointment, true);
        else
            $inputs = $request->all();

        if(count($inputs)==0)
            abort(404);

        $coach           =  $this->userRepo->get($inputs['coach_id']);
        // Get session cost
        $carbon_start    = Carbon::createFromFormat('Y-m-d H:i:s', $inputs['start']);
        $carbon_end      = Carbon::createFromFormat('Y-m-d H:i:s', $inputs['end']);
        
        $duration_min    = $carbon_end->diffInMinutes($carbon_start);
        $duration_hr     = $duration_min/60;
        $session_cost    = $duration_hr*$coach->price_per_hour;

        // Generate Appointment data Array
        $appointment_arr =  array(
                                'coach_id'       => $inputs['coach_id'],
                                'category_id'    => $inputs['category_id'],
                                'start'          => $inputs['start'],
                                'end'            => $inputs['end'],
                                'mode'           => $inputs['mode'],
                                'notes'          => $inputs['notes'],
                                'status'         => 'payment pending',
                                'price_per_hour' => $coach->price_per_hour,
                                'amount'         => $session_cost
                            );

        // Check if slot got booking in between user booking flow
        $total_appnmnts = $this->appointmentRepo->checkTotalSchedule($inputs['coach_id'], $inputs['start'], $inputs['end']);
        if($total_appnmnts>0) {
            $page_title = 'Slot already booked';
            if ($request->expectsJson())
                return response()->json(['success' => 'false', 'message' => 'Slot has booking'] ,200);
            else
                return view('booking_exist', compact('page_title'));
        }

        // Save appointment if user is logged in
        $user = null;
        if(Auth::guard('user')->check())
            $user = Auth::guard('user')->user();
        else if(Auth::guard('guest_user')->check())
            $user = Auth::guard('guest_user')->user();
        if($user!=null) {
            $appointment_arr['user_id'] = $user->id;
            $appointment_id  = $this->appointmentRepo->add($appointment_arr, false);

            // Save appointment id
            if(isset($app_request)) {
                $app_request->appointment_id = $appointment_id;
                $app_request->save();
            }

            Session::forget('appointment_data');
            if ($request->expectsJson()) {
                $summary_url = route('booking-summary', ['id' => $appointment_id]);
                return response()->json([
                                        'success' => 'true', 
                                        'appointment_id' => $appointment_id, 
                                        'user'=> $user,
                                        'summary_url' => $summary_url,
                                        'message' => 'Slot blocked,  complete payment to book appointment'
                                    ] ,200);
            } else {
                return redirect()->route('booking-summary', ['id' => $appointment_id]);
            }
        } else {
            $appointment_arr['coach']    = $coach;
            $appointment_arr['duration'] = $carbon_end->diffInMinutes($carbon_start);
            Session::put('appointment_data', json_encode($appointment_arr));
            return response()->json(['success' => 'false', 'user'=> null, 'message' => 'Unauthorized request'] ,200);
        }
    }

    public function requestAppointment(Request $request)
    {
        $inputs      = $request->all();
        $slots      = json_decode($inputs['slots']);
        if(Auth::guard('user')->check()) {
            $user_id = Auth::guard('user')->id();
            $requested_by   = 'user';
            
            // Send Appointment Proposal to coach
            if(isset($inputs['coach_id'])){
                $id = $inputs['coach_id'];
                // Get coach details
                $coach  = $this->userRepo->get($id);
                
                Mail::to($coach->email)->send(new appointmentInquiryForCoach($slots,$coach));
            }
        }
        else if (Auth::guard('coach')->check() && isset($request->request_id)) {
            $user_id = Auth::guard('coach')->id();
            $requested_by   = 'coach';
            $app_request_id = $request->request_id;
        }
        else
            return response()->json(['success' => 'false', 'message' => 'Unauthorized Request'] ,200);
        if(!isset($app_request_id)) {
            if($requested_by=='coach')
                return response()->json(['success' => 'false', 'message' => 'Unauthorized Request'] ,200);
            $request_arr = array(
                                'user_id' => $user_id, 
                                'coach_id' => $inputs['coach_id'], 
                                'category_id' => $inputs['category_id'], 
                                'notes' => $inputs['notes'], 
                                'price_per_hour' => 1, 
                                'status' => 'user_requested'
                            );
            $app_request_id = $this->appRequestRepo->add($request_arr, false);
        } else {
            
            $appRequest = $this->appRequestRepo->get($app_request_id);

            Mail::to($appRequest->user->email)->send(new appmntAlternativeRequest($slots,$appRequest));

            // Update Request status
            $this->appRequestRepo->updateRequest($app_request_id, 'coach', $user_id, 'coach_suggested');
        }

        // echo "<pre>";print_r($slots);exit();
        
        $slot_arr   = array();
        foreach ($slots as $slot) {
            $slot_arr[] = array(
                            'appointment_request_id' => $app_request_id,
                            'start'                  => $slot->start,
                            'end'                    => $slot->end,
                            'mode'                   => $slot->mode,
                            //'mode'                 => 'offline',
                            'location'               => '',
                            'requested_by'           => $requested_by,
                            'created_at'             => date('Y-m-d H:i:s'),
                            'updated_at'             => date('Y-m-d H:i:s'),
                        );
        }
        $this->appRequestRepo->addSlots($slot_arr);
        if($requested_by=='coach')
            return response()->json(['success' => 'true', 'message' => 'New suggestion sent to the user'] ,200);
        else
            return response()->json(['success' => 'true', 'message' => 'Appointment request send to the coach'] ,200);
    }

    /**
     * get all coaches location 
     * 
     * @return Response
     */
    public function getALLCoachesLocations()
    {
        $getAllCoachLocations = $this->userRepo->getCoachLocations();
        return response()->json(['success' => 'true', 'getAllCoachLocations'=> $getAllCoachLocations, 'message' => 'Load Coaches Successfully'] ,200);
    }

}