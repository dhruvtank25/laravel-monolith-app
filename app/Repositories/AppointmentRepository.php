<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Models\ActivityLog;
use App\Repositories\ActivityLogRepository;
use Auth;
use Session;
use DB;
use Carbon\Carbon;
use PDF;
use App\Helpers\FileUploadHelper;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Events\AppointmentCompletedEvent;

class AppointmentRepository extends EloquentRepository
{

    protected $model;

    function __construct(Appointment $appointment)
    {
        $this->model = $appointment;
    }

    public function getCounts($status='')
    {
        return $this->model
                    ->when($status, function($q) use ($status){
                        $q->where('status', $status);
                    })
                    ->count();
    }

    public function getGuestAppointment($user_id)
    {
        return $this->model
                ->where('status', 'scheduled')
                ->where('user_id', $user_id)
                ->with(['coach:id,first_name,last_name,coaching_method', 'categories:id,title'])
                ->get();
    }

    public function getDataTable($inputs=[])
    {
        $status      = isset($inputs['status'])?$inputs['status']:'';
        $user_id     = isset($inputs['user_id'])?$inputs['user_id']:'';
        $coach_id    = isset($inputs['coach_id'])?$inputs['coach_id']:'';
        $category_id = isset($inputs['category_id'])?$inputs['category_id']:'';
        $start       = isset($inputs['start_range'])?$inputs['start_range']:'';
        $end         = isset($inputs['end_range'])?$inputs['end_range']:'';
        return $this->model
                    ->select(['appointments.id','appointments.start','appointments.end','appointments.user_id','appointments.coach_id','appointments.status'])
                    ->when($status, function($query) use ($status){
                        $query->where('status', $status);
                    })
                    ->when($user_id, function($query) use ($user_id){
                        $query->where('user_id', $user_id);
                    })
                    ->when($coach_id, function($query) use ($coach_id){
                        $query->where('coach_id', $coach_id);
                    })
                    ->when($category_id, function($query) use ($category_id){
                        $query->where('category_id', $category_id);
                    })
                    ->when($start && $end, function($query) use ($start, $end) {
                        $query->whereBetween('start', [$start.' 00:00:00', $end.' 23:59:59']);
                    })
                    ->with(['user:id,first_name,last_name','coach:id,first_name,last_name']);
    }

    public function getDataTableCoach($coach_id, $type='')
    {
        return $this->model
                    ->select(['appointments.id','appointments.start','appointments.end','appointments.user_id','appointments.coach_id','appointments.status','appointments.category_id', 'appointments.mode', 'appointments.amount', 'appointments.notes'])
                    ->where('coach_id', $coach_id)
                    ->when($type=='future', function($query) {
                        $query->whereIn('appointments.status', ['cancelled', 'completed', 'scheduled'])
                            ->where('end', '>', Carbon::now('Europe/Berlin'));
                    })
                    ->when($type=='past', function($query) {
                        $query->whereIn('appointments.status', ['cancelled', 'completed', 'scheduled'])
                            ->where('end', '<=', Carbon::now('Europe/Berlin'));
                    })
                    ->with(['user:id,first_name,last_name,is_anonymous,user_name','coach:id,first_name,last_name','categories:id,title'])
                    ->when($type=='past', function($query) {
                        $query->orderBy('start', 'desc');
                    }, function($query) {
                        $query->orderBy('start', 'asc');
                    });
    }

    public function getForPayment($appointment_id, $user_id)
    {
        return  $this->model->where('status', 'payment pending')
                    ->where('user_id', $user_id)
                    ->where('id', $appointment_id)
                    ->first();
    }

    /**
     * Add new Activity Log for appointment.
     *
     * @param  Array  $activity
     * @return Boolean
     */
    public function setActivity($activity)
    {
        $activityRepo = new ActivityLogRepository(new ActivityLog);
        $activityRepo->setActivity($activity);
    }

