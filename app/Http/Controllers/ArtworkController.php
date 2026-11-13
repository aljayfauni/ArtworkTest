<?php

namespace App\Http\Controllers;

use App\Models\AoModel;
use App\Models\TimeLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtworkController extends Controller
{
    //
    public function index() // index
    {

  
        
    }

     public function getArtworkFileDetail(Request $request) // index
    {

    // $item_detail = AoModel::where('id',$request->id)
    //                         ->first();
  
    //   return view('gd.artwork_file_detail', [
    //     'item_detail'              => $item_detail,
       

    // ]); 

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
    return view('gd.artwork_file_detail', compact('item_detail', 'selectedEmails', 'users'));
        
    }




}
