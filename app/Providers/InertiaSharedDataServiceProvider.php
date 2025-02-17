<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Auth;
use App\Models\Group;

class InertiaSharedDataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        Inertia::share([
            'publicgroups' => function () {
                // Fetch and return the groups data from your backend here
                // For example, you can use an Eloquent query to retrieve the groups
                return \App\Models\Group::where('type','public')->get();
            },
            'groupleader' => function (){
                $leader = [];
                $user = Auth::user();
                if($user){
                 $leader = $user->leadergroups;
                }
                return $leader;
            },
            'privategroups' => function (){
                $privategroups = [];
                $user = Auth::user();
                if($user){
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
                }
                
                // dd($mergedGroups);
                return $privategroups;
            }
        ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
