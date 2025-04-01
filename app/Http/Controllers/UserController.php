<?php

namespace App\Http\Controllers;

use App\Helpers\Auth;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\ProjectService;


class UserController extends Controller
{
    public function users(UserService $userService, ProjectService $projectService) {

        if(!Auth::hasPermission('manage_users')) return redirect('/');

        // if(\App\Helpers\Auth::$user->permission_level != 'admin') {
        //     return redirect()->to('/dashboard')->with('error', 'Sie haben keine Berechtigung für diese Seite.');
        // }
        
        $users = $userService->getAll();
        $roles = $userService->getAllRoles();
        $inviteCode = $projectService->getInviteCode(session('activeProject'));

        return view('pages/usersView', [
            'users' => $users,
            'roles' => $roles,
            'inviteCode' => $inviteCode
        ]);    
    }

    public function deleteUser(UserService $userService, $user_id, $project_id) {

        if(!Auth::hasPermission('manage_users')) return redirect('/');

        $isSaved = $userService->deleteUser($user_id, $project_id);

        if($isSaved) return redirect()->back()->with('success', 'Der Nutzer wurde gelöscht.');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function editProfile(UserService $userService) {
        $isSaved = $userService->editProfile();

        if($isSaved) return redirect()->back()->with('success', 'Deine Profilinformationen wurden gespeichert.');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    /*
        Roles and Permissions
    */

    public function addRole(UserService $userService) {

        if(!Auth::hasPermission('manage_users')) return redirect('/');

        $isSaved = $userService->addRole();

        if(!$isSaved) return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        
        return redirect()->back()->with('success', 'Die Rolle wurde erfolgreich angelegt.');
         
    }

    public function editRole(UserService $userService, $id) {

        if(!Auth::hasPermission('manage_users')) return redirect('/');

        $isSaved = $userService->editRole($id);

        if(!$isSaved) return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten');  
        
        return redirect()->back()->with('success', 'Die Rolle wurde erfolgreich bearbeitet.');
        
    }

    public function deleteRole(UserService $userService, $id) {

        if(!Auth::hasPermission('manage_users')) return redirect('/');
        
        $isSaved = $userService->deleteRole($id);

        if($isSaved) return redirect()->back()->with('success', 'Die Rolle wurde erfolgreich gelöscht.');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten');  
    }

    public function assignRole(UserService $userService, $user_id) {

        if(!Auth::hasPermission('manage_users')) return redirect('/');
        
        $isSaved = $userService->assignRole($user_id);
        
        if($isSaved) return redirect()->back()->with('success', 'Die Rolle wurde erfolgreich zugewiesen.');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten');  
    }

    public function setFavoriteProject(UserService $userService) {
        
        $isSaved = $userService->setFavoriteProject();
        
        if($isSaved) return redirect()->back()->with('success', 'Das Projekt wurde als Favorit markiert.');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten');  
    }

}
