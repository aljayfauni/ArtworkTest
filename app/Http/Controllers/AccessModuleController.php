<?php

namespace App\Http\Controllers;

use App\Models\AccessModule;
use Exception;
use Illuminate\Http\Request;

class AccessModuleController extends Controller
{
    public function index()
    {
        return view('access-module.index');
    }

    public function getAllAccessModules()
    {
        try{
            $data = AccessModule::get();
            
            return datatables()->of($data)
            ->addColumn('action',function($data){
                $button = '<div class="btn-group btn-group-sm">';
                // $button .= '<a href="'. route('access-modules.edit',['access_module' => $data->id]) .'" class="btn btn-info"><i class="fas fa-solid fa-pen"></i></a>';
                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function create()
    {
        return view('access-module.create');
    }

    public function store()
    {
        $data = request()->validate([
            'name' => 'required|unique:access_modules,name',
            'description' => 'nullable',
        ]);

        AccessModule::create($data);

        return redirect()->route('access-modules.index')->with('success', 'Access Module created successfully.');
    }

    public function edit(AccessModule $accessModule)
    {
        return redirect()->route('access-modules.index')->with('error', 'Editing Access Modules is currently disabled.'); // Temporary disable edit functionality
        // return view('access-module.edit', compact('accessModule'));
    }

    public function update(AccessModule $accessModule)
    {
        // $data = request()->validate([
        //     'name' => 'required|unique:access_modules,name,' . $accessModule->id,
        //     'description' => 'nullable',
        // ]);

        // $accessModule->update($data);

        // return redirect()->route('access-modules.index')->with('success', 'Access Module updated successfully.');
    }
}