    public function add($data, $showMessage = true, $message = null)
    {
        $this->model = $this->newInstance();
        if (is_null($message)) {
            $message = 'Data was successfully added.';
        }

        $saved = $this->fillNSave($data);

        if ($saved && $showMessage) {
            $this->setSavedMessage($message);
        }

        if($saved){
            /** Create activity log */
            $activity = array(
                            'activity_type' => 'appointment_created',
                            'activity'      => 'New appointment scheduled',
                            'type'          => 'appointment',
                            'activitable_id'=> $this->model->id
                        );
            $this->setActivity($activity);
            /** Create activity log End */

            return $this->model->id;
        }else{
            return false;
        }
    }

    public function getAppointments($inputs = array())
    {
        
        return $this->model
                    ->when(isset($inputs['coach_id']), function($q) use ($inputs) {
                        if(is_array($inputs['coach_id']))
                            $q->whereIn('coach_id', $inputs['coach_id']);
                        else
                            $q->where('coach_id', $inputs['coach_id']);
                    })
                    ->when(isset($inputs['start']), function($q) use ($inputs) {
                        $q->where('start', '>=', $inputs['start']);
                    })
                    ->when(isset($inputs['end']), function($q) use ($inputs) {
                        $q->where('end', '<=', $inputs['end']);
                    })
                    ->with(['user:id,first_name,last_name','coach:id,first_name,last_name'])
                    ->get();
    }

    public function getAppointmentById($appointment_id)
    {
        return  $this->model
                    ->where('id',$appointment_id)
                    ->with(['user:*','coach:*'])
                    ->get();
    }

    public function checkTotalSchedule($coach_id, $start, $end)
    {
        return  $this->model->where('coach_id', $coach_id)
                    ->whereNotIn('status', ['payment failed', 'cancelled'])
                    ->where(function($q) use ($start, $end) {
                        $q->where(function($query) use ($start, $end) {
                                $query->where('start', '>', $start)
                                    ->where('start', '<', $end);
                            })
                            ->orWhere(function($query) use ($start, $end) {
                                $query->where('end', '>', $start)
                                    ->where('end', '<', $end);
                            })
                            ->orWhere(function($query) use ($start, $end) {
                                $query->where('start', '<=', $start)
                                        ->where('end', '>=', $end);
                            });
                    })
                    /*->where(function($q) use ($start, $end) {
                        $q->whereBetween('start', [$start, $end])
                            ->orWhereBetween('end', [$start, $end]);
                    })*/
                    ->count();
    }

    public function getTotalSchedule($coach_id, $start, $end)
    {
        return  $this->model->where('coach_id', $coach_id)
                    ->whereNotIn('status', ['payment failed', 'cancelled'])
                    ->where(function($q) use ($start, $end) {
                        $q->where(function($query) use ($start, $end) {
                                $query->where('start', '>', $start)
                                    ->where('start', '<', $end);
                            })
                            ->orWhere(function($query) use ($start, $end) {
                                $query->where('end', '>', $start)
                                    ->where('end', '<', $end);
                            })
                            ->orWhere(function($query) use ($start, $end) {
                                $query->where('start', '<=', $start)
                                        ->where('end', '>=', $end);
                            });
                    })
                    ->get();
    }

    public function getBookings($user_id = '',$request_table = '')
    {
        $current_time = Carbon::now('Europe/Berlin');
        $status = ($request_table == 'N')?array('scheduled'):array('cancelled','scheduled','completed');
        return $this->model
                    ->when($user_id, function($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    })
                    ->whereIn('appointments.status', $status)
                    ->when($request_table == 'N', function($q) use ($current_time){
                        $q->where('end','>=',$current_time);
                    })
                    ->when($request_table == 'P', function($q) use ($current_time){
                        $q->where('end','<',$current_time);
                    })
                    ->with(['user:id,first_name,last_name','coach:id,first_name,last_name,coaching_method','categories:id,title']);
    }

    public function getBookedByCoachDate($coach_id, $date)
    {
        return $this->model
                    ->where('coach_id', $coach_id)
                    ->where('start', '>=', $date.' 00:00:00')
                    ->where('end', '<=', $date.' 23:59:59')
                    ->whereIn('status', ['scheduled', 'completed', 'payment pending'])
                    ->orderBy('start')
                    ->get();
    }

