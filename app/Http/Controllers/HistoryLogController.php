<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\HistoryLog;
use Illuminate\Http\Request;

class HistoryLogController extends Controller
{
    /**
     * Display a master listing of all background history updates with search filtering.
     */


// public function getHistoryLogs(Request $request)
// {
//     // 1. Get what the user typed in the search box
//     $search = $request->input('search');

//     // 2. Start building the database query with user and artwork details
//     $query = ActivityLog::with(['user', 'loggable']);

//     // 3. Only show history logs, started tasks, or finished tasks
//     $query->where(function ($q) {
//         $q->where('log_type', 'history')
//           ->orWhere('description', 'like', '%started work tracking%')
//           ->orWhere('description', 'like', '%finished%');
//     });

//     // 4. If the user typed something in the search box, filter the results
//     if ($search) {
//         $query->where(function ($q) use ($search) {
//             // Search in the description text
//             $q->where('description', 'like', "%{$search}%")
//               // OR search by the User's name
//               ->orWhereHas('user', function ($user) use ($search) {
//                   $user->where('name', 'like', "%{$search}%");
//               })
//               // OR search by the Artwork's item code
//               ->orWhereHas('loggable', function ($artwork) use ($search) {
//                   $artwork->where('item_code', 'like', "%{$search}%");
//               });
//         });
//     }

//     // 5. Sort by newest first and show 15 items per page
//     $historyLogs = $query->latest()
//                          ->paginate(15)
//                          ->appends(['search' => $search]);

//     // 6. Send the data to the Blade page
//     return view('history_logs', compact('historyLogs', 'search'));
// }


public function getHistoryLogs(Request $request)
{
    // 1. Get what the user typed in the search box
    $search = $request->input('search');

    // 2. Query the dedicated HistoryLog table with user and artwork (model) details
    $query = HistoryLog::with(['user', 'model']);

    // 3. Filter results if the user searched for an RFA reference, item code, artist name, or diary note
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('rfa_reference', 'like', "%{$search}%")
              ->orWhere('remarks', 'like', "%{$search}%")
              ->orWhereHas('user', function ($user) use ($search) {
                  $user->where('name', 'like', "%{$search}%");
              })
              ->orWhereHasMorph('model', [\App\Models\AoModel::class], function ($artwork) use ($search) {
                  $artwork->where('item_code', 'like', "%{$search}%");
              });
        });
    }

    // 4. Sort by newest first and show 15 items per page
    $historyLogs = $query->latest()
                         ->paginate(15)
                         ->appends(['search' => $search]);

    // 5. Send the data to the Blade page
    return view('history_logs', compact('historyLogs', 'search'));
}

}
