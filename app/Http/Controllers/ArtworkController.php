<?php

namespace App\Http\Controllers;

use App\Models\ActionTakenLog;
use App\Models\ActivityLog;
use App\Models\AoModel;
use App\Models\AuditLog;
use App\Models\HistoryLog;
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

    //      public function toAddItemCode(Request $request) // index
    // {

    //     // Fetch the target submission
    //     $rfa = AoModel::where('rfa_reference', $request->item_id)->first();
    
    //     $users= User::whereNotNull('email')->get();
    
    //     // Fetch all recipient emails linked to this submission batch
    //     $selectedEmails = AoModel::

    //         where('rfa_reference', $rfa)
    //         ->pluck('to_email')
    //         ->toArray();
        
    //     // Fetch all available system users to populate options
    // // $users = User::all();
    //     $item_detail = $rfa;
    //     return view('ao.add_item_code', compact('item_detail', 'selectedEmails', 'users'));
        
    // }

public function toAddItemCode(Request $request)
{
    // Fetch the target submission
    $rfa = AoModel::where('rfa_reference', $request->item_id)->first();

    if ($rfa) {
        $user    = auth()->user();
        $ref     = trim($rfa->rfa_reference);
        $modelId = $rfa->id;

        // Check kung nabuksan na dati ang Add Item Code para sa RFA na ito
        $isReopen = HistoryLog::where('rfa_reference', $ref)
            ->where('remarks', 'LIKE', '%Add Item Code%')
            ->exists();

        $remark = $isReopen
            ? 'Re-opened Add Item Code.'
            : 'Started adding Item Code.';

        // 1. HISTORY LOG (Marks T0 start time for Item Code creation)
        HistoryLog::create([
            'user_id'       => $user->id,
            'rfa_reference' => $ref,
            'model_type'    => AoModel::class,
            'model_id'      => $modelId,
            'status'        => null,
            'old_values'    => null,
            'new_values'    => ['rfa_reference' => $ref],
            'remarks'       => $remark,
        ]);

        // 2. ACTION TAKEN LOG
        ActionTakenLog::create([
            'user_id'       => $user->id,
            'rfa_reference' => $ref,
            'subject_type'  => AoModel::class,
            'subject_id'    => $modelId,
            'action'        => $remark,
            'remarks'       => "{$remark} ({$ref})",
        ]);

        // 3. ACTIVITY LOG
        ActivityLog::create([
            'user_id'       => $user->id,
            'rfa_reference' => $ref,
            'event'         => $isReopen ? 'reopened_add_itemcode' : 'started_add_itemcode',
            'description'   => "{$user->name} - {$remark} ({$ref})",
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
        ]);

        // 4. AUDIT LOG
        AuditLog::create([
            'user_id'        => $user->id,
            'rfa_reference'  => $ref,
            'auditable_type' => AoModel::class,
            'auditable_id'   => $modelId,
            'event'          => $isReopen ? 'reopened_add_itemcode' : 'started_add_itemcode',
            'metadata'       => ['rfa_reference' => $ref],
            'url'            => request()->fullUrl(),
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
        ]);
    }

    $users = User::whereNotNull('email')->get();

    // Fixed: gamitin ang $rfa->rfa_reference imbes na buong $rfa object
    $selectedEmails = AoModel::where('rfa_reference', $rfa->rfa_reference ?? $request->item_id)
        ->pluck('to_email')
        ->toArray();

    $item_detail = $rfa;

    return view('ao.add_item_code', compact('item_detail', 'selectedEmails', 'users'));
}





}
