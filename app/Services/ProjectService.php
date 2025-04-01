<?php
namespace App\Services;

use App\Models;
use App\Helpers\Auth;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use App\Helpers\Subscription;
use App\Models\CommunityCenterConfig;

class ProjectService extends Service {

     public function create() {
        extract(Request::post());

        $project = $this->createModelFromArray(new Models\Project, [
            'name' => $projectName, 
            'trial_end' => Date('y-m-d', strtotime('+10 days')), 
            'owner_id' => Auth::user('id'), 
            'project_hash' => $this->generateProjektHash(),
            'livechat_token' => $this->generateLivechatToken(),
        ]);

        if($project) {
            $userProject = $this->createModelFromArray(new Models\UserProject, [
                'project_id' => $project->id, 
                'user_id' => Auth::user('id'), 
            ]);
    
            $isSaved = $userProject->save();
        }

        session(['activeProject' => $project->id]);
        return $project;
     }

    function generateProjektHash($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function generateLivechatToken($length = 10) {
        return bin2hex(random_bytes($length));
    }

     public function getInviteCode($project_id) {
        $checkCode = Models\Invite::where('project_id', $project_id)->first();

        return $checkCode;
     }  

     public function generateInviteCode($project_id) {

        $checkTeam = Models\UserProject::where([
            ['user_id', '=', Auth::user('id')],
            ['project_id', '=', $project_id],
        ])->first();


        if(!$checkTeam) {
            return 'not_inside_team';
        }

        $invitationCode = rand(1000,9999);
        $checkCode = Models\Invite::find($invitationCode);
        // Check ob der Invitation Code bereits vorhanden ist TODO.
        if($checkCode) {
            return null;
        } else {
            $checkInviteElement = Models\Invite::where('project_id', $project_id)->first();

            if($checkInviteElement) {
                $operation = $this->setModelFromArray($checkInviteElement, [
                    'code' => $invitationCode, 
                    'project_id' => $project_id, 
                ]);
    
                $isSaved = $operation->save();
            } else {
                $project = $this->createModelFromArray(new Models\Invite, [
                    'code' => $invitationCode, 
                    'project_id' => $project_id, 
                ]);
            }
            return $invitationCode;
        }
    
     }

     public function join() {
        extract(Request::post());

        $invitationCode = $digit1 . $digit2 . $digit3 . $digit4;
        $checkInviteCode = Models\Invite::where('code', $invitationCode)->first();

        if($checkInviteCode) {

                $userIsInProject = Models\UserProject::where([
                    ['user_id', '=', Auth::user('id')],
                    ['project_id', '=', $checkInviteCode->project_id],
                ])->first();

                if($userIsInProject) {
                    return false;
                }

            $upgradeInviteUsed = $this->setModelFromArray($checkInviteCode, [
                'used' => $checkInviteCode->used + 1, 
            ]);

            $isSaved = $upgradeInviteUsed->save();

            $addUserToProject = $this->createModelFromArray(new Models\UserProject, [
                'project_id' => $checkInviteCode->project_id, 
                'user_id' => Auth::user('id'), 
            ]);

            session(['activeProject' => $checkInviteCode->project_id]);
            return $addUserToProject;
        }

        return false;

     }

     public function change($project_id) {

        $project = Models\UserProject::where([
            ['user_id', '=', Auth::user('id')],
            ['project_id', '=', $project_id],
        ])->first();

        if($project) {
            session(['activeProject' => $project->project_id]);
        }

        return $project;
     }

    public function setProjectGuildID() {

        $guild_id = $_GET['guild_id'];

        $project = Models\Project::find(Session::get('activeProject'));

        if(!$project) return false;

        $project->guild_id = $guild_id;
        $project->save();

        return true;

    }

    public function setProjectInitChannel() {

        $channel = Request::post('discord_channel');

        $project = Models\Project::find(session('activeProject'));

        $this->initChannelDiscordBot($project->guild_id, $channel);

        return true;

    }

    private function initChannelDiscordBot($project_id, $channel_id) {

        $support_categories = \App\Models\SupportCategory::where('project_id', Session::get('activeProject'))->get();

        $project = Models\Project::find(Session::get('activeProject'));
        $supportConfig = $project->supportConfig;

        if ($project->show_whitelabel == "true" || !Subscription::hasActiveSubscription('branding', $project->id)) {
            $showWhitelabel = true;
        } else {
            $showWhitelabel = false;
        }

        $response = Http::post(env('DISCORD_BOT_HOST').'/ticketInitChannel/'.$project_id.'/'.$channel_id, [
            'categories' => $support_categories,
            'showWhitelabel' => $showWhitelabel,
            'discord_init_title' => $supportConfig->discord_init_title ?? null,
            'discord_init_description' => $supportConfig->discord_init_description ?? null,
        ]);

    }

    public function deleteProject() {

        $project = Models\Project::find(session('activeProject'));
        $user = Models\User::find(Auth::user('id'));
        
        if(!$project) return false;
        if(!$user) return false;

        if($user->id != $project->owner_id) {
            return 'not_owner';
        }

        $operation = $project->delete();

        // detach user projects from project
        $project->userProjects()->delete();

        return $operation;

    }

    public function getCommunityCenterHome($projectHash) {
        $projectData = Models\Project::where('project_hash', $projectHash)->first();

        return $projectData;
    }

    public function getProjectByLivechatID(string $livechatID) : ?Models\Project {
        $project = Models\Project::where('livechat_token', $livechatID)->first();

        return $project;
    }

    public function createCommunityCenterDBEntry() {
        // $projects = Models\Project::get();

        // foreach ($projects as $project) {
        //     \App\Models\CommunityCenterConfig::create([
        //         'project_id' => $project->id,
        //     ]);
        // }

        $eventAction = "DUTPYs";
        $title = "MEINS PROJEKT";        

        $apiURL = "https://api.simplepush.io/send/$eventAction/Hood%20-%20Neuer%20Chat%20gestartet/Es%20wurde%20ein%20neuer%20Chat%20in%20deinem%20Hood%20Projekt:%20".$title."%20eröffnet.";
        info($apiURL);
        $response = Http::get($apiURL);
    
        return true;
    }

}