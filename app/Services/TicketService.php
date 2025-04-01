<?php
namespace App\Services;

use App\Models;
use App\Helpers\Auth;
use App\Helpers\AppHelper;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use App\Helpers\Subscription;
use Illuminate\Support\Facades\Session;


class TicketService extends Service {

        public function getAll() {
            $tickets =  Models\Ticket::where('project_id', session('activeProject'))->orderBy('created_at', 'DESC')->with('ticketChat')->get();
    
            return $tickets;
        }

        public function getTicketDiscordUser($id) {

            $ticket = Models\Ticket::find($id);

            if(!$ticket)
                return false;

            if(!$ticket->ticketChat)
                return false;

            foreach($ticket->ticketChat->reverse() as $chat) {
                if(!empty($chat->discord_author)) {
                    return $chat->discord_author;
                }
            }

            return false;

        }

        public function closeTicket($id) {

            $ticket = Models\Ticket::where([
                ['id', '=', $id],
                ['project_id', '=', session('activeProject')],
            ])->first();

            if(!$ticket) {
                return 'not_inside_team';
            }

            $operation = $this->setModelFromArray($ticket, [
                'closed' => 1
            ]);
            $isSaved = $operation->save();

            if($ticket->type == "discord") {
                $this->closeTicketDiscordBot($ticket);
            }

            if($isSaved) return true;

            return false;

        }

        public function deleteTicket($id) {
    
            $deleteTicket = Models\Ticket::where([
                ['id', '=', $id],
                ['project_id', '=', session('activeProject')],
            ])->first();

            if(!$deleteTicket) {
                return 'not_inside_team';
            }

            $deleteTicketChat = Models\TicketChat::where('ticket_id', $deleteTicket->id)->delete();
    
            $isSaved = $deleteTicket->delete();
    
            return $isSaved;

        }

        public function getSingleTicket($id) {
            $ticket =  Models\Ticket::find($id);
    
            return $ticket;
        }

        // TODO Alert meldungen einbauen
        public function changeStatus($status, $id) {
            // Check if string is vaild
            if ($status == 'open') {
                $secCheck = true;
            } elseif ($status == 'pending') {
                $secCheck = true;
            } elseif ($status == 'closed') {
                $secCheck = true;
            } else {
                $secCheck = false;
            }

            if ($secCheck == true) {
                $ticket = (new Models\Ticket)->find($id);
                $operation = $this->setModelFromArray($ticket, [
                    'status' => $status,
                ]);
    
                $isSaved = $operation->save();
            }

            return $isSaved;
        }

        public function addNote($id) {
            if($this->isInvalid(['note'], Request::post()))
                return false;
            $ticket = Models\Ticket::find($id);
            $operation = $this->setModelFromArray($ticket, [
                'note' => Request::post('note'),
            ]);

            $isSaved = $operation->save();

            return $isSaved;

        }

        public function changeTitle() {
            extract(Request::post());

            if($ticketTitle == null) 
                return false;

            $ticket = Models\Ticket::find($ticketId);
            $operation = $this->setModelFromArray($ticket, [
                'ticket_title' => $ticketTitle,
            ]);

            $isSaved = $operation->save();

            return $isSaved;
        }

        public function ticketChangeAgent($ticketId) {
            extract(Request::post());

            if($newSupportagent == null) 
                return false;

            $ticket = Models\Ticket::find($ticketId);
            $operation = $this->setModelFromArray($ticket, [
                'leading_operator' => $newSupportagent,
            ]);

            $isSaved = $operation->save();

            return $isSaved;
        }

        // TODO Alert meldungen einbauen
        public function ticketAnswer($id) {
            extract(Request::post());

            $ticket = Models\Ticket::find($id);

            $supporterAlreadyAnswered = false;
            $chats = $ticket->ticketChat;
            foreach($chats as $chat) {
                if(!empty($chat->author)) $supporterAlreadyAnswered = true;
            }

            $user = $this->setModelFromArray(new Models\TicketChat, [
                'input' => $this->formatMessageForCustomFields($reply, $ticket->ticket_creator), 
                'ticket_id' => $ticket->id, 
                'author' => Auth::user()->id,
            ]);
    
            $isSaved = $user->save();

            if(!$ticket->leading_operator) {
                $ticket->leading_operator = Auth::user()->id;
                $ticket->save();
            }

            if($ticket->type == "discord") {
                $this->sendDiscordBotMessage($reply, $ticket, $supporterAlreadyAnswered);
            }
    
            return $isSaved;
        }

        private function formatMessageForCustomFields($message, $creator_name) {

            // replace @user with custom span
            $message = str_replace('@user', '<span class="ping-user-text">@'.$creator_name.'</span>', $message);

            return $message;
        }

        private function sendDiscordBotMessage($message, $ticket, $supporterAlreadyAnswered) {
            $project = Models\Project::find($ticket->project_id);

            $sendViaWebhook = Subscription::hasActiveSubscription('support');
            $discordData = json_decode($ticket->discord_webhook, true);
            $encoded_url = str_replace(' ', '%20', Session::get('user')->avatar);

            $postData = [
                'message' => $message,
                'channel_id' => $ticket->channel_discord_id,
                'ticket_creator_id' => $ticket->ticket_creator_id,
                'agent_avatar' => $encoded_url,
                'agent_name' => Session::get('user')->fullname ? Session::get('user')->fullname : Session::get('user')->username,
                'sendViaWebhook' => $sendViaWebhook,
                'webHookChannelID' => $discordData['id'] ? $discordData['id']: '',
                'webHookChannelToken' => $discordData['token'] ? $discordData['token'] : '',
            ];

            if(!$supporterAlreadyAnswered) {

                $user = Models\User::find(Auth::user()->id);
                if($user) {
                    $userFullname = $user->fullname;
                }else {
                    $userFullname = Auth::user()->fullname;
                }

                $postData['firstMessageAuthor'] = $userFullname;
            }

            if($ticket->type == 'discord') {
                $response = Http::post(env('DISCORD_BOT_HOST').'/sendTicketMessage/'.$project->guild_id, $postData);
            }

        }
        
        private function closeTicketDiscordBot($ticket) {
            $project = Models\Project::find($ticket->project_id);
            $response = Http::get(env('DISCORD_BOT_HOST').'/closeTicket/'.$project->guild_id.'/'.$ticket->channel_discord_id);
        }

        public function getTicketMessages($id) {

            $ticket = Models\Ticket::find($id);
            $messages = $ticket->ticketChat;

            foreach($messages as $i => $message) {
                $check_string = $message->input;
                preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $check_string, $match);

                if(empty($match[0])) continue;

                $text = '';

                foreach($match[0] as $i_match => $url) {

                    if (AppHelper::isImage($url)) {
                        /* is an image */
                        $text .= '<img src="'.$url.'"></img>'.'<br>';
                    } else {
                        /* not an image */
                        $text .= '<a href="'.$url.'" target="_blank">'.$url.'</a>'.'<br>';
                    }
                    if(count($match[0]) <= $i_match + 1) $text .= '<br>';
                    
                }

                $check_string = preg_replace('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', " ", $check_string);

                $messages[$i]->input = $text.$check_string;

            }
        

            return $messages;

        }
}