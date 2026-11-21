<?php

namespace App\Http\Controllers;

use App\Models\AoModel;
use App\Models\CommercialArtist;
use App\Models\TimeLog;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArtistController extends Controller
{
    //
    public function gdArtistPage(){
    
     // Use leftJoin so the artwork is still found even if no artist is assigned yet
        // $myArtwork = AoModel::where('ao_file_links.item_code', $id)
        // ->leftJoin('commercial_artist', 'commercial_artist.item_code', '=', 'ao_file_links.item_code')
        // ->leftJoin('users', 'users.emp_id', '=', 'commercial_artist.user_id')
        // ->select(
        //     'ao_file_links.*', 
        //     'users.name as artist_name', 
        //     'users.email as artist_email', 
        //     'users.emp_id as artist_emp_id',
        //     'users.role_id as artist_role_id'
        // )
        // ->first();


        $myArtwork = CommercialArtist::leftJoin('ao_file_links', 'ao_file_links.item_code', '=', 'commercial_artist.item_code')
            ->leftJoin('users', 'users.emp_id', '=', 'commercial_artist.user_id')
            ->select(
                'commercial_artist.*', // It is highly recommended to select the main table columns too
                'ao_file_links.*',
                'users.name as artist_name',
                'users.email as artist_email',
                'users.emp_id as artist_emp_id',
                'users.role_id as artist_role_id'
            )
            ->get();
          
          return view('gd.commercial_artist', [
            'artwork' => $myArtwork,
        ]);

    }

    public function getAllMyArtworkList()
{
    try {
        // Fetch AoModel records via your helper function
        // $fileRecieved = geFileRecievedLinkById() 
        //     ->orderBy('created_at', 'desc')
        //     ->get();

        $myArtwork = CommercialArtist::leftJoin('ao_file_links', 'ao_file_links.item_code', '=', 'commercial_artist.item_code')
            ->leftJoin('users', 'users.emp_id', '=', 'commercial_artist.user_id')
            ->where('commercial_artist.user_id',Auth::user()->emp_id)
            ->where('users.role_id',4)
            ->where('commercial_artist.status','!=','Reassigned')
            ->select(
                'commercial_artist.*', // It is highly recommended to select the main table columns too
                // 'ao_file_links.*',
                'ao_file_links.file_link',
                'ao_file_links.item_code',
                'ao_file_links.ao_name',
                'ao_file_links.file_info',
                'ao_file_links.client_name',
                'ao_file_links.status',
                'users.name as artist_name',
                'users.email as artist_email',
                'users.emp_id as artist_emp_id',
                'users.role_id as artist_role_id'
            )
            // ->where('users.emp_id','commercial_artist.user_id')
            
            ->get();
        
            return datatables()->of($myArtwork)
           
                ->addColumn('item_code', function($data) {
                    return strtoupper($data->item_code);
                })
                ->addColumn('client_name', function($data) {
                    return strtoupper($data->client_name);
                })
                ->addColumn('ao_name', function($data) {
                    return $data->ao_name;
                })  
                ->addColumn('file_link', function($data) {
                    return $data->file_link;
                })    
                ->addColumn('item_description', function($data) {
                    return $data->file_info;
                })   
                ->addColumn('status', function($data) {
                    return strtoupper($data->status);
                })   
                // ->addColumn('created_at', function($data) {
                //     return strtoupper($data->created_at);
                // })
                ->make(true);
        } catch(Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

        public function getAllMyArtworkCompanion()
{
    try {
        // Fetch AoModel records via your helper function
        // $fileRecieved = geFileRecievedLinkById() 
        //     ->orderBy('created_at', 'desc')
        //     ->get();

      $companionArtwork = CommercialArtist::join('companion', 'companion.item_code', '=', 'commercial_artist.item_code')
    ->leftJoin('ao_file_links', 'ao_file_links.item_code', '=', 'commercial_artist.item_code')
    ->leftJoin('users as lead_artist', 'lead_artist.emp_id', '=', 'commercial_artist.user_id')
    ->where('companion.user_id', Auth::user()->emp_id)
    // ->where('companion.status', '!=', 'Reassigned') // optional: remove if you want all statuses
    // ->where('commercial_artist.status', '!=', 'Reassigned')
    ->select(
        'commercial_artist.*',
        'ao_file_links.file_link',
        'ao_file_links.item_code as ao_item_code',
        'ao_file_links.ao_name',
        'ao_file_links.file_info',
        'ao_file_links.client_name',
        'ao_file_links.status as ao_status',
        'lead_artist.name as lead_artist_name',
        'lead_artist.email as lead_artist_email',
        'lead_artist.emp_id as lead_artist_emp_id',
        'companion.id as companion_id',
        'companion.item_code as companion_item_code',
        'companion.rfa_reference as companion_rfa_reference',
        'companion.user_id as companion_user_id',
        'companion.status as companion_status',
        'companion.added_by as companion_added_by'
    )
            // ->where('users.emp_id','commercial_artist.user_id')
            
            ->get();
        
            return datatables()->of($companionArtwork)
           
                ->addColumn('item_code', function($data) {
                    return strtoupper($data->item_code);
                })
                ->addColumn('client_name', function($data) {
                    return strtoupper($data->client_name);
                })
                ->addColumn('ao_name', function($data) {
                    return $data->ao_name;
                })  
                ->addColumn('file_link', function($data) {
                    return $data->file_link;
                })    
                ->addColumn('item_description', function($data) {
                    return $data->file_info;
                })   
                ->addColumn('status', function($data) {
                    return strtoupper($data->status);
                })   
                // ->addColumn('created_at', function($data) {
                //     return strtoupper($data->created_at);
                // })
                ->make(true);
        } catch(Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function getAllArtist()
    {
        $artistLists = User::where('role_id', 4)->get();

        return view('partials.modal.gd.assign_to_artist', compact('artistLists'));
    }

//     public function getViewArtworkById($id = null)
//     {
//     // 1. Check if the ID parameter is missing or null
//     if (empty($id)) {
//         return redirect()->back()->with('error', 'Invalid artwork ID provided.');
//     }

//     // Use leftJoin so the artwork is still found even if no artist is assigned yet
//     $artwork = AoModel::where('ao_file_links.item_code', $id)
//         ->leftJoin('commercial_artist', 'commercial_artist.item_code', '=', 'ao_file_links.item_code')
//         ->leftJoin('users', 'users.emp_id', '=', 'commercial_artist.user_id')
//         ->select(
//             'ao_file_links.*', 
//             'users.name as artist_name', 
//             'users.email as artist_email', 
//             'users.emp_id as artist_emp_id',
//             'users.role_id as artist_role_id'
//         )
//         ->first();

//     // 2. Check if the artwork itself does not exist
//     if (!$artwork) {
//         return redirect()->back()->with('error', 'Artwork not found.');
//     }

//     // 3. Check if the artwork exists but has no artist assigned yet
//     if (empty($artwork->artist_emp_id)) {
//         return redirect()->back()->with('error', 'No artist assigned to this artwork yet!');
//     }

//     return view('gd.commercial_view', [
//         'artwork' => $artwork,
//     ]);
// }

public function getViewArtworkById($id = null)
{
    // 1. Check if the ID parameter is missing or null
    if (empty($id)) {
        return redirect()->back()->with('error', 'Invalid artwork ID provided.');
    }

    // Join ONLY the currently active artist row (unassigned_at IS NULL)
    $artwork = AoModel::where('ao_file_links.item_code', $id)
        ->leftJoin('commercial_artist', function ($join) {
            $join->on('commercial_artist.item_code', '=', 'ao_file_links.item_code')
                 ->whereNull('commercial_artist.unassigned_at');
        })
        ->leftJoin('users', 'users.emp_id', '=', 'commercial_artist.user_id')
        ->select(
            'ao_file_links.*',
            'commercial_artist.assigned_at',
            'commercial_artist.status as assignment_status',
            'users.name as artist_name',
            'users.email as artist_email',
            'users.emp_id as artist_emp_id',
            'users.role_id as artist_role_id'
        )
        ->orderBy('commercial_artist.id', 'desc')
        ->first();

    // 2. Check if the artwork itself does not exist
    if (!$artwork) {
        return redirect()->back()->with('error', 'Artwork not found.');
    }

    // 3. Check if the artwork exists but has no artist assigned yet
    if (empty($artwork->artist_emp_id)) {
        return redirect()->back()->with('error', 'No artist assigned to this artwork yet!');
    }

    return view('gd.commercial_view', [
        'artwork' => $artwork,
    ]);
}
// public function getShareTimeDuration(Request $request)
// {
//     $artwork_id = $request->id;

//     $userTimeConsumption = TimeLog::query()
//         ->select('sub.user_id', 'users.name as user_name', 'users.role_id as role') 
//         // 🟢 Return raw cumulative milliseconds
//         ->selectRaw("
//             SUM(
//                 CASE WHEN action IN ('start', 'resume') THEN DATEDIFF_BIG(ms, recorded_at, next_recorded_at) ELSE 0 END
//             ) as time_spent_milliseconds
//         ")
//         ->selectRaw("
//             ROUND(
//                 (CAST(SUM(CASE WHEN action IN ('start', 'resume') THEN DATEDIFF_BIG(ms, recorded_at, next_recorded_at) ELSE 0 END) AS FLOAT) / 
//                 NULLIF(SUM(SUM(CASE WHEN action IN ('start', 'resume') THEN DATEDIFF_BIG(ms, recorded_at, next_recorded_at) ELSE 0 END)) OVER(), 0)) * 100, 
//                 2
//             ) as duration_share_percentage
//         ")
//         ->fromSub(function ($query) use ($artwork_id) {
//             $query->from((new TimeLog)->getTable())
//                 ->select('user_id', 'action', 'recorded_at', 'ao_file_link_id')
//                 ->selectRaw("LEAD(recorded_at) OVER (PARTITION BY ao_file_link_id ORDER BY recorded_at) as next_recorded_at")
//                 ->where('item_code', $artwork_id);
//         }, 'sub')
//         ->leftJoin('users', 'sub.user_id', '=', 'users.id') 
//         ->groupBy('sub.user_id', 'users.name', 'users.role_id')
//         ->get();

//     return response()->json($userTimeConsumption);
// }


public function getShareTimeDuration(Request $request)
{
    $artwork_id = $request->id;
    $timeLogTable = (new TimeLog)->getTable();

    // 1. Collect distinct users.id across time_logs, commercial_artist, and companion
    $participantIds = DB::query()
        ->fromSub(function ($query) use ($artwork_id, $timeLogTable) {
            // Users from time_logs (already stores users.id)
            $query->from($timeLogTable)
                ->select('user_id as uid')
                ->where('item_code', $artwork_id)
                ->whereNotNull('user_id')
                // Main artists (convert emp_id -> users.id)
                ->union(
                    DB::table('commercial_artist')
                        ->join('users', 'users.emp_id', '=', 'commercial_artist.user_id')
                        ->select('users.id as uid')
                        ->where('commercial_artist.item_code', $artwork_id)
                )
                // Companions (convert emp_id -> users.id)
                ->union(
                    DB::table('companion')
                        ->join('users', 'users.emp_id', '=', 'companion.user_id')
                        ->select('users.id as uid')
                        ->where('companion.item_code', $artwork_id)
                );
        }, 'u')
        ->distinct()
        ->pluck('uid');

    // 2. Query users once by primary key and join their time logs
    $userTimeConsumption = DB::table('users')
        ->whereIn('users.id', $participantIds)
        ->leftJoinSub(function ($query) use ($artwork_id, $timeLogTable) {
            $query->from($timeLogTable)
                ->select('user_id', 'action', 'recorded_at')
                ->selectRaw("LEAD(recorded_at) OVER (PARTITION BY ao_file_link_id ORDER BY recorded_at) as next_recorded_at")
                ->where('item_code', $artwork_id);
        }, 'sub', 'sub.user_id', '=', 'users.id')
        ->select(
            'users.id as user_id',
            'users.name as user_name',
            'users.role_id as role'
        )
        ->selectRaw("
            COALESCE(SUM(
                CASE 
                    WHEN sub.action IN ('start', 'resume') 
                    THEN DATEDIFF_BIG(ms, sub.recorded_at, COALESCE(sub.next_recorded_at, GETDATE())) 
                    ELSE 0 
                END
            ), 0) as time_spent_milliseconds
        ")
        ->selectRaw("
            COALESCE(ROUND(
                (CAST(SUM(
                    CASE 
                        WHEN sub.action IN ('start', 'resume') 
                        THEN DATEDIFF_BIG(ms, sub.recorded_at, COALESCE(sub.next_recorded_at, GETDATE())) 
                        ELSE 0 
                    END
                ) AS FLOAT) / 
                NULLIF(SUM(SUM(
                    CASE 
                        WHEN sub.action IN ('start', 'resume') 
                        THEN DATEDIFF_BIG(ms, sub.recorded_at, COALESCE(sub.next_recorded_at, GETDATE())) 
                        ELSE 0 
                    END
                )) OVER(), 0)) * 100, 
                2
            ), 0) as duration_share_percentage
        ")
        ->groupBy('users.id', 'users.name', 'users.role_id')
        ->get();

    return response()->json($userTimeConsumption);
}

public function getShareTimeDurationCompanion(Request $request)
{
    $artwork_id = $request->id;
    $timeLogTable = (new TimeLog)->getTable();

    // 1. Collect distinct users.id across time_logs, commercial_artist, and companion
    $participantIds = DB::query()
        ->fromSub(function ($query) use ($artwork_id, $timeLogTable) {
            // Users from time_logs (already stores users.id)
            $query->from($timeLogTable)
                ->select('user_id as uid')
                ->where('item_code', $artwork_id)
                ->whereNotNull('user_id')
                // Main artists (convert emp_id -> users.id)
                ->union(
                    DB::table('companion')
                        ->join('users', 'users.emp_id', '=', 'companion.user_id')
                        ->select('users.id as uid')
                        ->where('companion.item_code', $artwork_id)
                )
                // Companions (convert emp_id -> users.id)
                ->union(
                    DB::table('companion')
                        ->join('users', 'users.emp_id', '=', 'companion.user_id')
                        ->select('users.id as uid')
                        ->where('companion.item_code', $artwork_id)
                );
        }, 'u')
        ->distinct()
        ->pluck('uid');

    // 2. Query users once by primary key and join their time logs
    $userTimeConsumption = DB::table('users')
        ->whereIn('users.id', $participantIds)
        ->leftJoinSub(function ($query) use ($artwork_id, $timeLogTable) {
            $query->from($timeLogTable)
                ->select('user_id', 'action', 'recorded_at')
                ->selectRaw("LEAD(recorded_at) OVER (PARTITION BY ao_file_link_id ORDER BY recorded_at) as next_recorded_at")
                ->where('item_code', $artwork_id);
        }, 'sub', 'sub.user_id', '=', 'users.id')
        ->select(
            'users.id as user_id',
            'users.name as user_name',
            'users.role_id as role'
        )
        ->selectRaw("
            COALESCE(SUM(
                CASE 
                    WHEN sub.action IN ('start', 'resume') 
                    THEN DATEDIFF_BIG(ms, sub.recorded_at, COALESCE(sub.next_recorded_at, GETDATE())) 
                    ELSE 0 
                END
            ), 0) as time_spent_milliseconds
        ")
        ->selectRaw("
            COALESCE(ROUND(
                (CAST(SUM(
                    CASE 
                        WHEN sub.action IN ('start', 'resume') 
                        THEN DATEDIFF_BIG(ms, sub.recorded_at, COALESCE(sub.next_recorded_at, GETDATE())) 
                        ELSE 0 
                    END
                ) AS FLOAT) / 
                NULLIF(SUM(SUM(
                    CASE 
                        WHEN sub.action IN ('start', 'resume') 
                        THEN DATEDIFF_BIG(ms, sub.recorded_at, COALESCE(sub.next_recorded_at, GETDATE())) 
                        ELSE 0 
                    END
                )) OVER(), 0)) * 100, 
                2
            ), 0) as duration_share_percentage
        ")
        ->groupBy('users.id', 'users.name', 'users.role_id')
        ->get();

    return response()->json($userTimeConsumption);
}


// public function toggleTimer(Request $request, $item_code)
// {
    
//     // $artwork = AoModel::where('item_code', $item_code)->firstOrFail();
    
//     $artwork = AoModel::query()
//         ->select([
//             'ao_file_links.id',        // Only select columns you actually need
//             'ao_file_links.item_code', // instead of ao_file_links.*
       
//             'commercial_artist.user_id as artist_emp_id'
//         ])
//         ->leftJoin('commercial_artist', 'commercial_artist.item_code', '=', 'ao_file_links.item_code')
//         ->where('ao_file_links.item_code', $item_code) 
//         ->first();


//     $action = $request->input('action'); 
//     $now = Carbon::now();

//     // 💡 BLOCK ATTEMPTS OUTSIDE WORKING HOURS
//     if (in_array($action, ['start', 'resume']) && $artwork->isCompanyOffTime()) {
//         return response()->json([
//             'status' => 'error',
//             'message' => 'Tracking is locked. Operational hours are Monday to Friday, 8:00 AM - 6:00 PM.'
//         ], 403);
//     }

//     // Handle incoming actions normally...
//     TimeLog::create([
//         'ao_file_link_id' => $artwork->id,
//         'item_code' => $artwork->item_code, 
        
//         // 💡 FIX: Change from emp_id to the actual primary key id column to satisfy the foreign key constraint
//         'user_id' => $artwork->artist_emp_id, 
        
//         'action' => $action,
//         'recorded_at' => $now,
//     ]);

//     $isRunning = in_array($action, ['start', 'resume']);

//     return response()->json([
//         'status' => 'success',
//         'is_running' => $isRunning, 
//         'started_at' => $isRunning ? $now->toIso8601String() : null,
//         'total_seconds' => $artwork->total_seconds 
//     ]);
// }

// public function toggleTimer(Request $request, $item_code)
// {
//     $artwork = AoModel::where('item_code', $item_code)->firstOrFail();

//     $action = $request->input('action');
//     $now = Carbon::now();

//     // Block attempts outside working hours
//     if (in_array($action, ['start', 'resume']) && $artwork->isCompanyOffTime()) {
//         return response()->json([
//             'status'  => 'error',
//             'message' => 'Tracking is locked. Operational hours are Monday to Friday, 8:00 AM - 6:00 PM.'
//         ], 403);
//     }

//     $logUserId = auth()->id();

//     if (!$logUserId) {
//         return response()->json([
//             'status'  => 'error',
//             'message' => 'User is not authenticated.'
//         ], 422);
//     }

//     TimeLog::create([
//         'ao_file_link_id' => $artwork->id,
//         'item_code'       => $artwork->item_code,
//         'user_id'         => $logUserId, // Always logs the actual logged-in user (e.g., Vegeta)
//         'action'          => $action,
//         'recorded_at'     => $now,
//     ]);

//     $isRunning = in_array($action, ['start', 'resume']);

//     return response()->json([
//         'status'        => 'success',
//         'is_running'    => $isRunning,
//         'started_at'    => $isRunning ? $now->toIso8601String() : null,
//         'total_seconds' => $artwork->total_seconds,
//     ]);
// }

// app/Http/Controllers/ArtistController.php

public function toggleTimer(Request $request, $item_code)
{
    $artwork = AoModel::where('item_code', $item_code)->firstOrFail();

    $action = $request->input('action'); // 'start', 'pause', 'resume', 'finish'
    $actionTaken = $request->input('action_taken')
        ?? $artwork->timeLogs()->latest('recorded_at')->value('action_taken');// 'Re-touching', 'Revision', 'Redesign', 'For Profreading'
  
        $now = Carbon::now();

    // Block attempts outside working hours
    if (in_array($action, ['start', 'resume']) && $artwork->isCompanyOffTime()) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Tracking is locked. Operational hours are Monday to Friday, 8:00 AM - 6:00 PM.'
        ], 403);
    }

    $logUserId = auth()->id();
    $userName  = auth()->user()?->name ?? 'Artist';

    if (!$logUserId) {
        return response()->json([
            'status'  => 'error',
            'message' => 'User is not authenticated.'
        ], 422);
    }

    // 1. Create TimeLog (existing logic)
    TimeLog::create([
        'ao_file_link_id' => $artwork->id,
        'item_code'       => $artwork->item_code,
        'user_id'         => $logUserId,
        'action'          => $action,
        'action_taken'    => $actionTaken,
        'recorded_at'     => $now,
    ]);

    // 2. Create ActivityLog entry so it appears in the Artwork timeline
    $actionLabels = [
        'start'  => "{$userName} started working on item [{$item_code}].",
        'pause'  => "{$userName} paused the timer.",
        'resume' => "{$userName} resumed working on item [{$item_code}].",
        'finish' => "{$userName} finished the task.",
    ];

    $artwork->activityLogs()->create([
        'user_id'     => $logUserId,
        'log_type'    => 'history',
        'event'       => "timer_{$action}",
        'description' => $actionLabels[$action] ?? "{$userName} triggered {$action}.",
        'properties'  => [
            'item_code'   => $item_code,
            'action'      => $action,
            'recorded_at' => $now->toDateTimeString(),
        ],
    ]);

    $isRunning = in_array($action, ['start', 'resume']);

    return response()->json([
        'status'        => 'success',
        'is_running'    => $isRunning,
        'started_at'    => $isRunning ? $now->toIso8601String() : null,
        'total_seconds' => $artwork->total_seconds,
    ]);
}

/**
 * Fetch combined assignment & timer activity logs for an item_code
 */
// public function getActivityLogs(Request $request)
// {
//     $itemCode = $request->id; // Matches how getShareTimeDuration receives $request->id
//     $artwork = AoModel::where('item_code', $itemCode)->firstOrFail();

//     $logs = $artwork->activityLogs()
//         ->with('user:id,name')
//         ->orderBy('created_at', 'desc')
//         ->get()
//         ->map(function ($log) {
//             return [
//                 'id'          => $log->id,
//                 'event'       => $log->event,
//                 'description' => $log->description,
//                 'actor_name'  => $log->user->name ?? 'System',
//                 'properties'  => $log->properties,
//                 'time'        => $log->created_at->format('h:i:s A'),
//                 'date'        => $log->created_at->format('M d, Y'),
//             ];
//         });

//     return response()->json($logs);
// }

// app/Http/Controllers/ArtistController.php

// public function getActivityLogs(Request $request)
// {
//     $itemCode = $request->id;
//     $artwork = AoModel::where('item_code', $itemCode)->first();

//     if (!$artwork) {
//         return response()->json([]);
//     }

//     $logs = $artwork->activityLogs()
//         ->reorder() // Clears any default ORDER BY defined on the AoModel relationship
//         ->leftJoin('users', 'users.id', '=', 'activity_logs.user_id')
//         ->select(
//             'activity_logs.id',
//             'activity_logs.event',
//             'activity_logs.description',
//             'activity_logs.properties',
//             'activity_logs.created_at',
//             'users.name as actor_name'
//         )
//         ->orderBy('activity_logs.created_at', 'desc')
//         ->get()
//         ->map(function ($log) {
//             return [
//                 'id'          => $log->id,
//                 'event'       => $log->event,
//                 'description' => $log->description,
//                 'actor_name'  => $log->actor_name ?? 'System',
//                 'properties'  => $log->properties,
//                 'time'        => \Carbon\Carbon::parse($log->created_at)->format('h:i:s A'),
//                 'date'        => \Carbon\Carbon::parse($log->created_at)->format('M d, Y'),
//             ];
//         });

//     return response()->json($logs);
// }

// app/Http/Controllers/ArtistController.php

// public function getActivityLogs(Request $request)
// {
//     $itemCode = $request->id;
//     $artwork = AoModel::where('item_code', $itemCode)->first();

//     if (!$artwork) {
//         return response()->json([]);
//     }

//     // 1. Start query for this specific artwork only
//     $query = $artwork->activityLogs()
//         ->reorder()
//         ->leftJoin('users', 'users.id', '=', 'activity_logs.user_id')
//         ->select(
//             'activity_logs.id',
//             'activity_logs.event',
//             'activity_logs.description',
//             'activity_logs.properties',
//             'activity_logs.created_at',
//             'users.name as actor_name'
//         );

//     // 2. Filter: ONLY show artist timer actions, reassignments, or added companions
//     $query->where(function ($q) {
//         // Match by event name (if your logs save event names)
//         $q->whereIn('activity_logs.event', [
//             'timer_start',
//             'timer_pause',
//             'timer_resume',
//             'timer_finish',
//             'artist_reassigned',
//             'companion_added',
//         ])
//         // OR match by keywords in the description text
//         ->orWhere('activity_logs.description', 'like', '%work tracking%')
//         ->orWhere('activity_logs.description', 'like', '%active work session%')
//         ->orWhere('activity_logs.description', 'like', '%finished%')
//         ->orWhere('activity_logs.description', 'like', '%reassign%')
//         ->orWhere('activity_logs.description', 'like', '%companion%');
//     });

//     // 3. Get newest logs first and format for the timeline UI
//     $logs = $query->orderBy('activity_logs.created_at', 'desc')
//         ->get()
//         ->map(function ($log) {
//             return [
//                 'id'          => $log->id,
//                 'event'       => $log->event,
//                 'description' => $log->description,
//                 'action_taken' => $log->action_taken,      // <-- Sends 'Re-touching', 'Revision', etc.
//                 'actor_name'  => $log->actor_name ?? 'System',
//                 'properties'  => $log->properties,
//                 'time'        => \Carbon\Carbon::parse($log->created_at)->format('h:i:s A'),
//                 'date'        => \Carbon\Carbon::parse($log->created_at)->format('M d, Y'),
//             ];
//         });

//     return response()->json($logs);
// }


public function getActivityLogs(Request $request)
{
    $itemCode = $request->id;
    $artwork = AoModel::where('item_code', $itemCode)->first();

    if (!$artwork) {
        return response()->json([]);
    }

    // 1. Start query for this specific artwork only
    $query = $artwork->activityLogs()
        ->reorder()
        ->leftJoin('users', 'users.id', '=', 'activity_logs.user_id')
        ->select(
            'activity_logs.id',
            'activity_logs.event',
            'activity_logs.description',
            'activity_logs.properties',
            'activity_logs.created_at',
            'users.name as actor_name'
        );

    // 2. Filter: ONLY show artist timer actions, reassignments, or added companions
 $query
    // ->where('log_type', '!=', 'history')
    ->where(function ($q) {
        $q->whereIn('activity_logs.event', [
            'timer_start',
            'timer_pause',
            'timer_resume',
            'timer_finish',
            'artist_reassigned',
            'companion_added',
        ])
        ->orWhere('activity_logs.description', 'like', '%work tracking%')
        ->orWhere('activity_logs.description', 'like', '%active work session%')
        ->orWhere('activity_logs.description', 'like', '%finished%')
        ->orWhere('activity_logs.description', 'like', '%reassign%')
        ->orWhere('activity_logs.description', 'like', '%companion%');
    });


    // Get time_logs for this item to grab action_taken
    $timeLogs = TimeLog::where('item_code', $itemCode)->get();

    // 3. Get newest logs first and format for the timeline UI
    $logs = $query->orderBy('activity_logs.created_at', 'desc')
        ->get()
        ->map(function ($log) use ($timeLogs) {
            $props = is_string($log->properties) ? json_decode($log->properties, true) : ($log->properties ?? []);

            // Check properties first, otherwise match with time_logs by timestamp
            $actionTaken = $props['action_taken'] ?? null;

            if (!$actionTaken && str_starts_with((string) $log->event, 'timer_')) {
                $action = str_replace('timer_', '', $log->event);
                $matched = $timeLogs->first(function ($t) use ($action, $log) {
                    return $t->action === $action
                        && abs(\Carbon\Carbon::parse($t->recorded_at)->diffInSeconds($log->created_at)) <= 5;
                });
                $actionTaken = $matched->action_taken ?? null;
            }

            return [
                'id'           => $log->id,
                'event'        => $log->event,
                'description'  => $log->description,
                'action_taken' => $actionTaken, // <-- Sends 'Re-touching', 'Revision', etc.
                'actor_name'   => $log->actor_name ?? 'System',
                'properties'   => $log->properties,
                'time'         => \Carbon\Carbon::parse($log->created_at)->format('h:i:s A'),
                'date'         => \Carbon\Carbon::parse($log->created_at)->format('M d, Y'),
            ];
        });

    return response()->json($logs);
}
//check if its holiday or weekend 8am to 6pm 




        
    
}
