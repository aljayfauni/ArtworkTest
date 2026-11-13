<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Models\AoModel;
use App\Models\ArtworkLog;
use App\Models\ItemList;
use App\Models\TimeLine;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
//     public function index()
//     {

//     $totalUsers = User::count(); 
//     $timeLineList = ItemList::get();
//     // $totalStart = TimeLine::where('status', 'started')->count();
//     $totalAvailable = $totalAvailable = DB::selectOne("
//     SELECT COUNT(*) as count 
//     FROM item_lists 
//     WHERE item_code NOT IN (
//         SELECT item_code 
//         FROM time_lines 
//         WHERE status IS NOT NULL AND status != ''
//     )
// ")->count;


//     $totalStart = DB::select("SELECT COUNT(*) as count FROM time_lines WHERE status = 'started'")[0]->count;// using raw
//     $totalStop = DB::select("SELECT COUNT(*) as count FROM time_lines WHERE status = 'stopped'")[0]->count;// using raw
//     // return view('home')->with('totalUsers', $totalUsers); 
//         return view('home')->with([
//             'totalUsers' => $totalUsers,
//             'totalAvailable' => $totalAvailable,
//             'totalStart' => $totalStart,
//             'totalStop' => $totalStop,

//             'timeLineList' => $timeLineList
//         ]); 
//     }

// public function index()
// {
//     $totalUsers = User::count(); 
    
//     // 1. Fetch available item counts
//     $totalAvailable = DB::selectOne("
//         SELECT COUNT(*) as count 
//         FROM item_lists 
//         WHERE item_code NOT IN (
//             SELECT item_code 
//             FROM time_lines 
//             WHERE status IS NOT NULL AND status != ''
//         )
//     ")->count;

//     // 2. Fetch tracking status counter aggregations
//     $totalStart = DB::selectOne("SELECT COUNT(*) as count FROM time_lines WHERE status = 'started'")->count;
//     $totalStop  = DB::selectOne("SELECT COUNT(*) as count FROM time_lines WHERE status = 'stopped'")->count;

//     // 3. FIX: Join tables together so you get a flat list with item_name available!
//     $timeLineList = DB::table('time_lines')
//         ->leftJoin('item_lists', 'time_lines.item_code', '=', 'item_lists.item_code')
//         ->select(
//             'time_lines.*', 
//             'item_lists.item_name'
//         )
//         ->orderBy('time_lines.item_code')
//         ->orderBy('time_lines.start_time', 'asc')
//         ->get(); // Keeping it as a flat collection avoids the collection property error!

//     return view('home', [
//         'totalUsers'     => $totalUsers,
//         'totalAvailable' => $totalAvailable,
//         'totalStart'     => $totalStart,
//         'totalStop'      => $totalStop,
//         'timeLineList'   => $timeLineList
//     ]); 
// }

