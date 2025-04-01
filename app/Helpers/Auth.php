<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;
use App\Models;

class Auth {

    public static $user;

    public static function user($key = null) {

        if($key) {
            if(isset(self::$user->$key))
                return self::$user->$key;
        }
        return self::$user;
    }

    public static function setUser($user) {
        self::$user = $user;
        Session::put('user', $user);
    }

    /**
    * @param $type -> "manage_users" || "settings" || "support" || "ingame_integration"
    */ 
    public static function hasPermission($type) {

        if(Session::get('user')->isOwner) return true;

        if(!Session::get('user')->roleID) return false;

        $role = Models\Role::find(Session::get('user')->roleID);

        if(!$role) return false;

        if($type == 'manage_users' && $role->manage_users) return true;
        elseif($type == 'settings' && $role->settings) return true;
        elseif($type == 'support' && $role->support) return true;
        elseif($type == 'ingame_integration' && $role->ingame_integration) return true;
        elseif($type == 'monitoring' && $role->monitoring) return true;
        elseif($type == 'surveys' && $role->surveys) return true;
        elseif($type == 'wishes' && $role->wishes) return true;
        elseif($type == 'bugreports' && $role->bugreports) return true;

        return false;

    }
    
}


?>