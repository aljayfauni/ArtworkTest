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

    public function getAllArtist()
    {
        $artistLists = User::where('role_id', 4)->get();

        return view('partials.modal.gd.assign_to_artist', compact('artistLists'));
    }

    public function getViewArtworkById($id = null)
    {
    // 1. Check if the ID parameter is missing or null
    if (empty($id)) {
        return redirect()->back()->with('error', 'Invalid artwork ID provided.');
    }

    // Use leftJoin so the artwork is still found even if no artist is assigned yet
    $artwork = AoModel::where('ao_file_links.item_code', $id)
        ->leftJoin('commercial_artist', 'commercial_artist.item_code', '=', 'ao_file_links.item_code')
        ->leftJoin('users', 'users.emp_id', '=', 'commercial_artist.user_id')
        ->select(
            'ao_file_links.*', 
            'users.name as artist_name', 
            'users.email as artist_email', 
            'users.emp_id as artist_emp_id',
            'users.role_id as artist_role_id'
        )
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


public function getShareTimeDuration(Request $request)
{
    $artwork_id = $request->id;

    $userTimeConsumption = TimeLog::query()
        ->select('sub.user_id', 'users.name as user_name', 'users.role_id as role') 
        // 🟢 Return raw cumulative milliseconds
        ->selectRaw("
            SUM(
                CASE WHEN action IN ('start', 'resume') THEN DATEDIFF_BIG(ms, recorded_at, next_recorded_at) ELSE 0 END
            ) as time_spent_milliseconds
        ")
        ->selectRaw("
            ROUND(
                (CAST(SUM(CASE WHEN action IN ('start', 'resume') THEN DATEDIFF_BIG(ms, recorded_at, next_recorded_at) ELSE 0 END) AS FLOAT) / 
                NULLIF(SUM(SUM(CASE WHEN action IN ('start', 'resume') THEN DATEDIFF_BIG(ms, recorded_at, next_recorded_at) ELSE 0 END)) OVER(), 0)) * 100, 
                2
            ) as duration_share_percentage
        ")
        ->fromSub(function ($query) use ($artwork_id) {
            $query->from((new TimeLog)->getTable())
                ->select('user_id', 'action', 'recorded_at', 'ao_file_link_id')
                ->selectRaw("LEAD(recorded_at) OVER (PARTITION BY ao_file_link_id ORDER BY recorded_at) as next_recorded_at")
                ->where('item_code', $artwork_id);
        }, 'sub')
        ->leftJoin('users', 'sub.user_id', '=', 'users.id') 
        ->groupBy('sub.user_id', 'users.name', 'users.role_id')
        ->get();

    return response()->json($userTimeConsumption);
}





public function toggleTimer(Request $request, $item_code)
{
    
    // $artwork = AoModel::where('item_code', $item_code)->firstOrFail();
    
    $artwork = AoModel::query()
        ->select([
            'ao_file_links.id',        // Only select columns you actually need
            'ao_file_links.item_code', // instead of ao_file_links.*
       
            'commercial_artist.user_id as artist_emp_id'
        ])
        ->leftJoin('commercial_artist', 'commercial_artist.item_code', '=', 'ao_file_links.item_code')
        ->where('ao_file_links.item_code', $item_code) 
        ->first();


    $action = $request->input('action'); 
    $now = Carbon::now();

    // 💡 BLOCK ATTEMPTS OUTSIDE WORKING HOURS
    if (in_array($action, ['start', 'resume']) && $artwork->isCompanyOffTime()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Tracking is locked. Operational hours are Monday to Friday, 8:00 AM - 6:00 PM.'
        ], 403);
    }

    // Handle incoming actions normally...
    TimeLog::create([
        'ao_file_link_id' => $artwork->id,
        'item_code' => $artwork->item_code, 
        
        // 💡 FIX: Change from emp_id to the actual primary key id column to satisfy the foreign key constraint
        'user_id' => $artwork->artist_emp_id, 
        
        'action' => $action,
        'recorded_at' => $now,
    ]);

    $isRunning = in_array($action, ['start', 'resume']);

    return response()->json([
        'status' => 'success',
        'is_running' => $isRunning, 
        'started_at' => $isRunning ? $now->toIso8601String() : null,
        'total_seconds' => $artwork->total_seconds 
    ]);
}


//check if its holiday or weekend 8am to 6pm 




        
    
}
