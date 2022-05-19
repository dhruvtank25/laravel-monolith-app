<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;
use Auth;

trait AvailabilityTrait
{
    
    public function store(Request $request)
    {
        $coach_id      = $request->coach_id;
        $selected_date = $request->selected_date;

        // Delete availability if requested
        $delete_obj_arr     = $request->delete_ids;
        if(isset($delete_obj_arr) && count($delete_obj_arr)>0) {
            $delete_id_arr  = array();
            foreach ($delete_obj_arr as $delete_obj) {
                if($delete_obj['recurring']=='single') {
                    $delete_id_arr[] = $delete_obj['id'];
                } else {
                    $result = $this->availabilityRepo->deleteRecurring($coach_id, $delete_obj['id'],  $selected_date, $delete_obj['stop_recurring']);
                    if(!$result) {
                        $message = 'Cannot remove slots as booking exists in between!';
                        return response()->json(['success'=>'false', 'message'=> $message], 200);
                    }
                }
            }
            // Delete all single slots
            if(count($delete_id_arr)>0)
                $this->availabilityRepo->bulkDelete($coach_id, $delete_id_arr);
        }
                
        // Add/Update availability
        $availabilities = $request->availabilities;
                
        foreach ($availabilities as $availability) {
            if($availability['status']=='unavailable') {
                $result = $this->availabilityRepo->setUnavailable($coach_id, $selected_date, $selected_date);
                /*if(!$result['success']) {
                    $message = 'Availabilities updated successfully.';
                    return response()->json(['success'=>'false','message'=> $result['message']], 200);
                }*/
            } else {
                if(isset($availability['id']) && $availability['id']>0) {
                    $this->availabilityRepo->update($coach_id, $availability, $selected_date);
                } else {
                    $this->availabilityRepo->create($coach_id, $availability);
                }
            }
        }
        
        $message = 'Availabilities updated successfully.';
        if ($request->expectsJson()) {
            return response()->json(['success'=>'true','message'=>$message], 200);
        }
        return redirect()->back()->withSuccess($message);
    }

    public function getDateSlots(Request $request)
    {
        $coach_id  = $request->coach_id;
        $date      = $request->date;
        $slots     = $this->availabilityRepo->getSlotsForDate($coach_id, $date);
        return response()->json(['success'=>'true', 'availabilities'=> $slots]);
    }

    public function getAvailabilityStatuses(Request $request)
    {
        if(!isset($request->coach_id)) {
            return response()->json(['success'=>'true', 'data'=> []]);
        }
        $coach_id = $request->coach_id;
        $date     = $request->start_date;
        /*$carbonInstance = Carbon::createFromFormat('Y-m-d', $date)->startOfMonth();
        $start = $carbonInstance->format('Y-m-d');
        $end   = $carbonInstance->addMonths(1)->endOfMonth()->format('Y-m-d');*/

        $startCarbon = Carbon::createFromFormat('Y-m-d', $date)->startOfMonth();
        $endCarbon   = Carbon::createFromFormat('Y-m-d', $date)->startOfMonth()->addMonths(1)->endOfMonth();
        $start = $startCarbon->format('Y-m-d');
        $end   = $endCarbon->format('Y-m-d');
        // Get Availability/Unvailable/Atleast 1 Booking dates
        $data  = $this->availabilityRepo->getAvailableDates($coach_id, $start, $end);

        // if(count($data)==0) {
            while ($startCarbon->lessThanOrEqualTo($endCarbon)) {
                $date = $startCarbon->format('Y-m-d').' 00:00:00';
                if(!isset($data[$date]))
                    $data[$date] = 'requestable';
                $startCarbon->addDays(1);
            }
        // }
                
        return response()->json(['success'=>'true', 'data'=> $data]);
    }

    /** UNAVALABILITY START */

    public function getUnavailabilityDataTables($coach_id)
    {
        $unavailabilities = $this->availabilityRepo->getUnavailableDataTable($coach_id);
        return DataTables::of($unavailabilities)->toJson();
    }

    public function storeUnavailability(Request $request)
    {
        $inputs = $request->all();

        $coach_id         = $inputs['coach_id'];
        $unavailable_from = $inputs['unavailable_from'];
        $unavailable_to   = $inputs['unavailable_to'];

        $result = $this->availabilityRepo->setUnavailable($coach_id, $unavailable_from, $unavailable_to, false);
        if($result['success']) {
            if(Auth::guard('coach')->check()) {
                $coach = Auth::guard('coach')->user();
            }
            if($coach->status=='active')
                $message  = 'Die Abwesenheit wurde erfolgreich hinterlegt. Bitte beachte: in diesem Zeitraum befinden sich bestätigte Buchungen.';
            else
                $message  = 'Die Abwesenheit wurde erfolgreich hinterlegt.';
            return response()->json(['success'=>'true', 'message'=>$message], 200);
        } else {
            return response()->json(['success'=> 'false', 'message'=> $result['message']], 200);
        }
        
        /*
            $unavailability = $this->availabilityRepo->saveUnavailability($inputs);
            if(!$unavailability['status']){
                if(isset($unavailability['scheduled_record']))
                    $message  = 'Conflict! '.$unavailability['scheduled_record'].' appointment already booked in between selected range.';
                else
                    $mesage   = $unavailability['message']?$unavailability['message']:'Something went wrong!';
                return response()->json(['success'=>'false','message'=>$message], 200);
            }
            else{
                $message  = 'UnAvailability added successfully.';
                return response()->json(['success'=>'true','message'=>$message], 200);
            }
        */
    }

    public function destroyUnavailability(Request $request, $id)
    {
        $coach_id = $request->coach_id;
        $this->availabilityRepo->bulkDelete($coach_id, array($id));
        $message = 'UnAvailability removed successfully.';
        return response()->json(['success'=>'true','message'=>$message], 200);
    }

    /** UNAVALABILITY END */

}