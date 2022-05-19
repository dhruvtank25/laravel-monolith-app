<?php

namespace App\Repositories;

use Auth;
use Carbon\Carbon;
use App\Models\CoachAvailability;
use App\Repositories\AppointmentRepository;

class CoachAvailabilityRepository extends EloquentRepository
{
    protected $model;

    function __construct(CoachAvailability $coachAvailability, AppointmentRepository $appointmentRepo)
    {
        $this->model           = $coachAvailability;
        $this->appointmentRepo = $appointmentRepo;
    }

    public function getSlotsForDate($coach_id, $date_on)
    {
        $week =  date('w', strtotime($date_on));
        return $this->model
                    ->where('coach_id', $coach_id)
                    ->where(function($query) use ($date_on, $week) {
                        $query->where('date_on', $date_on)
                            ->orWhereRaw('recurring = "daily" and date_on <= ? and (recurring_end IS NULL || recurring_end>=?)', [$date_on, $date_on])
                            ->orwhereRaw('recurring = "weekly" and recurring_week_days like ? and ? between date_on and recurring_end', ['%'.$week.'%', $date_on]);
                    })
                    //->orderBy('status', 'desc')
                    //->orderBy('date_on')
                    ->orderBy('time_from')
                    ->get();
    }

    public function getAvailableDates($coach_id, $start='', $end='')
    {
        $available_dates = $unavailable_dates = $requestable_dates = $booked_dates =  array();

        // Availabilities
        $results = $this->model
                    ->where('coach_id', $coach_id)
                    ->where(function($query) use ($start, $end) {
                        $query->whereBetween('date_on', [$start, $end])
                            ->orWhereBetween('recurring_end', [$start, $end])
                            ->orWhereRaw('recurring = "daily" and date_on <= ? and (recurring_end IS NULL || recurring_end>=?)', [$start, $end]);
                    })
                    ->orderBy('status')
                    ->get(['date_on', 'status', 'is_requestable', 'recurring', 'recurring_end']);
                
        foreach ($results as $result) {
            if($result->recurring!='single') {
                $c_recurring_date = $result->date_on;
                $recurring_end    = $result->recurring_end!=null?$result->recurring_end:$end;
                if($result->date_on<$start) {
                    if($result->recurring=='weekly') {
                        $week =  date('w', strtotime($result->date_on));
                        $c_recurring_date = Carbon::createFromFormat('Y-m-d', $start);
                        $recurring_week_day = $c_recurring_date->dayOfWeek;
                        if($recurring_week_day!=$week) {
                            if($recurring_week_day<$week)
                                $c_recurring_date->addDays($week-$recurring_week_day);
                            else
                                $c_recurring_date->addDays((7-$c_recurring_date->dayOfWeek)+$week);
                        }
                    } else {
                        $c_recurring_date = Carbon::createFromFormat('Y-m-d', $start);
                    }
                }     
                while ($c_recurring_date->toDateString() <= $recurring_end) {
                    $c_date = $c_recurring_date->toDateString();
                    if($result->status=='available') {
                        $available_dates[$c_date.' 00:00:00'] = 'available';
                    } else {
                        unset($available_dates[$c_date.' 00:00:00']);
                        if($result->is_requestable==0)
                            $unavailable_dates[$c_date.' 00:00:00'] = 'unavailable';
                        else
                            $requestable_dates[$c_date.' 00:00:00'] = 'requestable';
                    }
                    if($result->recurring=='daily') {
                        $c_recurring_date->addDay();
                    } else if($result->recurring=='weekly') {
                        $c_recurring_date->addWeek();
                    }
                }
            } else {
                $date = $result->date_on->format('Y-m-d 00:00:00');
                if($result->status=='available') {
                    $available_dates[$date] = 'available';
                } else {
                    unset($available_dates[$date]);
                    if($result->is_requestable==0)
                        $unavailable_dates[$date] = 'unavailable';
                    else
                        $requestable_dates[$date] = 'requestable';
                }
            }
        }

        // Appointments
        $appointments   =   $this->appointmentRepo->getAppointments([
                                'coach_id' => $coach_id,
                                'start'    => $start,
                                'end'      => $end
                            ]);
        foreach ($appointments as $appointment) {
            $booked_dates[$appointment->start->format('Y-m-d 00:00:00')] = 'booked';
        }

        // Preference => Booked > requestable > unavailable > available
        $result = array_merge($available_dates, $unavailable_dates, $requestable_dates, $booked_dates);
        return $result;
    }

