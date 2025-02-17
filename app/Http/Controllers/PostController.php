<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ShareEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\Post;
use App\Models\PostVisibility;
use App\Models\PostTranslation;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupsUser;
use Auth;
use Inertia\Inertia;
use Response;

class PostController extends Controller
{

    public function mycontent(){
        $user = Auth::user();
        $groupsid = $user->groups;
        $groups = Group::whereIn('id',$groupsid->pluck('id'))->orWhere('type','public')->where('permissions','readwrite')->get();
        $posts = Post::with('translations','user','groups','favoritedBy')->where('user_id', Auth::user()->id)->orderBy('id','desc')->get();
        $posts->each(function ($post) use ($user) {
            $post->isFavorited = $post->favoritedBy->contains('id', $user->id);
        });
        return inertia('MyContent', ['user' => $user, 'posts' => $posts, 'currentpage' => 'My Content']);
    }

    public function addPost(){
        $user = Auth::user();
        $groupsid = $user->groups;
        $userGroups = $user->groups;

        $groups = Group::with('sharedWith')->whereIn('id',$groupsid->pluck('id'))->orWhere('type','public')->where('permissions','readwrite')->get();
        // dd($mygroups);
        // $sharedGroups = $userGroups->flatMap(function ($group) {
        //     return $group->sharedWith; // Assuming sharedWith is the relation name for group shares
        // })->unique();
        // $mergedGroups = $groups->merge($sharedGroups)->unique('id');
        $post = '';
        $postgroups = [];
        $visibile = false;
        return inertia('AddUpdateRecord', ['user' => $user, 'groups' => $groups, 'post' => $post,'postgroups' => $postgroups, 'visibile' => $visibile]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'subject' => 'required|string',
            'files' => 'nullable|file', // Assuming files are optional
            'content' => 'required',
        ]);

        $visibility = '';
        $status = '';
        if($request->input('visibility_me') == '1'){
            $visibility = 'private';
        }else{
            $visibility = 'public';
        }

        if($request->input('publish') == true){
            $status = 'published';
        }else{
            $status = 'unpublished';
        }
        // If the validation passes, create a new post
        $post = new Post();
        $post->user_id = Auth::user()->id;
        $post->type = $request->input('type');
        $post->subject = $request->input('subject');
        $post->visibility = $visibility;
        $post->status = $status;

        // Handle file upload if provided
        if ($request->hasFile('files')) {
            $file = $request->file('files');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destination ='/posts';
            $file->move(public_path($destination), $fileName);
            $post->files = '/posts'.'/'.$fileName;
        }

        // Save the post
        $post->save();

        $translate = new PostTranslation();
        $translate->post_id = $post->id;
        $translate->title = $request->input('title');
        $translate->content = $request->input('content');
        $translate->save();

        foreach($request->input('visibility') as $group){
            $visibile = new PostVisibility();
            $visibile->group_id = $group;
            $visibile->post_id = $post->id;
            $visibile->save();
        }

        return redirect('/content');