    public function getPendingPayments()
    {
        return $this->model
                        ->where('amount', '>', 0)
                        //->where('coach_credited', 0)
                        //->endedBefore(1) // No of days passed after the session
                        ->where(function($query) {
                            $query->endedBefore(1)
                                ->orWhere(function($q) {
                                    $q->where('status', 'cancelled')
                                        ->where('cancel_fee_percent', '>', 0);
                                });
                        })
                        ->get();
    }

    public function getPendingRefunds()
    {
        return $this->model
                        ->where('amount', '>', 0)
                        ->where('status', 'cancelled')
                        ->where('refund_status', 'unpaid')
                        ->get();
    }

    /**
     * Get total succesful session duration for coach
     * @param  Integer $coach_id
     * @return Integer
     */
    public function getCoachingTime($coach_id)
    {
        $result =   $this->model->where('coach_id', $coach_id)
                        ->where('status', 'completed')
                        ->selectRaw('SUM(time_to_sec(timediff(end, start )) / 60) as total_minutes')
                        ->first();
        return (int) ($result->total_minutes?$result->total_minutes:0);
    }

    /**
     * Caculates the Fee percent to be charged to the coach.
     * @param  Integer $coach_id
     * @return Integer 
     */
    public function calculateFee($coach_id)
    {
        $total_minutes = $this->getCoachingTime($coach_id);
        if($total_minutes>(15*60))
            $fee_percent = 12;
        else if($total_minutes>(10*60))
            $fee_percent = 15;
        else if($total_minutes>(5*60))
            $fee_percent = 17;
        else
            $fee_percent = 20;
        return $fee_percent;
    }

    public function setAppointmentsDuration()
    {
        $appointments = $this->model
                            //->where('mode', 'online')
                            ->where('status', 'scheduled')
                            //->where('call_duration', 0)
                            ->where('end', '<=', Carbon::now('Europe/Berlin')->toDateTimeString())
                            ->with('callLogs')
                            ->get();
        $affected_rows  = 0;
        foreach ($appointments as $appointment) {
            $total_duration = $appointment->callLogs->sum('duration');
            //if($total_duration>0) {
                // Update appointment duration and status
                $appointment->call_duration = $total_duration;
                $appointment->status        = 'completed';
                $appointment->fee_percent   = $this->calculateFee($appointment->coach_id);
                $appointment->save();
                $affected_rows++;
                event(new AppointmentCompletedEvent($appointment));
            //}
        }
        return $affected_rows;
    }

    public function releaseSlots()
    {
        return $this->model
                    ->whereIn('status', ['payment pending', 'payment processing'])
                    ->where('created_at', '<=', Carbon::now()->subHours(3)->toDateTimeString())
                    ->update(['status' => 'payment failed']);
    }

    /** Invoicing */
    public function createInvoice($appointment, $user_type, $return_pdfObj=false)
    {
        $coach       = $appointment->coach;
        $user        = $appointment->user;
        $category    = $appointment->categories;

        // Get cost calculations
        $cost_calculation = $appointment->formatted_cost_calculation;
        $file_path = FileUploadHelper::getFilePath('invoice');
        if($appointment->status!='cancelled') {
            $file_name = $user_type.'_invoice_'.$appointment->id.'.pdf';
            $view_path = $user_type=='user'?'pdf.user_invoice':'pdf.coach_invoice';
        }
        else {
            $file_name = $user_type.'_creditnote_'.$appointment->id.'.pdf';
            $view_path = $user_type=='user'?'pdf.user_creditnote':'pdf.coach_invoice_cancel';
        }
        $pdf       = PDF::loadView($view_path, compact('appointment', 'coach', 'user', 'category', 'cost_calculation'));
        
        if($return_pdfObj)
            return $pdf;
        FileUploadHelper::putToS3($file_path, $file_name, $pdf, true);
        return FileUploadHelper::getS3FileUrl($file_path.$file_name);
        //$pdf->save($path)->stream('invoice.pdf');
    }

    public function getInvoice($appointment, $user_type)
    {
        return $this->createInvoice($appointment, $user_type);
        if($appointment->status!='cancelled')
            $file_name = $user_type.'_invoice_'.$appointment->id.'.pdf';
        else
            $file_name = $user_type.'_creditnote_'.$appointment->id.'.pdf';
        $file_path = FileUploadHelper::getFilePath('invoice');
        if(FileUploadHelper::checkFileExists($file_name, 'invoice'))
            return FileUploadHelper::getS3FileUrl($file_path.$file_name);
        else
            return $this->createInvoice($appointment, $user_type);
    }
    /** Invoicing End */

