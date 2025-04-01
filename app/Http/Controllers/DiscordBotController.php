<?php

namespace App\Http\Controllers;

use App\Services\DiscordBotService;
use Illuminate\Http\Request;

class DiscordBotController extends Controller
{
    
    public function ticketIndexAndNewTicket(DiscordBotService $discordBotService, $id) {
        $result = $discordBotService->ticketIndexAndNewTicket($id);

        /* sends welcome message to discordbot */
        if($result === true) {
            return response()->json(['error' => false]);
        }else if(is_string($result)) {
            return response()->json(['error' => false, 'message' => $result]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    // public function createTicket(DiscordBotService $discordBotService) {
    //     $result = $discordBotService->createTicket();
    //     return response()->json($result);
    // }

    public function ticketMessage(DiscordBotService $discordBotService) {
        $result = $discordBotService->ticketMessage();
        return response()->json($result);
    }

    public function closeTicket(DiscordBotService $discordBotService) {
        $result = $discordBotService->closeTicket();
        return response()->json($result);
    }

}
