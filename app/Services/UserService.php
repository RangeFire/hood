<?php
namespace App\Services;

use App\Models;
use App\Helpers\Auth;
use Firebase\JWT\JWT;
use DateTimeImmutable;
use App\Lib\FileUpload;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\PasswordReset;
use App\Models\User;

class UserService extends Service {

    public function generateAccessToken($user) {

        if(!$user) return false;

        $secret_Key  = '68V0zWFrS72GbpPreidkQFLfj4v9m3Ti+DXc8OB0gcM=';
        $date   = new DateTimeImmutable();
        $expire_at     = $date->modify('+7 days')->getTimestamp();      // Add 60 seconds
        $domainName = "wehood.app";
        $username   = $user->username;                                           // Retrieved from filtered POST data
        $request_data = [
            'iat'  => $date->getTimestamp(),         // Issued at: time when the token was generated
            'iss'  => $domainName,                       // Issuer
            'nbf'  => $date->getTimestamp(),         // Not before
            'exp'  => $expire_at,                           // Expire
            'userName' => $username,                     // User name
        ];

        $user->jwt_access_key = JWT::encode(
            $request_data,
            $secret_Key,
            'HS512'
        );

        $user->save();

        return $user->jwt_access_key;

    }

    public function login() {

        // Check if Request is empty
        if($this->isInvalid(['username', 'password'], Request::post())) {
            return [true, null];
        }

        extract(Request::post());
        $user = Models\User::where(['username' => $username, 'password' => hash('sha256', $password)])->first();

        // Check if userdata is wrong
        if(!$user) {
            return [true, null];
        }
        
        Auth::setUser($user);
        $userLastProject = $this->getLastUserProject($user->id);

        if($user->favorite_project != null) {
            Session::put('activeProject', $user->favorite_project);
            return [false, $user];
        }

        if($userLastProject) {
            Session::put('activeProject', $userLastProject->project_id);
            return [false, $user];
        } else {
            Session::put('activeProject', 'empty');
            return ['no_projects', $user];
        }

    }

    public function register() {
        
        extract(Request::post());

        if($this->isInvalid(['email', 'username', 'password'], Request::post())) 
            return [true, 'not_filled'];

        if(strlen($password) < 6)
            return [true , 'password_length'];

            $existingCustomer = (new Models\User)->checkExistingEmail($email);

            $existingUsername = (new Models\User)->checkExistingUsername($username);
    
            if($existingCustomer)
                return [true, 'customer_exists'];
    
            if($existingUsername)
                return [true, 'username_already_assigned'];


        $registerUser = Models\User::create([
            'username' => $username,
            'email' => $email,
            'password' => hash('sha256',$password),
            'avatar' => '/images/roboter.png',
        ]);

        $isSaved = $registerUser->save();

        if($isSaved)
            (new GameOSAdminService)->alertNewRegestration();
            return [false, $registerUser];

    }

    public function getAll() {
        $allUsers =  Models\UserProject::where('project_id', session('activeProject'))->get();

        foreach($allUsers as $i => $userProject) {
            $user = Models\User::find($userProject->user_id);
            $allUsers[$i]->currentRole = $user->currentUserRole();
        }

        return $allUsers;
    }

    public function deleteUser($user_id, $project_id) {

        $checkOwner = Models\Project::where([
            ['id', '=', $project_id],
            ['owner_id', '=', $user_id],
        ])->first();

        if($checkOwner) {
            return null;
        }

        $checkIfUserIsSameProject = Models\UserProject::where([
            ['user_id', '=', Auth::$user->id],
            ['project_id', '=', $project_id],
        ])->first();

        if($checkIfUserIsSameProject) {
            $deleteUser = Models\UserProject::where([
                ['user_id', '=', $user_id],
                ['project_id', '=', $project_id],
            ])->first();

            if(!$deleteUser) return info('error_no_delet_user:userService:122');
    
            $isSaved = $deleteUser->delete();
            return $isSaved;
        } else {
            return false;
        }
    }

