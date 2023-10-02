<?php


namespace App\DAO;


use App\DTO\Ticket\CreateTicketDTO;
use App\Models\Hall;
use App\Models\Seat;
use App\Models\Session;
use App\Models\Ticket;

class SeatDAO
{
    public function getAllSeats()
    {
        return Seat::all();
    }
    public function getSeatsBySessionId($sessionId,$hallId)
    {
        if (Hall::find($hallId) == null || Session::find($sessionId) == null) return false;
        return Ticket::where('session_id',$sessionId)->where('hall_id',$hallId)->pluck('seat_id');
    }
    public function availableSeats(CreateTicketDTO $ticketDTO)
    {
        return Ticket::where('hall_id',$ticketDTO->hallId)
            ->where('session_id', $ticketDTO->sessionId)
            ->whereIn('seat_id', $ticketDTO->seats)->count() == 0;
    }
}