        // Optionally, you can redirect to a success page or do anything else here.
    }

    public function edit($id){
        $user = Auth::user();
        $groupsid = $user->groups;
        $groups = Group::whereIn('id',$groupsid->pluck('id'))->orWhere('type','public')->where('permissions','readwrite')->get();
        $post = Post::with('groups','translations','user')->where('id', $id)->first();
        $postgroups = $post->groups()->pluck('groups.id')->toArray();
        $visibile = false;
        if($post->visibility == 'private'){
            $visibile = true;
        }
        return inertia('AddUpdateRecord', ['user' => $user, 'post' => $post, 'groups' => $groups, 'postgroups' => $postgroups, 'visibile' => $visibile]);
    }

    public function update(Request $request){
        // dd($request->all());
        $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'subject' => 'required|string',
            'files' => 'nullable|file', // Assuming files are optional
            'content' => 'required',
        ]);

        $visibility = '';
        $status = '';
        if($request->input('visibility_me') == '1'){
            $visibility = 'private';
        }else{
            $visibility = 'public';
        }

        if($request->input('publish') == true){
            $status = 'published';
        }else{
            $status = 'unpublished';
        }
        // If the validation passes, create a new post
        $post = Post::where('id',$request->input('id'))->first();
        $post->user_id = Auth::user()->id;
        $post->type = $request->input('type');
        $post->subject = $request->input('subject');
        $post->visibility = $visibility;
        $post->status = $status;

        // Handle file upload if provided
        if ($request->hasFile('files')) {
            $file = $request->file('files');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destination ='/posts';
            $file->move(public_path($destination), $fileName);
            $post->files = '/posts'.'/'.$fileName;
        }

        // Save the post
        $post->save();

        $translate = PostTranslation::where('post_id',$post->id)->first();
        $translate->post_id = $post->id;
        $translate->title = $request->input('title');
        $translate->content = $request->input('content');
        $translate->save();
        if($visibility == 'public'){
            PostVisibility::where('post_id',$post->id)->delete();
            foreach($request->input('visibility') as $group){
                $visibile = New PostVisibility();
                $visibile->group_id = $group;
                $visibile->post_id = $post->id;
                $visibile->save();
            }
        }else{
            $visibiles = PostVisibility::where('post_id',$post->id)->delete();
        }


        return redirect('/content');

    }
    public function detail($id){
        $user = Auth::user();
        $groups = $user->groups;
        $post = Post::with('translations','user','groups','favoritedBy')->where('id', $id)->first();
        return inertia('RecordDetail', compact('user','post'));
    }
    public function search(Request $request){

        $posts = Post::with('translations', 'user', 'groups', 'favoritedBy')
        ->where('user_id', Auth::user()->id);

    if ($request->input('groupsearch')) {
        $groupId = $request->input('groupsearch');
        $posts->whereHas('groups', function ($query) use ($groupId) {
            $query->where('post_visibility.group_id', $groupId);
        });
    } if ($request->input('recordtype')) {
        $recordType = $request->input('recordtype');
        $posts->where('type', $recordType);
    }

    $posts = $posts->orderBy('id', 'desc')->get();

    $user = Auth::user(); // You should define $user if not already defined

    $posts->each(function ($post) use ($user) {
        $post->isFavorited = $post->favoritedBy->contains('id', $user->id);
    });
        return $posts;
    }
    public function favorite(Post $post)
    {
        $user = Auth::user();
        $user->favorites()->attach($post->id);

        return $user;
    }

    public function unfavorite(Post $post)
    {
        $user = Auth::user();
        $user->favorites()->detach($post->id);

        return $user;
    }
    public function allfavorite(){
        $user = Auth::user();
        $groups = $user->groups;

        $posts = Post::with('favoritedBy','translations', 'user', 'groups')->whereIn('posts.id', $user->favorites()->pluck('posts.id'))->get();

        // Attach the isFavorited property to each post
        $posts->each(function ($post) use ($user) {
            $post->isFavorited = true;
        });
        return inertia('MyContent', ['user' => $user, 'posts' => $posts, 'currentpage' => 'My Favorite']);

    }
    public function privatePosts(Request $request,$id){

        $user = Auth::user();
        $groups = $user->groups;
        $segment = $request->segment(1);
        $groupId = 0;
        if($segment == 'public-content' && $user->role != 'admin'){
           $group = Group::where('id',$id)->where('type','public')->first();
           $groupId = @$group->id;
        }elseif($user->role == 'admin'){
           $groupId = $id;
        }else{
            $userGroups = $user->groups;

            $groups = Group::with('sharedWith')
            ->whereIn('id', $userGroups->pluck('id'))
            ->where('type', 'private')
            ->get();

            $sharedGroups = $userGroups->flatMap(function ($group) {
                return $group->sharedWith; // Assuming sharedWith is the relation name for group shares
            })->unique();

            // Adding the is_shared flag to original groups
            $groups->each(function ($group) {
                $group->is_shared = false;
            });

            // Adding the is_shared flag to shared groups
            $sharedGroups->each(function ($group) {
                $group->is_shared = true;
            });

            $mergedGroups = $groups->merge($sharedGroups)->unique('id');

        // Now, when iterating over $mergedGroups, you can check $group->is_shared to determine if the group is shared or not.
           $privategroups = $mergedGroups;
           $p_group = $privategroups->find($id);
           $groupId = @$p_group->id;
        }
        // Retrieve the group with its postVisibilities
        $group = Group::with('postVisibilities')->find($groupId);
        if (!$group) {
            // Return an error or handle this case as needed.
            $posts = [];
        }else{
            // Get an array of post IDs associated with the group
            $postIds = $group->postVisibilities->pluck('post_id')->toArray();
            // Retrieve the posts based on the post IDs
            $posts = Post::with('translations','user','groups','favoritedBy')->whereIn('id', $postIds)->where('visibility','public')->where('status','published')->orderBy('id','desc')->get();
            $posts->each(function ($post) use ($user) {
                $post->isFavorited = $post->favoritedBy->contains('id', $user->id);
            });
        }

        return inertia('MyContent', ['user' => $user, 'posts' => $posts, 'group' => $group,'currentpage' => @$group->name]);

    }
    public function sharePost($id){
        $user = Auth::user();
        $groups = $user->groups;
        $post = Post::with('favoritedBy','translations', 'user', 'groups')->where('id', $id)->first();
        return inertia('Share', ['user' => $user, 'post' => $post]);
    }
    public function share(Request $request){
        $post = Post::find($request->input('id'));
        Mail::to($request->input('email'))->send(new ShareEmail($post,$request->all()));
        return "email sent!";
    }
    public function destory($id){
        $post = Post::where('id',$id)->delete();
        return $post;
    }
}
