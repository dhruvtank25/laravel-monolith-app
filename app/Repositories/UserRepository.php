<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Input;
use App\Repositories\ActivityLogRepository;
use App\Models\Role;
use Auth;
use Session;
use DB;

class UserRepository extends EloquentRepository
{

	protected $model;

	function __construct(User $user)
	{
		$this->model 	   = $user;
	}

    /**
     * Add new Activity Log for user.
     *
     * @param  Array  $activity
     * @return Boolean
     */
    public function setActivity($activity)
    {
        $activityRepo = new ActivityLogRepository(new ActivityLog);
        $activityRepo->setActivity($activity);
    }

    /** OVERRIDING PARENT METHOD START */
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
                            'activity_type' => 'user_created',
                            'activity'      => 'New user created',
                            'type'          => 'user',
                            'activitable_id'=> $this->model->id
                        );
            $this->setActivity($activity);
            /** Create activity log End */

            return $this->model->id;
        }else{
            return false;
        }
    }


    public function edit($data, $showMessage = true, $message = null)
    {
        $this->model = $this->get($data['id']);
        if (is_null($message)) {
            $message = 'Data was successfully updated.';
        }

        $saved = $this->fillNSave($data);

        if ($saved && $showMessage) {
            $this->setSavedMessage($message);
        }

        if($saved){
            $activity_msg = isset($data['password'])?' | password updated':'';
            /** Create activity log */
            $activity = array(
                            'activity_type' => 'user_updated',
                            'activity'      => 'user details has been updated'.$activity_msg,
                            'type'          => 'user',
                            'activitable_id'=> $this->model->id
                        );
            $this->setActivity($activity);
            /** Create activity log End */
            return $this->model->id;
        }else{
            return false;
        }
    }
    /** OVERRIDING PARENT METHOD END */

	public function getDataTable($role_name='', $inputs=[])
	{
		$status = isset($inputs['status'])?$inputs['status']:'';
		return $this->model
					->select(['users.id','users.first_name','users.last_name','users.email','users.person_type', 'users.status', 'users.kyc_status', 'users.ubo_status', 'users.avatar', 'users.role_id'])
					->with('roles:id,name')
					->when($status, function($q) use ($status){
						$q->where('status', $status);
					})
                    ->when($role_name, function($q) use ($role_name) {
                    	$q->whereHas("roles", function($query) use ($role_name){
						   $query->where("name", $role_name);
						});
                    });
	}

    public function getActiveCoaches()
    {
        return $this->model
                    ->whereHas("roles", function($query) {
                       $query->where("name", "coach");
                    })
                    ->where('status', 'active')
                    ->get();
    }

	public function getUsers($role_name='')
	{
		return $this->model->with('roles:id,name')
                    ->when($role_name, function($q) use ($role_name) {
                    	$q->whereHas("roles", function($query) use ($role_name){
						   $query->where("name", $role_name);
						});
                    })
                    ->get();
	}

    /**
     * Returns Active Coaches who have set availability
     * @return EloquentArray
     */
    public function getAvailabilityCoaches()
    {
        return $this->model
                    ->where('status', 'active')
                    ->whereHas("roles", function($query) {
                       $query->where("name", 'coach');
                    })
                    ->whereHas("availabilites")
                    ->get();
    }

    public function getTopCoaches()
    {
        return $this->model
                    ->select('users.id','users.first_name','users.last_name','users.avatar', DB::raw('avg(coach_reviews.rating) AS avg_rating'))
                    ->where('status', 'active')
                    ->whereHas("roles", function($query) {
                        $query->where("name", 'coach');
                    })
                    ->with([
                        'categories:id,title,icon',
                        //'reviews'
                    ])
                    ->leftJoin('coach_reviews', 'coach_reviews.coach_id', '=', 'users.id')
                    ->groupBy('users.id')
                    ->orderBy('avg_rating', 'DESC')
                    ->paginate(10);
    }

    public function getCoaches($coaching_method = '', $inputs = [])
    {
        $search_input = isset($inputs['search_consult'])?$inputs['search_consult']:'';
        $category_id = isset($inputs['category_id'])?$inputs['category_id']:'';
        $price_range = isset($inputs['price_range'])?$inputs['price_range']:'';
        $coach_community = isset($inputs['coach_community'])?$inputs['coach_community']:'';
        // Check for availability filter
        $avail_date = $week = $start_time = $end_time = '';
        if(isset($inputs['availability_date']) && isset($inputs['availability_start_time']) && isset($inputs['availability_end_time'])) {
            $avail_date = $inputs['availability_date'];
            $start_time = date("H:i:s", strtotime($inputs['availability_start_time']));
            $end_time   = date("H:i:s", strtotime($inputs['availability_end_time']));
            $week       =  date('w', strtotime($avail_date));
        }
        // Check for availability filter end
        $place = isset($inputs['place'])?$inputs['place']:'';
        $distance = isset($inputs['distance'])?$inputs['distance']:'10';
        $coordinates['latitude'] = isset($inputs['latitude'])?$inputs['latitude']:'';
        $coordinates['longitude'] = isset($inputs['longitude'])?$inputs['longitude']:'';
        $sorting = isset($inputs['sorting'])?$inputs['sorting']:'';
        if ($coordinates['latitude'] == '')
            $coordinates = '';
        if($price_range !== '')
            $price_range = explode(',',$price_range);

        return $this->model
                    ->where('status', 'active')
                    ->when($coordinates, function($q) use($coordinates, $distance){
                        $haversine = "(6371 * acos(cos(radians(" . $coordinates['latitude'] . ")) 
                        * cos(radians(`latitude`)) 
                        * cos(radians(`longitude`) 
                        - radians(" . $coordinates['longitude'] . ")) 
                        + sin(radians(" . $coordinates['latitude'] . ")) 
                        * sin(radians(`latitude`))))";

                        return $this ->select('*')
                                     ->selectRaw("{$haversine} AS distance")
                                     ->whereRaw("{$haversine} < ?", [$distance]);
                    })
                    ->when($search_input, function($q) use ($search_input){
                        $q->where(function($query) use ($search_input){
                            $query->where('description', 'LIKE', '%' . $search_input . '%');
                            $query->orwhere('first_name', 'LIKE', '%' . $search_input . '%');
                            $query->orwhere('last_name', 'LIKE', '%' . $search_input . '%');
                            $query->orwhere('language', 'LIKE', '%' . $search_input . '%');
                        });
                    })
                    ->when($coaching_method, function($q) use ($coaching_method){
						$q->whereIn('coaching_method', $coaching_method);
                    })
                    ->when($price_range, function($q) use ($price_range){
                        $q->whereBetween('price_per_hour', [$price_range[0],$price_range[1]]);
                    })
                    ->when($coach_community, function($query) use ($coach_community){
                        $query->where('community', 'LIKE', '%' . $coach_community . '%');
                    })
                    // Filter by availability
                    ->when($avail_date, function($q) use ($avail_date, $week, $start_time, $end_time) {
                        $q->whereHas('availabilites', function($query) use ($avail_date, $week, $start_time, $end_time) {
                            $query->whereRaw("status='available' 
                                    AND 
                                    (
                                        (recurring='single' and date_on='".$avail_date."') 
                                        OR 
                                        (
                                            (recurring='daily' OR (recurring='weekly' AND recurring_week_days IN  ('".$week."'))) 
                                            AND 
                                            (
                                                (recurring_end IS NULL AND date_on<='".$avail_date."')
                                                OR
                                                (recurring_end is NOT NULL AND '".$avail_date."' BETWEEN date_on AND recurring_end)
                                            )
                                        )
                                    ) 
                                    AND 
                                    ('".$start_time."' BETWEEN time_from AND time_to) AND ('".$end_time."' BETWEEN time_from AND time_to)");
                        });
                    })
                    ->whereHas("roles", function($query) {
                        $query->where("name", 'coach');
                    })
                    ->when($category_id, function($q) use ($category_id){
                        $q->whereHas("categories", function($query) use ($category_id){
                            $query->where("category_id", $category_id);
                         });
                    })
                    ->when($sorting, function($q) use ($sorting){
                        $q->orderBy('price_per_hour', $sorting);
                    })
                    ->orderBy('price_per_hour', 'asc')
                    ->with('categories:id,title,icon', 'companies', 'reviews')
                    ->paginate(10);
    }

    public function getMaxPrice()
    {   
        return $this->model->max('price_per_hour');
    }

	public function updateStatus($id, $status)
	{
        $user    =  $this->model->find($id);
                
		$updated =  $this->model
    					->where('id', $id)
    					->update(['status' => $status]);
        if($updated) {
            /** Create activity log */
            if($status=='inactive') {
                $activity_type = 'user_blocked';
                $activity_msg  = 'user account blocked';
            } else if($user->status=='inactive') {
                $activity_type = 'user_unblocked';
                $activity_msg  = 'user account unblocked';
            } else {
                return true;
            }
            $activity = array(
                            'activity_type' => $activity_type,
                            'activity'      => $activity_msg,
                            'type'          => 'user',
                            'activitable_id'=> $id
                        );
            $this->setActivity($activity);
            /** Create activity log End */
            return true;
        }else {
            return false;
        }
	}

    public function fieldUniqueCheck($field, $value)
    {
        $guest_id = Role::where('name', 'guest')->first()->id;
        return $this->model->where($field, $value)->where('role_id', '!=', $guest_id)->get();
    }

	public function changeUserPassword($user_id,$new_password)
	{
		return $this->model->where('id',$user_id)
					->update(['password' => $new_password]);
    }
    
    public function getCoachLocations()
    {
        return $this->model->whereHas("roles", function($query) {
                    $query->where("name", 'coach');
                })
                ->where('status', 'active')
                ->with(['companies:id,name', 'categories:id'])->get();
    }

    public function getByMangoId($mango_user_id)
    {
        return $this->model->where('mango_user_id', $mango_user_id)->first();
    }

    public function getPendingKYCs($limit=0)
    {
        return $this->model->whereNotIn('status', ['inactive', 'incomplete'])
                            ->where('kyc_status', 'pending')
                            ->whereNotNull('id_doc')
                            ->where(function($q) {
                                $q->where(function($query) {
                                        $query->where('person_type', 'business')
                                            ->whereNotNull('ustid_doc')
                                            ->whereNotNull('commercial_doc');
                                    })
                                    ->orWhere(function($query) {
                                        $query->where('person_type', 'soletrader')
                                            ->where('is_commercial', 1)
                                            ->whereNotNull('ustid_doc');
                                    })
                                    ->orWhere(function($query) {
                                        $query->where('person_type', 'soletrader')
                                            ->where('is_commercial', 0);
                                    });
                            })
                            ->when($limit>0, function($q) use ($limit) {
                                $q->take($limit);
                            })
                            ->get();
    }

    public function getPendingUbos($limit=0)
    {
        return $this->model
                    ->whereNotIn('status', ['inactive', 'incomplete', 'kyc pending'])
                    ->where('person_type', 'business')
                    ->where('kyc_status', 'validated')
                    ->where('ubo_status', 'pending')
                    ->with('shareholders')
                    ->when($limit>0, function($q) use ($limit) {
                        $q->take($limit);
                    })
                    ->get();
    }

    public function getByUBODeclarationId($ubo_declaration_id)
    {
        return $this->model
                    ->where('ubo_message', 'like', '%'.$ubo_declaration_id.'%')
                    ->first();
    }

}