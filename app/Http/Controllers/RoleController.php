<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Exception;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('role.index');
    }

    public function getAllRoles()
    {
        try{
            $roles = Role::get();
            
            return datatables()->of($roles)
            ->addColumn('action',function($data){
                $button = '<div class="btn-group btn-group-sm">';
                $button .= '<a href="'. route('roles.edit',['role' => $data->id]) .'" class="btn btn-info"><i class="fas fa-solid fa-pen"></i></a>';
                // $button .= '<a href="/employees/' . $data->id . '" class="btn btn-info"><i class="fas fa-solid fa-eye"></i></a>';
                $button .= '</div>';
                return $button;
            })
            ->addColumn('access',function($data){
                return implode('<br>', $data->access);
            })
            ->addColumn('view_all_requests',function($data){
                $badge = '<span class="badge badge-'. ($data->view_all_requests == 1? 'success':'danger') .'">' . ($data->view_all_requests == 1? 'Yes':'No') . '</span>';
                return $badge;
            })
            ->addColumn('read_only',function($data){
                $badge = '<span class="badge badge-'. ($data->read_only == 1? 'success':'danger') .'">' . ($data->read_only == 1? 'Yes':'No') . '</span>';
                return $badge;
            })
            ->addColumn('status',function($data){
                $badge = '<span class="badge badge-'. ($data->status == 1? 'success':'danger') .'">' . ($data->status == 1? 'Active':'Inactive') . '</span>';
                return $badge;
            })
            ->rawColumns(['view_all_requests','read_only','action','status','access'])
            ->make(true);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
