<?php

namespace App\Http\Controllers;

use App\Models\AoFileLink; // Your actual database model
use App\Models\AoModel;
use App\Models\ArtWorkFileLinkTemp;
use App\Models\ArtworkLog;
use App\Models\FileVerification;
use App\Models\GdSupervisorCommerial;
use App\Models\User;
use App\Notifications\ArtWorkNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PharIo\Manifest\Email;

class AoFormController extends Controller
{
    
    public function index() // index
    {

    $records = AoModel::where('ao_user_id', Auth::user()->emp_id)
                ->orderBy('created_at', 'desc')
                ->get();

                // dd($records);
    // Pass the records to your blade file
    return view('ao.sendFileLink', compact('records')); //ao send file link to gd
    //return view('ao.sendFileLink'); //ao send file link to gd
        
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

      public function FileSendLinkShowAll()
    {
        try{
           $fileSend = AoModel::orderBy('created_at', 'desc')
            ->whereNotNull('item_code')
            ->where('item_code','!=','')->get();
            // dd($fileSend);
            
            return datatables()->of($fileSend)
            // ->addColumn('action',function($data){
            //     $button = '<div class="btn-group btn-group-sm">';
            //     $button .= '<a href="'. route('users.edit',['user' => $data->id]) .'" class="btn btn-info"><i class="fas fa-solid fa-pen"></i></a>';
            //     // $button .= '<a href="/employees/' . $data->id . '" class="btn btn-info"><i class="fas fa-solid fa-eye"></i></a>';
            //     $button .= '</div>';
            //     return $button;
            // })
            ->addColumn('item_code',function($data){
               
                return strtoupper($data->item_code);
            })
            ->addColumn('ao_name',function($data){
               
                 return strtoupper($data->ao_name);
            })
            // ->addColumn('ao_user_id',function($data){
            //     return strtoupper($data->user_id);
            // })
             ->addColumn('from_email',function($data){
                return strtoupper($data->from_email);
            })
             ->addColumn('to_email',function($data){
                return strtoupper($data->to_email);
            })
            ->addColumn('file_link', function($data){
            return $data->file_link;

            })
             ->addColumn('created_at',function($data){
                return strtoupper($data->created_at);
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

    //get list of ao file links that are ready for itemcode creation 
       public function showAllReadyForItemCode()
    {
        try{
        //    $forItemCode = AoModel::orderBy('created_at', 'desc')
        //     ->whereNull('item_code')->get();

            // $forItemCode = AoModel::leftJoin('file_verifications', 'file_verifications.rfa_number', '=', 'ao_file_links.rfa_reference')
            // ->whereNull('ao_file_links.item_code')
            // ->where('ao_file_links.status',"For Item Code Creation")
            // ->orWhere('ao_file_links.item_code','')
    
            // ->orderBy('ao_file_links.created_at', 'desc')
            // ->select('ao_file_links.*') // Prevents column overwriting from file_verifications
            // ->get();

            $forItemCode = AoModel::join('file_verifications', 'file_verifications.rfa_number', '=', 'ao_file_links.rfa_reference')
                ->whereNull('ao_file_links.item_code')
                ->where('ao_file_links.status',"For Item Code Creation")
                ->orWhere('ao_file_links.item_code','')

                ->orderBy('ao_file_links.created_at', 'desc')
                ->select('ao_file_links.*') // Prevents column overwriting from file_verifications
                ->get();

            // dd($forItemCode);

            //  $forItemCode = AoModel::join('file_verifications', 'file_verifications.rfa_number', '=', 'ao_file_links.rfa_reference')
            // ->whereNull('ao_file_links.item_code')
            // ->orderBy('ao_file_links.created_at', 'desc')
            // ->select('ao_file_links.*')
            // ->get();
            // dd($fileSend);
            
            return datatables()->of($forItemCode)
            // ->addColumn('action',function($data){
            //     $button = '<div class="btn-group btn-group-sm">';
            //     $button .= '<a href="'. route('users.edit',['user' => $data->id]) .'" class="btn btn-info"><i class="fas fa-solid fa-pen"></i></a>';
            //     // $button .= '<a href="/employees/' . $data->id . '" class="btn btn-info"><i class="fas fa-solid fa-eye"></i></a>';
            //     $button .= '</div>';
            //     return $button;
            // })
            ->addColumn('item_code',function($data){
               
                return strtoupper($data->item_code);
            })
            ->addColumn('ao_name',function($data){
               
                 return strtoupper($data->ao_name);
            })
            // ->addColumn('ao_user_id',function($data){
            //     return strtoupper($data->user_id);
            // })
             ->addColumn('from_email',function($data){
                return strtoupper($data->from_email);
            })
             ->addColumn('to_email',function($data){
                return strtoupper($data->to_email);
            })
            ->addColumn('file_link', function($data){
            return $data->file_link;

            })
             ->addColumn('created_at',function($data){
                return strtoupper($data->created_at);
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



    public function store(Request $request)
{
    // 1. Validate form input arrays
    $validated = $request->validate([
        // 'item_code'        => 'required|array',
        // 'item_code.*'      => 'required',
        'link'             => 'required|array',
        'link.*'           => 'required',
        // 'employee_email'   => 'required|array',
        // 'employee_email.*' => 'required|array|min:1', // Each form card must have at least 1 email
    ]);

    $user = Auth::user();
    if (!$user) {
        abort(401, 'Unauthorized action.');
    }

    $savedRecords = [];
    $allRecipientEmails = [];

    DB::transaction(function () use ($request, $user, &$savedRecords, &$allRecipientEmails) {
        $itemCodes = $request->input('item_code', []);

        // Retrieve latest ID once for shared item grouping across batch inserts
        $latestId = (AoModel::max('id') ?? 0) + 1;

        // Loop through each submitted form card
        foreach ($itemCodes as $index => $itemCode) {
            // $emails = $request->input("employee_email.{$index}", []);
            $sharedLink = $request->input("link.{$index}");

            // // Filter out non-email values for actual email sending
            // $validEmails = array_values(array_filter($emails, function ($item) {
            //     return filter_var($item, FILTER_VALIDATE_EMAIL);
            // }));

            // Aggregate unique recipient emails for batch notification
            // $allRecipientEmails = array_unique(array_merge($allRecipientEmails, $validEmails));

            // Create 1 DB record per recipient email for the current form card
       
            // foreach ($emails as $email) {
                $record = new AoModel();
                $record->item_id       = $latestId;
                $record->item_code     = $itemCode;
                $record->ao_user_id   = $user->emp_id;
                $record->from_email    = $user->email;
                $record->ao_name       = $user->name;
                // $record->to_email      = $email;
                $record->assigned_to_gd_supervisor      = 2; //role id == 2 for supervisor
                $record->file_link     = $sharedLink;
                $record->file_info     = $request->input("file_info.{$index}");
                $record->spread_size   = $request->input("spread_size.{$index}");
                $record->spread_h      = $request->input("spread_h.{$index}");
                $record->spread_w      = $request->input("spread_w.{$index}");
                $record->spread_l      = $request->input("spread_l.{$index}");
                $record->folded_size   = $request->input("folded_size.{$index}");
                $record->folded_h      = $request->input("folded_h.{$index}");
                $record->folded_w      = $request->input("folded_w.{$index}");
                $record->folded_l      = $request->input("folded_l.{$index}");
                $record->colors        = $request->input("colors.{$index}");
                $record->num_of_colors = $request->input("num_colors.{$index}");
                $record->image_type    = $request->input("image_type.{$index}");
                $record->client_name   = $request->input("client_name.{$index}");
                $record->description   = $request->input("description.{$index}");
                $record->is_sent       = 1;
                $record->status      = "Submitted";
                $record->with_lsd      =$request->input("description.{$index}" ?? "");
                $record->save();

                $this->logActivity($record, 'File Link Sent', $record->created_at);

                $savedRecords[] = $record;
            // }
        }
    });

     // 2. Send notifications OUTSIDE the transaction loop to the actual recipients
 foreach ($savedRecords as $record) {
    // FIX: Pull the email directly from the database record property
    $recipientUser = \App\Models\User::where('role_id', 2)->first();

    if ($recipientUser) {
        $recipientUser->notify(new ArtWorkNotification($record,'ao_to_gd'));
    }
}

    // 2. Send email notification to all unique recipients across all submitted forms
    // if (!empty($allRecipientEmails)) {
    //     try {
    //         Mail::html("
    //             <p>Hello,</p>
    //             <p>An administrator has shared link(s) with you.</p>
    //             <p>Please log in to your portal to view the details.</p>
    //             <p>Thank you!</p>
    //         ", function ($message) use ($allRecipientEmails) {
    //             $message->to($allRecipientEmails)
    //                     ->subject('New Link(s) Shared With You');
    //         });
    //     } catch (\Exception $e) {
    //         return redirect()->route('ao.sendFileLink')
    //             ->with('warning', count($savedRecords) . " record(s) created across form(s), but the email failed to send.");
    //     }
    // }

    return redirect()->route('ao.sendFileLink')
        ->with('success', count($savedRecords) . ' record(s) successfully created and emailed!');
}

public function store_without_itemcode(Request $request)
{
    // 1. Validate form input arrays
    $validated = $request->validate([
        'link' => 'required|array',
        'link.*' => 'required|url',
        'client_name' => 'nullable|array',
    ]);

    $user = Auth::user();
    if (!$user) {
        abort(401, 'Unauthorized action.');
    }

    $savedRecords = [];
    $createdAoRecords = [];

    DB::transaction(function () use ($request, $user, &$savedRecords, &$createdAoRecords) {
        $itemLinks = $request->input('link', []);

        // Retrieve latest ID once for shared item grouping across batch inserts
        $latestId = (AoModel::max('id') ?? 0) + 1;

        foreach ($itemLinks as $index => $itemLink) {
            $sharedLink = $request->input("link.{$index}");
            $clientName = $request->input("client_name.{$index}");

            // 1. Save primary AoModel record (Model hook generates rfa_reference on save)
            $rfa_file_link = new AoModel();
            $rfa_file_link->item_id     = $latestId;
            $rfa_file_link->ao_user_id = $user->emp_id;
            $rfa_file_link->from_email  = $user->email;
            $rfa_file_link->ao_name     = $user->name;
            $rfa_file_link->file_link   = $sharedLink;
            $rfa_file_link->client_name = $clientName;
            $rfa_file_link->is_sent     = 1;
            $rfa_file_link->status      = "For File Verification";
            $rfa_file_link->assigned_to_gd_supervisor      = 2; //role id == 2 for supervisor
            $rfa_file_link->save();

            $this->logActivity($rfa_file_link, 'File Link Sent', $rfa_file_link->created_at);
            
            $savedRecords[] = $rfa_file_link;
            $createdAoRecords[] = $rfa_file_link;

            // 2. Save to temporary table using the newly generated rfa_reference
            $rfa_file_link_temp = new ArtWorkFileLinkTemp();
            $rfa_file_link_temp->rfa_reference = $rfa_file_link->rfa_reference;
            $rfa_file_link_temp->ao_user_id   = $user->emp_id;
            $rfa_file_link_temp->from_email    = $user->email;
            $rfa_file_link_temp->ao_name       = $user->name;
            $rfa_file_link_temp->file_link     = $sharedLink;
            $rfa_file_link_temp->client_name   = $clientName;
            $rfa_file_link_temp->is_sent       = 1;
            $rfa_file_link_temp->status        = "For File Verification";
            $rfa_file_link->assigned_to_gd_supervisor      = 2; //role id == 2 for supervisor
            $rfa_file_link_temp->save();

            $this->logActivity($rfa_file_link_temp, 'File Link Save as Temporary, No Item Code yet', $rfa_file_link_temp->created_at);

            $savedRecords[] = $rfa_file_link_temp;

            // 3. Save to GD Supervisor Commercial table using the generated rfa_reference
            $rfa_saveToGDSV = new GdSupervisorCommerial();
            $rfa_saveToGDSV->rfa_reference = $rfa_file_link->rfa_reference;
            $rfa_saveToGDSV->item_code     = '';
            $rfa_saveToGDSV->status        = "For File Verification";
            $rfa_saveToGDSV->save();

            $this->logActivity($rfa_saveToGDSV, 'File Link Save as Temporary, No Item Code yet', $rfa_saveToGDSV->created_at);

            $savedRecords[] = $rfa_saveToGDSV;
        }
    });

    // 2. Send notifications outside transaction only for the primary AoModel records
    $recipientUser = \App\Models\User::where('role_id', 2)->first();

    if ($recipientUser) {
        foreach ($createdAoRecords as $record) {
            $recipientUser->notify(new ArtWorkNotification($record, 'ao_to_gd'));
        }
    }

    return redirect()->route('ao.sendFileLink')
        ->with('success', count($savedRecords) . ' record(s) successfully created and emailed!');
}
    public function getRfaFileList(){// list of all file send by ao to gd

      $records = AoModel::where('ao_user_id', Auth::user()->emp_id)
        ->whereNotNull('item_code')
        ->latest()
        ->get();
                // dd($records);
        // Pass the records to your blade file
        return view('ao.rfa_file_list', compact('records')); 
        //return view('ao.sendFileLink'); //ao send file link to gd

    }

    
    public function logActivity($artwork, $log, $timestamp)
    {
        $data['artwork_id'] = $artwork->id;
        $data['user_id'] = $artwork->ao_user_id;
        $data['log'] = $log;
        $data['file_id'] = $artwork->id;
        $data['created_at'] = $timestamp;
        $data['updated_at'] = $timestamp;
        // $data['log_level'] = 'ADMIN';

        ArtworkLog::create($data);
    }



    //gd_supervisor_supplied_file.blade
    public function gd_supervisor_supplied_file(){

        return view('ao.gd_supervisor_supplied_file');
    }


     public function timeline(Request $request, $item_code)
    {
        // 1. Locate the primary artwork anchor record or abort with a 404 error if missing
        $artwork = AoModel::where('item_code', $item_code)->firstOrFail();

        // 2. Core Dashboard Summary Metrics & Counts
        $totalUsers = User::count(); 
        $users = User::whereNotNull('email')->get();

        // Fetch sent and opened link aggregates in a single optimized database transaction
        $counts = AoModel::selectRaw("
            COUNT(*) as total_sent,
            COUNT(CASE WHEN status = 'Opened' THEN 1 END) as total_open
        ")->first();

        $totalSentFileLink = $counts->total_sent ?? 0;
        $totalOpenFile     = $counts->total_open ?? 0;
            
        // Fetch remaining available item counts via subquery exclusions
        $totalAvailable = DB::selectOne("
            SELECT COUNT(*) as count 
            FROM item_lists 
            WHERE item_code NOT IN (
                SELECT item_code 
                FROM time_lines 
                WHERE status IS NOT NULL AND status != ''
            )
        ")->count;

        // Fetch legacy tracking dashboard state aggregates
        $totalStart = DB::selectOne("SELECT COUNT(*) as count FROM time_lines WHERE status = 'started'")->count;
        $totalStop  = DB::selectOne("SELECT COUNT(*) as count FROM time_lines WHERE status = 'stopped'")->count;

        // Fetch items that have not yet been initiated inside the legacy tracking tables
        $availableItemsForSelect = DB::table('item_lists')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('time_lines')
                    ->whereColumn('time_lines.item_code', 'item_lists.item_code');
            })
            ->select('item_code')
            ->get();

        // 💡 3. FIX: Fetch polymorphic activity logs scoped strictly to this asset item.
        // reorder() completely removes the duplicate query builder sorting loops crashing SQL Server.
        $activityLogs = $artwork->activityLogs()
            ->reorder()
            ->orderBy('created_at', 'asc') // Render chronologically from oldest to newest (left to right)
            ->with('user')
            ->get();

        // 4. Accounts Officer Data Matrix Mappings
        $ao_records_links = AoModel::where('ao_user_id', Auth::user()->emp_id)
            ->orderBy('created_at', 'desc')
            ->get();
                    
        // 5. Native Notifications / Real-time Component Feeds Tracking Block
        $getAtworkLogs = ArtworkLog::with(['user', 'file'])
            ->latest()
            ->take(15)
            ->get();
        $logs = $getAtworkLogs;

        // Calculate active unread alert queues
        $unreadCount = $logs->filter(function ($log) {
            return $log->file && is_null($log->file->status);
        })->count();

        // 6. Handle Async AJAX Live Polling Notifications Requests
        if ($request->ajax()) {
            return response()->json([
                'success'     => 'Data synchronized successfully!',
                'html'        => isset($html) ? $html : '',
                'unreadCount' => $unreadCount,
            ]);
        }

        // 7. Render complete metric data bundles cleanly to the horizontal timeline view
        return view('time_line', [
            'artwork'                 => $artwork,
            'totalUsers'              => $totalUsers,
            'totalAvailable'          => $totalAvailable,
            'totalStart'              => $totalStart,
            'totalStop'               => $totalStop,
            'activityLogs'            => $activityLogs,
            'availableItemsForSelect' => $availableItemsForSelect, 
            'users'                   => $users,
            'ao_records_links'        => $ao_records_links,
            'totalSentFileLink'       => $totalSentFileLink,
            'totalOpenFile'           => $totalOpenFile,
            'unreadCount'             => $unreadCount,
        ]); 
    }

    public function aoAddedItemCode(Request $request){

         $addItemCode_ao_file_links = AoModel::where('rfa_reference', trim($request->input('rfa_reference')))->first();
         $addItemCode_file_verifications = FileVerification::where('rfa_number', trim($request->input('rfa_reference')))->first();
        $addItemCode_artwork_fileLink_temp = ArtWorkFileLinkTemp::where('rfa_reference', trim($request->input('rfa_reference')))->first();
        // dd($addItemCode);
        if ($addItemCode_ao_file_links) {
                $addItemCode_ao_file_links->item_code      = $request->input('item_code');
                $addItemCode_ao_file_links->status      = "Submitted";
                $addItemCode_ao_file_links->save();
        }
         if ($addItemCode_file_verifications) {
                $addItemCode_file_verifications->item_code      = $request->input('item_code');
                $addItemCode_file_verifications->status      = "Submitted";
                $addItemCode_file_verifications->save();
        }
                if ($addItemCode_artwork_fileLink_temp) {
                $addItemCode_artwork_fileLink_temp->item_code      = $request->input('item_code');
                $addItemCode_artwork_fileLink_temp->status      = "Submitted";
                $addItemCode_artwork_fileLink_temp->save();
        }

        return redirect()->back()->with('success', 'Item Code Updated &  saved successfully!');
    }

}
