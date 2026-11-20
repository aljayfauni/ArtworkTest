<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class HistoryLogController extends Controller
{
    /**
     * Display a master listing of all background history updates with search filtering.
     */
    // public function getHistoryLogs(Request $request)
    // {
    //     // 1. Capture the search parameter string from the request stream
    //     $search = $request->input('search');

    //     // 2. Fetch logs matching the 'history' type configuration
    //     $historyLogs = ActivityLog::where('log_type', 'history')
    //         ->with(['user', 'loggable']) 
    //         // 💡 Apply search filters dynamically if the search input box is filled out
    //         ->when($search, function ($query, $search) {
    //             $query->where(function ($q) use ($search) {
    //                 $q->where('description', 'like', "%{$search}%")
    //                   // Search via the User relationship (Artist/Supervisor name)
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
    //         // 💡 Crucial: Appends the search term to page links so moving to page 2 doesn't clear your search!
    //         ->appends(['search' => $search]); 

    //     // 3. Send the records and search text down to your Blade template layout
    //     return view('history_logs', compact('historyLogs', 'search'));
    // }
//     public function getHistoryLogs(Request $request)
// {
//     $search = $request->input('search');

//     // Do NOT put ->where('log_type', 'history') here, because TimeLog's automatic log uses a different log_type
//     $historyLogs = ActivityLog::with(['user', 'loggable'])
//         ->when($search, function ($query, $search) {
//             $query->where(function ($q) use ($search) {
//                 $q->where('description', 'like', "%{$search}%")
//                   ->orWhereHas('user', function ($userQuery) use ($search) {
//                       $userQuery->where('name', 'like', "%{$search}%");
//                   })
//                   ->orWhereHas('loggable', function ($loggableQuery) use ($search) {
//                       $loggableQuery->where('item_code', 'like', "%{$search}%");
//                   });
//             });
//         })
//         ->latest()
//         ->paginate(15)
//         ->appends(['search' => $search]);

//     return view('history_logs', compact('historyLogs', 'search'));
// }

public function getHistoryLogs(Request $request)
{
    // 1. Get what the user typed in the search box
    $search = $request->input('search');

    // 2. Start building the database query with user and artwork details
    $query = ActivityLog::with(['user', 'loggable']);

    // 3. Only show history logs, started tasks, or finished tasks
    $query->where(function ($q) {
        $q->where('log_type', 'history')
          ->orWhere('description', 'like', '%started work tracking%')
          ->orWhere('description', 'like', '%finished%');
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
    $historyLogs = $query->latest()
                         ->paginate(15)
                         ->appends(['search' => $search]);

    // 6. Send the data to the Blade page
    return view('history_logs', compact('historyLogs', 'search'));
}
}