    public function addNote($note, $appointment_id)
    {
        /** Create activity log */
        $activity = array(
                        'activity_type' => 'notes',
                        'activity'      => $note,
                        'type'          => 'appointment',
                        'activitable_id'=> $appointment_id
                    );
        $this->setActivity($activity);
        /** Create activity log End */
    }

    public function cancelAppointment($appointment_id, $coach_id='', $user_id='')
    {
        // Calculate cancellation charges
        $appointment = $this->model->find($appointment_id);
        if($user_id!='' && $appointment->start->diffInHours(Carbon::now('Europe/Berlin'))<=24)
            $cancel_per = env('SESSION_CANCEL_FEE', 60); // Cancel 60% amount
        else
            $cancel_per = 0;

        return $this->model->where('id', $appointment_id)
                    ->when($coach_id, function($q) use ($coach_id){
                        $q->where('coach_id', $coach_id);
                    })
                    ->when($user_id, function($q) use ($user_id){
                        $q->where('user_id', $user_id);
                    })
                    ->update([
                        'status'             => 'cancelled', 
                        'cancelled_by'       => $user_id!=''?'user':'coach',
                        'cancelled_on'       => date('Y-m-d H:i:s'),
                        'cancel_fee_percent' => $cancel_per,
                        'refund_status'      => 'unpaid',
                        'fee_percent'        => $this->calculateFee($appointment->coach_id)
                    ]);
    }

    public function updateUserAppointment($appointment_id, $user_id, $data)
    {
        return $this->model->where('id', $appointment_id)
                        ->whereNull('user_updated_on') // Only 1 update allowed for user
                        ->when($user_id, function($q) use ($user_id){
                            $q->where('user_id', $user_id);
                        })
                        ->update($data);
    }

    public function getCallInfo($conversationId)
    {
        $apiRTCBaseUrl = 'https://cloud.apizee.com/api';
        $client = new Client(); //GuzzleHttp\Client
        $response = $client->post($apiRTCBaseUrl.'/token', [
            'form_params' => [
                'grant_type' => 'password',
                'username'   => 'alok@creative-mantra.com',
                'password'   => 'Password123'
            ]
        ]);
        $body    = $response->getBody();
        $content = json_decode($body->getContents());
        if(!isset($content->access_token))
            return false;
        $token   = $content->access_token;

        // Get Conversation
        $client = new Client(); //GuzzleHttp\Client
        $response = $client->get($apiRTCBaseUrl.'/conversations/'.$conversationId.'/timeline', [
            'headers' => [
                'Accept'     => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ]
        ]);
        $body    = $response->getBody();
        $content = json_decode($body->getContents());
        return $content;
    }

      /**
     * Returns bookings to be notified before requested hours
     * @param  String $hrs_before
     * @return EloquentObjectArray
    */
	public function getNotifyBooking($hrs_before)
	{
        $dt 	   = Carbon::now('Europe/Berlin')->addHours($hrs_before);
        // var_dump(Carbon::now());
        if($dt->format('i')>=00 && $dt->format('i')<15){
            $start_min = '00:00';
            $end_min   = '14:59';
        }else if($dt->format('i')>=15 && $dt->format('i')<30){
            $start_min = '15:00';
            $end_min   = '29:59';
        }else if($dt->format('i')>=30 && $dt->format('i')<45){
            $start_min = '30:00';
            $end_min   = '44:59';
        }else if($dt->format('i')>=45 && $dt->format('i')<60){
            $start_min = '45:00';
            $end_min   = '59:59';
        }
		$start     = $dt->format('Y-m-d H:'.$start_min);
        $start_end = $dt->format('Y-m-d H:'.$end_min);
        var_dump($start);
        var_dump($start_end);
        // exit();
		return $this->model
					->where('status', 'scheduled')
					->where('start', '>=', $start)
                    ->where('start', '<=', $start_end)
                    ->with(['user:*','coach:*'])
					->get();
	}

}