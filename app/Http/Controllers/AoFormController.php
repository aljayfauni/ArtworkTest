<?php

namespace App\Http\Controllers;

use App\Models\ActionTakenLog;
use App\Models\ActivityLog;
use App\Models\AoFileLink; // Your actual database model
use App\Models\AoModel;
use App\Models\ArtWorkFileLinkTemp;
use App\Models\ArtworkLog;
use App\Models\AuditLog;
use App\Models\FileVerification;
use App\Models\GdSupervisorCommerial;
use App\Models\HistoryLog;
use App\Models\Traffic;
use App\Models\User;
use App\Notifications\ArtWorkNotification;
use App\Services\AuditLogger;
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




  $userId = auth()->id();

    // 1. Kunin lahat ng rfa_reference ng user na hindi pa CANCEL sa history_logs
    $activeLoggedRefs = HistoryLog::where('user_id', $userId)
        ->whereNotNull('rfa_reference')
        ->where(function ($q) {
            $q->whereNull('status')->orWhere('status', '!=', 'CANCEL');
        })
        ->pluck('rfa_reference')
        ->map(fn($ref) => trim($ref))
        ->unique();

    if ($activeLoggedRefs->isNotEmpty()) {
        // 2. Tingnan kung alin ang na-save na talaga sa ao_file_links (AoModel)
        $savedRefs = AoModel::whereIn('rfa_reference', $activeLoggedRefs)
            ->pluck('rfa_reference')
            ->map(fn($ref) => trim($ref))
            ->toArray();

        // 3. Kunin yung mga hindi na-save bago nag-refresh
        $cancelledRefs = $activeLoggedRefs->diff($savedRefs)->toArray();

        // 4. I-mark as 'CANCEL' sa logs imbes na i-delete
        if (!empty($cancelledRefs)) {
            HistoryLog::whereIn('rfa_reference', $cancelledRefs)->update([
                'status'  => 'CANCEL',
                'remarks' => 'Cancelled due to page refresh before submission.',
            ]);

            ActionTakenLog::whereIn('rfa_reference', $cancelledRefs)->update([
                'status'  => 'CANCEL', // Alisin ang line na ito kung walang status column ang action_taken_logs
                'remarks' => 'Cancelled due to page refresh before submission.',
            ]);
        }
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
            //create reference number
            $currentNumber = $latestId + $index;
            $rfaReference = 'RFA-' . date('Y') . '-' . str_pad($currentNumber, 5, '0', STR_PAD_LEFT); // e.g., RFA-2026-00001
   
                $record = new AoModel();
                $record->item_id       = $latestId;
                $record->item_code     = $itemCode;
                $record->ao_user_id   = $user->emp_id;
                $record->from_email    = $user->email;
                $record->ao_name       = $user->name;
                // $record->to_email      = $email;
                $record->assigned_to_role_id      = 3; //role id == 3 initially assigned to traffic officer
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
                $record->other_specs1      = $request->input("other_specs1.{$index}");
                $record->other_specs2      = $request->input("other_specs2.{$index}");
                $record->other_specs3      = $request->input("other_specs3.{$index}");
                $record->with_lsd           =$request->input("with_lsd.{$index}" ?? "");
                $record->rfa_reference      = $rfaReference;
                $record->save();

               //insert also in traffic_officer table
                $traffic  = new Traffic();
                $traffic->item_code = $record->item_code ?? "";
                $traffic->rfa_reference = $record->rfa_reference ?? "";
                $traffic->file_link = $record->file_link ?? "";
                $traffic->status = $record->status ?? "";
                $traffic->save();
               
                // $this->logActivity($record, 'File Link Sent', $record->created_at);

                $savedRecords[] = $record;
            // }
              AuditLogger::action(
                subject: $record,
                action: 'AO sent a new RFA file link without item code',
                remarks: "AO {$user->name} submitted file link for verification without an item code."
            );

            AuditLogger::history(
                model: $record,
                oldValues: null,
                newValues: $record->toArray(),
                remarks: 'Artwork record created.'
            );

            // 3. Activity Log (User Interaction)
            AuditLogger::activity(
                event: 'submitted_file_link_no_itemcode',
                description: "AO {$user->name} submitted file link for verification.",
                rfaReference: $record->rfa_reference
            );

            AuditLogger::audit(
                event: 'rfa_created_without_itemcode',
                auditable: $record,
               
                metadata: $record->toArray()
            );
        }
    });

     // 2. Send notifications OUTSIDE the transaction loop to the actual recipients
 foreach ($savedRecords as $record) {
    // FIX: Pull the email directly from the database record property
    $recipientUser = \App\Models\User::where('role_id', 3)->first();

    if ($recipientUser) {
        $recipientUser->notify(new ArtWorkNotification($record,'ao_to_traffic'));
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
            $currentNumber = $latestId + $index;
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
            $rfa_file_link->assigned_to_role_id      = 3; 
            $rfa_file_link->save();

            // $this->logActivity($rfa_file_link, 'File Link Sent', $rfa_file_link->created_at);
            
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
            $rfa_file_link->assigned_to_role_id      = 3; //role id == 2 for supervisor
            $rfa_file_link_temp->save();

            // $this->logActivity($rfa_file_link_temp, 'File Link Save as Temporary, No Item Code yet', $rfa_file_link_temp->created_at);

            $savedRecords[] = $rfa_file_link_temp;

            // 3. Save to GD Supervisor Commercial table using the generated rfa_reference
            $rfa_saveToGDSV = new GdSupervisorCommerial();
            $rfa_saveToGDSV->rfa_reference = $rfa_file_link->rfa_reference;
            $rfa_saveToGDSV->item_code     = '';
            $rfa_saveToGDSV->status        = "For File Verification";
            $rfa_saveToGDSV->save();

            // $this->logActivity($rfa_saveToGDSV, 'File Link Save as Temporary, No Item Code yet', $rfa_saveToGDSV->created_at);

            $savedRecords[] = $rfa_saveToGDSV;

            AuditLogger::action(
                subject: $rfa_file_link,
                action: 'AO sent a new RFA file link without item code',
                remarks: "AO {$user->name} submitted file link for verification without an item code."
            );

            AuditLogger::history(
                model: $rfa_file_link,
                oldValues: null,
                newValues: $rfa_file_link->toArray(),
                remarks: 'Artwork record created.'
            );

            // 3. Activity Log (User Interaction)
            AuditLogger::activity(
                event: 'submitted_file_link_no_itemcode',
                description: "AO {$user->name} submitted file link for verification.",
                rfaReference: $rfa_file_link->rfa_reference
            );

            AuditLogger::audit(
                event: 'rfa_created_without_itemcode',
                auditable: $rfa_file_link,
               
                metadata: $rfa_file_link->toArray()
            );


            // 1. HISTORY LOG (Data snapshot + Artist's editable diary remarks) // manual logging
                // HistoryLog::create([
                //     'user_id'       => auth()->id(),
                //     'rfa_reference' => $rfa_file_link->rfa_reference,
                //     'model_type'    => AoModel::class,
                //     'model_id'      => $rfa_file_link->id,
                //     'old_values'    => null,
                //     'new_values'    => $rfa_file_link->toArray(),
                //     'remarks'       => 'Artwork record created.',
                // ]);

                // // 2. ACTION TAKEN LOG (What workflow step the user completed)
                // ActionTakenLog::create([
                //     'user_id'       => auth()->id(),
                //     'rfa_reference' => $rfa_file_link->rfa_reference,
                //     'subject_type'  => AoModel::class,
                //     'subject_id'    => $rfa_file_link->id,
                //     'action'        => 'AO sent a new RFA file link without item code',
                //     'remarks'       => "Submitted file link for verification without an item code.",
                // ]);

                // // 3. ACTIVITY LOG (User click / form submission event)
                // ActivityLog::create([
                //     'user_id'       => auth()->id(),
                //     'rfa_reference' => $rfa_file_link->rfa_reference,
                //     'event'         => 'submitted_file_link_no_itemcode',
                //     'description'   => "AO {$user->name} submitted a new file link.",
                //     'ip_address'    => request()->ip(),
                //     'user_agent'    => request()->userAgent(),
                // ]);

                // // 4. AUDIT LOG 
                // AuditLog::create([
                //     'user_id'        => auth()->id(),
                //     'rfa_reference'  => $rfa_file_link->rfa_reference,
                //     'auditable_type' => AoModel::class,
                //     'auditable_id'   => $rfa_file_link->id,
                //     'event'          => 'rfa_created',
                //     'metadata'       => $rfa_file_link->toArray(),
                //     'url'            => request()->fullUrl(),
                //     'ip_address'     => request()->ip(),
                //     'user_agent'     => request()->userAgent(),
                // ]);
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

public function aoAddedItemCode(Request $request)
{
    $request->validate([
        'rfa_reference' => 'required|string',
        'item_code'     => 'required|string',
    ]);

    $rfaReference = trim($request->input('rfa_reference'));
    $itemCode = trim($request->input('item_code'));
    $createdAoRecords = [];
    $aoFileLink = AoModel::where('rfa_reference', $rfaReference)->first();
    $fileVerification = FileVerification::where('rfa_number', $rfaReference)->first();
    $artworkFileLinkTemp = ArtWorkFileLinkTemp::where('rfa_reference', $rfaReference)->first();

    // Check if the record exists first, not its status property
    if ($aoFileLink) {
        $aoFileLink->item_code = $itemCode;
        $aoFileLink->status = 'Verified';
        $aoFileLink->save();
    }

    if ($fileVerification) {
        $fileVerification->item_code = $itemCode;
        $fileVerification->status = 'Verified';
        $fileVerification->save();
    }

    if ($artworkFileLinkTemp) {
        $artworkFileLinkTemp->item_code = $itemCode;
        $artworkFileLinkTemp->status = 'Verified';
        $artworkFileLinkTemp->save();
    }

        // 2. Send notifications outside transaction only for the primary AoModel records
    $recipientUser = \App\Models\User::where('role_id', 3)->first();
    $createdAoRecords [] = $aoFileLink;
   
    if ($recipientUser) {
        foreach ($createdAoRecords as $record) {
            $recipientUser->notify(new ArtWorkNotification($record, 'ao_to_traffic'));
        }
    }

    return redirect()->back()->with('success', 'Item Code Updated & saved successfully!');
}


    public function aoAddItemCode(Request $request) // index
    {


    // Fetch the target submission
    $rfa = AoModel::where('rfa_reference', $request->item_id)->first();
 
    $users= User::whereNotNull('email')->get();
   
    // Fetch all recipient emails linked to this submission batch
    $selectedEmails = AoModel::

        where('rfa_reference', $rfa)
        ->pluck('to_email')
        ->toArray();
    
    // Fetch all available system users to populate options
   // $users = User::all();
    $item_detail = $rfa;
    return view('ao.add_item_code', compact('item_detail', 'selectedEmails', 'users'));
        
    }
    


public function getNewLatestReference()
{
    $user   = auth()->user();
    $userId = $user->id;
    $prefix = 'RFA-' . now('Asia/Manila')->format('dmY') . '-';

    // 1. Only reuse if there is an ACTIVE (status IS NULL) reference not yet saved in AoModel
    $existingUnused = HistoryLog::where('user_id', $userId)
        ->where('rfa_reference', 'LIKE', $prefix . '%')
        ->whereNull('status')
        ->orderBy('id', 'desc')
        ->first();

    if ($existingUnused && !AoModel::where('rfa_reference', $existingUnused->rfa_reference)->exists()) {
        $ref = trim($existingUnused->rfa_reference);
        $now = now('Asia/Manila')->format('Y-m-d H:i:s.v');

        DB::table('history_logs')->where('id', $existingUnused->id)->update(['updated_at' => $now]);
        DB::table('action_taken_logs')->where('rfa_reference', $ref)->update(['updated_at' => $now]);
        DB::table('activity_logs')->where('rfa_reference', $ref)->update(['updated_at' => $now]);
        // DB::table('audit_logs')->where('rfa_reference', $ref)->update(['updated_at' => $now]);

        return response()->json([
            'new_ref' => $ref,
            'reused'  => true
        ]);
    }

    // 2. Otherwise, ignore CANCEL rows, grab the next number (reusing any cancelled number), and INSERT fresh rows with status = NULL
    $newRef = DB::transaction(function () use ($prefix, $user, $userId) {
        $latestAo = AoModel::where('rfa_reference', 'LIKE', $prefix . '%')
            ->orderBy('rfa_reference', 'desc')
            ->first();

        // Ignore CANCEL rows so cancelled references can be generated and re-inserted with status = NULL
        $latestHistory = HistoryLog::where('rfa_reference', 'LIKE', $prefix . '%')
            ->whereNull('status')
            ->orderBy('rfa_reference', 'desc')
            ->first();

        $lastAoNum   = $latestAo ? (int) substr(trim($latestAo->rfa_reference), -3) : 0;
        $lastHistNum = $latestHistory ? (int) substr(trim($latestHistory->rfa_reference), -3) : 0;

        $lastNumber   = max($lastAoNum, $lastHistNum);
        $generatedRef = $prefix . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        // 1. HISTORY LOG
        HistoryLog::create([
            'user_id'       => $userId,
            'rfa_reference' => $generatedRef,
            'model_type'    => AoModel::class,
            'model_id'      => 0,
            'status'        => null,
            'old_values'    => null,
            'new_values'    => ['rfa_reference' => $generatedRef],
            'remarks'       => 'Initiated new RFA Artwork Creation.',
        ]);

        // 2. ACTION TAKEN LOG
        ActionTakenLog::create([
            'user_id'       => $userId,
            'rfa_reference' => $generatedRef,
            'subject_type'  => AoModel::class,
            'subject_id'    => 0,
            'action'        => 'AO initiated a new RFA ',
            'remarks'       => 'Create new item  ',
        ]);

        // 3. ACTIVITY LOG
        ActivityLog::create([
            'user_id'       => $userId,
            'rfa_reference' => $generatedRef,
            'event'         => 'initiated_new_rfa',
            'description'   => "AO {$user->name} Created new Artwork {$generatedRef}.",
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
        ]);

        // 4. AUDIT LOG
        AuditLog::create([
            'user_id'        => $userId,
            'rfa_reference'  => $generatedRef,
            'auditable_type' => AoModel::class,
            'auditable_id'   => 0,
            'event'          => 'rfa_initiated',
            'metadata'       => ['rfa_reference' => $generatedRef],
            'url'            => request()->fullUrl(),
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
        ]);

        return $generatedRef;
    });

    return response()->json(['new_ref' => $newRef]);
}

public function getExistingReferenceNum()
{
    $user   = auth()->user();
    $userId = $user->id;
    $prefix = 'RFA-' . now('Asia/Manila')->format('dmY') . '-';

    // 1. Only reuse if there is ALREADY an active (status IS NULL) reference not yet saved in AoModel
    $existingUnused = HistoryLog::where('user_id', $userId)
        ->where('rfa_reference', 'LIKE', $prefix . '%')
        ->whereNull('status') // <-- MUST be whereNull('status'), NOT where('status', 'CANCEL')
        ->orderBy('id', 'desc')
        ->first();

    if ($existingUnused && !AoModel::where('rfa_reference', $existingUnused->rfa_reference)->exists()) {
        $ref = trim($existingUnused->rfa_reference);
        $now = now('Asia/Manila')->format('Y-m-d H:i:s.v');

        DB::table('history_logs')->where('id', $existingUnused->id)->update(['updated_at' => $now]);
        DB::table('action_taken_logs')->where('rfa_reference', $ref)->update(['updated_at' => $now]);
        DB::table('activity_logs')->where('rfa_reference', $ref)->update(['updated_at' => $now]);
        // DB::table('audit_logs')->where('rfa_reference', $ref)->update(['updated_at' => $now]);

        return response()->json([
            'new_ref' => $ref,
            'reused'  => true
        ]);
    }

    // 2. Otherwise, ignore CANCEL rows, grab the next number (e.g., 008 or 011), and INSERT fresh rows with status = NULL
    $newRef = DB::transaction(function () use ($prefix, $user, $userId) {
        $latestAo = AoModel::where('rfa_reference', 'LIKE', $prefix . '%')
            ->orderBy('rfa_reference', 'desc')
            ->first();

        // Ignore CANCEL rows here so cancelled numbers like 011 can be generated & inserted again!
        $latestHistory = HistoryLog::where('rfa_reference', 'LIKE', $prefix . '%')
            ->whereNull('status')
            ->orderBy('rfa_reference', 'desc')
            ->first();

        $lastAoNum   = $latestAo ? (int) substr(trim($latestAo->rfa_reference), -3) : 0;
        $lastHistNum = $latestHistory ? (int) substr(trim($latestHistory->rfa_reference), -3) : 0;

        $lastNumber   = max($lastAoNum, $lastHistNum);
        $generatedRef = $prefix . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        // 1. HISTORY LOG (Inserts a fresh row with status = NULL)
        HistoryLog::create([
            'user_id'       => $userId,
            'rfa_reference' => $generatedRef,
            'model_type'    => AoModel::class,
            'model_id'      => 0,
            'status'        => null,
            'old_values'    => null,
            'new_values'    => ['rfa_reference' => $generatedRef],
            'remarks'       => 'Initiated existing RFA item creation.',
        ]);

        // 2. ACTION TAKEN LOG
        ActionTakenLog::create([
            'user_id'       => $userId,
            'rfa_reference' => $generatedRef,
            'subject_type'  => AoModel::class,
            'subject_id'    => 0,
            'action'        => 'AO initiated a new RFA  (existing item code)',
            'remarks'       => 'Generated new RFA with existing item code.',
        ]);

        // 3. ACTIVITY LOG
        ActivityLog::create([
            'user_id'       => $userId,
            'rfa_reference' => $generatedRef,
            'event'         => 'initiated_existing_rfa',
            'description'   => "AO {$user->name} generated reference {$generatedRef} for existing item.",
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
        ]);

        // 4. AUDIT LOG
        AuditLog::create([
            'user_id'        => $userId,
            'rfa_reference'  => $generatedRef,
            'auditable_type' => AoModel::class,
            'auditable_id'   => 0,
            'event'          => 'rfa_existing_initiated',
            'metadata'       => ['rfa_reference' => $generatedRef],
            'url'            => request()->fullUrl(),
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
        ]);

        return $generatedRef;
    });

    return response()->json(['new_ref' => $newRef]);
}


public function getAddFormReferenceNum()
{
    $user   = auth()->user();
    $userId = $user->id;
    $prefix = 'RFA-' . now('Asia/Manila')->format('dmY') . '-';

    $newRef = DB::transaction(function () use ($prefix, $user, $userId) {
        $latestAo = AoModel::where('rfa_reference', 'LIKE', $prefix . '%')
            ->orderBy('rfa_reference', 'desc')
            ->first();

        // Ignore CANCEL rows so cancelled numbers can be reused and re-inserted with status = NULL
        $latestHistory = HistoryLog::where('rfa_reference', 'LIKE', $prefix . '%')
            ->whereNull('status')
            ->orderBy('rfa_reference', 'desc')
            ->first();

        $lastAoNum   = $latestAo ? (int) substr(trim($latestAo->rfa_reference), -3) : 0;
        $lastHistNum = $latestHistory ? (int) substr(trim($latestHistory->rfa_reference), -3) : 0;

        $lastNumber   = max($lastAoNum, $lastHistNum);
        $generatedRef = $prefix . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        // 1. HISTORY LOG
        HistoryLog::create([
            'user_id'       => $userId,
            'rfa_reference' => $generatedRef,
            'model_type'    => AoModel::class,
            'model_id'      => 0,
            'status'        => null,
            'old_values'    => null,
            'new_values'    => ['rfa_reference' => $generatedRef],
            'remarks'       => 'Added another RFA item form.',
        ]);

        // 2. ACTION TAKEN LOG
        ActionTakenLog::create([
            'user_id'       => $userId,
            'rfa_reference' => $generatedRef,
            'subject_type'  => AoModel::class,
            'subject_id'    => 0,
            'action'        => 'AO added another RFA item form',
            'remarks'       => 'Generated new RFA  for additional form .',
        ]);

        // 3. ACTIVITY LOG
        ActivityLog::create([
            'user_id'       => $userId,
            'rfa_reference' => $generatedRef,
            'event'         => 'added_rfa_form',
            'description'   => "AO {$user->name} added another form   {$generatedRef}.",
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
        ]);

        // 4. AUDIT LOG
        AuditLog::create([
            'user_id'        => $userId,
            'rfa_reference'  => $generatedRef,
            'auditable_type' => AoModel::class,
            'auditable_id'   => 0,
            'event'          => 'rfa_add_form_initiated',
            'metadata'       => ['rfa_reference' => $generatedRef],
            'url'            => request()->fullUrl(),
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
        ]);

        return $generatedRef;
    });

    return response()->json(['new_ref' => $newRef]);
}



}



