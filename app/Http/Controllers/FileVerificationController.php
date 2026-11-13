<?php

namespace App\Http\Controllers;

use App\Models\AoModel;
use App\Models\ArtWorkFileLinkTemp;
use App\Models\CommercialArtist;
use App\Models\FileSpecification;
use App\Models\FileVerification;
use App\Notifications\ArtWorkNotification;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class FileVerificationController extends Controller
{
    // 1. Endpoint for DataTables list
//    public function FileRecievedLinkShowAll() // recieve by gd supervisor
//     {
//         try{
//             // $fileRecieved = AoModel::where('to_email', Auth::user()->email)
//             //     ->orderBy('created_at', 'desc')
//             //     ->get();

//             $fileRecieved = geFileRecievedLinkById() 
//                 ->orderBy('created_at', 'desc')
//                 ->get();//helper function

         
//             return datatables()->of($fileRecieved)
//             // ->addColumn('action',function($data){
//             //     $button = '<div class="btn-group btn-group-sm">';
//             //     $button .= '<a href="'. route('users.edit',['user' => $data->id]) .'" class="btn btn-info"><i class="fas fa-solid fa-pen"></i></a>';
//             //     // $button .= '<a href="/employees/' . $data->id . '" class="btn btn-info"><i class="fas fa-solid fa-eye"></i></a>';
//             //     $button .= '</div>';
//             //     return $button;
//             // })
//             ->addColumn('ao_name',function($data){
               
//                  return strtoupper($data->ao_name);
//             })
//             // ->addColumn('ao_user_id',function($data){
//             //     return strtoupper($data->user_id);
//             // })
//              ->addColumn('from_email',function($data){
//                 return strtoupper($data->from_email);
//             })
//             //  ->addColumn('to_email',function($data){
//             //     return strtoupper($data->to_email);
//             // })
//             ->addColumn('file_link', function($data){
//             return $data->file_link;

//             })    
//             ->addColumn('status', function($data){
//             return $data->status;

//             })      
//              ->addColumn('created_at',function($data){
//                 return strtoupper($data->created_at);
//             })
//             // ->addColumn('receive_notifications',function($data){
//             //     $badge = '<span class="badge badge-'. ($data->receive_notifications == 1? 'success':'danger') .'">' . ($data->receive_notifications == 1? 'Yes':'No') . '</span>';
//             //     return $badge;
//             // })
//             // ->rawColumns(['roles','action','receive_notifications'])
//             ->make(true);
//         } catch(Exception $e){
//             return response()->json(['error' => $e->getMessage()]);
//         }
//     }



public function FileRecievedLinkShowAll()
{
    try {

        // Fetch AoModel records via your helper function
        // $fileRecieved = getFileReceivedLinkById() 
        //     ->orderBy('created_at', 'desc')
        //     ->get();

              $fileRecieved = getFileReceivedLinkById() 
            ->whereNotNull('item_code')
            ->Where('item_code','!=','')
                ->orderBy('created_at', 'desc')
                ->get();//helper function

// $fileRecieved = geFileRecievedLinkById()
//     // Join 1: AO User details
//     ->join('users as ao_user', 'ao_user.emp_id', '=', 'ao_file_links.ao_user_id')
    
//     // Join 2: Commercial Artist assignment
//     ->join('commercial_artist', 'commercial_artist.item_code', '=', 'ao_file_links.item_code')
    
//     // Join 3: Artist User details
//     ->join('users as artist_user', 'artist_user.emp_id', '=', 'commercial_artist.user_id')
    
//     ->select(
//         'ao_file_links.*',
//         'ao_user.name as ao_user_name',
//         'artist_user.name as artist_name', // <--- Artist's name from users table
//         'commercial_artist.user_id as artist_user_id',
//         'commercial_artist.item_code'
//     )
//     ->orderBy('ao_file_links.created_at', 'desc')
//     ->get();


        // Pluck item_codes from AoModel collection
        $itemCodes = $fileRecieved->pluck('item_code')->filter()->toArray();
        $itemUser = $fileRecieved->pluck('artist_name')->filter()->toArray();

        //find the supplied file verification with status for approval 
        $forApprovalSuppliedVerified = FileVerification::whereIn('item_code', $itemCodes)
        ->where('status','GD Assess')
        ->pluck('item_code')
        ->toArray();

        // Find which item_codes exist in the FileVerification table in 1 query
        $verifiedItemCodes = FileVerification::whereIn('item_code', $itemCodes)
            ->where('status','GD to Assign Artist')
            ->pluck('item_code')
            ->toArray();

        $specificationItemCodes = FileSpecification::whereIn('item_code', $itemCodes)
            ->pluck('item_code')
            ->toArray();

        $assignToaArtistItemCode = CommercialArtist::whereIn('commercial_artist.item_code', $itemCodes)
        ->join('users', 'users.emp_id', '=', 'commercial_artist.user_id')
        ->pluck('users.name', 'commercial_artist.item_code')
        ->toArray();
     
        // return datatables()->of($fileRecieved)
        //       ->addColumn('is_Assign_to_Artist', function($data) use ($assignToaArtistItemCode) {
        //         return in_array($data->item_code, $assignToaArtistItemCode);
        //     })

            return datatables()->of($fileRecieved)
            ->addColumn('is_Ao_Approve', function($data) use ($forApprovalSuppliedVerified) {
                // Return artist name if found in array, or null if unassigned
             
                return in_array($data->item_code, $forApprovalSuppliedVerified);
            })

            ->addColumn('is_Assign_to_Artist', function($data) use ($assignToaArtistItemCode) {
                // Return artist name if found in array, or null if unassigned
                return $assignToaArtistItemCode[$data->item_code] ?? null;
            })
            // Add custom column checking if item_code exists in FileVerification
            ->addColumn('is_verified', function($data) use ($verifiedItemCodes) {
                return in_array($data->item_code, $verifiedItemCodes);
            })
            ->addColumn('is_specification_exist', function($data) use ($specificationItemCodes) {
                return in_array($data->item_code, $specificationItemCodes);
            })
            ->addColumn('ao_name', function($data) {
                return strtoupper($data->ao_name);
            })
            ->addColumn('from_email', function($data) {
                return strtoupper($data->from_email);
            })
            ->addColumn('file_link', function($data) {
                return $data->file_link;
            })    
            ->addColumn('status', function($data) {
                return $data->status;
            })      
            ->addColumn('created_at', function($data) {
                return strtoupper($data->created_at);
            })
            ->make(true);
    } catch(Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
}


public function FileRecievedLinkShowAllNoItemCode()
{
    try {
        // Fetch AoModel records via your helper function
        $fileRecieved = getFileReceivedLinkById_NoItemCode() 
            ->orderBy('created_at', 'desc')
            ->get();

        // Pluck item_codes from AoModel collection
        $itemCodes = $fileRecieved->pluck('item_code')->filter()->toArray();
        $itemUser = $fileRecieved->pluck('artist_name')->filter()->toArray();
        //pluck rfa_reference
        $rfa_ref = $fileRecieved->pluck('rfa_reference')->filter()->toArray();
        //find the supplied file verification with status for approval 
        $forApprovalSuppliedVerified = FileVerification::whereIn('item_code', $itemCodes)
        ->where('status','For Approval')
        ->pluck('item_code')
        ->toArray();

        // Find which item_codes exist in the FileVerification table in 1 query
        $verifiedItemCodes = FileVerification::whereIn('item_code', $itemCodes)
            ->where('status','GD to Assign Artist')
            ->pluck('item_code')
            ->toArray();

        $specificationItemCodes = FileSpecification::whereIn('item_code', $itemCodes)
            ->pluck('item_code')
            ->toArray();

        // Find which rfa_reference exist in the FileVerification table
        $rfa_reference = FileVerification::whereIn('rfa_number', $rfa_ref)
            ->where('rfa_number',$rfa_ref)
            ->pluck('rfa_number')
            ->toArray();
            

        $assignToaArtistItemCode = CommercialArtist::whereIn('commercial_artist.item_code', $itemCodes)
        ->join('users', 'users.emp_id', '=', 'commercial_artist.user_id')
        ->pluck('users.name', 'commercial_artist.item_code')
        ->toArray();
     
        // return datatables()->of($fileRecieved)
        //       ->addColumn('is_Assign_to_Artist', function($data) use ($assignToaArtistItemCode) {
        //         return in_array($data->item_code, $assignToaArtistItemCode);
        //     })

            return datatables()->of($fileRecieved)
            ->addColumn('is_Ao_Approve', function($data) use ($forApprovalSuppliedVerified) {
                // Return artist name if found in array, or null if unassigned
             
                return in_array($data->item_code, $forApprovalSuppliedVerified);
            })

            ->addColumn('is_Assign_to_Artist', function($data) use ($assignToaArtistItemCode) {
                // Return artist name if found in array, or null if unassigned
                return $assignToaArtistItemCode[$data->item_code] ?? null;
            })
            // Add custom column checking if item_code exists in FileVerification
            ->addColumn('is_verified', function($data) use ($verifiedItemCodes) {
                return in_array($data->item_code, $verifiedItemCodes);
            })
            ->addColumn('is_specification_exist', function($data) use ($specificationItemCodes) {
                return in_array($data->item_code, $specificationItemCodes);
            })
            ->addColumn('is_rfa_reference_exist', function($data) use ($rfa_reference) {
                return in_array($data->rfa_reference, $rfa_reference);
            })
            ->addColumn('ao_name', function($data) {
                return strtoupper($data->ao_name);
            })
            ->addColumn('from_email', function($data) {
                return strtoupper($data->from_email);
            })
            ->addColumn('file_link', function($data) {
                return $data->file_link;
            })    
            ->addColumn('status', function($data) {
                return $data->status;
            })      
            ->addColumn('created_at', function($data) {
                return strtoupper($data->created_at);
            })
            ->make(true);
    } catch(Exception $e) {
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

        public function showSuppliedFileVerified_NoItemCode($id)
    {
        try {
         
            // Reuses helper query + security check on to_email
            $data = getFileReceivedLinkById_NoItemCode()->findOrFail($id);

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

        public function getSuppliedVeried()
    {
       
            $data = new FileVerification();

            $checkSuppliedVerified = $data::whereNotNull('item_code')->get();
             
      try{
             return datatables()->of($checkSuppliedVerified)
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
            // ->addColumn('ao_user_id',function($data){
            //     return strtoupper($data->user_id);
            // })
             ->addColumn('item_code',function($data){
                return strtoupper($data->item_code);
            })
            ->addColumn('client',function($data){
                return strtoupper($data->client);
            })
             ->addColumn('rfa_number',function($data){
                return strtoupper($data->rfa_number);
            })
            
            // ->addColumn('file_link', function($data){
            // return $data->file_link;
            // })   
            ->addColumn('item_description', function($data){
            return $data->item_description;
            })    
            ->addColumn('created_at',function($data){
                return strtoupper($data->created_at);
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
    } catch(Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }

         
      
    }

    // save Supplied File Verification
        /**
     * Store artwork verification and specification details.
     */

public function saveFileVerification(Request $request)
{
    try {
        // 1. Validate only primary fields
        $validated = $request->validate([
            'client'            => 'required',
            'item_description'  => 'required|string',
        ]);
       
        // 2. Instantiate object
        $suppliedVerified = new FileVerification();
        // 3. Map primary requirements
        $suppliedVerified->item_code          = $request->input('item_code');
        $suppliedVerified->client             = $validated['client'];
        $suppliedVerified->item_description   = $validated['item_description'];

        // 4. FIX: Map single-value form inputs directly as standard strings
        $suppliedVerified->file_source        = $request->input('file_source'); // Text from Radio Button
        $suppliedVerified->application        = $request->input('application');  // Text from Dropdown Select
        $suppliedVerified->file_source_other  = $request->input('file_source_other');
        
        // Map document trackers
        $suppliedVerified->rfa_number         = $request->input('rfa_reference');
        $suppliedVerified->item_code          = $request->input('item_code');
        $suppliedVerified->size_spread        = $request->input('size_spread');
        $suppliedVerified->size_folded        = $request->input('size_folded');
        
        // Dimensional Metrics
        $suppliedVerified->spread_h           = $request->input('spread_h');
        $suppliedVerified->spread_w           = $request->input('spread_w');
        $suppliedVerified->spread_l           = $request->input('spread_l');
        $suppliedVerified->folded_h           = $request->input('folded_h');
        $suppliedVerified->folded_w           = $request->input('folded_w');
        $suppliedVerified->folded_l           = $request->input('folded_l');
        
        // Verification & Audit metrics
        $suppliedVerified->number_of_colors   = $request->input('number_of_colors');
        $suppliedVerified->verified_by_date   = $request->input('verified_by_date');
        $suppliedVerified->verified_by        = $request->input('verified_by');

        // 5. Keep checkbox groups structured as JSON strings for SQL Server compatibility
        $suppliedVerified->color              = $request->input('color') ;
        $suppliedVerified->text_specs         = $request->input('text_specs');
        $suppliedVerified->other_specs        = is_array($request->input('other_specs')) ? json_encode($request->input('other_specs')) : json_encode([]);
        $suppliedVerified->image_type         = is_array($request->input('image_type')) ? json_encode($request->input('image_type')) : json_encode([]);
        $suppliedVerified->status             = "GD Assess";
      
        // 6. Persist to MS SQL Server
        $suppliedVerified->save();

        //ao_file_links

        $aoModelRecord = AoModel::where('item_code', trim($request->input('item_code')))->first();
        if ($aoModelRecord) {
        $aoModelRecord->status = "GD Assess";
         $aoModelRecord->save();
        }
        //artworkFileTemp
         
        $artwork_filelink_temp = ArtWorkFileLinkTemp::where('item_code', trim($request->input('item_code')))->first();
          if ($artwork_filelink_temp) {
        $artwork_filelink_temp->status = "GD Assess";
        $artwork_filelink_temp->save();
           }
    //   // 1. Find the existing record (e.g., by ID or item_code)
    //     // ==========================================
    //     // PROCESS 2: Find and Update existing AoModel
    //     // ==========================================
    //     $aoModelRecord = AoModel::where('rfa_reference', trim($request->input('rfa_reference')))->first();

    //     if ($aoModelRecord) {
    //         // Map fields using your exact database column names
    //         $aoModelRecord->item_code      = $request->input('item_code');
    //         $aoModelRecord->client_name    = $validated['client']; // Matches [client_name]
    //         $aoModelRecord->description    = $request->input('item_description'); // Matches [description]
    //         $aoModelRecord->file_info    = $request->input('item_description'); // Matches [description]
            
    //         // Sizes & Dimensions
    //         $aoModelRecord->spread_size    = $request->input('size_spread'); // Matches [spread_size]
    //         $aoModelRecord->folded_size    = $request->input('size_folded'); // Matches [folded_size]
            
    //         $aoModelRecord->spread_h       = $request->input('spread_h');
    //         $aoModelRecord->spread_w       = $request->input('spread_w');
    //         $aoModelRecord->spread_l       = $request->input('spread_l');
    //         $aoModelRecord->folded_h       = $request->input('folded_h');
    //         $aoModelRecord->folded_w       = $request->input('folded_w');
    //         $aoModelRecord->folded_l       = $request->input('folded_l');
    //         //file source
    //         $aoModelRecord->file_source  = $request->input('file_source');
    //         $aoModelRecord->file_source_other  = $request->input('file_source_other');
    //         // Colors & Specs
    //         $aoModelRecord->num_of_colors  = $request->input('number_of_colors'); // Matches [num_of_colors]
    //         $aoModelRecord->colors         = $request->input('color'); // Matches [colors]
    //         //text specs
    //           $aoModelRecord->text_specs         = $request->input('text_specs');
    //         // JSON fields that exist in your schema
    //         $aoModelRecord->image_type     = is_array($request->input('image_type')) ? json_encode($request->input('image_type')) : json_encode([]);
            
    //         // Status update
    //         $aoModelRecord->status         = "For Item Code Creation";
    //         // application 
    //          $aoModelRecord->application         = $request->input('application');
    //         // Save updates to AoModel table
    //         $aoModelRecord->save();
    //     }

    //      $artwork_filelink_temp = ArtWorkFileLinkTemp::where('rfa_reference', trim($request->input('rfa_reference')))->first();

    //     if ($artwork_filelink_temp) {
    //         // Map fields using your exact database column names
    //         $artwork_filelink_temp->item_code      = $request->input('item_code');
    //         $artwork_filelink_temp->client_name    = $validated['client']; // Matches [client_name]
    //         $artwork_filelink_temp->description    = $request->input('item_description'); // Matches [description]
    //         $artwork_filelink_temp->file_info    = $request->input('item_description'); // Matches [description]
            
    //         // Sizes & Dimensions
    //         $artwork_filelink_temp->spread_size    = $request->input('size_spread'); // Matches [spread_size]
    //         $artwork_filelink_temp->folded_size    = $request->input('size_folded'); // Matches [folded_size]
            
    //         $artwork_filelink_temp->spread_h       = $request->input('spread_h');
    //         $artwork_filelink_temp->spread_w       = $request->input('spread_w');
    //         $artwork_filelink_temp->spread_l       = $request->input('spread_l');
    //         $artwork_filelink_temp->folded_h       = $request->input('folded_h');
    //         $artwork_filelink_temp->folded_w       = $request->input('folded_w');
    //         $artwork_filelink_temp->folded_l       = $request->input('folded_l');
    //          //file source
    //         $artwork_filelink_temp->file_source  = $request->input('file_source');
    //         $artwork_filelink_temp->file_source_other  = $request->input('file_source_other');
    //         // Colors & Specs
    //         $artwork_filelink_temp->num_of_colors  = $request->input('number_of_colors'); // Matches [num_of_colors]
    //         $artwork_filelink_temp->colors         = $request->input('color'); // Matches [colors]
            
    //         // JSON fields that exist in your schema
    //         $artwork_filelink_temp->image_type     = is_array($request->input('image_type')) ? json_encode($request->input('image_type')) : json_encode([]);
            
    //         // Status update
    //         $artwork_filelink_temp->status         = "For Item Code Creation";
    //         // application 
    //          $artwork_filelink_temp->application         = $request->input('application');
    //         // Save updates to AoModel table
    //         $artwork_filelink_temp->save();
    //     }

   
        // FIX: Pull the email directly from the database record property

        //send notification
         $recipientUser = \App\Models\User::where('role_id',1)->first();

        if ($recipientUser) {
           
        $recipientUser->notify(new ArtWorkNotification($suppliedVerified ,'gd_to_ao'));
            }
        

        return redirect()->back()->with('success', 'Artwork request & specifications saved successfully!');

    } catch (ValidationException $e) {
        throw $e;

    } catch (QueryException $e) {
        Log::error('SQL Server Insertion Crash:', [
            'message' => $e->getMessage(),
            'code'    => $e->getCode(),
            'payload' => $request->all()
        ]);

        return redirect()->back()
            ->withInput()
            ->with('error', 'Database Processing Failure. Please ensure all values conform to structural requirements.');

    } catch (\Exception $e) {
        Log::critical('System Core Level Failure inside saveFileVerification:', [
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->withInput()
            ->with('error', 'An unexpected core system issue occurred. Please check system audit trails.');
    }
}

public function suppliedVerified_no_item_code(Request $request)
{


    try {
        // 1. Validate only primary fields
      
       
        // 2. Instantiate object
        $suppliedVerified = new FileVerification();
        // 3. Map primary requirements
        $suppliedVerified->item_code          = $request->input('item_code_no_item_code');
        $suppliedVerified->client             = $request->input('client_no_item_code');
        $suppliedVerified->item_description   =$request->input('item_description_no_item_code');

        // 4. FIX: Map single-value form inputs directly as standard strings
        $suppliedVerified->file_source        = $request->input('file_source_no_item_code'); // Text from Radio Button
        $suppliedVerified->application        = $request->input('application_no_item_code');  // Text from Dropdown Select
        $suppliedVerified->file_source_other  = $request->input('file_source_other_no_item_code');
       
        // Map document trackers
        $suppliedVerified->rfa_number         = $request->input('rfa_reference_no_item_code');
        $suppliedVerified->item_code          = $request->input('item_code_no_item_code');
        $suppliedVerified->size_spread        = $request->input('size_spread_no_item_code');
        $suppliedVerified->size_folded        = $request->input('size_folded_no_item_code');
        
        // Dimensional Metrics
        $suppliedVerified->spread_h           = $request->input('spread_h_no_item_code');
        $suppliedVerified->spread_w           = $request->input('spread_w_no_item_code');
        $suppliedVerified->spread_l           = $request->input('spread_l_no_item_code');
        $suppliedVerified->folded_h           = $request->input('folded_h_no_item_code');
        $suppliedVerified->folded_w           = $request->input('folded_w_no_item_code');
        $suppliedVerified->folded_l           = $request->input('folded_l_no_item_code');
        
        // Verification & Audit metrics
        $suppliedVerified->number_of_colors   = $request->input('number_of_colors_no_item_code');
        $suppliedVerified->verified_by_date   = $request->input('verified_by_date_no_item_code');
        $suppliedVerified->verified_by        = $request->input('verified_by_no_item_code');

        // 5. Keep checkbox groups structured as JSON strings for SQL Server compatibility
        $suppliedVerified->color              = $request->input('color_no_item_code') ;
        $suppliedVerified->text_specs         = $request->input('text_specs_no_item_code');
        $suppliedVerified->other_specs        = is_array($request->input('other_specs_no_item_code')) ? json_encode($request->input('other_specs_no_item_code')) : json_encode([]);
        $suppliedVerified->image_type         = is_array($request->input('image_type_no_item_code')) ? json_encode($request->input('image_type_no_item_code')) : json_encode([]);
        $suppliedVerified->status             = "For Approval";
      
        // 6. Persist to MS SQL Server
        $suppliedVerified->save();



      // 1. Find the existing record (e.g., by ID or item_code)
        // ==========================================
        // PROCESS 2: Find and Update existing AoModel
        // ==========================================
        $aoModelRecord = AoModel::where('rfa_reference', trim($request->input('rfa_reference_no_item_code')))->first();

        if ($aoModelRecord) {
            // Map fields using your exact database column names
            $aoModelRecord->item_code      = $request->input('item_code_no_item_code');
            $aoModelRecord->client_name    = $request->input('client_no_item_code'); // Matches [client_name]
            $aoModelRecord->description    = $request->input('item_description_no_item_code'); // Matches [description]
            $aoModelRecord->file_info    = $request->input('item_description_no_item_code'); // Matches [description]
            
            // Sizes & Dimensions
            $aoModelRecord->spread_size    = $request->input('size_spread_no_item_code'); // Matches [spread_size]
            $aoModelRecord->folded_size    = $request->input('size_folded_no_item_code'); // Matches [folded_size]
            
            $aoModelRecord->spread_h       = $request->input('spread_h_no_item_code');
            $aoModelRecord->spread_w       = $request->input('spread_w_no_item_code');
            $aoModelRecord->spread_l       = $request->input('spread_l_no_item_code');
            $aoModelRecord->folded_h       = $request->input('folded_h_no_item_code');
            $aoModelRecord->folded_w       = $request->input('folded_w_no_item_code');
            $aoModelRecord->folded_l       = $request->input('folded_l_no_item_code');
            //file source
            $aoModelRecord->file_source  = $request->input('file_source_no_item_code');
            $aoModelRecord->file_source_other  = $request->input('file_source_other_no_item_code');
            // Colors & Specs
            $aoModelRecord->num_of_colors  = $request->input('number_of_colors_no_item_code'); // Matches [num_of_colors]
            $aoModelRecord->colors         = $request->input('color_no_item_code'); // Matches [colors]
            //text specs
              $aoModelRecord->text_specs         = $request->input('text_specs_no_item_code');
            // JSON fields that exist in your schema
            $aoModelRecord->image_type     = is_array($request->input('image_type_no_item_code')) ? json_encode($request->input('image_type_no_item_code')) : json_encode([]);
            
            // Status update
            $aoModelRecord->status         = "For Item Code Creation";
            // application 
             $aoModelRecord->application         = $request->input('application_no_item_code');
            // Save updates to AoModel table
            $aoModelRecord->save();
        }

         $artwork_filelink_temp = ArtWorkFileLinkTemp::where('rfa_reference', trim($request->input('rfa_reference_no_item_code')))->first();

        if ($artwork_filelink_temp) {
            
            // Map fields using your exact database column names
            $artwork_filelink_temp->item_code      = $request->input('item_code_no_item_code');
        
            $artwork_filelink_temp->client_name    = $request->input('client_no_item_code'); // Matches [client_name]
              
            $artwork_filelink_temp->description    = $request->input('item_description_no_item_code'); // Matches [description]
            $artwork_filelink_temp->file_info    = $request->input('item_description_no_item_code'); // Matches [description]
              
            // Sizes & Dimensions
            $artwork_filelink_temp->spread_size    = $request->input('size_spread_no_item_code'); // Matches [spread_size]
            $artwork_filelink_temp->folded_size    = $request->input('size_folded_no_item_code'); // Matches [folded_size]
            
            $artwork_filelink_temp->spread_h       = $request->input('spread_h_no_item_code');
            $artwork_filelink_temp->spread_w       = $request->input('spread_w_no_item_code');
            $artwork_filelink_temp->spread_l       = $request->input('spread_l_no_item_code');
            $artwork_filelink_temp->folded_h       = $request->input('folded_h_no_item_code');
            $artwork_filelink_temp->folded_w       = $request->input('folded_w_no_item_code');
            $artwork_filelink_temp->folded_l       = $request->input('folded_l_no_item_code');
             //file source
            $artwork_filelink_temp->file_source  = $request->input('file_source_no_item_code');
            $artwork_filelink_temp->file_source_other  = $request->input('file_source_other_no_item_code');
            // Colors & Specs
            $artwork_filelink_temp->num_of_colors  = $request->input('number_of_colors_no_item_code'); // Matches [num_of_colors]
            $artwork_filelink_temp->colors         = $request->input('color_no_item_code'); // Matches [colors]
            
            // JSON fields that exist in your schema
            $artwork_filelink_temp->image_type     = is_array($request->input('image_type_no_item_code')) ? json_encode($request->input('image_type_no_item_code')) : json_encode([]);
            
            // Status update
            $artwork_filelink_temp->status         = "For Item Code Creation";
            // application 
             $artwork_filelink_temp->application         = $request->input('application_no_item_code');
          
            // Save updates to AoModel table
            $artwork_filelink_temp->save();
        }

   
        // FIX: Pull the email directly from the database record property

        //send notification
         $recipientUser = \App\Models\User::where('role_id',1)->first();

        if ($recipientUser) {
           
        $recipientUser->notify(new ArtWorkNotification($suppliedVerified ,'gd_to_ao'));
            }
        

        return redirect()->back()->with('success', 'Artwork request & specifications saved successfully!');

    } catch (ValidationException $e) {
        throw $e;

    } catch (QueryException $e) {
        Log::error('SQL Server Insertion Crash:', [
            'message' => $e->getMessage(),
            'code'    => $e->getCode(),
            'payload' => $request->all()
        ]);

        return redirect()->back()
            ->withInput()
            ->with('error', 'Database Processing Failure. Please ensure all values conform to structural requirements.');

    } catch (\Exception $e) {
        Log::critical('System Core Level Failure inside saveFileVerification:', [
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->withInput()
            ->with('error', 'An unexpected core system issue occurred. Please check system audit trails.');
    }
}

    public function updateStatus(Request $request)
        {
            // $request->validate([
            //     'item_code' => 'required',
            //     'supplied_status'    => 'required',
            // ]);
      
            // $item = FileVerification::where('item_code', $request->item_code)->firstOrFail();
            // $item->status = $request->supplied_status;
            // $item->save();

            // $item = AoModel::where('item_code', $request->item_code)->firstOrFail();
            // $item->status = $request->supplied_status;
            // $item->save();

            $verification = FileVerification::where('rfa_number', $request->rfa_ref)->first();

            if ($verification) {
                $verification->status = $request->supplied_status;
                $verification->save();
            }

            $ao = AoModel::where('rfa_reference', $request->rfa_ref)->firstOrFail();
            $ao->status = $request->supplied_status;
            $ao->assigned_to_role_id = 2;
            $ao->save();

            return response()->json([
                'success' => true,
                'message' => 'Item status updated successfully!'
            ]);
        }



}