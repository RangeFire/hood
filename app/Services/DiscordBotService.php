<?php
namespace App\Services;

use DateTime;
use App\Models;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Request;

class DiscordBotService extends Service {

    public function ticketIndexAndNewTicket($discordID) {

        $project = Models\Project::where('guild_id', $discordID)->first();
        $option = Request::post('option') ?: null;

        if(empty($project)) return 'error';

        $lastTicket = Models\Ticket::orderBy('id', 'desc')->first();

        if(!$lastTicket) {
            $newTicketID = 1;
        } else {
            $newTicketID = intval($lastTicket->id) + 1;
        }
    
        $this->createTicket($newTicketID, $project, $option);
        // Send Alerts if new Ticket is Created
        info("Discord Ticket Event wird gecallt");
        (new EventActionService)->doEventAction('new_support_chat', $project, $project->id);
        (new GameOSAdminService)->alertNewGlobalTicket();

        if(!$project->supportConfig) return true;

        /* handle out of support time */
        info('check if out of support time');
        if($project->supportConfig->time_start && $project->supportConfig->time_end && $project->supportConfig->out_of_time_message) {
            info('checking');
            if($this->checkIfOutOfSupportTime($project->supportConfig->support_days, $project->supportConfig->time_start, $project->supportConfig->time_end)) {
                info('out of support time');
                return $this->formatOutOfSupportTimeMessage($project->supportConfig->out_of_time_message, $project->supportConfig->time_start, $project->supportConfig->time_end);
            }
        }

        if($project->supportConfig && $project->supportConfig->discord_ticket_welcome_message)
            return $project->supportConfig->discord_ticket_welcome_message;

        return true;
    }

    private function formatOutOfSupportTimeMessage($message, $start, $end) {

        $message .= "
            Wir sind erreichbar von $start bis $end Uhr";

        return $message;
    }


    private function checkIfOutOfSupportTime($support_days, $time_start, $time_end) {

        $now = new DateTime();
        $now->setTimezone(new \DateTimeZone('Europe/Berlin'));

        $now_day_int = $now->format('N');
        $now_time = $now->format('H:i');

        if(isset($support_days) && !empty($support_days)) {
            $support_days = json_decode($support_days, true);
            foreach($support_days as $day) {
                if($day['day'] != $now_day_int) continue;
                if($day['active'] === false) continue;

                if($now_time < $time_start || $now_time > $time_end) return true;

            }
            return false;
        }else {
            if($now_time < $time_start || $now_time > $time_end) return true;
        }

        return false;

    }
 
    private function createTicket($internal_ticket_id, $project, $option) {
        $ticket = new Models\Ticket;
        $ticket->ticket_creator = Request::post('creator_name') ?? null;
        $ticket->internal_ticket_id = $internal_ticket_id;
        $ticket->channel_discord_id = Request::post('channel_discord_id');
        $ticket->ticket_creator_id = Request::post('ticket_creator_id') ?: null;
        $ticket->discord_webhook = Request::post('discord_webhook') ?: null;
        $ticket->project_id = $project->id;

        // $category_id = explode('category-', $option);
        // $category = Models\SupportCategory::find($category_id[1]);

        // if(isset($category)) {
        //     if(isset($category->name)) {
        //         $ticket->category = $category->name;
        //     }
        // }

        $ticket->category = $option;

        $ticket->save();

        return $ticket;
    }

    public function ticketMessage() {

        if($this->isInvalid(['channel_id', 'message'], Request::post())) {
            return 'error';
        }

        extract(Request::post());

        $ticket = Models\Ticket::where('channel_discord_id', $channel_id)->first();
        $project = $ticket->project;

        if(!$project || !$ticket) {
            return 'error';
        }

        $frmt_message_input = ""; 

        if(isset($attachments)) {

            foreach($attachments as $attachment) {
                $frmt_message_input .= $attachment." \r\n";
            }

        }

        $frmt_message_input .= $message;

        // creates entry in ticket chat
        $ticketChat = new Models\TicketChat();
        $ticketChat->ticket_id = $ticket->id;
        $ticketChat->input = $frmt_message_input;

        if(isset($author)) {
            $ticketChat->discord_author = $author;
        }

        $ticketChat->save();

    }

    public function formatTicketID($val) {
        return str_pad($val, 4, "0", STR_PAD_LEFT);
    }

    public function closeTicket() {
        $ticket = Models\Ticket::where('channel_discord_id', Request::post('channel_id'))->first();
    
        if(!$ticket) return false;

        $ticket->closed = true;
        $ticket->save();

        return true;
    
    }

}