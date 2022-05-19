<?php

namespace App\Repositories;

use App\Models\ActivityLog;
use App\Helpers\CommonHelper;

class ActivityLogRepository
{
    protected $model;

    function __construct(ActivityLog $activityLog)
    {
        $this->model = $activityLog;
    }

    /**
     * Add new Activity Log for user.
     *
     * @param  Array  $activity
     * @return Boolean
     */
    public function setActivity($activity)
    {
        // Get logged in user
        $user = CommonHelper::checkUserType();
        if($user['data']['id']==0 || $user['type']=='guest'){
            $user['type']       = 'user';
            $user['data']['id'] = $activity['activitable_id'];
        }

        $log = new ActivityLog;
        $log->initiated_by     = $user['type'];
        $log->initiated_by_id  = $user['data']['id'];
        $log->activity_type    = $activity['activity_type'];
        $log->activity         = $activity['activity'];
        $log->activitable_type = $activity['type'];
        $log->activitable_id   = $activity['activitable_id'];
        return $log->save();
    }

}