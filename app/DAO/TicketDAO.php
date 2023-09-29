<?php


namespace App\DAO;


use App\DTO\CreateTicketDTO;
use App\Http\Resources\TicketResource;
use App\Models\Session;
use App\Models\Ticket;

class TicketDAO
{
    public function allTickets()
    {
        $tickets = Ticket::all();
        return $tickets;
    }

    public function getTicketById($id)
    {
        $ticket = Ticket::findOrFial($id);
        return $ticket;
    }
    public function getTicketsBySessionId($sessionId)
    {
        $tickets = Ticket::where('session_id',$sessionId)->get();
        return $tickets;
    }
    public function updateTicket($data, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($data);
        return $ticket;
    }
    public function createTicket($data)
    {
        $ticket = Ticket::create($data);
        $ticket->load('session');
        $ticket->load('seat');
        $ticket->load('user');
        $ticket->load('hall');
        return $ticket;
    }
    public function deleteTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        return $ticket->delete();
    }

    public function getTotalPrice(CreateTicketDTO $ticketDTO)
    {
        return Session::find($ticketDTO->sessionId)->price->price * count($ticketDTO->seats);
    }

}