    public function getAvailability($coach_id='', $day='', $category_id='')
    {
        return $this->model
                    ->when($coach_id, function($query) use ($coach_id) {
                        $query->where('coach_id', $coach_id);
                    })
                    ->when($day, function($query) use ($day) {
                        $query->where('day', $day);
                    })
                    ->when($category_id, function ($query) use ($category_id) {
                        $query->whereHas('coach', function ($q) use ($category_id) {
                            $q->whereHas('categories', function($q) use ($category_id) {
                                $q->where('category_id', $category_id);
                            });
                        });
                    })
                    ->with('coach:id,first_name,last_name')
                    ->orderBy('coach_id')
                    ->orderBy('position_no')
                    ->get();
    }

    /**
     * Returns Coaches Ids working on requested day
     * @param  String $day
     * @return Array
     */
    public function getCoachIdByDay($day, $ignore_id_arr='')
    {
        return $this->model
                    ->select('coach_id')
                    ->where('day', $day)
                    ->when($ignore_id_arr, function($query) use ($ignore_id_arr) {
                        $query->whereNotIn('coach_id', $ignore_id_arr);
                    })
                    ->groupBy('coach_id')
                    ->get()
                    ->toArray();
    }

    public function checkAvailability($slot, $coach_id='', $ignore_id_arr='')
    {
        $carbonInstance = Carbon::createFromFormat('Y-m-d H:i:s', $slot);
        $day            = $carbonInstance->format('l');
        $start_time     = $carbonInstance->format('H:i:s');
        $end_time       = $carbonInstance->addMinutes(env('SESSION_DURATION_MINS',90))->format('H:i:s');
        return $this->model
                    ->where('day', $day)
                    ->whereHas('coach')
                    ->when($coach_id, function($query, $coach_id){
                        $query->where('coach_id', $coach_id);
                    })
                    ->when($ignore_id_arr, function($query) use ($ignore_id_arr) {
                        $query->whereNotIn('coach_id', $ignore_id_arr);
                    })
                    ->where('time_from', '<=', $start_time)
                    ->where('time_to', '>=', $end_time)
                    ->get();
    }

    public function create($coach_id, $availability_arr)
    {
        // Weekly slot
        if($availability_arr['recurring']=='weekly') {
            $start      = $availability_arr['date_on'];
            $total      = $availability_arr['recurring_weeks']-1;
            $end        = date('Y-m-d', strtotime($start.' + ' .$total .' weeks'));
            $week_day   = date('w', strtotime($start));
            $availability_arr['recurring_week_days'] = $week_day;
            $availability_arr['recurring_end']       = $end;
        }
                
        $availability_arr['coach_id'] = $coach_id;

        // Remove unavailable record if any for this date
        $this->unsetUnavailable($coach_id, $availability_arr['date_on']);
        return  $this->add($availability_arr, false);
    }

