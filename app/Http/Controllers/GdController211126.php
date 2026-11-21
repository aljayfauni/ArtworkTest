<?php

namespace App\Http\Controllers;

use App\Models\AoFileLink; // Your actual database model
use App\Models\AoModel;
use App\Models\ArtworkLog;
use App\Models\CommercialArtist;
use App\Models\Companion;
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

class GdController extends Controller
{
    
    public function index() // index
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

     return view('gd.commercial', [
        'totalUsers'              => $totalUsers,
        'totalAvailable'          => $totalAvailable,
        'totalStart'              => $totalStart,
        'totalStop'               => $totalStop,
        'timeLineList'            => $timeLineList,
        'availableItemsForSelect' => $availableItemsForSelect, // Added variable
        'gd_records_links'        =>$gd_records_links,
        'logs'                      =>$logs,
        
    ]); 

       
    }



    public function FileRecievedLinkShowAll() // recieve by gd supervisor
    {
        try{
            // $fileRecieved = AoModel::where('to_email', Auth::user()->email)
            //     ->orderBy('created_at', 'desc')
            //     ->get();

            $fileRecieved = getFileReceivedLinkById() 
                ->orderBy('created_at', 'desc')
                ->get();//helper function
                


        // Pluck item_codes from AoModel collection
        $itemCodes = $fileRecieved->pluck('item_code')->filter()->toArray();

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
        public function gd_supervisor() // index
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

     return view('gd.gd_supervisor', [
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

    // * Display the combined form view.
    //  */
    // public function create(): View
    // {
    //     $rfaNumber = 'RFA-' . date('Ymd') . '-' . rand(1000, 9999);
        
    //     return view('artwork-request.create', compact('rfaNumber'));
    // }


    // 
   
    /**
     * Store artwork verification and specification details.
     */
    // public function saveFileVerification(Request $request): RedirectResponse
    // {
    //     $validated = $request->validate([
    //         // Form 1: Supplied File Verification (GD)
    //         'client'                        => 'required|string|max:255',
    //         'item_description'              => 'required|string|max:255',
    //         'file_source'                   => 'nullable|array',
    //         'file_source.*'                 => 'string',
    //         'file_source_other'             => 'nullable|string|max:255',
    //         'application'                   => 'nullable|array',
    //         'application.*'                 => 'string',
    //         'size_spread'                   => 'nullable|string|max:255',
    //         'size_folded'                   => 'nullable|string|max:255',
    //         'color'                         => 'nullable|array',
    //         'color.*'                       => 'string',
    //         'text_specs'                    => 'nullable|array',
    //         'text_specs.*'                  => 'string',
    //         'other_specs'                   => 'nullable|array',
    //         'other_specs.*'                 => 'string',
    //         'image_type'                    => 'nullable|array',
    //         'image_type.*'                  => 'string',
    //         'number_of_colors'              => 'nullable|string|max:255',
    //         'verified_by_date'              => 'nullable|string|max:255',
    //         'spread_h'                      => 'nullable|string|max:255',
    //         'spread_w'                      => 'nullable|string|max:255',
    //         'spread_l'                      => 'nullable|string|max:255',
    //         'folded_h'                      => 'nullable|string|max:255',
    //         'folded_w'                      => 'nullable|string|max:255',

          
            
    //     ]);

    //     // Save record into database
    //     $artworkRequest = ArtworkRequest::create([
    //         'rfa_number'                 => $request->input('rfa_number', 'RFA-' . time()),
    //         'client'                     => $validated['client'],
    //         'item_description'           => $validated['item_description'],
    //         'file_source'                => $validated['file_source'] ?? [],
    //         'file_source_other'          => $validated['file_source_other'] ?? null,
    //         'application'                => $validated['application'] ?? [],
    //         'size_spread'                => $validated['size_spread'] ?? null,
    //         'size_folded'                => $validated['size_folded'] ?? null,
    //         'spread_h'                   => $validated['spread_h'] ?? null,
    //         'spread_w'                   => $validated['spread_w'] ?? null,
    //         'spread_l'                   => $validated['spread_l'] ?? null,
    //         'folded_h'                   => $validated['folded_h'] ?? null,
    //         'folded_w'                   => $validated['folded_w'] ?? null,
    //         'folded_l'                   => $validated['folded_l'] ?? null,
    //         'color'                      => $validated['color'] ?? [],
    //         'text_specs'                 => $validated['text_specs'] ?? [],
    //         'other_specs'                => $validated['other_specs'] ?? [],
    //         'image_type'                 => $validated['image_type'] ?? [],
    //         'number_of_colors'           => $validated['number_of_colors'] ?? null,
    //         'verified_by_date'           => $validated['verified_by_date'] ?? null,

            
    //     ]);

    //     return redirect()->back()->with('success', 'Artwork request & specifications saved successfully!');
    // }

    // public function saveSpecification(Request $request): RedirectResponse
    // {
    //     $validated = $request->validate([
    //         // Form 1: Supplied File Verification (GD)
          

          
    //         'item_code'                     => 'required|string|max:255',
    //         'spec_size_spread'              => 'nullable|string|max:255',
    //         'spec_size_folded'              => 'nullable|string|max:255',
    //         'printing_process'              => 'nullable|array',
    //         'printing_process.*'            => 'string',
    //         'substrate'                     => 'nullable|string|max:255',
    //         'spec_number_of_colors'         => 'nullable|string|max:255',
    //         'special_process'               => 'nullable|array',
    //         'special_process.*'             => 'string',
    //         'output_digital_proof_check'    => 'nullable|boolean',
    //         'output_digital_proof_pc'       => 'nullable|integer|min:0',
    //         'output_mockup_check'           => 'nullable|boolean',
    //         'output_mockup_pc'              => 'nullable|integer|min:0',
    //         'efile_format'                  => 'nullable|array',
    //         'efile_format.*'                => 'string',
    //         'color_guide'                   => 'nullable|array',
    //         'color_guide.*'                 => 'string',
    //         'color_guide_others'            => 'nullable|string|max:255',
    //         'reference_item_code'           => 'nullable|string|max:255',
    //         'requested_by'                  => 'nullable|string|max:255',
    //         'date_requested'                => 'nullable|date',
    //         'date_required'                 => 'nullable|date',
    //         'instruction_agreement'         => 'nullable|string',
    //     ]);

    //     // Save record into database
    //     $artworkRequest = ArtworkRequest::create([
   

    //         'item_code'                  => $validated['item_code'],
    //         'spec_size_spread'           => $validated['spec_size_spread'] ?? null,
    //         'spec_size_folded'            => $validated['spec_size_folded'] ?? null,
    //         'printing_process'           => $validated['printing_process'] ?? [],
    //         'substrate'                  => $validated['substrate'] ?? null,
    //         'spec_number_of_colors'      => $validated['spec_number_of_colors'] ?? null,
    //         'special_process'            => $validated['special_process'] ?? [],
    //         'output_digital_proof_check' => $request->has('output_digital_proof_check'),
    //         'output_digital_proof_pc'    => $validated['output_digital_proof_pc'] ?? null,
    //         'output_mockup_check'        => $request->has('output_mockup_check'),
    //         'output_mockup_pc'           => $validated['output_mockup_pc'] ?? null,
    //         'efile_format'               => $validated['efile_format'] ?? [],
    //         'color_guide'                => $validated['color_guide'] ?? [],
    //         'color_guide_others'         => $validated['color_guide_others'] ?? null,
    //         'reference_item_code'        => $validated['reference_item_code'] ?? null,
    //         'requested_by'               => $validated['requested_by'] ?? null,
    //         'date_requested'             => $validated['date_requested'] ?? null,
    //         'date_required'              => $validated['date_required'] ?? null,
    //         'instruction_agreement'      => $validated['instruction_agreement'] ?? null,
    //         'document_control_code'      => 'UGEC - PDP - F169 R01',
    //     ]);

    //     return redirect()->back()->with('success', 'Artwork request & specifications saved successfully!');
    // }

//         public function assignToArtist(Request $request)
// {
//     $request->validate([
//         'item_code'       => 'required',
//         'assigned_artist' => 'required',
//     ]);

//     // Check if the item code already exists
//     $exists = CommercialArtist::where('item_code', $request->item_code)->exists();

//     if ($exists) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Item code already exists!'
//         ], 422);
//     }

//     // Save if it does not exist
//     $item = new CommercialArtist();
//     $item->item_code = $request->item_code;
//     $item->user_id   = $request->assigned_artist;
//     $item->save();

    
//     // send notification to artist
//     $recipientUser = \App\Models\User::where('emp_id', $request->assigned_artist)->first();

//     if ($recipientUser) {
//         $recipientUser->notify(new ArtWorkNotification($item,'gd_to_artist'));
//     }

//     // 2. Find the corresponding artwork asset
//     $artwork = AoModel::where('item_code', $request->item_code)->first();

//     if ($artwork) {
//         // 3. Manually push a history entry to the logging pipeline
//         $artwork->activityLogs()->create([
//             'user_id'     => auth()->id(),
//             'log_type'    => 'history',
//             'event'       => 'artist_assigned',
//             'description' => "Supervisor assigned a new commercial artist to Item Code [{$request->item_code}].",
//             'properties'  => [
//                 'ref_number' => $artist->ref_number,
//                 'artist_user_id' => $artist->user_id
//             ]
//         ]);
//     }
   
    
//     return response()->json([
//         'success' => true,
//         'message' => 'Item Assigned To Artist!'
//     ]);
// }

// public function assignToArtist(Request $request)
// {
//     $request->validate([
//         'item_code'       => 'required',
//         'assigned_artist' => 'required',
//     ]);

//     // Check if the item code already exists
//     $exists = CommercialArtist::where('item_code', $request->item_code)->exists();

//     if ($exists) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Item code already exists!'
//         ], 422);
//     }

//     // Save if it does not exist
//     $item = new CommercialArtist();
//     $item->item_code  = $request->item_code;
//     $item->user_id    = $request->assigned_artist;
//     // $item->ref_number = $request->ref_number ?? null; // Added fallback to avoid undefined field errors
//     $item->save();

//     // Send notification to artist
//     $recipientUser = \App\Models\User::where('emp_id', $request->assigned_artist)->first();

//     if ($recipientUser) {
//         $recipientUser->notify(new ArtWorkNotification($item, 'gd_to_artist'));
//     }

//     // 2. Find the corresponding artwork asset
//     $artwork = AoModel::where('item_code', $request->item_code)->first();
//     $file_verification =  FileVerification::where('item_code', $request->item_code)->first();
//     $file_verification->status = "Assigned To Artist";
//     $file_verification->save();
//     if ($artwork) {
  
//     // status 
//     $artwork->status = "Assigned To Artists";
//     $artwork->save();
//         // 3. Manually push a history entry to the logging pipeline
//         // 💡 FIX: Changed $artist to $item to match the model instance variable declared above
//         $artwork->activityLogs()->create([
//             'user_id'     => auth()->id(),
//             'log_type'    => 'history',
//             'event'       => 'artist_assigned',
//             'description' => (auth()->user()?->name ?? 'Supervisor') . " assigned artist [" . ($recipientUser->name ?? $request->assigned_artist) . "] to Item Code [{$request->item_code}].",
//             'properties'  => [
//                 // 'ref_number'     => $item->ref_number,
//                 'artist_user_id' => $item->user_id,
//                 'artist_name'    => $recipientUser->name ?? 'Unknown Artist'
//             ]
//         ]);
//     }
   
//     return response()->json([
//         'success' => true,
//         'message' => 'Item Assigned To Artist!'
//     ]);
// }

// public function assignToArtist(Request $request)
// {
//     $validated = $request->validate([
//         'item_code'       => 'required|string',
//         'assigned_artist' => 'required',
//     ]);

//     // Check if the item code already exists
//     if (CommercialArtist::where('item_code', $validated['item_code'])->exists()) {
//         return redirect()->back()
//             ->withInput()
//             ->with('error', 'Item code already exists!');
//     }

//     DB::transaction(function () use ($validated,$request) {

  
//         // 1. Create assignment record
//         $item = new CommercialArtist();
//         $item->item_code = $validated['item_code'];
//         $item->user_id   = $request['companion'];
//         $item->save();

//         //added companion

//         $companion = new Companion();
//         $companion->item_code = $validated['item_code'];
//         $companion->item_code = $validated['item_code'];


//         // 2. Update FileVerification safely
//         $fileVerification = FileVerification::where('item_code', $validated['item_code'])->first();
//         if ($fileVerification) {
//             $fileVerification->status = 'Assigned To Artist';
//             $fileVerification->save();
//         }

//         // 3. Update Artwork & log activity
//         $artwork = AoModel::where('item_code', $validated['item_code'])->first();
//         $recipientUser = User::where('emp_id', $validated['assigned_artist'])->first();

//         if ($artwork) {
//             $artwork->status = 'Assigned To Artist';
//             $artwork->save();

//             $assignerName = auth()->user()?->name ?? 'Supervisor';
//             $artistName   = $recipientUser->name ?? $validated['assigned_artist'];

//             $artwork->activityLogs()->create([
//                 'user_id'     => auth()->id(),
//                 'log_type'    => 'history',
//                 'event'       => 'artist_assigned',
//                 'description' => "{$assignerName} assigned artist [{$artistName}] to Item Code [{$validated['item_code']}].",
//                 'properties'  => [
//                     'artist_user_id' => $item->user_id,
//                     'artist_name'    => $recipientUser->name ?? 'Unknown Artist',
//                 ]
//             ]);
//         }

//         // 4. Send notification after database writes succeed
//         if ($recipientUser) {
//             $recipientUser->notify(new ArtWorkNotification($item, 'gd_to_artist'));
//         }

//          return redirect()->route('gd_supervisor')->with('success', 'Item Assigned To Artist!');
//     });

   

// }

public function assignToArtist(Request $request)
{
    $validated = $request->validate([
        'item_code'       => 'required|string',
        'assigned_artist' => 'nullable|string',
        'companions'      => 'nullable|array',
        'companions.*'    => 'string',
    ]);

    $item = null;

    DB::transaction(function () use ($validated, &$item) {
        $itemCode       = $validated['item_code'];
        $assignedArtist = $validated['assigned_artist'] ?? null;
        $companions     = $validated['companions'] ?? [];

        // 1. Update existing assignment or create a new one
        $item = CommercialArtist::firstOrNew(['item_code' => $itemCode]);
        $item->user_id = $assignedArtist;
        $item->save();

        // 2. If the new main artist was previously a companion, remove them from companions
        if ($assignedArtist) {
            Companion::where('item_code', $itemCode)
                ->where('user_id', $assignedArtist)
                ->delete();
        }

        // 3. Insert or update companions (excluding duplicates and the main artist)
        foreach (array_unique($companions) as $companionEmpId) {
            if ($companionEmpId != $assignedArtist) {
                $companion = Companion::where('item_code', $itemCode)
                    ->where('user_id', $companionEmpId)
                    ->first();

                if (!$companion) {
                    $companion = new Companion();
                }

                $companion->item_code = $itemCode;
                $companion->user_id   = $companionEmpId;
                $companion->save();
            }
        }

        // 4. Update FileVerification status
        $fileVerification = FileVerification::where('item_code', $itemCode)->first();
        if ($fileVerification) {
            $fileVerification->status = 'Assigned To Artist';
            $fileVerification->save();
        }

        // 5. Update Artwork & log activity
        $artwork       = AoModel::where('item_code', $itemCode)->first();
        $recipientUser = $assignedArtist ? User::where('emp_id', $assignedArtist)->first() : null;

        if ($artwork) {
            $artwork->status = 'Assigned To Artist';
            $artwork->save();

            $assignerName     = auth()->user()?->name ?? 'Supervisor';
            $artistName       = $recipientUser->name ?? ($assignedArtist ?? 'Unassigned');
            $activeCompanions = Companion::where('item_code', $itemCode)->pluck('user_id')->toArray();

            $artwork->activityLogs()->create([
                'user_id'     => auth()->id(),
                'log_type'    => 'history',
                'event'       => 'artist_assigned',
                'description' => "{$assignerName} assigned artist [{$artistName}] to Item Code [{$itemCode}].",
                'properties'  => [
                    'artist_user_id' => $item->user_id,
                    'artist_name'    => $recipientUser->name ?? 'Unknown Artist',
                    'companions'     => $activeCompanions,
                ],
            ]);
        }

        // 6. Send notification
        if ($recipientUser) {
            $recipientUser->notify(new ArtWorkNotification($item, 'gd_to_artist'));
        }
    });

    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'status'  => 'success',
            'message' => 'Item Assigned To Artist!',
        ]);
    }

    return redirect()->route('gd_supervisor')->with('success', 'Item Assigned To Artist!');
}


public function reassignArtist(Request $request)
{
    $validated = $request->validate([
        'item_code'       => 'required|string',
        'assigned_artist' => 'required|string',
        'companions'      => 'nullable|array',
        'companions.*'    => 'string',
        'remarks'         => 'nullable|string|max:500',
    ]);

    // Find the currently active assignment
    $previousAssignment = CommercialArtist::where('item_code', $validated['item_code'])
        ->whereNull('unassigned_at')
        ->latest('id')
        ->first();

    if (!$previousAssignment) {
        return response()->json([
            'message' => 'Item code has not been assigned yet. Please use Assign first.'
        ], 422);
    }

    if ($previousAssignment->user_id == $validated['assigned_artist']) {
        return response()->json([
            'message' => 'This artist is already the current assigned artist.'
        ], 422);
    }

    DB::transaction(function () use ($validated, $previousAssignment) {
        $itemCode    = $validated['item_code'];
        $newArtistId = $validated['assigned_artist'];
        $oldArtistId = $previousAssignment->user_id;
        $companions  = $validated['companions'] ?? [];
        $now         = now();

        // 1. Close the previous artist's session & calculate time spent as an integer
        $startTime = \Carbon\Carbon::parse($previousAssignment->assigned_at ?? $previousAssignment->created_at);
        $previousAssignment->unassigned_at    = $now;
        $previousAssignment->duration_minutes = (int) round($startTime->diffInMinutes($now));
        $previousAssignment->status           = 'Reassigned';
        $previousAssignment->save();

        // 2. Create a NEW row for the new artist with a fresh start timestamp
        $newAssignment = new CommercialArtist();
        $newAssignment->item_code   = $itemCode;
        $newAssignment->user_id     = $newArtistId;
        $newAssignment->assigned_at = $now;
        $newAssignment->status      = 'Active';
        $newAssignment->save();

        // 3. Remove new main artist from companions if they were previously one
        Companion::where('item_code', $itemCode)
            ->where('user_id', $newArtistId)
            ->delete();

        // 4. Keep existing companions & insert any new ones without duplicates
        foreach (array_unique($companions) as $companionEmpId) {
            if ($companionEmpId != $newArtistId) {
                $companion = Companion::where('item_code', $itemCode)
                    ->where('user_id', $companionEmpId)
                    ->first();

                if (!$companion) {
                    $companion = new Companion();
                }

                $companion->item_code = $itemCode;
                $companion->user_id   = $companionEmpId;
                $companion->save();
            }
        }

        // 5. Log activity & notify new artist
        $artwork       = AoModel::where('item_code', $itemCode)->first();
        $recipientUser = User::where('emp_id', $newArtistId)->first();

        if ($artwork) {
            $assignerName     = auth()->user()?->name ?? 'Supervisor';
            $artistName       = $recipientUser->name ?? $newArtistId;
            $remarkText       = !empty($validated['remarks']) ? " Remarks: {$validated['remarks']}" : '';
            $activeCompanions = Companion::where('item_code', $itemCode)->pluck('user_id')->toArray();

            $artwork->activityLogs()->create([
                'user_id'     => auth()->id(),
                'log_type'    => 'history',
                'event'       => 'artist_reassigned',
                'description' => "{$assignerName} re-assigned item [{$itemCode}] to [{$artistName}].{$remarkText}",
                'properties'  => [
                    'old_artist_id'      => $oldArtistId,
                    'old_artist_minutes' => $previousAssignment->duration_minutes,
                    'new_artist_id'      => $newArtistId,
                    'companions'         => $activeCompanions,
                    'remarks'            => $validated['remarks'] ?? null,
                ]
            ]);
        }

        if ($recipientUser) {
            $recipientUser->notify(new ArtWorkNotification($newAssignment, 'gd_to_artist'));
        }
    });

    return response()->json([
        'status'  => 'success',
        'message' => 'Item successfully re-assigned!'
    ]);
}
public function gdArtist(){
    
return view('gd.commercial.artist');
}

        
}
