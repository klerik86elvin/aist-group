<?php


namespace App\Services;


use App\DAO\SeatDAO;
use App\DTO\Ticket\CreateTicketDTO;
use App\DTO\SeatDTO;

class SeatService
{
    private $seatDAO;
    public function __construct(SeatDAO $seatDAO)
    {
        $this->seatDAO = $seatDAO;
    }
    public function getSeatsStatus($sessionId,$hall_id)
    {
        $sessionSeats = $this->seatDAO->getSeatsBySessionId($sessionId,$hall_id);
        if (!$sessionSeats) return false;
        $seatsDTOs = [];
        $seats = $this->seatDAO->getAllSeats();

        foreach ($seats as $item){
            $isAvailable = !$sessionSeats->contains($item->id);
            $seatDTO = new SeatDTO($item->id,$item->row_number, $item->seat_number, $isAvailable);
            $seatsDTOs[] = $seatDTO;
        }
        return $seatsDTOs;
    }
    public function availableSeats(CreateTicketDTO $ticketDTO)
    {
        return $this->seatDAO->availableSeats($ticketDTO);
    }
}
