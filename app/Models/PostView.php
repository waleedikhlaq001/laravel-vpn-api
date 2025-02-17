<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostView extends Model
{
    use HasFactory;
    // protected $fillable = ['post_id', 'user_id', 'ip', 'device', 'browser', 'platform', 'country', 'city', 'region', 'lat', 'lon', 'timezone', 'lang', 'referer', 'user_agent', 'created_at', 'updated_at'];
    protected $guarded = [];
}
