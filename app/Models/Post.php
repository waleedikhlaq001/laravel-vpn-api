<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['files', 'status', 'type', 'lang', 'user_id', 'category_id', 'references','visibility'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $appends = ['title', 'content', 'excerpt', 'posted_at','views_count'];



    /** Relations */
    public function postVisibilities()
    {
        return $this->hasMany(PostVisibility::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'post_id', 'user_id')->withTimestamps();
    }
    public function translations()
    {
        return $this->hasOne(PostTranslation::class);
    }
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'post_visibility');
    }
    public function views()
    {
        return $this->hasMany(PostView::class);
    }
    public function signals()
    {
        return $this->hasMany(PostSignal::class);
    }






    /** Attributes */
    public function getTranslation($lang)
    {
        $q =  $this->translations()->where('lang', $lang)->first();
        if (!$q) {
            $q = $this->translations()->where('lang', $this->lang)->first();
        }
        return $q;
    }
    public function getTitleAttribute()
    {
        $user = auth()->user();
        $lang = app()->getLocale();
        if ($user) {
            $lang = $user->lang;
        }
        if ($this->getTranslation($lang)) {
            return $this->getTranslation($lang)->title;
        }
        /** return parent  */
        return '';
    }
    public function getContentAttribute()
    {
        $user = auth()->user();
        $lang = app()->getLocale();
        if ($user) {
            $lang = $user->lang;
        }
        if ($this->getTranslation($lang)) {
            return $this->getTranslation($lang)->content;
        }
        return '';
    }
    public function getExcerptAttribute()
    {
        $content = strip_tags($this->content);
        if (strlen($content) <= 150) {
            return $content;
        }
        $excerpt = substr($content, 0, 150);
        $lastSpace = strrpos($excerpt, ' ');
        if ($lastSpace !== false) {
            $excerpt = substr($excerpt, 0, $lastSpace);
        }
        return $excerpt . '...';
    }
    public function getPostedAtAttribute()
    {
        return $this->created_at ? $this->created_at->diffForHumans() : '';
    }
    public function getViewsCountAttribute()
    {
        return $this->views()->count();
    }
}
