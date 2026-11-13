<?php

namespace App\Http\Controllers;

use App\Models\FileSpecification;
use App\Services\AoFileService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class FileSpecificationController extends Controller
{
    //
    protected $aoFileService;

    public function __construct(AoFileService $aoFileService)
    {
        $aoFileService =$aoFileService;
    }

        public function showSuppliedFileVerified($id)
    {
        try {
            // Reuses helper query + security check on to_email
            $data = $this->aoFileService->getFileReceivedLinkById($id);
            // dd($data);
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


public function saveSpecification(Request $request): RedirectResponse
{
    
    try {
    
        // 1. Validate primary required fields
        $validated = $request->validate([
            'spec_item_code' => 'required|max:255',
        ]);
    
        // 2. Instantiate object
        $specification = new FileSpecification();

        // 3. Map primary requirements
        $specification->item_code = $validated['spec_item_code'];
    
        // 4. Map single-value form inputs directly
        $specification->spec_size_spread        = $request->input('spec_size_spread');
        $specification->spec_size_folded        = $request->input('spec_size_folded');
        $specification->substrate               = $request->input('substrate');
        $specification->spec_number_of_colors   = $request->input('spec_number_of_colors');
        $specification->output_digital_proof_pc = $request->input('output_digital_proof_pc');
        $specification->output_mockup_pc        = $request->input('output_mockup_pc');
        $specification->color_guide_others      = $request->input('color_guide_others');
        $specification->reference_item_code     = $request->input('reference_item_code');
        $specification->requested_by            = $request->input('requested_by');
        $specification->date_requested          = $request->input('date_requested');
        $specification->date_required           = $request->input('date_required');
        $specification->instruction_agreement   = $request->input('instruction_agreement');
        // $specification->document_control_code   = 'UGEC - PDP - F169 R01';

        // Map boolean checkbox checks
        $specification->output_digital_proof_check = $request->has('output_digital_proof_check');
        $specification->output_mockup_check        = $request->has('output_mockup_check');

        // 5. Keep checkbox groups structured as JSON strings for SQL Server compatibility
        $specification->printing_process = is_array($request->input('printing_process')) ? json_encode($request->input('printing_process')) : json_encode([]);
        $specification->special_process  = is_array($request->input('special_process'))  ? json_encode($request->input('special_process'))  : json_encode([]);
        $specification->efile_format     = is_array($request->input('efile_format'))     ? json_encode($request->input('efile_format'))     : json_encode([]);
        $specification->color_guide      = is_array($request->input('color_guide'))      ? json_encode($request->input('color_guide'))      : json_encode([]);

        // 6. Persist to MS SQL Server
        $specification->save();

        return redirect()->back()->with('success', 'Artwork request & specifications saved successfully!');

    } catch (ValidationException $e) {
        throw $e;

    } catch (QueryException $e) {
        Log::error('SQL Server Insertion Crash inside saveSpecification:', [
            'message' => $e->getMessage(),
            'code'    => $e->getCode(),
            'payload' => $request->all()
        ]);

        return redirect()->back()
            ->withInput()
            ->with('error', 'Database Processing Failure. Please ensure all values conform to structural requirements.');

    } catch (\Exception $e) {
        Log::critical('System Core Level Failure inside saveSpecification:', [
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->withInput()
            ->with('error', 'An unexpected core system issue occurred. Please check system audit trails.');
    }
}

        public function getSpecificationsAll()
    {
       
            $data = new FileSpecification();

            $checkSpecifications = $data::get();
             
      try{
             return datatables()->of($checkSpecifications)
            // ->addColumn('action',function($data){
            //     $button = '<div class="btn-group btn-group-sm">';
            //     $button .= '<a href="'. route('users.edit',['user' => $data->id]) .'" class="btn btn-info"><i class="fas fa-solid fa-pen"></i></a>';
            //     // $button .= '<a href="/employees/' . $data->id . '" class="btn btn-info"><i class="fas fa-solid fa-eye"></i></a>';
            //     $button .= '</div>';
            //     return $button;
            // })
           
         ->addColumn('id', function($data){ 
            return strtoupper($data->id);
        })
        ->addColumn('item_code', function($data){ 
            return strtoupper($data->item_code);
        })
        ->addColumn('instruction_agreement', function($data){ 
            return strtoupper($data->instruction_agreement);
        })
        ->addColumn('printing_process', function($data){ 
            return strtoupper($data->printing_process);
        })
        ->addColumn('special_process', function($data){ 
            return strtoupper($data->special_process);
        })
        ->addColumn('efile_format', function($data){ 
            return strtoupper($data->efile_format);
        })
        ->addColumn('color_guide', function($data){ 
            return strtoupper($data->color_guide);
        })
        ->addColumn('output_digital_proof_pc', function($data){ 
            return strtoupper($data->output_digital_proof_pc);
        })
        ->addColumn('output_mockup_pc', function($data){ 
            return strtoupper($data->output_mockup_pc);
        })
        ->addColumn('output_digital_proof_check', function($data){ 
            return strtoupper($data->output_digital_proof_check);
        })
        ->addColumn('output_mockup_check', function($data){ 
            return strtoupper($data->output_mockup_check);
        })
        ->addColumn('date_requested', function($data){ 
            return strtoupper($data->date_requested);
        })
        ->addColumn('date_required', function($data){ 
            return strtoupper($data->date_required);
        })
        ->addColumn('spec_size_spread', function($data){ 
            return strtoupper($data->spec_size_spread);
        })
        ->addColumn('spec_size_folded', function($data){ 
            return strtoupper($data->spec_size_folded);
        })
        ->addColumn('substrate', function($data){ 
            return strtoupper($data->substrate);
        })
        ->addColumn('spec_number_of_colors', function($data){ 
            return strtoupper($data->spec_number_of_colors);
        })
        ->addColumn('color_guide_others', function($data){ 
            return strtoupper($data->color_guide_others);
        })
        ->addColumn('reference_item_code', function($data){ 
            return strtoupper($data->reference_item_code);
        })
        ->addColumn('requested_by', function($data){ 
            return strtoupper($data->requested_by);
        })
        ->addColumn('created_at', function($data){ 
            return strtoupper($data->created_at);
        })
        ->addColumn('updated_at', function($data){ 
            return strtoupper($data->updated_at);
        })
        ->addColumn('spread_h', function($data){ 
            return strtoupper($data->spread_h);
        })
        ->addColumn('spread_w', function($data){ 
            return strtoupper($data->spread_w);
        })
        ->addColumn('spread_l', function($data){ 
            return strtoupper($data->spread_l);
        })
        ->addColumn('folded_h', function($data){ 
            return strtoupper($data->folded_h);
        })
        ->addColumn('folded_w', function($data){ 
            return strtoupper($data->folded_w);
        })
        ->addColumn('folded_l', function($data){ 
            return strtoupper($data->folded_l);
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
}
