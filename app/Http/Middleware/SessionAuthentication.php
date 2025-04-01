<?php

namespace App\Http\Middleware;

use Closure;
use App\Models;

use \App\Helpers\Auth;
use Illuminate\Http\Request;
use App\Scopes\CustomerScope;
use Illuminate\Support\Facades\Session;

class SessionAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // $request->session()->put('key', 'value');
        // if(!$request->bearerToken())
        //     return response()->json(['error' => 'Unauthorized', 'data' => null], 401);

        if(!$request->session()->has('user')) {
            return redirect()->to('/');
        }

        $user = $request->session()->get('user');

        $isOwner = false;

        if(!empty(Session::get('activeProject')) && Session::get('activeProject') != 'empty') {
            $project = Models\Project::find(Session::get('activeProject'));

            if(!$project) {
                // Session::set('activeProject');
                return redirect()->to('/logout');
            }

            /* sets active role */
            $userProject = $project->userProjects()->where('user_id', $user->id)->first();
            if(isset($userProject->role)) {
                $user->roleID = $userProject->role->id;
            }

            // check if project owner_id matches current logged in id
            if($project->owner_id == $user->id) {
                $isOwner = true;
            }

            $user->activeProject = $project;

        }else {
            $isOwner = false;
            $user->activeProject = null;
        }

        $user->isOwner = $isOwner;

        Auth::setUser($user);

        return $next($request);

    }

}
