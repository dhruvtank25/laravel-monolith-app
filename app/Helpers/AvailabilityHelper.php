<?php

namespace App\Helpers;

class AvailabilityHelper
{
    ###############
    #  GET LOGIC  # 
    ###############
    
    /**
     * GET UNAVAILABILITY
     * 
     * Unavailability is set by coach in datetime as
     * unavailable from as start
     * unavailable to as end
     *
     * Unavailabilites types/Cases
     * 1) Unavailable for single day
     *      - Completly unavailable
     *      - Partially unavailable
     * 2) Unavailable for multiple days (2 or more days)
     *      - Completly unavailable first day
     *      - Partially unavailable first day
     *      - Completely unavailabe last day
     *      - Partially unavailable last day
     *      - Completly unavailable for days in between
     * Partially unavailable: unavailable after first slot start/unavailable before last slot end
     */
    public static function getCoachUnavailability($unavailabilities, $availability_arr)
    {
        $unavailable_dates = $partial_avail_slots  = array();
        
        foreach ($unavailabilities as $unavailability) {

            $unavail_from_date  = $unavailability->unavailable_from->toDateString();
            $unavail_to_date    = $unavailability->unavailable_to->toDateString();

            $unavail_from_time  = $unavailability->unavailable_from->toTimeString();
            $unavail_to_time    = $unavailability->unavailable_to->toTimeString();

            $c_unavailable_date = $unavailability->unavailable_from;
            while ($c_unavailable_date->toDateString() <= $unavail_to_date) {
                $c_date     =   $c_unavailable_date->toDateString();

                /**
                 * First Unavailable Day (or only unavailable day) / Last Unavailable Day
                 * 
                 * It is possible for this unavailabilities are not for whole day
                 * and coach might teach in some of the slots in these day.
                 *
                 * 1) Check if coach works on this day
                 *     - No, set unavailable for this day
                 * 2) Yes, Check if coach is unavailable for all slots in this day
                 *     2a) Check Unavailability start time is <= start time of this slot of day
                 *         - No, set available for this day (as available for some time in this day)
                 *     2b) Yes, Check Unavailability ends on other day (multiple day unavailability)
                 *         - Yes, set unavailable for this day
                 *     2c) No, check Unavailability end time is >= end time of last slot of day
                 *         - Yes, set unavailable for this day
                 *         - No, set available for this day (as available for some time in this day)
                 *
                 * MULTIPLE UNAVAILABILITES SET IN SAME DAY CONDITION NOT YET ACHIEVIED
                 */
                if(isset($availability_arr[$c_unavailable_date->englishDayOfWeek])) {
                    $day_slot_arr = $availability_arr[$c_unavailable_date->englishDayOfWeek];
                    $first_slot   = $day_slot_arr[0];
                    $last_slot    = $day_slot_arr[count($day_slot_arr)-1];
                    if($c_date == $unavail_from_date) {  // First unavalability day
                        if($unavail_from_time <= $first_slot['time_from']) {
                            // Checking if unavailability ends on same date
                            if($unavail_to_date == $c_date) {
                                if($unavail_to_time >= $last_slot['time_to']) {
                                    $unavailable_dates[] = $c_date;
                                } else {
                                    // Available partially
                                   $partial_avail_slots  = self::getPartialAvailableSlot($unavailability, $c_date, $day_slot_arr, $partial_avail_slots);
                                }
                            } else {
                                $unavailable_dates[] = $c_date;
                            }
                        } else {
                            // Available partially
                            $partial_avail_slots  = self::getPartialAvailableSlot($unavailability, $c_date, $day_slot_arr, $partial_avail_slots);
                        }
                    }
                    else if($c_date == $unavail_to_date) { // Last unavalability day
                        if($unavail_to_time >= $last_slot['time_to']) {
                            $unavailable_dates[] = $c_date;
                        } else {
                            // Available partially
                            $partial_avail_slots  = self::getPartialAvailableSlot($unavailability, $c_date, $day_slot_arr, $partial_avail_slots);
                        }
                    }
                    else { // All other days in between
                        $unavailable_dates[] = $c_date;
                    }
                } else {
                    $unavailable_dates[] = $c_date;
                }
                $c_unavailable_date->addDay();
            }
        }
        return ['unavailable_dates' => $unavailable_dates,'partial_avail_slots' => $partial_avail_slots];
    }