    public function savePasswordReset($resetToken) {
        extract(Request::post());

        if(strlen($password) < 6) {
            return redirect()->back()->with('error', 'Passwort muss mindestens 6 Zeichen lang sein');
        }

        if($password === $password_confirmation) {
            $token = PasswordReset::where('token', $resetToken)->first();

            $user = User::where('username', $token->username)->first();

            $user->update([
                'password' => hash('sha256', $password),
            ]);
            return true;
        }else {
            return false;
        }
    }

    public function editProfile() {
        extract(Request::post());
        $userData = Models\User::find(Auth::$user->id);

        // check password submitGuzzleRequest
        if ($password) {
            $operation = $this->setModelFromArray($userData, [
                'fullname' => $fullname,
                'email' => $email,
                'username' => $username,
                'password' => hash('sha256',$password),
                'avatar' => $profileImageURL,
            ]);
        } else {
            $operation = $this->setModelFromArray($userData, [
                'fullname' => $fullname,
                'email' => $email,
                'username' => $username,
                'avatar' => $profileImageURL,
            ]);
        }

        $isSaved = $operation->save();

        return $isSaved;
    }

    public function getLastUserProject($user_id) {
        $lastProject = Models\UserProject::where('user_id', $user_id)->orderBy('created_at', 'desc')->first();

        return $lastProject;
    }


    /*
        Roles and Permissions
    */

    public function getAllRoles() {
        $allRoles = Models\Role::where('project_id', Session::get('activeProject'))->get();

        return $allRoles;
    }

    public function addRole() {
        return $this->manageRole();
    }

    public function editRole($id) {
        return $this->manageRole($id);
    }

    public function manageRole($id = null) {
        extract(Request::post());

        if(!isset($manage_users)) $manage_users = 0;
        else $manage_users = 1;

        if(!isset($settings)) $settings = 0;
        else $settings = 1;

        if(!isset($support)) $support = 0;
        else $support = 1;

        // if(!isset($ingame_integration)) $ingame_integration = 0;
        // else $ingame_integration = 1;

        if(!isset($monitoring)) $monitoring = 0;
        else $monitoring = 1;

        if(!isset($surveys)) $surveys = 0;
        else $surveys = 1;

        if(!isset($wishes)) $wishes = 0;
        else $wishes = 1;

        if(!isset($bugreports)) $bugreports = 0;
        else $bugreports = 1;

        if($id) {
            $role = Models\Role::find($id);

            $role->title = $rolename ?? 0;
            $role->manage_users = $manage_users ?? 0;
            $role->settings = $settings ?? 0;
            $role->support = $support ?? 0;
            // $role->ingame_integration = $ingame_integration ?? 0;

            $role->monitoring = $monitoring ?? 0;
            $role->surveys = $surveys ?? 0;
            $role->wishes = $wishes ?? 0;
            $role->bugreports = $bugreports ?? 0;

            $isSaved = $role->save();

            return true;

        } else {
            $model = new Models\Role;

            $project = Models\Project::find(Session::get('activeProject'));

            $role = $this->setModelFromArray($model, [
                'title' => $rolename,
                'project_id' => $project->id,
                // Permissions
                'manage_users' => $manage_users ?? 0,
                'settings' => $settings ?? 0,
                'support' => $support ?? 0,
                // 'ingame_integration' => $ingame_integration ?? 0,

                'monitoring' => $monitoring ?? 0,
                'surveys' => $surveys ?? 0,
                'wishes' => $wishes ?? 0,
                'bugreports' => $bugreports ?? 0,
            ]);

            $isSaved = $role->save();
            return $isSaved;
    
        }

        return false;

    }

    public function deleteRole($id) {
        $role = Models\Role::find($id);

        $isSaved = $role->delete();

        return $isSaved;
    }

    public function assignRole($user_id) {

        extract(Request::post());

        $user = Models\User::find($user_id);
        $role = Models\Role::find($roleID);

        if(!$user || !$role) return false;

        $userProject = $user->currentUserProject();

        $userProject->role_id = $role->id;
        $isSaved = $userProject->save();

        if(!$isSaved) return false;

        return true;

    }

    public function setFavoriteProject() {

        extract(Request::post());

        $user = Models\User::find($userId);

        if(!$user) return false;

        $user->favorite_project = $favoriteProjectId;
        $isSaved = $user->save();

        if(!$isSaved) return false;

        return true;

    }

