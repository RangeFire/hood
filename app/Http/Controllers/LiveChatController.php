<?php

namespace App\Http\Controllers;

use App\Services\LiveChatService;
use Illuminate\Http\Request;

class LiveChatController extends Controller
{

    public function openTicket(LiveChatService $liveChatService, string $livechat_token) {

        $ticket = $liveChatService->openTicket($livechat_token);

        if(!$ticket) {
            return response()->json([
                'error' => 'No ticket found',
            ], 404);
        }

        if($ticket === 'no_ticket_token') {
            return response()->json([
                'error' => 'No ticket token provided',
            ], 404);
        }

        return response()->json([
            'livechat_ticket_token' => $ticket->livechat_ticket_token,
        ]);

    }
 
    public function loadTicketMessages(LiveChatService $liveChatService, string $livechat_token) {

        $data = $liveChatService->loadTicketMessages($livechat_token);

        if(!$data) {
            return response()->json([
                'error' => 'no_ticket_found',
            ], 404);
        }

        if($data === 'error') {
            return response()->json([
                'error' => 'error',
            ], 404);
        }

        if($data === 'no_ticket_token') {
            return response()->json([
                'error' => 'No ticket token provided',
            ], 404);
        }

        if($data === 'ticket_closed') {
            return response()->json([
                'error' => 'ticket_closed',
            ], 404);
        }

        return response()->json([
            'messages' => $data['messages'],
            'supportername' => $data['supportername'],
        ]);

    }

    public function messageTicket(LiveChatService $liveChatService, string $livechat_token) {

        $message = $liveChatService->messageTicket($livechat_token);

        if(!$message) {
            return response()->json([
                'error' => 'No message found',
            ], 404);
        }

        if($message === 'no_ticket_token') {
            return response()->json([
                'error' => 'No ticket token provided',
            ], 404);
        }

        return response()->json('success');

    }

    public function loadLiveChatSettings(LiveChatService $liveChatService, string $livechat_token) {

        $data = $liveChatService->loadLiveChatSettings($livechat_token); 

        if(!$data) {
            return response()->json([
                'error' => 'No Data found',
            ], 404);
        }

        return response()->json($data);

    }

    public function loadProjectData(LiveChatService $liveChatService, string $livechat_token) {

        $data = $liveChatService->loadProjectData($livechat_token); 

        if(!$data) {
            return response()->json([
                'error' => 'No Data found',
            ], 404);
        }

        return response()->json($data);

    }

    public function loadProjectBrandingStatus(LiveChatService $liveChatService, string $livechat_token) {

        $data = $liveChatService->loadProjectBrandingStatus($livechat_token); 

        if(!$data) {
            return response()->json([
                'error' => 'No Data found',
            ], 404);
        }

        return response()->json($data);

    }
    
}
