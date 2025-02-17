<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostVisibility extends Model
{
    protected $table = 'post_visibility';
    use HasFactory;
    
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