public function index(Request $request)
{
    $totalUsers = User::count(); 
    $users= User::whereNotNull('email')->get();

// Fetch both counts in a single database query
    $counts = AoModel::selectRaw("
        COUNT(*) as total_sent,
        COUNT(CASE WHEN status = 'Opened' THEN 1 END) as total_open
    ")->first();

    $totalSentFileLink = $counts->total_sent ?? 0;
    $totalOpenFile     = $counts->total_open ?? 0;
        
        // 1. Fetch available item counts
        $totalAvailable = DB::selectOne("
            SELECT COUNT(*) as count 
            FROM item_lists 
            WHERE item_code NOT IN (
                SELECT item_code 
                FROM time_lines 
                WHERE status IS NOT NULL AND status != ''
            )
        ")->count;

        // 2. Fetch tracking status counter aggregations
        $totalStart = DB::selectOne("SELECT COUNT(*) as count FROM time_lines WHERE status = 'started'")->count;
        $totalStop  = DB::selectOne("SELECT COUNT(*) as count FROM time_lines WHERE status = 'stopped'")->count;

        // Fetch items that are NOT YET inserted into the time_lines table
        $availableItemsForSelect = DB::table('item_lists')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('time_lines')
                    ->whereColumn('time_lines.item_code', 'item_lists.item_code');
            })
            ->select('item_code')
            ->get();

        // 3. JOIN both item_lists AND users tables to get all required metadata
        $timeLineList = DB::table('time_lines')
            ->leftJoin('item_lists', 'time_lines.item_code', '=', 'item_lists.item_code')
            ->leftJoin('users', 'time_lines.user_id', '=', 'users.id') // Joins the user accounts
            ->select(
                'time_lines.*', 
                'item_lists.item_name',
                'users.name as user_name' // Aliased to prevent naming conflicts
            )
            ->orderBy('time_lines.item_code')
            ->orderBy('time_lines.start_time', 'asc')
            ->get(); 

        // return view('dashboard', [
        //     'totalUsers'              => $totalUsers,
        //     'totalAvailable'          => $totalAvailable,
        //     'totalStart'              => $totalStart,
        //     'totalStop'               => $totalStop,
        //     'timeLineList'            => $timeLineList,
        //     'availableItemsForSelect' => $availableItemsForSelect, // Added variable
        //       'users'                   => $users,
        // ]); 

   $user= Auth::user();
// dd($user->role->access);


        // ao 
        $ao_records_links = AoModel::where('ao_user_id', Auth::user()->emp_id)
                    ->orderBy('created_at', 'desc')
                    ->get();
                
        
        // get all artworklogs
        //  $getAtworkLogs =  ArtworkLog::where('user_id',Auth::user()->id);

        $getAtworkLogs = ArtworkLog::with(['user', 'file'])
                ->latest()
                ->take(15)
                ->get();
        $logs = $getAtworkLogs;
        // $unreadCount = ArtworkLog::where('status', NULL)->count(); //

        // Filter the collection based on the nested 'file' relationship status
        $unreadCount = $logs->filter(function ($log) {
            return $log->file && is_null($log->file->status);
        })->count();

        // 3. If AJAX submission, return JSON response with rendered partial HTML
        if ($request->ajax()) {
            // $html = view('partials.artwork_logs', compact('logs', 'unreadCount'))->render();

            return response()->json([
                'success'     => 'RFA Link sent successfully!',
                'html'        => $html,
                'unreadCount' => $unreadCount,
            ]);
        }


// Query to pull all user tables from SQL Server
// Query to find tables containing 'FG' anywhere in their name
// $mo_sql = "
//     SELECT TABLE_NAME 
//     FROM INFORMATION_SCHEMA.TABLES 
//     WHERE TABLE_TYPE = 'BASE TABLE'
//       AND TABLE_NAME LIKE '%FG%'
// ";







        // Pass the records to your blade file
            return view('dashboard', [
            'totalUsers'              => $totalUsers,
            'totalAvailable'          => $totalAvailable,
            'totalStart'              => $totalStart,
            'totalStop'               => $totalStop,
            'timeLineList'            => $timeLineList,
            'availableItemsForSelect' => $availableItemsForSelect, // Added variable
            'users'                   => $users,

            'ao_records_links'        =>$ao_records_links,
            'totalSentFileLink'       => $totalSentFileLink,
            'totalOpenFile'           => $totalOpenFile,
            // 'logs'                    =>$logs, //show logs and activity
            'unreadCount'             =>$unreadCount,
            
        ]); 
}

