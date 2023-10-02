<?php


namespace App\Services;


use App\DAO\SeatDAO;
use App\DAO\SessionDAO;
use App\DAO\TicketDAO;
use App\DTO\Ticket\CreateTicketDTO;
use App\DTO\Ticket\TicketDTO;
use App\Http\Resources\TicketResource;
use App\Models\Hall;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class TicketService
{
    private $ticketDAO;
    private $sessioService;
    private $userService;
    private $seatService;

    public function __construct(
        TicketDAO $ticketDAO,
        SessionService $sessionService,
        UserService $userService,
        SeatService $seatService
    )
    {
        $this->ticketDAO = $ticketDAO;
        $this->sessioService = $sessionService;
        $this->userService = $userService;
        $this->seatService = $seatService;
    }
    public function getAllTickets()
    {
        $ticketDTOs = [];
        $tickets = $this->ticketDAO->allTickets();
        foreach ($tickets as $ticket){
            $dto = $this->convertTicketModelToDTO($ticket);
            $ticketDTOs[] = $dto;
        }
        return $ticketDTOs;
    }

    public function findTicketById($id)
    {
        $ticket = $this->ticketDAO->getTicketById($id);

        $dto = $this->convertTicketModelToDTO($ticket);
        return $dto;
    }

    public function deleteTicket($id)
    {
        return $this->ticketDAO->deleteTicket($id);
    }

    public function getTotalPrice(CreateTicketDTO $ticketDTO)
    {
        $totalPrice = $this->ticketDAO->getTotalPrice($ticketDTO);
        return $totalPrice;
    }
    public function byTicket(CreateTicketDTO $ticketDTO, $totalPrice)
    {
        $totalPrice = $this->ticketDAO->getTotalPrice($ticketDTO);
         return DB::transaction(function () use($ticketDTO,$totalPrice){
            $result = $this->createTicket($ticketDTO);
            $this->userService->decrement($totalPrice);
            return $result;
        });
    }
    public function returnTickets($id)
    {
        $price = $this->findTicketById($id)->price;
        return DB::transaction(function () use($price, $id){
            $this->ticketDAO->deleteTicket($id);
            $this->userService->increment($price);
        });
    }

    public function expired($id)
    {
        return $this->ticketDAO->expired($id);
    }

    public function createTicket(CreateTicketDTO $ticketDTO)
    {
        $ticketDTOs = [];
        foreach ($ticketDTO->seats as $item) {
            $data = [
                'user_id' => $ticketDTO->userId,
                'session_id' => $ticketDTO->sessionId,
                'hall_id' => $ticketDTO->hallId,
                'seat_id' => $item
            ];
            $ticket = $this->ticketDAO->createTicket($data);
            $dto = $this->convertTicketModelToDTO($ticket);
            $ticketDTOs[] = $dto;
        }
        return $ticketDTOs;
    }

    private function convertTicketModelToDTO($ticket)
    {
        $dto = new TicketDTO(
            $ticket->id,
            $ticket->hall->name,
            $ticket->session->movie->name,
            $ticket->session->price->price,
            $ticket->seat->row_number,
            $ticket->seat->seat_number,
            $ticket->session->date
        );
        return $dto;
    }
}
