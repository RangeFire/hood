<?php

namespace App\Http\Controllers;

use App\Helpers\Auth;
use App\Models\System;
use App\Models\TextSnippets;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\DBSyncService;
use App\Services\TicketService;
use App\Services\UserService;

class TicketController extends Controller
{
    public function tickets(TicketService $ticketService) {
        $tickets = $ticketService->getAll();

        $open_tickets = [];
        $assigned_tickets = [];
        $all_tickets = [];
        $closed_tickets = [];

        foreach($tickets as $i => $ticket) {
            $all_tickets[] = $ticket;
            if($ticket->closed === 1) {
                $closed_tickets[] = $ticket;
            } else {
                if($ticket->leading_operator == (int) Auth::user()->id) {
                    $assigned_tickets[] = $ticket;
                } else {
                    $open_tickets[] = $ticket;
                }
            }
        }

        return view('pages/support/ticketsView', [
            'open_tickets' => $open_tickets,
            'assigned_tickets' => $assigned_tickets,
            'all_tickets' => $all_tickets,
            'closed_tickets' => $closed_tickets,
        ]);    
    }

    public function countOpenTickets() {
        $ticketService = new TicketService;
        $tickets = $ticketService->getAll();

        $open_tickets = [];

        foreach($tickets as $i => $ticket) {
            if(!$ticket->closed && $ticket->leading_operator != (int) Auth::user()->id) {
                $open_tickets[] = $ticket;
            }
        }

        return response()->json(count($open_tickets));

    }

    public function countOpenTicketsInternal() {
        $ticketService = new TicketService;
        $tickets = $ticketService->getAll();

        $open_tickets = [];

        foreach($tickets as $i => $ticket) {
            if(!$ticket->closed && $ticket->leading_operator != (int) Auth::user()->id) {
                $open_tickets[] = $ticket;
            }
        }

        return count($open_tickets);

    }

    public function ticketDetail(TicketService $ticketService, DBSyncService $dBSyncService, $id) {
        $ticket = $ticketService->getSingleTicket($id);
        $ticket_discord_user = $ticketService->getTicketDiscordUser($id);
        $textSnippets = TextSnippets::where("project_id", session('activeProject'))->get();
        $users = (new UserService)->getAll();

        return view('pages/support/ticketDetailView', [
            'ticket' => $ticket,
            'ticket_discord_user' => $ticket_discord_user,
            'sync_users' => $dBSyncService->getSyncUsers() ?: [],
            'textSnippets' => $textSnippets,
            'users' => $users,
        ]);    
    }

    public function addNote(TicketService $ticketService, $id) {
        $isSaved = $ticketService->addNote($id);

        if($isSaved) return redirect()->back()->with('success', 'Die Notiz wurde erfolgreich gespeichert');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function closeTicket(TicketService $ticketService, $id) {
        $isSaved = $ticketService->closeTicket($id);

        if($isSaved === 'not_inside_team') {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung dieses Ticket zu schließen.'); 
        } 

        if($isSaved) return redirect()->to('/tickets')->with('success', 'Das Ticket wurde erfolgreich geschlossen');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function deleteTicket(TicketService $ticketService, $id) {
        $isSaved = $ticketService->deleteTicket($id);

        if($isSaved === 'not_inside_team') {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung dieses Ticket zu löschen.'); 
        } 

        if($isSaved) return redirect()->to('/tickets')->with('success', 'Das Ticket wurde entgültig gelöscht');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function ticketChangeStatus(TicketService $ticketService, $status, $id) {
        $isSaved = $ticketService->changeStatus($status, $id);

        if($isSaved) return redirect()->back()->with('success', 'Der Ticket Status wurde geändert.');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten');    
    }

    public function ticketChangeTitle(TicketService $ticketService) {
        $isSaved = $ticketService->changeTitle();

        if($isSaved) return redirect()->back()->with('success', 'Der Ticket Name wurde geändert.');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten');    
    }

    public function ticketAnswer(TicketService $ticketService, $id) {
        $isSaved= $ticketService->ticketAnswer($id);

        if($isSaved) return redirect()->back()->with('success', 'Ihre Antwort wurde erstellt.');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten');   
    }

    public function ticketChangeAgent(TicketService $ticketService, $id) {
        $isSaved = $ticketService->ticketChangeAgent($id);

        if($isSaved) return redirect()->back()->with('success', 'Das Ticket wurde einem neuen Agent zugewiesen.');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten');    
    }

    public function getTicketMessages(TicketService $ticketService, $id) {
        $messages = $ticketService->getTicketMessages($id);
        return response()->json($messages, 200);
    }

}
