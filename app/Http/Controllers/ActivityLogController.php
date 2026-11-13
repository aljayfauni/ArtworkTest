<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    //
    public function getActivityLogs(Request $request)
{
    // 1. Capture the search parameter string
    $search = $request->input('search');

    // 2. Fetch ALL logs mixed together chronologically
    $activityLogs = ActivityLog::with(['user', 'loggable'])
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('event', 'like', "%{$search}%")
                  ->orWhere('log_type', 'like', "%{$search}%")
                  // Search via the User relationship
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
        ->appends(['search' => $search]); // Preserves search criteria context across pages

    // 3. Send records and search context down to the unified view
    return view('activity_logs', compact('activityLogs', 'search'));
}
}
