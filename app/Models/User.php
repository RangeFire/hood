<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;

    // protected $hidden = ['password'];

    protected $guarded = [];

    /* relationships */

        public function userProjects() {
            return $this->hasMany(UserProject::class, 'user_id');
        }

    /* relationships */

    public function currentUserProject() {

        $userProject = $this->userProjects()->where('project_id', Session::get('activeProject'))->first();
        if(!$userProject) return false;
        return $userProject;
    }

    public function currentUserRole() {

        $userProject = $this->currentUserProject();

        if(!$userProject) return false;

        $role = Role::find($userProject->role_id);

        if(!$role) return false;

        return $role;

    }

    public function findUser($user_id) {
            
            $user = User::find($user_id);
    
            if(!$user) return false;
    
            return $user;
    
    }

    
    public function checkExistingEmail ($email) {
        return $this::where([
            'email' => $email,
        ])->first();
    }

    public function checkExistingUsername ($username) {
        return $this::where([
            'username' => $username,
        ])->first();
    }
    /* adds: where('customer_id', Auth::check()) */
    // protected static function booted() {
    //     static::addGlobalScope(new CustomerScope);
    // }

}
