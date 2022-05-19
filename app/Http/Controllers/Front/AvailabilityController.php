<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CoachAvailabilityRepository;
use App\Repositories\AppointmentRepository;
use App\Traits\AvailabilityTrait;

class AvailabilityController extends Controller
{
    use AvailabilityTrait;

    function __construct(CoachAvailabilityRepository $availabilityRepo, AppointmentRepository $appointmentRepo)
    {
        $this->availabilityRepo = $availabilityRepo;
        $this->appointmentRepo  = $appointmentRepo;
    }

    public function getAvailableSlots(Request $request)
    {
        $date            = $request->date;
        $coach_id        = $request->coach_id;
        
        $slot_arr        = $booked_times = array();
        $allowed_min_dur = env('SLOT_MIN_DURATION', 30);
        $allowed_max_dur = env('SLOT_MAX_DURATION', 120);
        
        // Booked Slots
        $appointments = $this->appointmentRepo->getBookedByCoachDate($coach_id, $date);
        // Do not really need this loop and $booked_times until we need to show booked dates.
        foreach ($appointments as $appointment) {
            $start_time     = $appointment->start->format('H:i:s');
            $end_time       = $appointment->end->format('H:i:s');
            $booked_times[] = ['start' => $start_time, 'end' => $end_time];
        }

        // Available Slots
        $slots        = $this->availabilityRepo->getSlotsForDate($coach_id, $date);

        $combined_slots = array();
        foreach ($slots as $slot) {
            if(count($combined_slots)==0) {
                $combined_slots[] = $slot;
                continue;
            }
            $last_slot = end($combined_slots);
                    
            if($slot->time_from<=$last_slot->time_to && $slot->time_to>$last_slot->time_to) {
                $last_slot->time_to = $slot->time_to;
                array_pop($combined_slots);
                array_push($combined_slots, $last_slot);
            } else {
                $combined_slots[] = $slot;
            }
        };

        $max_duration = 0;
        $is_unavailable = false;
        foreach ($combined_slots as $slot) {
            if($slot->status!='unavailable') {
                $this_slot   = ['start' => $slot->time_from, 'end' => $slot->time_to];

                // Check if created slot has any booking 
                $new_slot_arr = array(['start' => $this_slot['start'], 'end' => $this_slot['end']]);
                                        
                foreach ($appointments as $appointment) {
                    if(count($new_slot_arr)==0)
                        break;

                    $last_this_slot = end($new_slot_arr);
                    $check_start    = $last_this_slot['start'];
                    $check_end      = $last_this_slot['end'];

                    $start_time   = $appointment->start->format('H:i:s');
                    // Add 15 minutes to end time to keep gap of 15 minute in bookings
                    $end_time     = $appointment->end->addMinutes(15)->format('H:i:s');

                    if($end_time<=$check_start)
                        continue;
                    if($start_time>=$check_end)
                        break;

                    // So this booking is between our availability start and end 
                    // thus availability will be breaked/updated based on this booking
                    $duration_diff = $this->setMaxDuration($check_start, $start_time, 0);
                    $last_arr = array_pop($new_slot_arr);
                    if($duration_diff>=$allowed_min_dur) {
                        $last_arr['end'] = $start_time;
                        array_push($new_slot_arr, $last_arr);
                    }

                    $duration_diff = $this->setMaxDuration($end_time, $check_end, 0);
                    if($duration_diff>=$allowed_min_dur){
                        $check_start = $end_time;
                        $check_end   = $check_end;
                        array_push($new_slot_arr, ['start' => $check_start, 'end' => $check_end]);
                    }
                    else
                        break;
                }

                foreach ($new_slot_arr as $key => $new_slot) {
                    $slot_arr[]   = $new_slot['start'].' '.$new_slot['end'];
                    // Check and set if this slot has max duration
                    $max_duration = $this->setMaxDuration(
                                        $new_slot['start'],
                                        $new_slot['end'],
                                        $max_duration
                                    );
                }
            } else {
                $slot_arr = array();
                $is_unavailable = true;
                break;
            }
        }

        $only_requestable = false;
        if(count($slot_arr)==0 && count($booked_times)==0 && !$is_unavailable) {
            $max_duration = $this->setMaxDuration('04:00:00', '23:00:00', 0);
            $slot_arr[] = '04:00:00 23:00:00';
            $only_requestable = true;
        }
        if($date==date('Y-m-d'))  {
            $valid_carbon = \Carbon\Carbon::now("Europe/Berlin")->addHours(2);
            foreach ($slot_arr as $key => $slot) {
                $this_slot_arr = explode(' ', $slot);
                // If slot starts after valid availability time then further test needed
                $start_time = \Carbon\Carbon::createFromFormat('H:i:s', $this_slot_arr[0]);
                if($start_time->isAfter($valid_carbon))
                    break;

                // If slot ends before valid availabililty time then slot is not needed
                $end_time = \Carbon\Carbon::createFromFormat('H:i:s', $this_slot_arr[1]);
                if($end_time->isBefore($valid_carbon)) {
                    unset($slot_arr[$key]);
                    continue;
                }

                $minutes = (int) $valid_carbon->format('i');
                if($minutes==0)
                    $new_start_time = $valid_carbon->format('H:00:00');
                else if($minutes<30)
                    $new_start_time = $valid_carbon->format('H:30:00');
                else
                    $new_start_time = $valid_carbon->addHour()->format('H:00:00');
                $new_start_carbon = \Carbon\Carbon::createFromFormat('H:i:s', $new_start_time);
                if($end_time->diffInMinutes($new_start_carbon)>=env('SLOT_MIN_DURATION', 30))
                    $slot_arr[$key] = $new_start_carbon->format('H:i:s').' '.$end_time->format('H:i:s');
                else
                    unset($slot_arr[$key]);
            }
            $slot_arr = array_values($slot_arr);
        }

        return response()->json(['slots'=>$slot_arr, 'booked'=>$booked_times, 'max_duration'=>$max_duration, 'only_requestable'=>$only_requestable]);
    }

    private function setMaxDuration($from,$to,$max_duration)
    {
        $str_from  = strtotime($from);
        $str_to    = strtotime($to);
        $time_diff = ($str_to-$str_from)/60;
        if($time_diff>$max_duration)
            $max_duration = $time_diff;
        return $max_duration;
    }

}