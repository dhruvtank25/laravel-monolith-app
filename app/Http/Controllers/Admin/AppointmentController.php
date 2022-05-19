<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\CoachAvailabilityRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\TransactionRepository;
use App\Helpers\AvailabilityHelper;
use DataTables;
use Auth;

class AppointmentController extends Controller
{

    function __construct(AppointmentRepository $appointmentRepo, UserRepository $userRepo, CoachAvailabilityRepository $availabilityRepo, CategoryRepository $categoryRepo, TransactionRepository $transactionRepo)
    {
        $this->userRepo         = $userRepo;
        $this->appointmentRepo  = $appointmentRepo;
        $this->availabilityRepo = $availabilityRepo;
        $this->categoryRepo     = $categoryRepo;
        $this->transactionRepo  = $transactionRepo;
    }

    public function index(Request $request)
    {
        $page_title    = 'Appointments';
        $users         =  $this->userRepo->getUsers('user');
        $coaches       =  $this->userRepo->getUsers('coach');
        $filter_status =  isset($request->status)?$request->status:'';
        $categories    =  $this->categoryRepo->get_search_custom([]);
      return view('appointments.index',compact('page_title', 'users', 'coaches', 'filter_status', 'categories'));
    }

    public function getDatatables(Request $request)
    {
      $inputs       = $request->all();
      $appointments = $this->appointmentRepo->getDataTable($inputs);
      return DataTables::of($appointments)
                      ->addColumn('start_time', function ($appointment) {
                        return $appointment->start->format('H:i A');
                      })
                      ->editColumn('start', function ($appointment) {
                          return $appointment->start->format('Y-m-d');
                      })
                      ->editColumn('end', function ($appointment) {
                          return $appointment->end->format('H:i A');
                      })
                      ->filterColumn('user.first_name', function($query, $keyword) {
                          $query->whereHas("user", function($query) use ($keyword) {
                            $query
                              ->whereRaw('CONCAT(users.first_name," ",users.last_name) like ?', ["%{$keyword}%"]);
                          });
                      })
                      ->filterColumn('coach.first_name', function($query, $keyword) {
                          $query->whereHas("coach", function($query) use ($keyword) {
                            $query
                              ->whereRaw('CONCAT(users.first_name," ",users.last_name) like ?', ["%{$keyword}%"]);
                          });
                      })
                      ->toJson();
    }

    public function getTransactionDataTables($appointment_id)
    {
        $transactions = $this->transactionRepo->getAppointmentDataTable($appointment_id);
        return DataTables::of($transactions)->toJson();
    }

    public function store(Request $request)
    {
        $inputs  = $request->all();

        $user_id = $this->appointmentRepo->add($inputs, false);

        $message = 'Session created successfully.';
        if ($request->expectsJson()) {
            return response()->json(['success'=>'true','message'=>$message], 200);
        }
        return redirect()->back()->withSuccess($message);
    }

    public function show(Request $request, $id)
    {
        $page_title  = 'Appointment Details';
        $appointment = $this->appointmentRepo->get($id);
        if ($request->expectsJson()) {
            $html    = view('appointments.show_html', compact('appointment'))->render();
            return response()->json(['success'=>'true', 'appointment' => $appointment, 'html'=>$html], 200);
        }
        return view('appointments.show',compact('page_title','appointment'));
    }

    public function addNote(Request $request, $id)
    {
        $note  = $request->note;
        $this->appointmentRepo->addNote($note, $id);
        
        $message = 'Note added successfully';
        if ($request->expectsJson()) {
            return response()->json(['success'=>'true','message'=>$message], 200);
        }
        return redirect()->back()->withSuccess($message);
    }

    public function schedule($user_id)
    {
        $page_title  = 'Coach Schedule';
        $users       =  $this->userRepo->getUsers('user');
        $coach       =  $this->userRepo->get($user_id);
        $categories  =  $this->categoryRepo->get_search_custom([]);

        $resources[] = ['id' => $coach->id, 'title' => $coach->first_name.' '.$coach->last_name];
        $resources   = json_encode($resources);
        return view('coaches.schedule',compact('page_title', 'users', 'resources', 'coach', 'categories'));
    }

    public function scheduler()
    {
        $page_title = 'Coach Scheduler';
        $users      =  $this->userRepo->getUsers('user');
        //$coaches  =  $this->userRepo->getUsers('coach');
        $coaches    =  $this->userRepo->getAvailabilityCoaches();
        $categories =  $this->categoryRepo->get_search_custom([]);
        return view('coaches.schedule',compact('page_title', 'users', 'categories', 'coaches'));
    }

