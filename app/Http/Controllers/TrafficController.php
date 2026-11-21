<?php

namespace App\Http\Controllers;

use App\Models\AoFileLink; // Your actual database model
use App\Models\AoModel;
use App\Models\ArtworkLog;
use App\Models\CommercialArtist;
use App\Models\FileVerification;
use App\Models\User;
use App\Notifications\ArtWorkNotification;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PharIo\Manifest\Email;

class TrafficController extends Controller
{
    
    // public function index() // index
    // {


    //  $totalUsers = User::count(); 
    
    // // 1. Fetch available item counts
    // $totalAvailable = DB::selectOne("
    //     SELECT COUNT(*) as count 
    //     FROM item_lists 
    //     WHERE item_code NOT IN (
    //         SELECT item_code 
    //         FROM time_lines 
    //         WHERE status IS NOT NULL AND status != ''
    //     )
    // ")->count;

    // // 2. Fetch tracking status counter aggregations
    // $totalStart = DB::selectOne("SELECT COUNT(*) as count FROM time_lines WHERE status = 'started'")->count;
    // $totalStop  = DB::selectOne("SELECT COUNT(*) as count FROM time_lines WHERE status = 'stopped'")->count;

    // // Fetch items that are NOT YET inserted into the time_lines table
    // $availableItemsForSelect = DB::table('item_lists')
    //     ->whereNotExists(function ($query) {
    //         $query->select(DB::raw(1))
    //               ->from('time_lines')
    //               ->whereColumn('time_lines.item_code', 'item_lists.item_code');
    //     })
    //     ->select('item_code')
    //     ->get();

    // // 3. JOIN both item_lists AND users tables to get all required metadata
    // $timeLineList = DB::table('time_lines')
    //     ->leftJoin('item_lists', 'time_lines.item_code', '=', 'item_lists.item_code')
    //     ->leftJoin('users', 'time_lines.user_id', '=', 'users.id') // Joins the user accounts
    //     ->select(
    //         'time_lines.*', 
    //         'item_lists.item_name',
    //         'users.name as user_name' // Aliased to prevent naming conflicts
    //     )
    //     ->orderBy('time_lines.item_code')
    //     ->orderBy('time_lines.start_time', 'asc')
    //     ->get(); 


             
    // // Pass the records to your blade file

    // $gd_records_links = AoModel::where('to_email', Auth::user()->email)
    //             ->orderBy('created_at', 'desc')
    //             ->get();
    //             // dd($records);
    // // Pass the records to your blade file
    // // return view('gd.commercial', compact('records')); //ao send file link to gd

    //     $getAtworkLogs = ArtworkLog::with(['user', 'file'])
    //         ->latest()
    //         ->take(15)
    //         ->get();
    // $logs = $getAtworkLogs;

    //  return view('gd.commercial', [
    //     'totalUsers'              => $totalUsers,
    //     'totalAvailable'          => $totalAvailable,
    //     'totalStart'              => $totalStart,
    //     'totalStop'               => $totalStop,
    //     'timeLineList'            => $timeLineList,
    //     'availableItemsForSelect' => $availableItemsForSelect, // Added variable
    //     'gd_records_links'        =>$gd_records_links,
    //     'logs'                      =>$logs,
        
    // ]); 

       
    // }



