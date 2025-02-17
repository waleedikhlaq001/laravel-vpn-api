<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    /** group have many users each user have a role in the group (admin,memeber) */
    public function users()
    {
        return $this->belongsToMany(User::class, 'groups_users', 'group_id', 'user_id')->withPivot('group_permission','role','id');
    }
    public function sharedGroups()
    {
        return $this->hasMany(GroupShare::class, 'group_id')->with('sharedGroup','shareduser');
    }

    public function sharedWith()
    {
        return $this->belongsToMany(Group::class, 'group_shares', 'group_id', 'shared_group_id');
    }

    public function sharedFrom()
    {
        return $this->belongsToMany(Group::class, 'group_shares', 'shared_group_id', 'group_id');
    }
    public function groupUsers()
    {
        return $this->hasMany(GroupsUser::class);
    }
    public function leaders()
    {
        return $this->belongsToMany(User::class, 'groups_users', 'group_id', 'user_id')
            ->wherePivot('role', 'leader');
    }
    public function pendingUsers()
    {
        return $this->belongsToMany(User::class, 'groups_users', 'group_id', 'user_id')->withPivot('role','status', 'group_permission')
            ->wherePivot('status', 'pending');
    }
    public function postVisibilities()
    {
        return $this->hasMany(PostVisibility::class);
    }
    public function admins()
    {
        return $this->users()->wherePivot('role', 'admin');
    }
    public function guser()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
    public function members()
    {
        return $this->users()->wherePivot('role', 'member');
    }
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_visibility', 'group_id', 'post_id');
    }
}
