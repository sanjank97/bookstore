<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
      /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-user|edit-user|delete-user', ['only' => ['index','show']]);
        $this->middleware('permission:create-user', ['only' => ['create','store']]);
        $this->middleware('permission:edit-user', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-user', ['only' => ['destroy','trashedUsers','restoreUser','deleteUser']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest('id')->paginate(3);
        $total_trashed = User::onlyTrashed()->get()->count();
        return view('admin.users.index',['users'=>$users, 'total_trashed'=>$total_trashed]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name')->all();
        return view('admin.users.create', [
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|string|email:rfc,dns|max:250|unique:users,email',
            'mobile' => 'required|digits:10|unique:users,mobile',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required'
         ]);
         $input = $request->all();
         $input['password'] = Hash::make($request->password);
         $user = User::create($input);
         $user->assignRole($request->roles);

         return redirect()->route('users.index')
         ->withSuccess('New user is added successfully.');

    }

    public function show(User $user)
    {
       return view('admin.users.show', ['user'=>$user]);
    }

 
    public function edit(User $user)
    {
         // Check Only Super Admin can update his own Profile
         if ($user->hasRole('Super Admin')){
            if($user->id != auth()->user()->id){
                abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
            }
        }

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => Role::pluck('name')->all(),
            'userRoles' => $user->roles->pluck('name')->all()
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|string|email:rfc,dns|max:250|unique:users,email,'.$user->id,
            'mobile' => 'required|digits:10|unique:users,mobile,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required'
         ]);
        
         $input = $request->all();

         if(!empty($request->password)){
            $input['password'] = Hash::make($request->password);
        }else{
            $input = $request->except('password');
        }

        $user->update($input);

        $user->syncRoles($request->roles);

        return redirect()->route('users.index')
                ->withSuccess('User is updated successfully.');
       
    }

    public function destroy(User $user)
    {
         // About if user is Super Admin or User ID belongs to Auth User
         if ($user->hasRole('Super Admin') || $user->id == auth()->user()->id)
         {
             abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
         }
 
      
         $user->delete();
         return redirect()->route('users.index')
                 ->withSuccess('User is moved to trash successfully.');
    }

    public function trashedUsers() {
        $users = User::onlyTrashed()->paginate(3);      
        return view('admin.users.trashed', compact('users'));
    }

    public function restoreUser(Request $request, $id) {

        User::withTrashed()->find($id)->restore();
        return redirect()->route('users.trashed')->with('success', 'User restored successfully.');
     }
  
     public function deleteUser(Request $request, $id) {

        // About if user is Super Admin or User ID belongs to Auth User
        if ($user->hasRole('Super Admin') || $id == auth()->user()->id)
        {
            abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
        }
        $user = User::withTrashed()->find($id);
        $user->syncRoles([]);
        $user->forceDelete();
        return redirect()->route('users.trashed')->with('success', 'User deleted successfully.');
     }

}
