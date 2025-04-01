<?php
namespace App\Services;

use App\Models;
use App\Helpers\Subscription;
use App\Services;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class LiveChatService extends Service {

    public function openTicket(string $livechat_token) {
        $project = Models\Project::where('livechat_token', $livechat_token)->first();

        if(!$project) {
            return false;
        }

        $ticket = Models\Ticket::create([
            'type' => 'livechat',
            'project_id' => $project->id,
            'livechat_ticket_token' => $this->generateLivechatToken(),
        ]);

        $sendEventAction = (new Services\EventActionService)->doEventAction('new_support_chat', $project, $project->id);
        return $ticket;
    }

    public function generateTokenIfNotExist(Models\Project $project) : string {

        if ($project->livechat_token) {
            return $project->livechat_token;
        }

        return $this->renewLivechatToken($project);

    }

    public function renewLivechatToken(Models\Project $project) : string {
        
        $project->livechat_token = $this->generateLivechatToken();
        $project->save();

        return $project->livechat_token;

    }

    private function generateLivechatToken(int $length = 16) : string {
        return bin2hex(random_bytes($length));
    }

    public function loadTicketMessages(string $livechat_token) {
        $project = Models\Project::where('livechat_token', $livechat_token)->first();

        $ticket_token = Request::post('ticket_token');

        if(!$project) {
            return 'error';
        }

        if(!$ticket_token) {
            return 'no_ticket_token';
        }

        $ticket = Models\Ticket::where([
            'project_id' => $project->id,
            'livechat_ticket_token' => $ticket_token,
        ])->first();

        if(!$ticket) {
            return false;
        }

        if($ticket->closed) {
            return 'ticket_closed';
        }

        if($ticket->leadingOperator) {
            $supportername = $ticket->leadingOperator->fullname ?: $ticket->leadingOperator->username;
        }

        return [
            'messages' => $ticket->ticketChat()->get(),
            'supportername' => $supportername ?? null,
        ];
    }

    public function messageTicket(string $livechat_token) {
        $project = Models\Project::where('livechat_token', $livechat_token)->first();

        $ticket_token = Request::post('ticket_token');

        if(!$ticket_token) {
            return 'no_ticket_token';
        }

        $ticket = Models\Ticket::where([
            'project_id' => $project->id,
            'livechat_ticket_token' => $ticket_token,
        ])->first();

        if(!$ticket) {
            return false;
        }

        $message = Request::post('message');

        if(!$message) {
            return false;
        }

        $ticket->ticketChat()->create([
            // 'user_id' => $ticket->user_id,
            'input' => $message,
        ]);

        return true;
    }

    public function loadLiveChatSettings(string $livechat_token) {
        $project = Models\Project::where('livechat_token', $livechat_token)->first();
        $projectSettings = Models\LivechatConfig::where('project_id', $project->id)->first();

        if(!$projectSettings) return false;

        return $projectSettings;

    }

    public function loadProjectData(string $livechat_token) {
        $project = Models\Project::where('livechat_token', $livechat_token)->first();

        if(!$project) return false;

        return $project;

    }

    public function loadProjectBrandingStatus(string $livechat_token) {
        $project = Models\Project::where('livechat_token', $livechat_token)->first();
        if(!Subscription::hasActiveSubscription('branding', $project->id)) {
            $showWhitelabel = "on";
        } else {
            $showWhitelabel = "off";
        }

        if(!$showWhitelabel) return false;

        return $showWhitelabel;

    }

}