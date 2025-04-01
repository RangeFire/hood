<?php
namespace App\Services;

use App\Models;
use App\Helpers\Auth;
use App\Models\Project;
use App\Models\LivechatConfig;
use App\Models\SupportConfig;
use \App\Enums\CustomerStatusEnum;
use App\Http\Resources\UserResource;
use App\Models\CommunityCenterConfig;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class SettingsService extends Service {


    public function setCustomCategories() {

        $submit_data = Request::post('submit_data');

        $submit_data = json_decode($submit_data, true);
        
        $db_format = [];

        // clear old categories
        Models\SupportCategory::where('project_id', Session::get('activeProject'))->delete();

        foreach($submit_data as $e) {

            $db_format[] = [
                'name' => $e['name'],
                'location' => $e['location'],
            ];

            // save support categories
            $support_category = new Models\SupportCategory();
            $support_category->name = $e['name'];
            $support_category->location = $e['location'];
            $support_category->project_id = Session::get('activeProject');
            $support_category->save();

        }

        return true;

    }

    public function ticketChannelMessage() {

        if($this->isInvalid(['title', 'description'], Request::post())) {
            return false;
        }

        $project = Project::find(Session::get('activeProject'));
        
        $supportConfig = SupportConfig::updateOrCreate(
            ['project_id' => $project->id],
            [
                'discord_init_title' => Request::post('title'),
                'discord_init_description' => Request::post('description')
            ]
        );

        if(!$supportConfig) return false;

        return true;

    }

    public function ticketWelcomeMessage() {
            
        if($this->isInvalid(['message'], Request::post())) {
            return false;
        }
        
        $project = Project::find(Session::get('activeProject'));
        
        $supportConfig = SupportConfig::updateOrCreate(
            ['project_id' => $project->id],
            [
                'discord_ticket_welcome_message' => Request::post('message'),
            ]
        );
        
        if(!$supportConfig) return false;
        
        return true;
            
    }

    public function addTextSnippet() {
        extract(Request::post());

        $addTextSnippet = $this->createModelFromArray(new Models\TextSnippets, [
            'identifier' => $snippetIdentifier, 
            'message' => $snippetMessage, 
            'project_id' => session('activeProject'), 
        ]);

        return $addTextSnippet;
    }

    public function deleteTextSnippet() {
        extract(Request::post());

        $checkTeam = Models\UserProject::where([
            ['user_id', '=', Auth::user('id')],
            ['project_id', '=', session('activeProject')],
        ])->first();


        if(!$checkTeam) {
            return 'not_inside_team';
        }

        $deleteSnippet = Models\TextSnippets::where([
            ['id', '=', $snippetId],
            ['project_id', '=', session('activeProject')],
        ])->first();

        $isSaved = $deleteSnippet->delete();

        return $isSaved;

    }

    public function editSupportTime() {

        if($this->isInvalid(['time_start', 'time_end', 'out_of_time_message'], Request::post())) {
            return false;
        }

        info(Request::all());

        $project = Project::find(Session::get('activeProject'));
        
        $supportConfig = SupportConfig::updateOrCreate(
            ['project_id' => $project->id],
            [
                'support_days' => Request::post('support_days') ?: null,
                'time_start' => Request::post('time_start'),
                'time_end' => Request::post('time_end'),
                'out_of_time_message' => Request::post('out_of_time_message'),
            ]
        );
        
        if(!$supportConfig) return false;
        
        return true;
        
    }

    public function editLogo() {

        $project = Project::find(Session::get('activeProject'));
        extract(Request::post());

        if($logoURL == null) return false;

        $operation = $this->setModelFromArray($project, [
            'logo' => $logoURL
        ]);
        
        $isSaved = $operation->save();
        if(!$operation) return false;

        return true;
        
    }

    public function editDomain() {

        $project = Project::find(Session::get('activeProject'));
        extract(Request::post());

        if($customDomain == null) return false;
        $operation = $this->setModelFromArray($project, [
            'project_hash' => $customDomain
        ]);
        
        $isSaved = $operation->save();
        if(!$operation) return false;

        return true;
        
    }

    public function editWhitelabel() {

        extract(Request::post());

        $livechatConfig = Project::updateOrCreate(
            ['id' => Session::get('activeProject')],
            [
                'show_whitelabel' => $showWhitelabel,
            ]
        );
        
        $isSaved = $livechatConfig->save();
        if(!$livechatConfig) return false;

        return true;
        
    }
    
    public function editLivechatTexts() {

        extract(Request::post());

        $livechatConfig = LivechatConfig::updateOrCreate(
            ['project_id' => Session::get('activeProject')],
            [
                'chat_headline' => $headline,
                'chat_subtitle' => $subtitle,
            ]
        );
        
        $isSaved = $livechatConfig->save();
        if(!$livechatConfig) return false;

        return true;
        
    }

    public function editLiveChatBubbleImage() {

        extract(Request::post());

        $livechatConfig = LivechatConfig::updateOrCreate(
            ['project_id' => Session::get('activeProject')],
            [
                'bubble_image' => $liveChatBubbleImageURL,
            ]
        );
        
        $isSaved = $livechatConfig->save();
        if(!$livechatConfig) return false;

        return true;
        
    }

    public function editCommunityCenterHeadline() {

        extract(Request::post());

        $communityCenterConfig = CommunityCenterConfig::updateOrCreate(
            ['project_id' => Session::get('activeProject')],
            [
                'headline' => $communityCenter_headline,
            ]
        );
        
        $isSaved = $communityCenterConfig->save();
        if(!$communityCenterConfig) return false;

        return true;
        
    }

    public function editProjectName() {

        $project = Project::find(Session::get('activeProject'));
        extract(Request::post());

        if($projektName == null) return false;

        $operation = $this->setModelFromArray($project, [
            'name' => $projektName
        ]);
        
        $isSaved = $operation->save();
        if(!$operation) return false;

        return true;
        
    }

    public function deleteAlertElement() {

        extract(Request::post());

        if($elementId == null) return false;

        $deleteAlert = Models\EventAction::where([
            ['id', '=', $elementId],
        ])->first();

        $isSaved = $deleteAlert->delete();
        if(!$isSaved) return false;

        return true;
        
    }
}