public function timeline(Request $request)
{
    $totalUsers = User::count(); 
    $users= User::whereNotNull('email')->get();

// Fetch both counts in a single database query
    $counts = AoModel::selectRaw("
        COUNT(*) as total_sent,
        COUNT(CASE WHEN status = 'Opened' THEN 1 END) as total_open
    ")->first();

    $totalSentFileLink = $counts->total_sent ?? 0;
    $totalOpenFile     = $counts->total_open ?? 0;
        
        // 1. Fetch available item counts
        $totalAvailable = DB::selectOne("
            SELECT COUNT(*) as count 
            FROM item_lists 
            WHERE item_code NOT IN (
                SELECT item_code 
                FROM time_lines 
                WHERE status IS NOT NULL AND status != ''
            )
        ")->count;

        // 2. Fetch tracking status counter aggregations
        $totalStart = DB::selectOne("SELECT COUNT(*) as count FROM time_lines WHERE status = 'started'")->count;
        $totalStop  = DB::selectOne("SELECT COUNT(*) as count FROM time_lines WHERE status = 'stopped'")->count;

        // Fetch items that are NOT YET inserted into the time_lines table
        $availableItemsForSelect = DB::table('item_lists')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('time_lines')
                    ->whereColumn('time_lines.item_code', 'item_lists.item_code');
            })
            ->select('item_code')
            ->get();

        // 3. JOIN both item_lists AND users tables to get all required metadata
        $timeLineList = DB::table('time_lines')
            ->leftJoin('item_lists', 'time_lines.item_code', '=', 'item_lists.item_code')
            ->leftJoin('users', 'time_lines.user_id', '=', 'users.id') // Joins the user accounts
            ->select(
                'time_lines.*', 
                'item_lists.item_name',
                'users.name as user_name' // Aliased to prevent naming conflicts
            )
            ->orderBy('time_lines.item_code')
            ->orderBy('time_lines.start_time', 'asc')
            ->get(); 

        // return view('dashboard', [
        //     'totalUsers'              => $totalUsers,
        //     'totalAvailable'          => $totalAvailable,
        //     'totalStart'              => $totalStart,
        //     'totalStop'               => $totalStop,
        //     'timeLineList'            => $timeLineList,
        //     'availableItemsForSelect' => $availableItemsForSelect, // Added variable
        //       'users'                   => $users,
        // ]); 

   $user= Auth::user();
// dd($user->role->access);


        // ao 
        $ao_records_links = AoModel::where('ao_user_id', Auth::user()->emp_id)
                    ->orderBy('created_at', 'desc')
                    ->get();
                
        
        // get all artworklogs
        //  $getAtworkLogs =  ArtworkLog::where('user_id',Auth::user()->id);

        $getAtworkLogs = ArtworkLog::with(['user', 'file'])
                ->latest()
                ->take(15)
                ->get();
        $logs = $getAtworkLogs;
        // $unreadCount = ArtworkLog::where('status', NULL)->count(); //

        // Filter the collection based on the nested 'file' relationship status
        $unreadCount = $logs->filter(function ($log) {
            return $log->file && is_null($log->file->status);
        })->count();

        // 3. If AJAX submission, return JSON response with rendered partial HTML
        if ($request->ajax()) {
            // $html = view('partials.artwork_logs', compact('logs', 'unreadCount'))->render();

            return response()->json([
                'success'     => 'RFA Link sent successfully!',
                'html'        => $html,
                'unreadCount' => $unreadCount,
            ]);
        }


// Query to pull all user tables from SQL Server
// Query to find tables containing 'FG' anywhere in their name
// $mo_sql = "
//     SELECT TABLE_NAME 
//     FROM INFORMATION_SCHEMA.TABLES 
//     WHERE TABLE_TYPE = 'BASE TABLE'
//       AND TABLE_NAME LIKE '%FG%'
// ";







        // Pass the records to your blade file
            return view('dashboard', [
            'totalUsers'              => $totalUsers,
            'totalAvailable'          => $totalAvailable,
            'totalStart'              => $totalStart,
            'totalStop'               => $totalStop,
            'timeLineList'            => $timeLineList,
            'availableItemsForSelect' => $availableItemsForSelect, // Added variable
            'users'                   => $users,

            'ao_records_links'        =>$ao_records_links,
            'totalSentFileLink'       => $totalSentFileLink,
            'totalOpenFile'           => $totalOpenFile,
            // 'logs'                    =>$logs, //show logs and activity
            'unreadCount'             =>$unreadCount,
            
        ]); 
}
public function searchFACTItem(Request $request, $item_code = null)
    {
        $searchTerm = $item_code ?? $request->input('q');

        // $sql = "
        //     SELECT TOP 10
        //         TRIM(p.PRODCODE) as id,
        //         CONCAT(
        //             TRIM(p.PRODCODE) COLLATE SQL_Latin1_General_CP1_CI_AS,
        //             ' - ',
        //             TRIM(p.DESCRIPT) COLLATE SQL_Latin1_General_CP1_CI_AS
        //         ) as text,
        //         TRIM(p.PRODCODE) as item_code,
        //         CONCAT(
        //             TRIM(p.DESCRIPT) COLLATE SQL_Latin1_General_CP1_CI_AS,
        //             ' ',
        //             TRIM(pe.F27) COLLATE SQL_Latin1_General_CP1_CI_AS
        //         ) as item_desc,
        //         TRIM(SUBSTRING(p.UOM_STK, 2, LEN(p.UOM_STK))) as fact_uom,
        //         TRIM(pe.F6) as production_type,
        //         TRIM(pe.F19) as formed,
        //         TRIM(pe.F20) as color,
        //         TRIM(pe.F21) as stock,
        //         TRIM(pe.F23) as other_specs1,
        //         TRIM(pe.F38) as other_specs2,
        //         TRIM(pe.F42) as other_specs3,
        //         CASE WHEN UPPER(TRIM(pe.F29)) = 'N/A' THEN '' ELSE TRIM(pe.F29) END as spread_l,
        //         CASE WHEN UPPER(TRIM(pe.F30)) = 'N/A' THEN '' ELSE TRIM(pe.F30) END as spread_w,
        //         CASE WHEN UPPER(TRIM(pe.F31)) = 'N/A' THEN '' ELSE TRIM(pe.F31) END as spread_h,
        //         CASE WHEN UPPER(TRIM(pe.F32)) = 'N/A' THEN '' ELSE TRIM(pe.F32) END as folded_l,
        //         CASE WHEN UPPER(TRIM(pe.F18)) = 'N/A' THEN '' ELSE TRIM(pe.F18) END as folded_w,
        //         CASE WHEN UPPER(TRIM(pe.F33)) = 'N/A' THEN '' ELSE TRIM(pe.F33) END as folded_h,
        //         TRIM(pe.F34) as packing_specs,
        //         TRIM(pe.F35) as other_dimension,
        //         TRIM(pe.F36) as fact_process,
        //         TRIM(pe.F41) as fact_cylinder_type,
        //         TRIM(pe.F43) as winding_direction,
        //         TRIM(pe.F27) as additional_desc,
        //         TRIM(pe.F5) as product_class,
        //         TRIM(pe.F9) as sap_code
        //     FROM PRODUCT p
        //     LEFT JOIN PRODEXTN pe ON pe.PRODCODE = p.PRODCODE
        //     WHERE p.DORMANT=0 AND p.SERVICE=0 AND p.PRODCODE LIKE '100%'
        //     AND p.PRODCODE LIKE ?
        // ";

            $sql ="SELECT TOP 10
    TRIM(p.PRODCODE) as id,
    CONCAT(
        TRIM(p.PRODCODE) COLLATE SQL_Latin1_General_CP1_CI_AS,
        ' - ',
        TRIM(p.DESCRIPT) COLLATE SQL_Latin1_General_CP1_CI_AS
    ) as text,
    TRIM(p.PRODCODE) as item_code,
    CONCAT(
        TRIM(p.DESCRIPT) COLLATE SQL_Latin1_General_CP1_CI_AS,
        ' ',
        TRIM(pe.F27) COLLATE SQL_Latin1_General_CP1_CI_AS
    ) as item_desc,
    TRIM(SUBSTRING(p.UOM_STK, 2, LEN(p.UOM_STK))) as fact_uom,
    TRIM(pe.F6) as production_type,
    TRIM(pe.F19) as formed,
    TRIM(pe.F20) as color,
    TRIM(pe.F21) as stock,
    TRIM(pe.F23) as other_specs1,
    TRIM(pe.F38) as other_specs2,
    TRIM(pe.F42) as other_specs3,
    CASE WHEN UPPER(TRIM(pe.F29)) = 'N/A' THEN '' ELSE TRIM(pe.F29) END as spread_l,
    CASE WHEN UPPER(TRIM(pe.F30)) = 'N/A' THEN '' ELSE TRIM(pe.F30) END as spread_w,
    CASE WHEN UPPER(TRIM(pe.F31)) = 'N/A' THEN '' ELSE TRIM(pe.F31) END as spread_h,
    CASE WHEN UPPER(TRIM(pe.F32)) = 'N/A' THEN '' ELSE TRIM(pe.F32) END as folded_l,
    CASE WHEN UPPER(TRIM(pe.F18)) = 'N/A' THEN '' ELSE TRIM(pe.F18) END as folded_w,
    CASE WHEN UPPER(TRIM(pe.F33)) = 'N/A' THEN '' ELSE TRIM(pe.F33) END as folded_h,
    TRIM(pe.F34) as packing_specs,
    TRIM(pe.F35) as other_dimension,
    TRIM(pe.F36) as fact_process,
    TRIM(pe.F41) as fact_cylinder_type,
    TRIM(pe.F43) as winding_direction,
    TRIM(pe.F27) as additional_desc,
    TRIM(pe.F5) as product_class,
    TRIM(pe.F2) as customer_code,
    TRIM(pe.F1) as customer_name
FROM PRODUCT p
LEFT JOIN PRODEXTN pe ON pe.PRODCODE = p.PRODCODE
WHERE p.DORMANT=0 AND p.SERVICE=0 AND p.PRODCODE LIKE '100%'
AND p.PRODCODE LIKE ?";

        $items = DB::connection('sqlsrv_fact')->select($sql, ["%{$searchTerm}%"]);

        return $items;
    }