    public function fullCalendarData(Request $request)
    {
        $inputs = $request->all();
        $events = array();
        $distinct_coach = array();

        $filter_coach_id = isset($inputs['coach_id'])?$inputs['coach_id']:'';
        $filter_day      = isset($inputs['day'])?$inputs['day']:'';
        $filter_category = isset($inputs['category_id'])?$inputs['category_id']:'';

        /** SET UNAVAILABILITIES **/
        $availabilities = $this->availabilityRepo->getAvailability($filter_coach_id, $filter_day, $filter_category);
            
        // Set Pointers for previous element reference
        $start_time     = '00:00:00';
        $end_time       = '23:59:59';
        $prev_day       = $prev_dow = $prev_coach_id = null;
        $i              = 0;

        if(count($availabilities)>0){
          foreach ($availabilities as $available_obj) {
              $coach      = $available_obj->coach;
              $coach_name = $coach->first_name.' '.$coach->last_name;
              $dow        = AvailabilityHelper::getDow($available_obj->position_no);

              // Add to distinct coach array
              if(count($distinct_coach)==0)
                $distinct_coach[] = $coach->id;

              // Check if coach has changed
              if($prev_coach_id!==null && $prev_coach_id!=$coach->id){
                // End Unavailabilty for last day for last Coach
                $last_availability = $availabilities[$i-1];
                $events = AvailabilityHelper::setUnavailableEnd($events, $last_availability);

                // Check if last coach has not set availability till last day of week (Saturday)
                if($prev_dow<6){
                  // Set Unavailable for missing available days for last coach
                  $last_coach   = $last_availability->coach;
                  $events = AvailabilityHelper::setUnavailableDay($events, $prev_dow+1, 7, $last_coach);
                }
                
                // Reset Pointers
                $start_time     = '00:00:00';
                $end_time       = '23:59:59';
                $prev_day = $prev_dow = null;

                // Add to distinct coach array
                $distinct_coach[] = $coach->id;
              }

              if($prev_dow===null && $dow>0){
                // Set Unavailable for previous missing available days for current coach
                $events = AvailabilityHelper::setUnavailableDay($events, 0, $dow, $coach);
              }

              // Check if new day started for current coach
              if($prev_day!==null && $prev_day!=$available_obj->day){
                // End Unavailabilty for last day for current coach
                $last_availability = $availabilities[$i-1];
                $events = AvailabilityHelper::setUnavailableEnd($events, $last_availability);

                if($prev_dow+1!=$dow){
                  // Set Unavailable for previous missing available days for current coach
                  $events = AvailabilityHelper::setUnavailableDay($events, $prev_dow+1, $dow, $coach);
                }

                // Reset Pointer Time
                $start_time     = '00:00:00';
                $end_time       = '23:59:59';
              }

              // Set unavailability in working day
              $this_start        = $start_time;
              $this_end          = $end_time;
              if($start_time>=$available_obj->time_from){
                $this_start      = $available_obj->end_time;
                $this_end        = $availabilities[$i+1]->start_time;
                // Update start pointer
                $start_time      = $this_start;
              }else{
                $this_end        = $available_obj->time_from;
                // Update start pointer
                $start_time      = $available_obj->time_to;
              }

              // Set Unavailability for Working Day
              $events[] = array(
                             'start'         => $this_start,
                             'end'           => $this_end,
                             'rendering'     => 'background',
                             'color'         => '#cbced1',
                             'title'         => 'Unavailable',
                             'dow'           => [$dow], // Day of week
                             'resourceId'    => $coach->id,
                             'resourceName'  => $coach_name,
                          );
              
              // Update Previous Pointer Details
              $prev_coach_id = $coach->id;
              $prev_day      = $available_obj->day;
              $prev_dow      = $dow;
              $i++;
          }
          
          // End Unavailabilty for last day for last Coach
          $last_availability = $availabilities[count($availabilities)-1];
          $events = AvailabilityHelper::setUnavailableEnd($events, $last_availability);

          // Check if last coach has not set availability till last day of week (Saturday)
          if($prev_dow===null || $prev_dow<6){
            // Set Unavailable for missing available days for last coach
            $last_coach = $last_availability->coach;
            $prev_dow   = $prev_dow===null?0:$prev_dow;
            $events = AvailabilityHelper::setUnavailableDay($events, $prev_dow+1, 7, $last_coach);
          }
        }else{
          if($filter_coach_id!='' && $filter_day=='' && $filter_category==''){
            // Set complete Week unavailability
            $coach  = $this->userRepo->get($filter_coach_id);
            $events = AvailabilityHelper::setUnavailableDay($events, 0, 7, $coach);
            $distinct_coach[] = $filter_coach_id;
          }else {
            return  json_encode($events);
          }
        }

        $start     = str_replace('T', ' ', $inputs['start']);
        $end       = str_replace('T', ' ', $inputs['end']);
        /** Not available for selected date */
        $unavailabilities = $this->availabilityRepo->getUnavailability($distinct_coach, $start, $end);
        foreach ($unavailabilities as $unavailable) {
            $coach      = $unavailable->coach;
            $coach_name = $coach->first_name.' '.$coach->last_name;
            $events[]   = array(
                             'start'         => $unavailable->unavailable_from->format('Y-m-d H:i:s'),
                             'end'           => $unavailable->unavailable_to->format('Y-m-d H:i:s'),
                             'rendering'     => 'background',
                             'color'         => '#cbced1',
                             'title'         => 'Unavailable',
                             'resourceId'    => $coach->id,
                             'resourceName'  => $coach_name,
                         );
        }
        /** SET UNAVAILABILITIES END **/

        // Set Bookings
        $input_arr = array();
        $input_arr['coach_id'] = $distinct_coach;
        $input_arr['start']    = $start;
        $input_arr['end']      = $end;
        $appointments = $this->appointmentRepo->getAppointments($input_arr);
        foreach ($appointments as $appointment) {
            $editable = false;
            if($appointment->status=='scheduled'){
                $event_color = '#72b1eb';
                $editable    = true;
            }
            else if($appointment->status=='completed')
                $event_color = '#46be8a';
            else if($appointment->status=='cancelled')
                $event_color = '#f44336';
            else
                continue;
            $title      = $appointment->user->first_name.' '.$appointment->user->last_name.' ('.$appointment->mode.') <br> address';
            $coach_name = $appointment->coach->first_name.' '.$appointment->coach->last_name;
            $new_event  = array(
                           'id'            => $appointment->id,
                           'type'          => $appointment->status,
                           'start'         => $appointment->start,
                           'end'           => $appointment->end,
                           'color'         => $event_color,
                           'title'         => $title,
                           'resourceId'    => $appointment->coach->id,
                           'resourceName'  => $coach_name,
                           'editable'      => $editable
                        );
            $events[]  = $new_event;
        }
        return  json_encode($events);
    }

}