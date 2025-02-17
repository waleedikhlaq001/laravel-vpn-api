<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Laravel\Sanctum\HasApiTokens;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fname',
        'lname',
        'role',
        'tel',
        'profession',
        'status',
        'avatar',
        'job_title',
        'registration_type',
        'motivation',
        'subscription',
        'payement_plan_id',
        'payement_method',
        'payement_status',
        'payement_group_id',
        'payement_end_at',
        'email',
        'password',
        'free_trial_start_at',
        'free_trial_end_at',
        'remember_token',
        'lang'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // public function markEmailAsVerified()
    // {
    //     $result = $this->forceFill([
    //         'email_verified_at' => $this->freshTimestamp(),
    //     ])->save();

    //     if ($result) {
    //         $this->changeStatusOnVerification();
    //     }

    //     return $result;
    // }

    // protected function changeStatusOnVerification()
    // {
    //     // Here you can change the user's status or perform any other actions
    //     $this->status = 'active';  // assuming the user model has a 'status' column
    //     $this->save();
    // }

    public function groups()
    {
        // if ($this->role == 'admin') {
        //     return Group::query();
        // }
        return $this->belongsToMany(Group::class, 'groups_users', 'user_id', 'group_id')->withPivot('role', 'status','group_permission');
    }
    public function leadergroups()
    {
        return $this->belongsToMany(Group::class, 'groups_users', 'user_id', 'group_id')
            ->wherePivot('role', 'leader');
    }
    public function privateg() {
        return $this->belongsToMany(Group::class, 'groups_users', 'user_id', 'group_id')->where('type', 'private');
    }
    public function profile()
    {
        return $this->hasMany(UserProfile::class, 'user_id', 'id');
    }

    public function favorites()
    {
        return $this->belongsToMany(Post::class, 'favorites', 'user_id', 'post_id')->withTimestamps();
    }

    public function getExtraAttribute()
    {
        $profile = $this->profile->pluck('_value', '_key')->toArray();
        return $profile;
    }
    public function getNameAttribute()
    {
        return $this->fname . ' ' . $this->lname;
    }
    public function getIsAdminAttribute()
    {
        return $this->role == 'admin';
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function viewedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_views')->withTimestamps();
    }
}