public function ao_page(Request $request)
{
    $totalUsers = User::count(); 
    $users= User::whereNotNull('email')->get();

// Fetch both counts in a single database query
    $counts = AoModel::selectRaw("
        COUNT(*) as total_sent,
        COUNT(CASE WHEN status = 'Opened' THEN 1 END) as total_open
    ")->first();

    $totalSentFileLink = $counts->total_sent ?? 0;
    $totalOpenFile     = $counts->total_open ?? 0;
        
        // 1. Fetch available item counts
        $totalAvailable = DB::selectOne("
            SELECT COUNT(*) as count 
            FROM item_lists 
            WHERE item_code NOT IN (
                SELECT item_code 
                FROM time_lines 
                WHERE status IS NOT NULL AND status != ''
            )
        ")->count;

        // 2. Fetch tracking status counter aggregations
        $totalStart = DB::selectOne("SELECT COUNT(*) as count FROM time_lines WHERE status = 'started'")->count;
        $totalStop  = DB::selectOne("SELECT COUNT(*) as count FROM time_lines WHERE status = 'stopped'")->count;

        // Fetch items that are NOT YET inserted into the time_lines table
        $availableItemsForSelect = DB::table('item_lists')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('time_lines')
                    ->whereColumn('time_lines.item_code', 'item_lists.item_code');
            })
            ->select('item_code')
            ->get();

        // 3. JOIN both item_lists AND users tables to get all required metadata
        $timeLineList = DB::table('time_lines')
            ->leftJoin('item_lists', 'time_lines.item_code', '=', 'item_lists.item_code')
            ->leftJoin('users', 'time_lines.user_id', '=', 'users.id') // Joins the user accounts
            ->select(
                'time_lines.*', 
                'item_lists.item_name',
                'users.name as user_name' // Aliased to prevent naming conflicts
            )
            ->orderBy('time_lines.item_code')
            ->orderBy('time_lines.start_time', 'asc')
            ->get(); 

        // return view('home', [
        //     'totalUsers'              => $totalUsers,
        //     'totalAvailable'          => $totalAvailable,
        //     'totalStart'              => $totalStart,
        //     'totalStop'               => $totalStop,
        //     'timeLineList'            => $timeLineList,
        //     'availableItemsForSelect' => $availableItemsForSelect // Added variable
        // ]); 

        //     return view('home1', [
        //     'totalUsers'              => $totalUsers,
        //     'totalAvailable'          => $totalAvailable,
        //     'totalStart'              => $totalStart,
        //     'totalStop'               => $totalStop,
        //     'timeLineList'            => $timeLineList,
        //     'availableItemsForSelect' => $availableItemsForSelect // Added variable
        // ]); 



        // ao 
        $ao_records_links = AoModel::where('ao_user_id', Auth::user()->emp_id)
                    ->orderBy('created_at', 'desc')
                    ->get();
                
        
        // get all artworklogs
        //  $getAtworkLogs =  ArtworkLog::where('user_id',Auth::user()->id);

        $getAtworkLogs = ArtworkLog::with(['user', 'file'])
                ->latest()
                ->take(15)
                ->get();
        $logs = $getAtworkLogs;
        // $unreadCount = ArtworkLog::where('status', NULL)->count(); //

        // Filter the collection based on the nested 'file' relationship status
        $unreadCount = $logs->filter(function ($log) {
            return $log->file && is_null($log->file->status);
        })->count();

        // 3. If AJAX submission, return JSON response with rendered partial HTML
        if ($request->ajax()) {
            $html = view('partials.artwork_logs', compact('logs', 'unreadCount'))->render();

            return response()->json([
                'success'     => 'RFA Link sent successfully!',
                'html'        => $html,
                'unreadCount' => $unreadCount,
            ]);
        }

        // Pass the records to your blade file
            return view('ao.sendFileLink', [
            'totalUsers'              => $totalUsers,
            'totalAvailable'          => $totalAvailable,
            'totalStart'              => $totalStart,
            'totalStop'               => $totalStop,
            'timeLineList'            => $timeLineList,
            'availableItemsForSelect' => $availableItemsForSelect, // Added variable
            'users'                   => $users,

            'ao_records_links'        =>$ao_records_links,
            'totalSentFileLink'       => $totalSentFileLink,
            'totalOpenFile'           => $totalOpenFile,
            'logs'                    =>$logs, //show logs and activity
            'unreadCount'             =>$unreadCount,
            
        ]); 
}

    //time_line
    public function timelineSave(Request $request) 
    {
       

        //  $selectedItem = TimeLine::find($request->id); 
        $currentDateTimeString = now()->format('Y-m-d H:i:s'); 
     
        $time = new TimeLine();//time_line model
        
        $time->user_id = Auth::user()->id;
    
        $time->status = 'started';
        $time->item_code = $request->id;
        
        $time->start_time = $currentDateTimeString ?? ''; 
        $time->stop_time ='';
    
        $time->save();

        return response()->json(['success' => 'User started successfully!']);
    }


    public function timeStop(Request $request) 
    {
        // 1. Validate that the Select2 ID was sent over
        $request->validate([
            'id' => 'required'
        ]);

        // 2. Look up the specific item record that the user chose in the dropdown
        // (Assuming $timeLineList elements are stored in a lookup table or the same table)
        $selectedItem = TimeLine::where('item_code',$request->id)->first(); 
        
        if (!$selectedItem) {
            return response()->json(['error' => 'The selected item could not be found.'], 404);
        }

        // 3. Find the entry that has a status of 'started' for the authenticated user
        $time = TimeLine::where('user_id', Auth::user()->id)
                        ->where('status', 'started')
                        ->first();

        // If there is no active row running, notify the user via SweetAlert
        if (!$time) {
            return response()->json(['error' => 'You do not have an active session running.']);
        }

        // 4. Update the running entry with the item details and mark it complete
        $time->status = 'stopped';
        $time->item_code = $request->id; // Replaces 'started' with the chosen item code
        $time->stop_time = now()->format('Y-m-d H:i:s');

        $time->save();

        // 5. Returns the success key your AJAX script is waiting for
        return response()->json(['success' => 'Session stopped successfully!']);
    }

     public function TimeLineShowAll()
    {
        try{
            $users = TimeLine::get();
            
            return datatables()->of($users)
            // ->addColumn('action',function($data){
            //     $button = '<div class="btn-group btn-group-sm">';
            //     $button .= '<a href="'. route('users.edit',['user' => $data->id]) .'" class="btn btn-info"><i class="fas fa-solid fa-pen"></i></a>';
            //     // $button .= '<a href="/employees/' . $data->id . '" class="btn btn-info"><i class="fas fa-solid fa-eye"></i></a>';
            //     $button .= '</div>';
            //     return $button;
            // })
            ->addColumn('id',function($data){
               
                 return strtoupper($data->id);
            })
            ->addColumn('user_id',function($data){
                return strtoupper($data->user_id);
            })
             ->addColumn('item_code',function($data){
                return strtoupper($data->item_code);
            })
             ->addColumn('start_time',function($data){
                return strtoupper($data->start_time);
            })
                 ->addColumn('stop_time',function($data){
                return strtoupper($data->stop_time);
            })
            
             ->addColumn('status',function($data){
                return strtoupper($data->status);
            })
            // ->addColumn('receive_notifications',function($data){
            //     $badge = '<span class="badge badge-'. ($data->receive_notifications == 1? 'success':'danger') .'">' . ($data->receive_notifications == 1? 'Yes':'No') . '</span>';
            //     return $badge;
            // })
            // ->rawColumns(['roles','action','receive_notifications'])
            ->make(true);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

}
