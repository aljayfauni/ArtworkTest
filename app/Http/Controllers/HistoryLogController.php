<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class HistoryLogController extends Controller
{
    /**
     * Display a master listing of all background history updates with search filtering.
     */
    public function getHistoryLogs(Request $request)
    {
        // 1. Capture the search parameter string from the request stream
        $search = $request->input('search');

        // 2. Fetch logs matching the 'history' type configuration
        $historyLogs = ActivityLog::where('log_type', 'history')
            ->with(['user', 'loggable']) 
            // 💡 Apply search filters dynamically if the search input box is filled out
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                      // Search via the User relationship (Artist/Supervisor name)
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%");
                      })
                      // Search via the Polymorphic model relationship (Artwork item_code)
                      ->orWhereHas('loggable', function ($loggableQuery) use ($search) {
                          $loggableQuery->where('item_code', 'like', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(15)
            // 💡 Crucial: Appends the search term to page links so moving to page 2 doesn't clear your search!
            ->appends(['search' => $search]); 

        // 3. Send the records and search text down to your Blade template layout
        return view('history_logs', compact('historyLogs', 'search'));
    }
}