    public function prozessDiscordAuth() {
        $discord_code = $_GET['code'];

        $payload = [
            'code'=>$discord_code,
            'client_id'=>'1041742601520418826',
            'client_secret'=>'YMclML5-mm1xmtfCdohKYzc1YVdhMze0',
            'grant_type'=>'authorization_code',
            'redirect_uri'=> env('DISCORD_RedirectURI'),
            'scope'=>'identify%20guids%20email',
        ];
        
        $payload_string = http_build_query($payload);
        $discord_token_url = "https://discordapp.com/api/oauth2/token";
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $discord_token_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        $result = curl_exec($ch);

        if(!$result){
            return null;
        }

        $result = json_decode($result,true);
        $access_token = $result['access_token'];
        
        $discord_users_url = "https://discordapp.com/api/users/@me";
        $header = array("Authorization: Bearer $access_token", "Content-Type: application/x-www-form-urlencoded");
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $discord_users_url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        $result = curl_exec($ch);
        
        $result = json_decode($result, true);

        $this->loginWithDiscord($result);

    }
 
    public function loginWithDiscord($result) {

        $user = Models\User::where('external_auth', $result['id'])->first();
        
        if(!$user) {
            $discord_id = $result['id'];
            $avatar = $result['avatar'];
            $avatar_url = "https://cdn.discordapp.com/avatars/$discord_id/$avatar.jpg";

            $registerUser = Models\User::create([
                'username' => $result['username'],
                'email' => $result['email'],
                'external_auth' => $discord_id,
                'avatar' => $avatar_url,
            ]);

            $isSaved = $registerUser->save();
            $user = Models\User::where('external_auth', $result['id'])->first();
        }

        Auth::setUser($user);
        $userLastProject = $this->getLastUserProject($user->id);
        
        if($user->favorite_project != null) {
            Session::put('activeProject', $user->favorite_project);
            return [false, $user];
        }
        
        if($userLastProject) {
            Session::put('activeProject', $userLastProject->project_id);
            return [false, $user];
        } else {
            Session::put('activeProject', 'empty');
            return ['no_projects', $user];
        }
        
    }
    
    public function registerWithDiscord($result) {

        $user = Models\User::where('external_auth', $result['id'])->first();
        
        if(!$user) {
            $discord_id = $result['id'];
            $avatar = $result['avatar'];
            $avatar_url = "https://cdn.discordapp.com/avatars/$discord_id/$avatar.jpg";

            $registerUser = Models\User::create([
                'username' => $result['username'],
                'email' => $result['email'],
                'external_auth' => $discord_id,
                'avatar' => $avatar_url,
            ]);

            $isSaved = $registerUser->save();
            $user = Models\User::where('external_auth', $result['id'])->first();
        }

        Auth::setUser($user);
        $userLastProject = $this->getLastUserProject($user->id);
        
        if($user->favorite_project != null) {
            Session::put('activeProject', $user->favorite_project);
            return [false, $user];
        }
        
        if($userLastProject) {
            Session::put('activeProject', $userLastProject->project_id);
            return [false, $user];
        } else {
            Session::put('activeProject', 'empty');
            return ['no_projects', $user];
        }
    }

    public function registerGoogleAuth($result) {

        $user = Models\User::where('external_auth', $result['id'])->first();
        
        if(!$user) {

            $registerUser = Models\User::create([
                'username' => $result['email'],
                'email' => $result['email'],
                'external_auth' => $result['id'],
                'avatar' => null,
            ]);

            $isSaved = $registerUser->save();
            $user = Models\User::where('external_auth', $result['id'])->first();
        }
        
        Auth::setUser($user);
        $userLastProject = $this->getLastUserProject($user->id);
        
        if($user->favorite_project != null) {
            Session::put('activeProject', $user->favorite_project);
            return [false, $user];
        }
        
        if($userLastProject) {
            Session::put('activeProject', $userLastProject->project_id);
            return [false, $user];
        } else {
            Session::put('activeProject', 'empty');
            return ['no_projects', $user];
        }
    }
}