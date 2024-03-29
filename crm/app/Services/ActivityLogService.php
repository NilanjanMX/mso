<?php

namespace App\Services;

use App\Models\ActivityLogs;
use App\Models\User;
use Carbon\Carbon;
use DB;

class ActivityLogService {

    public function activity_logs()
    {
        $now = Carbon::now();
        $user_id = auth()->user()->id;
        // $activity_logs = ActivityLogs::query()
        //                 ->where('main_user_id', $user_id)
        //                 ->latest()
        //                 ->take(3)
        //                 ->get();
        $activity_logs = ActivityLogs::query()
            ->when($user_id, function ($q) use ($user_id) {
                if (auth()->user()->added_by == 0) {
                    $q->where('main_user_id', $user_id);
                } elseif (auth()->user()->role_id == 1) {

                    $group_id = auth()->user()->group_id;
                    if ($group_id) {
                        $userGroup = User::where('group_id', $group_id)->pluck('id')->toArray();
                    }
                    if ($userGroup > 0) {
                        $q->whereIn('user_id', $userGroup);
                    } else {
                        $q->where('user_id', $user_id);
                    }
                } else {
                    $q->where('user_id', $user_id);
                }
            })
            ->latest()
            ->take(3)
            ->get();
        return $activity_logs;
    }

    
}