    public function update($coach_id, $availability_arr, $selected_date)
    {
        $id     = $availability_arr['id'];
        unset($availability_arr['id']);
        
        $record = $this->model->where('coach_id', $coach_id)
                            ->where('id', $id)
                            ->first();

        /** Check if data has changed **/
        $record_arr       = $record->toArray();
        $new_data_arr     = $availability_arr;
        $new_data_arr['date_on']   = $new_data_arr['date_on'].' 00:00:00';
        $new_data_arr['time_from'] = $new_data_arr['time_from'].':00';
        $new_data_arr['time_to']   = $new_data_arr['time_to'].':00';
        unset($new_data_arr['update_recurring']);
        
        $similar_data_arr = array_intersect($record_arr,$new_data_arr);
        if(count($similar_data_arr)==count($new_data_arr))
            return true;

        /** Something has changed related to this record. **/
        $newRecord  = $record->replicate();
        if($record->recurring=='single') {
            $record->delete();
            return $this->create($coach_id, $availability_arr);
        }

        // End previous recurring
        $this->endRecurring($record, $selected_date);
                
        if($availability_arr['recurring']!='single') {
            // Update recurring
            // Start new recurring with new record
            $availability_arr['date_on'] = $selected_date;
            return $this->create($coach_id, $availability_arr);
        } else {
            if($availability_arr['update_recurring']=="false") {
                // Update for this one only, future recurring goes as set initially
                // Create single record for this date
                $availability_arr['date_on'] = $selected_date;
                $availability_arr['recurring'] = 'single';
                $this->create($coach_id, $availability_arr);
                
                // Start copy of recurring from next date
                if($newRecord->recurring_end==null || $newRecord->date_on<=$newRecord->recurring_end) {
                    $selected_carbon = Carbon::createFromFormat('Y-m-d', $selected_date);
                    if($record->recurring=='daily') {
                        $newRecord->date_on       = $selected_carbon->addDays(1)->toDateString();
                        $newRecord->recurring_end = null;
                    } else if($record->recurring=='weekly') {
                        $newRecord->date_on       = $selected_carbon->addWeeks(1)->toDateString();
                    }
                    return $newRecord->save();
                }
                return true;
            } else {
                // End of recurring
                // create a single slot with updated data for this date
                $availability_arr['date_on']    = $selected_date;
                $availability_arr['recurring']  = 'single';
                return $this->create($coach_id, $availability_arr);
            }
        }
    }

    public function setUnavailable($coach_id, $date_from, $date_to, $is_requestable=true)
    {
        $total_appmnts   =  $this->appointmentRepo->checkTotalSchedule(
                                $coach_id, $date_from.' 00:00:00', $date_to.' 23:59:59'
                            );
        if($total_appmnts>0)
            return ['success' => false, 'message' => $total_appmnts.' Scheduled Appointments exists in between'];

        $availability_arr = array(
                                    'status'        => 'unavailable',
                                    'is_requestable'=> $is_requestable,
                                    'date_on'       => $date_from,
                                    'time_from'     => '00:00:00',
                                    'time_to'       => '00:00:00',
                                    'recurring'     => 'daily',
                                    'recurring_end' => $date_to
                                );
        // Delete all single availability for this date
        $this->model->where('coach_id', $coach_id)
                    ->whereBetween('date_on', [$date_from, $date_to])
                    ->where('recurring', 'single')
                    ->delete();

        // Update recurring availability to start from next date
        $new_start       =  Carbon::parse($date_to)->addDays(1);
        $recurring_slots =  $this->model->where('coach_id', $coach_id)
                                        ->where('status', 'available')
                                        ->where('recurring', '!=', 'single')
                                        ->whereBetween('date_on', [$date_from, $date_to])
                                        ->where(function ($query) use ($date_to) {
                                            $query->where('recurring_end', '>', $date_to)
                                                    ->orWhereNull('recurring_end');
                                        })
                                        ->update(['date_on' => $new_start->format('Y-m-d')]);

        // Create unavailability record
        $this->create($coach_id, $availability_arr);
        return ['success' => true, 'message' => 'UnAvailability added successfully.'];
    }

    public function unsetUnavailable($coach_id, $date)
    {
        // Delete single day unavailability
        return $this->model->where('coach_id', $coach_id)
                    ->where('date_on', $date)
                    ->where('status', 'unavailable') 
                    ->delete();
    }

    public function endRecurring($record, $selected_date)
    {
        $selected_carbon = Carbon::createFromFormat('Y-m-d', $selected_date);
        if($record->recurring=='daily') {
            $new_end     = $selected_carbon->subDays(1);
        } else if($record->recurring=='weekly') {
            $new_end     = $selected_carbon->subWeeks(1);
        }

        // End current recurring
        if($new_end < $record->date_on) {
            $record->delete();
        } else {
            $record->recurring_end = $new_end;
            $record->save();
        }
    }

