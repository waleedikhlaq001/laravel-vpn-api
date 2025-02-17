<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostVisibility;
use App\Models\PostTranslation;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupsUser;
use Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(){
        $user = Auth::user();
        $groups = $user->groups;
        $posts = Post::with('translations','user','groups','favoritedBy')->where('visibility','public')->where('status','published')->orderBy('id','desc')->get();
        $posts->each(function ($post) use ($user) {
            $post->isFavorited = $post->favoritedBy->contains('id', $user->id);
        });
        return inertia('Overview', ['user' => $user, 'posts' => $posts]);
    }
    public function getTranslations($locale){
        $path = resource_path("js/translations/$locale.json"); // Adjust the path as needed
    return response()->json(json_decode(file_get_contents($path)));
    }
}
