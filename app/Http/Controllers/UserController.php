<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd("test");
        return view('user.index');
    }

    public function getAllUsers()
    {
        try{
            $users = User::get();
            
            return datatables()->of($users)
            ->addColumn('action',function($data){
                $button = '<div class="btn-group btn-group-sm">';
                $button .= '<a href="'. route('users.edit',['user' => $data->id]) .'" class="btn btn-info"><i class="fas fa-solid fa-pen"></i></a>';
                // $button .= '<a href="/employees/' . $data->id . '" class="btn btn-info"><i class="fas fa-solid fa-eye"></i></a>';
                $button .= '</div>';
                return $button;
            })
            ->addColumn('role',function($data){
                return $data->role?->name;
            })
            ->addColumn('name',function($data){
                return strtoupper($data->name);
            })
            ->addColumn('receive_notifications',function($data){
                $badge = '<span class="badge badge-'. ($data->receive_notifications == 1? 'success':'danger') .'">' . ($data->receive_notifications == 1? 'Yes':'No') . '</span>';
                return $badge;
            })
            ->rawColumns(['roles','action','receive_notifications'])
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
        $roles = Role::get();

        return view('user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'roles' => '',
            'password' => '',
        ]);

        $roles = null;
        if($request->has('roles')) {
            $roles = json_encode($data['roles']);
        }
        
        $pass = Hash::make($data['password']);

        $user = new User();
        $user->name = strtoupper($data['name']);
        $user->username = $data['username'];
        $user->password = $pass;
        $user->password_reset = 1;
        $user->active = 1;
        // $user->roles = $roles;
        $user->role_id = $request->role_id;
        $user->save();

        return redirect(route('users.index'))->with('success', 'User successfully added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::get();

        return view('user.edit', compact('roles','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            // 'name' => 'required',
            'role_id' => 'required',
            'receive_notifications' => '',
        ]);

        // $pass = Hash::make('ugec-12345');

        // $user = new User();
        // $user->name = strtoupper($data['name']);
        // $user->username = $data['username'];
        // $user->password = $pass;
        // $user->password_reset = 1;
        $user->active = 1;
        $user->role_id = $data['role_id'];
        $user->receive_notifications = $data['receive_notifications'] ?? 0;
        $user->save();

        return redirect(route('users.index'))->with('success', 'User successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function passwordReset(User $user)
    {
        $pass = Hash::make('Ugec-12345');

        $user->password = $pass;
        $user->password_reset = 1;
        $user->save();

        return response()->json(['success' => 'Password has been reset successfully!', 'pass' => 'Ugec-12345']);
    }

    public function indexChangePass()
    {
        return view('user.password');
    }
    
    public function passwordChange(Request $request)
    {
        $data = $request->validate([
            'password' => [
                'required',
                'confirmed',
                'min:12',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%^&*(),.?":{}|<>]/',
            ],
        ], [
            'password.regex' => 'The password must contain at least one uppercase letter, one number, one special character, and atleast 12 characters.',
        ]);

        $pass = Hash::make($data['password']);

        $user = User::find(Auth::id());
        $user->password = $pass;
        $user->password_reset = 0;
        $user->password_expire = Carbon::now()->addDays(90);
        $user->save();

        Session::flash('success', 'Password has been changed successfully!');
        return redirect()->route('home');
        // return response()->json(['success' => 'Password has been changed successfully!']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json(['success' => 'You have been logged out successfully.']);
    }

    // user profile 
    public function profile(User $user){

        // $roles = Role::get();

        $userRoles = User::Join('roles', 'role_id', '=' ,'roles.id')->select('roles.name')->first();
     
        return view('user.profile', compact('userRoles','user'));

    }
}
