<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupsUser;
use Auth;
use Inertia\Inertia;
use Lab404\Impersonate\Facades\Impersonate;


class AuthController extends Controller
{
    
    public function start($id)
    {
        $user = User::where('id',$id)->first();
        auth()->user()->impersonate($user);
        return redirect()->intended('/profile');
    }

    public function stop()
    {
      auth()->user()->leaveImpersonation();
      return redirect()->intended('/profile');

    }
    public function showLogin(){
       return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Checking if the user with the provided email has a status of 'active'
        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user && $user->status !== 'active' && $user->email_verified_at) {
            // return inertia('Auth/VerifyNofication');
            throw ValidationException::withMessages([
                'email' => ['Your account is still waiting for approvel.'],
            ]);
        }
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
    
        throw ValidationException::withMessages([
            'email' => [__('auth.failed')],
        ]);
    }

    public function showRegister(){
        $groups = Group::get();
        return Inertia::render('Auth/Register', ['groups' => $groups]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'fname' => 'required',
            'lname' => 'required',
            'tel' => 'required',
            'accept_terms' => 'required|accepted',
        ]);
    
        $user = User::create([
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'fname' => $request->input('fname'),
            'lname' => $request->input('lname'),
            'tel' => $request->input('tel'),
            'role' => 'member',
            'subscription' => $request->input('subscription'),
            'job_title' => $request->input('jobTitle'),
            'motivation' => $request->input('motivation'),
            'group' => $request->input('group'),
        ]);
        event(new Registered($user));

        if($user->subscription == 'paidbygroup'){
            $group = GroupsUser::create([
                'group_id' => $request->input('group'),
                'user_id' => $user->id,
                'role' => $user->role,
                'status' => 'pending',
            ]);
        }
        
        if ($user) {
            return Inertia::location(route('login'));
        } else {
            return response()->json(['message' => 'Registration failed'], 500);
        }
    }

    public function profile(){
        
        $user = Auth::user();
        $groups = Auth::user()->groups;
        return inertia('Profile', ['user' => $user, 'groups' => $groups]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'nullable|min:8',
            'fname' => 'required',
            'lname' => 'required',
            'tel' => 'required',
        ]);

        $user = User::findOrFail(auth()->id());
        $user->email = $request->input('email');
        $user->fname = $request->input('fname');
        $user->lname = $request->input('lname');
        $user->tel = $request->input('tel');
        $user->job_title = $request->input('job_title');
        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => 'nullable|image|max:2048',
            ]);
            $avatar = $request->file('avatar');
            $imageName = time() . '_' . $avatar->getClientOriginalName();
            $destination ='/profilePictures';
            $avatar->move(public_path($destination), $imageName);
            $user->avatar = '/profilePictures'.'/'.$imageName;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return redirect()->back()->with('message', 'Profile updated successfully');
    }

    public function userList(){
        $user = Auth::user();
        $groups = $user->groups;
        $users = User::with('privateg','leadergroups','groups')->where('role','<>','admin')->where('payement_status','payed')->get();

// Map each user to the shared groups
        $allusers = $users->map(function ($user) {
            $directGroups = $user->groups;

            // Fetch the names of the groups shared with the direct groups of the user
            $sharedGroupsNames = $directGroups->flatMap(function ($group) {
                return $group->sharedWith->pluck('name'); // Assuming sharedWith is the relationship name for group shares
            })->unique();
            $user->shared_groups = $sharedGroupsNames;
            return $user;
        });
        $unpaidUser = User::with('privateg')->where('role','<>','admin')->where('status','pending')->where('payement_status','pending')->get();
        // dd($users);
        return inertia('UsersList', ['user' => $user,'allusers' => $allusers, 'unpaidusers' => $unpaidUser]);
    }

    public function changeStatus(Request $request){
        $user = User::findOrFail($request->input('id'));
        $user->status = $request->input('status');
        $user->save();
        return $user;   
    }

    public function changePaidStatus(Request $request){
        $user = User::findOrFail($request->input('id'));
        if($request->input('status') == 'paid'){
            $user->payement_status = "payed";
            $user->status = "active";
        }else{
            $user->status = "rejected";
        }
        $user->save();
        return $user;
    }

    public function searchUsers(Request $request)
    {
        $email = $request->input('search');
        if($email){
            $users = User::where('id','<>', Auth::user()->id)->where('role','!=', 'admin')->where('email', 'LIKE', '%' . $email . '%')->get();
        }else{
            $users = []; 
        }

        return response()->json(['users' => $users]);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