    private static function getPartialAvailableSlot($unavailability, $c_date, $day_slot_arr, $partial_avail_slots)
    {
        $unavailability_start = $unavailability->unavailable_from->toTimeString();
        $unavailability_end   = $unavailability->unavailable_to->toTimeString();

        // Check if unavailability ends on this day
        if($unavailability->unavailable_to->toDateString() == $c_date) {
            foreach ($day_slot_arr as $slot) {
                $start = $end = null;
                
                // Single Day unavailabilitys
                if($unavailability->unavailable_from->toDateString()==$c_date) {

                    
                    if($slot->time_to <= $unavailability_start || $slot->time_from >= $unavailability_end) {
                        $start = $slot->time_from;
                        $end   = $slot->time_to;


                    } else if($slot->time_from < $unavailability_start || $slot->time_to > $unavailability_end) {
                        if($slot->time_from < $unavailability_start) {
                            $start = $slot->time_from;
                            $end   = $unavailability_start;
                        }
                        if($slot->time_to > $unavailability_end) {
                            /** Check if conflict with prev slots */
                            if(isset($partial_avail_slots[$c_date]) && count($partial_avail_slots[$c_date])>0) {
                                $last_slot = end($partial_avail_slots[$c_date]);
                                if($start<$last_slot->time_to || ($last_slot->time_to>$unavailability_start && $last_slot->time_to <= $unavailability_end)) {
                                        /*if($c_date=='2019-07-22' && $unavailability_start=='12:00:00') {
                                            echo $unavailability_start.' > '.$last_slot->time_from;
                                            exit;
                                        }*/
                                    if($unavailability_start>$last_slot->time_from) {
                                        $last_slot->time_to = $unavailability_start;
                                    }else {
                                        array_pop($partial_avail_slots[$c_date]);
                                    }
                                    $start = $end = null;
                                }
                            }
                            if(isset($start) && isset($end)) {
                                // Need to check duration is greater than equal to 15min
                                $partial_avail_slots[$c_date][] = (object) array('time_from'=> $start, 'time_to' => $end);
                            }
                            $start = $unavailability_end;
                            $end   = $slot->time_to;
                        }
                    }
                }else { // Last day of multiple day unavailability
                    if($slot->time_from >= $unavailability_end) {
                        $start = $slot->time_from;
                        $end   = $slot->time_to;
                    } else if($slot->time_to > $unavailability_end) {
                        $start = $unavailability_end;
                        $end   = $slot->time_to;
                    }
                }

                /** Check if conflict with prev slots */
                if(isset($partial_avail_slots[$c_date]) && count($partial_avail_slots[$c_date])>0) {
                    $last_slot = end($partial_avail_slots[$c_date]);
                    if($start<$last_slot->time_to || ($last_slot->time_to>$unavailability_start && $last_slot->time_to <= $unavailability_end)) {
                        if($unavailability_start>$last_slot->time_from) {        
                            $last_slot->time_to = $unavailability_start;
                        }else {
                            array_pop($partial_avail_slots[$c_date]);
                        }
                        if($unavailability_end>$slot->time_to || $start<$unavailability_end) {
                            $start = $end = null;
                        }
                        //$start = $end = null;
                    }
                }
                if(isset($start) && isset($end)) {
                    // Need to check duration is greater than equal to 15min
                    $partial_avail_slots[$c_date][] = (object) array('time_from'=> $start, 'time_to' => $end);
                }
            }
        } else { // Logically it can only be the first day of multiple day unavailability
            foreach ($day_slot_arr as $slot) {
                $start = $end = null;
                if($slot->time_to <= $unavailability_start) {
                    // Ofcourse slot time from is less than availability start
                    $start = $slot->time_from;
                    $end   = $slot->time_to;
                } 
                else if($slot->time_from<$unavailability_start) {
                    // Ofcouse slot time_to is greater than unavailability start
                    $start = $slot->time_from;
                    $end   = $unavailability_start;
                }
                /** Check if conflict with prev slots */
                if(isset($partial_avail_slots[$c_date]) && count($partial_avail_slots[$c_date])>0) {
                    $last_slot = end($partial_avail_slots[$c_date]);
                    if($start<$last_slot->time_to || ($last_slot->time_to>$unavailability_start && $last_slot->time_to <= $unavailability_end)) {
                        if($unavailability_start>$last_slot->time_from) {
                            $last_slot->time_to = $unavailability_start;
                        }else {
                            array_pop($partial_avail_slots[$c_date]);
                        }
                        $start = $end = null;
                    }
                }
                if(isset($start) && isset($end)) {
                    // Need to check duration is greater than equal to 15min
                    $partial_avail_slots[$c_date][] = (object) array('time_from'=> $start, 'time_to' => $end);
                }
            }
        }
                
        return $partial_avail_slots;
    } 


    ###############
    #  SET LOGIC  # 
    ###############

    /**
     * Set the days as completely unavailable for bookings
     * @param Array   $events         Currently set unavailability events
     * @param Integer $prev_dow       Day of week to start setting unavailability
     * @param Integer $dow            Day of week to end setting unavailability
     * @param Object  $coach          Coach to set unavailbility for
     * @return Array                      Updated unavailability events
     */
    public static function setUnavailableDay($events, $prev_dow, $dow, $coach)
    {
        $coach_name = $coach->first_name.' '.$coach->last_name;
        for ($missing_dow=$prev_dow; $missing_dow < $dow; $missing_dow++) { 
            $events[] = array(
                           'start'         => '00:00:00',
                           'end'           => '23:59:59',
                           'rendering'     => 'background',
                           'color'         => '#cbced1',
                           'title'         => 'Unavailable',
                           'dow'           => [$missing_dow], // Day of week
                           'resourceId'    => $coach->id,
                           'resourceName'  => $coach_name,
                        );
        }
        return $events;
    }

    /**
     * Sets further day as unavailble for a week day
     * @param  Array  $events             Currently set unavailability events
     * @param  Object $last_availability  Last available day and time
     * @return Array                      Updated unavailability events
     */
    public static function setUnavailableEnd($events, $last_availability)
    {
        if($last_availability->time_to<'23:59:59'){
          // Add remaing day as unavailable for day
          $last_coach        = $last_availability->coach;
          $last_coach_name   = $last_coach->first_name.' '.$last_coach->last_name;
          $last_dow          = self::getDow($last_availability->position_no);
          $events[] = array(
                         'start'         => $last_availability->time_to,
                         'end'           => '23:59:59',
                         'rendering'     => 'background',
                         'color'         => '#cbced1',
                         'title'         => 'Unavailable',
                         'dow'           => [$last_dow], // Day of week
                         'resourceId'    => $last_coach->id,
                         'resourceName'  => $last_coach_name,
                      );
        }
        return $events;
    }

    /**
     * Returns Integer representation of day of week
     * @param  Integer $position_no
     * @return Integer
     */
    public static function getDow($position_no)
    {
        return $position_no<10000?0:substr($position_no, 0, 1);
    }

}