    public function checkForBookings($coach_id, $selected_date, $stop_recurring, $availability_record)
    {
        if($stop_recurring=="false") {
            $check_start = $selected_date.' '.$availability_record->time_from;
            $check_end   = $selected_date.' '.$availability_record->time_to;
            $result = $this->appointmentRepo->checkTotalSchedule($coach_id, $check_start, $check_end);
            if($result>0)
                return false;
        } else {
            $check_start = $selected_date.' '.$availability_record->time_from;
            if($availability_record->recurring_end)
                $check_end = $availability_record->recurring_end.' '.$availability_record->time_to;
            else
                $check_end   = Carbon::createFromFormat('Y-m-d', $selected_date)->addMonths(4)->format('Y-m-d').' '.$availability_record->time_to;
            $results = $this->appointmentRepo->getTotalSchedule($coach_id, $check_start, $check_end);
            foreach ($results as $result) {
                $date             = $result->start->format('Y-m-d');
                if($availability_record->recurring=='weekly') {
                    if($availability_record->recurring_week_days==null)
                        continue;
                    $week_no  = $result->start->dayOfWeek;
                    $week_arr = explode(',', $availability_record->recurring_week_days);
                    if(!in_array($week_no, $week_arr))
                        continue;
                }
                $this_check_start = Carbon::createFromFormat('Y-m-d H:i:s', $date.' '.$availability_record->time_from);
                $this_check_end   = Carbon::createFromFormat('Y-m-d H:i:s', $date.' '.$availability_record->time_to);
                $this_result = $result->where(function($q) use ($this_check_start, $this_check_end) {
                                    $q->where(function($query) use ($this_check_start, $this_check_end) {
                                            $query->where('start', '>', $this_check_start)
                                                ->where('start', '<', $this_check_end);
                                        })
                                        ->orWhere(function($query) use ($this_check_start, $this_check_end) {
                                            $query->where('end', '>', $this_check_start)
                                                ->where('end', '<', $this_check_end);
                                        })
                                        ->orWhere(function($query) use ($this_check_start, $this_check_end) {
                                            $query->where('start', '<=', $this_check_start)
                                                    ->where('end', '>=', $this_check_end);
                                        });
                                })->count();
                if($this_result>0)
                    return false;
            }
        }
        return true;
    }

    public function deleteRecurring($coach_id, $id, $selected_date, $stop_recurring)
    {
        $record = $this->model->where('coach_id', $coach_id)
                            ->where('id', $id)
                            ->first();
        if(!$record)
            return true;

        // Check for bookings in between
        $result = $this->checkForBookings($coach_id, $selected_date, $stop_recurring, $record);
        if(!$result)
            return false;
        // Check end

        if($stop_recurring=="false") {
            $newRecord          = $record->replicate();
            $selected_carbon    = Carbon::createFromFormat('Y-m-d', $selected_date);
            if($record->recurring=='daily') {
                $newRecord->date_on = $selected_carbon->addDays(1)->toDateString();
                //$newRecord->recurring_end = null;
            } else if($record->recurring=='weekly') {
                $newRecord->date_on  = $selected_carbon->addWeeks(1)->toDateString();
            }
            if($newRecord->recurring_end==null || $newRecord->date_on<=$newRecord->recurring_end) {
                $newRecord->save();
            }
        }
        
        // End previous recurring
        $this->endRecurring($record, $selected_date);
        return true;
    }

    public function bulkDelete($coach_id, $id_arr)
    {
        return  $this->model->whereIn('id', $id_arr)
                    ->where('coach_id', $coach_id)
                    ->delete();
    }

    /** UNVAILABILITY STARTS */
    public function getUnavailableDataTable($coach_id)
    {
        return $this->model->where('coach_id', $coach_id)
                            ->where('is_requestable', 0)
                            ->where('status', 'unavailable');
    }

}