    public function trafficFileRecievedLink() // recieve by gd supervisor
    {
  
        try{
            // $fileRecieved = AoModel::where('to_email', Auth::user()->email)
            //     ->orderBy('created_at', 'desc')
            //     ->get();

                 $fileRecieved = getFileReceivedLinkById() 
                ->orWhere('assigned_to_role_id','!=',3)
                // ->orWhere('assigned_to_role_id',1)
                // ->whereIn('status',['Verified','GD Assess','GD to Assign Artist'])
                ->orderBy('created_at', 'desc')
                ->get();//helper function

        // Pluck item_codes from AoModel collection
        $itemCodes = $fileRecieved->pluck('item_code')->filter()->toArray();

            $forApprovalSuppliedVerified = FileVerification::whereIn('item_code', $itemCodes)
        ->where('status','GD Assess')
        ->pluck('item_code')
        ->toArray();

        // Find which item_codes exist in the FileVerification table in 1 query
        $verifiedItemCodes = FileVerification::whereIn('item_code', $itemCodes)
            ->whereIn('status',['GD Assess','Assigned To Artist','GD to Assign Artist'])
            ->pluck('item_code')
            ->toArray();

        // Find which item_codes exist in the FileVerification table in 1 query
       
            return datatables()->of($fileRecieved)
            // ->addColumn('action',function($data){
            //     $button = '<div class="btn-group btn-group-sm">';
            //     $button .= '<a href="'. route('users.edit',['user' => $data->id]) .'" class="btn btn-info"><i class="fas fa-solid fa-pen"></i></a>';
            //     // $button .= '<a href="/employees/' . $data->id . '" class="btn btn-info"><i class="fas fa-solid fa-eye"></i></a>';
            //     $button .= '</div>';
            //     return $button;
            // })

      
            ->addColumn('ao_name',function($data){
                // dd($data->rfa_reference);
                 return strtoupper($data->ao_name);
            })
            // ->addColumn('ao_user_id',function($data){
            //     return strtoupper($data->user_id);
            // })
             ->addColumn('from_email',function($data){
                return strtoupper($data->from_email);
            })
            //  ->addColumn('to_email',function($data){
            //     return strtoupper($data->to_email);
            // })
            ->addColumn('file_link', function($data){
            return $data->file_link;

            })    
            ->addColumn('status', function($data){
            return $data->status;

            })  
            // Add custom column checking if item_code exists in FileVerification
            ->addColumn('is_verified', function($data) use ($verifiedItemCodes) {
                return in_array($data->item_code, $verifiedItemCodes);
            })
                       ->addColumn('rfa_reference', function($data){
            return $data->rfa_reference;

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

    public function FileRecievedLinkShowAll_NoItemCode() // recieve by gd supervisor
    {
        try{
            // $fileRecieved = AoModel::where('to_email', Auth::user()->email)
            //     ->orderBy('created_at', 'desc')
            //     ->get();

            $fileRecieved = getFileReceivedLinkById_NoItemCode() 
                ->orderBy('created_at', 'desc')
                ->get();//helper function


        // Pluck item_codes from AoModel collection
        $itemCodes = $fileRecieved->pluck('item_code')->filter()->toArray();

        // Find which item_codes exist in the FileVerification table in 1 query
       
            return datatables()->of($fileRecieved)
                 
            ->addColumn('ao_name',function($data){
               
                 return strtoupper($data->ao_name);
            })
            // ->addColumn('ao_user_id',function($data){
            //     return strtoupper($data->user_id);
            // })
             ->addColumn('from_email',function($data){
                return strtoupper($data->from_email);
            })
            //  ->addColumn('to_email',function($data){
            //     return strtoupper($data->to_email);
            // })
            ->addColumn('file_link', function($data){
            return $data->file_link;

            })    
            ->addColumn('status', function($data){
            return $data->status;

            })      
             ->addColumn('created_at',function($data){
                return strtoupper($data->created_at);
            })
                ->addColumn('rfa_reference',function($data){
                return strtoupper($data->rfa_reference);
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

    public function showSuppliedFileVerified($id)
    {
        try {
            // Reuses helper query + security check on to_email
            $data = getFileReceivedLinkById()->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data'   => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Record not found or access denied.'
            ], 404);
        }
    }
        public function traffic_officer() // index
    {


     $totalUsers = User::count(); 
    
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


             
    // Pass the records to your blade file

    $gd_records_links = AoModel::where('to_email', Auth::user()->email)
                ->orderBy('created_at', 'desc')
                ->get();
                // dd($records);
    // Pass the records to your blade file
    // return view('gd.commercial', compact('records')); //ao send file link to gd

        $getAtworkLogs = ArtworkLog::with(['user', 'file'])
            ->latest()
            ->take(15)
            ->get();
    $logs = $getAtworkLogs;

    //get all artist
     $artistLists = User::where('role_id', 4)->get();

     return view('traffic_officer.traffic_officer', [
        'totalUsers'              => $totalUsers,
        'totalAvailable'          => $totalAvailable,
        'totalStart'              => $totalStart,
        'totalStop'               => $totalStop,
        'timeLineList'            => $timeLineList,
        'availableItemsForSelect' => $availableItemsForSelect, // Added variable
        'gd_records_links'        =>$gd_records_links,
        // 'logs'                      =>$logs,
        'artistLists'           => $artistLists,
        
    ]); 

       
    }

   

    public function updateFileStatus(Request $request)
{
    $record = AoModel::findOrFail($request->id);

    $record->status = $request->status;
    if (blank($record->is_opened)) {
    $record->is_opened = now();
    }

    $record->save();

    return response()->json([
        'success' => true  
    ]);
}

    public function store(Request $request)
    {
        // dd(Auth::user());
        // dd($request);
        $validated = $request->validate([
            'employee_email' => 'required|email',
            'link'           => 'required'
        ]);

        // 1. Save directly into your database and capture the created object
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthorized action.');
        }
        $record = new AoModel();

        $record->ao_user_id = $user->emp_id;
        $record->from_email = $user->email;
        $record->ao_name    = $user->name;
        $record->to_email   = $validated['employee_email'];
        $record->file_link  = $validated['link'];

        $record->save();


        // 2. Send email only if the database record was successfully created
        if ($record) {
            try {
                $sharedLink = $validated['link'];
                
                Mail::html("
                    <p>Hello,</p>
                    <p>An administrator has shared a link with you:</p>
                    <p><a href='{$sharedLink}' target='_blank'>{$sharedLink}</a></p>
                    <p>Thank you!</p>
                ", function ($message) use ($validated) {
                    $message->to($validated['employee_email'])
                            ->subject('A New Link Has Been Shared With You');
                });
            } catch (\Exception $e) {
                // The database insert succeeded, but the mail server failed
                // return redirect()->back()->with('warning', 'Link recorded in database, but the email failed to send.');
                 return redirect()->route('ao.SendFileLink')->with('warning', 'Link recorded in database, but the email failed to send.');
            }
        }

        // return redirect()->back()->with('success', 'Link successfully recorded and emailed to the employee!');
        return redirect()->route('ao.sendFileLink')->with('success', 'Link successfully recorded and emailed to the employee!');
    }

public function assignToArtist(Request $request)
{
    $request->validate([
        'item_code'       => 'required',
        'assigned_artist' => 'required',
    ]);

    // Check if the item code already exists
    $exists = CommercialArtist::where('item_code', $request->item_code)->exists();

    if ($exists) {
        return response()->json([
            'success' => false,
            'message' => 'Item code already exists!'
        ], 422);
    }

    // Save if it does not exist
    $item = new CommercialArtist();
    $item->item_code  = $request->item_code;
    $item->user_id    = $request->assigned_artist;
    // $item->ref_number = $request->ref_number ?? null; // Added fallback to avoid undefined field errors
    $item->save();

    // Send notification to artist
    $recipientUser = \App\Models\User::where('emp_id', $request->assigned_artist)->first();

    if ($recipientUser) {
        $recipientUser->notify(new ArtWorkNotification($item, 'gd_to_artist'));
    }

    // 2. Find the corresponding artwork asset
    $artwork = AoModel::where('item_code', $request->item_code)->first();

    if ($artwork) {
        // 3. Manually push a history entry to the logging pipeline
        // 💡 FIX: Changed $artist to $item to match the model instance variable declared above
        $artwork->activityLogs()->create([
            'user_id'     => auth()->id(),
            'log_type'    => 'history',
            'event'       => 'artist_assigned',
            'description' => (auth()->user()?->name ?? 'Supervisor') . " assigned artist [" . ($recipientUser->name ?? $request->assigned_artist) . "] to Item Code [{$request->item_code}].",
            'properties'  => [
                // 'ref_number'     => $item->ref_number,
                'artist_user_id' => $item->user_id,
                'artist_name'    => $recipientUser->name ?? 'Unknown Artist'
            ]
        ]);
    }
   
    return response()->json([
        'success' => true,
        'message' => 'Item Assigned To Artist!'
    ]);
}


public function gdArtist(){
    
return view('gd.commercial.artist');
}

        
}
