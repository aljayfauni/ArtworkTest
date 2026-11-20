<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    //
//     public function getActivityLogs(Request $request)
// {
//     // 1. Capture the search parameter string
//     $search = $request->input('search');

//     // 2. Fetch ALL logs mixed together chronologically
//     $activityLogs = ActivityLog::with(['user', 'loggable'])
//         ->when($search, function ($query, $search) {
//             $query->where(function ($q) use ($search) {
//                 $q->where('description', 'like', "%{$search}%")
//                   ->orWhere('event', 'like', "%{$search}%")
//                   ->orWhere('log_type', 'like', "%{$search}%")
//                   // Search via the User relationship
//                   ->orWhereHas('user', function ($userQuery) use ($search) {
//                       $userQuery->where('name', 'like', "%{$search}%");
//                   })
//                   // Search via the Polymorphic model relationship (Artwork item_code)
//                   ->orWhereHas('loggable', function ($loggableQuery) use ($search) {
//                       $loggableQuery->where('item_code', 'like', "%{$search}%");
//                   });
//             });
//         })
//         ->latest()
//         ->paginate(15)
//         ->appends(['search' => $search]); // Preserves search criteria context across pages

//     // 3. Send records and search context down to the unified view
//     return view('activity_logs', compact('activityLogs', 'search'));
// }

    public function getActivityLogs(Request $request)
    {
        // 1. Get what the user typed in the search box
        $search = $request->input('search');

        // 2. Start building the database query with user and artwork details
        $query = ActivityLog::with(['user', 'loggable']);

        // 3. Exclude supervisor 'history' logs so this page only shows live/system activity
        // (Also includes rows where log_type is NULL so SQL Server doesn't skip them)
        $query->where(function ($q) {
            $q->where('log_type', '!=', 'history')
            ->orWhereNull('log_type');
        });

        // 4. If the user typed something in the search box, filter the results
        if ($search) {
            $query->where(function ($q) use ($search) {
                // Search in the description text
                $q->where('description', 'like', "%{$search}%")
                // OR search by the User's name
                ->orWhereHas('user', function ($user) use ($search) {
                    $user->where('name', 'like', "%{$search}%");
                })
                // OR search by the Artwork's item code
                ->orWhereHas('loggable', function ($artwork) use ($search) {
                    $artwork->where('item_code', 'like', "%{$search}%");
                });
            });
        }

        // 5. Sort by newest first and show 15 items per page
        $activityLogs = $query->latest()
                            ->paginate(15)
                            ->appends(['search' => $search]);

        // 6. Send the data to the Blade page
        return view('activity_logs', compact('activityLogs', 'search'));
    }
}
