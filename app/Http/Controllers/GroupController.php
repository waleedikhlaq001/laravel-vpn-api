<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupsUser;
use App\Models\GroupShare;
use Auth;
use Inertia\Inertia;

class GroupController extends Controller
{
    public function index(){
        $user = Auth::user();
        $group = $user->groups;
        if($user->role == 'admin'){
            $groups = Group::with([
                'users' => function ($query) {
                    $query->where('groups_users.status', 'active');
                },
                'pendingUsers' => function ($query) {
                    $query->where('groups_users.status', 'pending');
                },
                'sharedFrom.users',
                'sharedWith'
            ])
            ->get();
        
        $result = $groups->map(function($group) {
            // Direct active users of the group
            $allUsers = $group->users;
        
            // Direct pending users of the group
            // $directPendingUsers = $group->pendingUsers;
        
            // // Check for groups that shared their users with the current group
            // $sharedUsers = collect();
            // $groupsSharedWithCurrentGroup = $group->sharedFrom; // Groups that shared users with this group
            // foreach ($groupsSharedWithCurrentGroup as $sharedGroup) {
            //     $sharedUsers = $sharedUsers->concat($sharedGroup->users);
            // }
        
            // // Combine the direct active users with the shared users
            // $allUsers = $directActiveUsers->concat($sharedUsers)->unique('id');
        
            // Add data directly into the group object
            $group->active_users = $allUsers;
        
            return $group;
        });
        
    
        }else{
            $userId = Auth::user()->id;

            $loggedInUserId = Auth::user()->id;

            $groups = Group::whereHas('leaders', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })->with([
                'users' => function ($query) {
                    $query->where('groups_users.status', 'active');
                },
                'pendingUsers' => function ($query) {
                    $query->where('groups_users.status', 'pending');
                },
                'sharedFrom.users',
                'sharedWith'
            ])
            ->get();
        
        $result = $groups->map(function($group) {
            // Direct active users of the group
            $allUsers = $group->users;
        
            // Direct pending users of the group
            // $directPendingUsers = $group->pendingUsers;
        
            // // Check for groups that shared their users with the current group
            // $sharedUsers = collect();
            // $groupsSharedWithCurrentGroup = $group->sharedFrom; // Groups that shared users with this group
            // foreach ($groupsSharedWithCurrentGroup as $sharedGroup) {
            //     $sharedUsers = $sharedUsers->concat($sharedGroup->users);
            // }
        
            // // Combine the direct active users with the shared users
            // $allUsers = $directActiveUsers->concat($sharedUsers)->unique('id');
        
            // Add data directly into the group object
            $group->active_users = $allUsers;
        
            return $group;
        });
            // dd($groups);
        }
       
        $allgroups = Group::get();
        return inertia('ManageGroup', ['groups' => $result, 'user' => $user, 'allgroups' => $allgroups]);
    }

    public function groupAdd(Request $request){
        $user = Auth::user();
        $group = $user->groups;
        $leaders = User::where('role','leader')->get();
        return inertia('CreateGroup', ['user' => $user, 'leaders' => $leaders]);
    }

    public function groupStore(Request $request){
        $user = Auth::user();
        $group = new Group;
        $group->user_id = $user->id;
        $group->name = $request->input('groupName');
        $group->description = $request->input('groupDescription');
        // $group->type = $request->input('content_visibility');
        // $group->permissions = $request->input('readPermission');
        $group->save();

        return redirect('/manage-group');

    }

    public function groupUpdate(Request $request){
        if(Auth::user()->role == 'member'){
            $group = Group::where('id',$request->input('id'))->first();
        }elseif(Auth::user()->role == 'admin'){
            $group = Group::where('id',$request->input('id'))->first();
        }
        $group->name = $request->input('name');
        $group->description = $request->input('description');
        $group->type = $request->input('content_visibility');
        $group->permissions = $request->input('permission');
        $group->save();
        return $group;
    }
    public function updateStatus($userid, $groupid){
        $update = GroupsUser::where('user_id',$userid)->where('group_id',$groupid)->update(['status' => 'active']);
        return $update;
    }
    public function removeUser($userid, $groupid){
        $delete = GroupsUser::where('user_id',$userid)->where('group_id',$groupid)->delete();
        return $delete;
    }
    
    public function joinGroup(){
        $user = Auth::user();
        $gorup = $user->groups;
        $groups = Group::with('guser')->whereDoesntHave('groupUsers', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        return inertia('JoinGroup', ['user' => $user, 'groups' => $groups]);
    }

    public function applyGroup($id){
        $group = new GroupsUser();
        $group->user_id = Auth::user()->id;
        $group->group_id = $id;
        $group->status = 'pending';
        $group->save();
        return $group;
    }
    public function addUserGroup(Request $request){
        $group = new GroupsUser();
        $group->user_id = $request->input('user_id');
        $group->group_id = $request->input('group_id');
        $group->status = 'active';
        $group->role = $request->input('role');
        $group->save();
        $groupuser = Group::where('id', $request->input('group_id'))
            ->with(['users' => function ($query) use($group) {
                $query->where('groups_users.user_id', $group->user_id);
            }])->first();
        return $groupuser->users[0];
    }

    public function updateUserRole(Request $request){
        $group = GroupsUser::where('id',$request->input('id'))->first();
        $group->role = $request->input('role');
        $group->save();
        return $group;
    }

    public function assignGroup(Request $request){
        
        $groupsusers = Group::where('id', $request->input('group_id'))
        ->with(['users' => function ($query) {
            $query->where('groups_users.status', 'active');
        }])->first()->users;
        foreach($request->input('groups') as $group){
            $groupshare = GroupShare::where('group_id',$request->input('group_id'))->where('shared_group_id',$group)->first();
            if(!$groupshare){
                $groupshare = new GroupShare();
                $groupshare->group_id = $group;
                $groupshare->shared_group_id =  $request->input('group_id');
                $groupshare->save();
            }
        }
    }

    public function detachGroup($shared_id, $id){
        // dd($shared_id,$id);
        $share =  GroupShare::where('group_id',$id)->where('shared_group_id',$shared_id)->delete();
        return $share;
    }
}
