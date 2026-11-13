<?php

namespace App\Http\Controllers;

use App\Models\AoFileLink; // Your actual database model
use App\Models\AoModel;
use App\Models\ArtworkLog;
use App\Models\User;
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

      public function FileSendLinkShowAll()
    {
        try{
           $fileSend = AoModel::orderBy('created_at', 'desc')->get();
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

    // public function store(Request $request)
    // {
    //     dd($request);
    //     $validated = $request->validate([
    //         'employee_email' => 'required|email',
    //         'link'           => 'required|url'
    //     ]);

   
    //     // Save directly into your database mapping inputs to database columns
    //     AoModel::create([
    //         'from_email' => Auth::user()->email,
    //         'ao_name'    => Auth::user()->first_name . ' ' . Auth::user()->last_name,
    //         'ao_user_id' => Auth::id(), // Shorthand for Auth::user()->id
    //         'to_email'   => $validated['employee_email'],
    //         'file_link'  => $validated['link'],
    //     ]);

    //     // 2. Send email directly using an inline layout closure
    //     $sharedLink = $validated['link'];
        
    //     Mail::html("
    //         <p>Hello,</p>
    //         <p>An administrator has shared a link with you:</p>
    //         <p><a href='{$sharedLink}' target='_blank'>{$sharedLink}</a></p>
    //         <p>Thank you!</p>
    //     ", function ($message) use ($validated) {
    //         $message->to($validated['employee_email'])
    //                 ->subject('A New Link Has Been Shared With You');
    //     });

    //     return redirect()->back()->with('success', 'Link successfully recorded and emailed to the employee!');
    // }

    // public function store(Request $request)
    // {
    //     // dd(Auth::user());
    //     // dd($request);
    //     $validated = $request->validate([
    //         'employee_email' => 'required|email',
    //         'link'           => 'required'
    //     ]);

    //     // 1. Save directly into your database and capture the created object
    //     $user = Auth::user();
    //     if (!$user) {
    //         abort(401, 'Unauthorized action.');
    //     }
    //     $record = new AoModel();

    //     $record->ao_user_id = $user->emp_id;
    //     $record->from_email = $user->email;
    //     $record->ao_name    = $user->name;
    //     $record->to_email   = $validated['employee_email'];
    //     $record->file_link  = $validated['link'];

    //     $record->save();


    //     // 2. Send email only if the database record was successfully created
    //     if ($record) {
    //         try {
    //             $sharedLink = $validated['link'];
                
    //             Mail::html("
    //                 <p>Hello,</p>
    //                 <p>An administrator has shared a link with you:</p>
    //                 <p><a href='{$sharedLink}' target='_blank'>{$sharedLink}</a></p>
    //                 <p>Thank you!</p>
    //             ", function ($message) use ($validated) {
    //                 $message->to($validated['employee_email'])
    //                         ->subject('A New Link Has Been Shared With You');
    //             });
    //         } catch (\Exception $e) {
    //             // The database insert succeeded, but the mail server failed
    //             // return redirect()->back()->with('warning', 'Link recorded in database, but the email failed to send.');
    //              return redirect()->route('ao.SendFileLink')->with('warning', 'Link recorded in database, but the email failed to send.');
    //         }
    //     }

    //     // return redirect()->back()->with('success', 'Link successfully recorded and emailed to the employee!');
    //     return redirect()->route('ao.sendFileLink')->with('success', 'Link successfully recorded and emailed to the employee!');
    // }

    // public function store(Request $request)
    // {
            
    //     // 1. Validate the input array and link
    //     $validated = $request->validate([
    //         'employee_email'   => 'required|array|min:1',
    //         'employee_email.*' => 'required',
    //         'link'             => 'required'
    //     ]);

    //     $user = Auth::user();
    //     if (!$user) {
    //         abort(401, 'Unauthorized action.');
    //     }

    //     $emails = $validated['employee_email'];
    //     $sharedLink = $validated['link'];

    //     // 2. Filter out non-email values (like INTERNAL_NOTE) to get valid recipient emails
    //     $recipientEmails = array_values(array_filter($emails, function ($item) {
    //         return filter_var($item, FILTER_VALIDATE_EMAIL);
    //     }));

    //     $savedRecords = [];
    //     $failedMails = 0;

    //     // 2. Loop through each email and create a separate database row
    //     foreach ($emails as $email) {
    //         $record = new AoModel();
    //         $record->ao_user_id = $user->emp_id;
    //         $record->from_email = $user->email;
    //         $record->ao_name    = $user->name;
    //         $record->to_email   = $email; // Stores a single email per row
    //         $record->file_link  = $sharedLink;
    //         $record->save();

    //         $savedRecords[] = $record;

    //         // 3. Send email only if it's a valid email address (skips special options like INTERNAL_NOTE)
    
    //     if (!empty($recipientEmails)) {
    //         try {
    //             Mail::html("
    //                 <p>Hello,</p>
    //                 <p>An administrator has shared a link with you:</p>
    //                 <p><a href='{$sharedLink}' target='_blank'>{$sharedLink}</a></p>
    //                 <p>Thank you!</p>
    //             ", function ($message) use ($recipientEmails) {
    //                 // Passing an array sends the email to all addresses simultaneously
    //                 $message->to($recipientEmails)
    //                         ->subject('A New Link Has Been Shared With You');
    //             });
    //         } catch (\Exception $e) {
    //             return redirect()->route('ao.sendFileLink')
    //                 ->with('warning', count($savedRecords). "record(s) created, but the email failed to send.");
    //         }
    //     }
            
    //     }

    //     // 4. Return user notification based on send status

    //     return redirect()->route('ao.sendFileLink')
    //         ->with('success', count($savedRecords) . ' record(s) successfully created and emailed!');
    // }

    public function store(Request $request)
    {
   
        // 1. Validate the input array and link
        $validated = $request->validate([
            'employee_email'   => 'required|array|min:1',
            'employee_email.*' => 'required',
            'link'             => 'required'
        ]);

        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthorized action.');
        }

        $emails = $validated['employee_email'];
        $sharedLink = $validated['link'];

        // 2. Filter out non-email values (like INTERNAL_NOTE) for actual email sending
        $recipientEmails = array_values(array_filter($emails, function ($item) {
            return filter_var($item, FILTER_VALIDATE_EMAIL);
        }));

        $savedRecords = [];

        // 3. Create a database row for EVERY selected email
        $latestId = AoModel::max('id') ?? 0;
        foreach ($emails as $email) {
            
            $record = new AoModel();
            $record->item_id = $latestId;
            $record->item_code = $request->item_code;
            $record->ao_user_id = $user->emp_id;
            $record->from_email = $user->email;
            $record->ao_name    = $user->name;
            $record->to_email   = $email; // Stores single email per row
            $record->file_link  = $sharedLink;
            $record->file_info  = $request->file_info;
            $record->spread_size  = $request->spread_size;
            $record->spread_h  = $request->spread_h;
            $record->spread_w  = $request->spread_w;
            $record->spread_l  = $request->spread_l;
            $record->folded_size  = $request->folded_size;
            $record->folded_h  = $request->folded_h;
            $record->folded_w  = $request->folded_w;
            $record->folded_l  = $request->folded_l;
            $record->colors    = $request->colors;
            $record->num_of_colors  = $request->num_colors;
            $record->image_type   = $request->image_type;
            $record->client_name  = $request->client_name;
            $record->description  = $request->description;
            $record->is_sent  = 1 ?? 0;
            $record->save();

            $this->logActivity($record, 'File Link Sent', $record->created_at);
         
            $savedRecords[] = $record;
             
        }
      
        // 4. Send ONE email to all valid recipients (OUTSIDE the loop)
        if (!empty($recipientEmails)) {
            try {
                Mail::html("
                    <p>Hello,</p>
                    <p>An administrator has shared a link with you:</p>
                    <p><a href='{$sharedLink}' target='_blank'>{$sharedLink}</a></p>
                    <p>Thank you!</p>
                ", function ($message) use ($recipientEmails) {
                    $message->to($recipientEmails)
                            ->subject('A New Link Has Been Shared With You');
                });
            } catch (\Exception $e) {
                return redirect()->route('ao.sendFileLink')
                    ->with('warning', count($savedRecords) . " record(s) created, but the email failed to send.");
            }
        }



        return redirect()->route('ao.sendFileLink')
            ->with('success', count($savedRecords) . ' record(s) successfully created and emailed!');
    }

    public function getRfaFileList(){

        $records = AoModel::where('ao_user_id', Auth::user()->emp_id)
                ->orderBy('created_at', 'desc')
                ->get();
                // dd($records);
        // Pass the records to your blade file
        return view('ao.rfa_file_list', compact('records')); //ao send file link to gd
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

}
