<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupShare extends Model
{
    use HasFactory;
    public function sharedGroup()
    {
        return $this->belongsTo(Group::class, 'shared_group_id');
    }

    public function shareduser()
    {
        return $this->hasMany(GroupsUser::class,'group_id', 'group_id');
        
    }

}
